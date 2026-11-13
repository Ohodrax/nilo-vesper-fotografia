import assert from 'node:assert/strict';
import { spawn, spawnSync } from 'node:child_process';
import { once } from 'node:events';
import { createServer } from 'node:net';
import { mkdtemp, rm, readFile } from 'node:fs/promises';
import path from 'node:path';

const root = process.cwd();
const php = process.env.PHP_BINARY || 'php';
const env = { ...process.env, TEST_DB_NAME: 'test_security_' + Date.now() };
const run = action => {
  const result = spawnSync(php, [...(process.platform === 'win32' ? ['-d', 'extension=gd'] : []), 'tests/mysql.php', action], { env, encoding: 'utf8' });
  assert.equal(result.status, 0, result.stderr || result.stdout);
  return result.stdout ? JSON.parse(result.stdout) : null;
};
const listener = createServer();
listener.listen(0, '127.0.0.1'); await once(listener, 'listening');
const port = listener.address().port; await new Promise(resolve => listener.close(resolve));
const origin = 'http://127.0.0.1:' + port;
const state = await mkdtemp(path.join(path.dirname(root), 'paulo-test-state-'));
let server, output = '', checks = 0;
try {
  const fixture = run('prepare');
  server = spawn(php, [...(process.platform === 'win32' ? ['-d', 'extension=gd'] : []), '-d', 'session.save_path=' + state, '-d', 'upload_tmp_dir=' + state, '-d', 'display_errors=0', '-S', '127.0.0.1:' + port, 'tests/router.php'], { env: { ...env, APP_CONFIG_FILE: '', APP_STATE_DIR: state, APP_URL: origin, DB_HOST: '127.0.0.1', DB_PORT: env.TEST_DB_PORT || '3306', DB_NAME: env.TEST_DB_NAME, DB_USER: env.TEST_DB_USER, DB_PASSWORD: env.TEST_DB_PASSWORD }, stdio: ['ignore', 'pipe', 'pipe'] });
  server.stdout.on('data', data => output += data);
  server.stderr.on('data', data => output += data);
  for (let i = 0; i < 100; i++) {
    try { if ((await fetch(origin + '/login')).ok) break; } catch {}
    await new Promise(resolve => setTimeout(resolve, 100));
  }
  class Browser {
    cookies = ''; csrf = '';
    async request(url, { method = 'GET', data, csrf = this.csrf } = {}) {
      const headers = { Cookie: this.cookies };
      if (method === 'POST') headers['X-CSRF-Token'] = csrf;
      const response = await fetch(origin + url, { method, headers, body: data instanceof FormData ? data : data ? new URLSearchParams(data) : undefined, redirect: 'manual' });
      for (const cookie of response.headers.getSetCookie()) this.cookies = cookie.split(';')[0];
      const text = await response.text();
      const token = text.match(/name="csrf-token" content="([^"]+)"/)?.[1];
      if (token) this.csrf = token;
      return { status: response.status, text, headers: response.headers };
    }
    post(url, data, csrf) { return this.request(url, { method: 'POST', data, ...(csrf === undefined ? {} : { csrf }) }); }
    async login(email, password) {
      assert.equal((await this.request('/login')).status, 200);
      assert.equal(JSON.parse((await this.post('/control/c_loginValidation.php', { username: email, userpassword: password })).text).return, true);
      return this.request('/admin');
    }
  }
  const visitor = new Browser();
  const login = await visitor.request('/login');
  assert.equal(login.status, 200);
  assert.match(login.headers.get('set-cookie'), /HttpOnly/i); checks++;
  assert.equal((await visitor.post('/control/c_loginValidation.php', { username: 'x', userpassword: 'x' }, 'wrong')).status, 403); checks++;
  assert.equal(JSON.parse((await visitor.post('/control/c_loginValidation.php', { username: "' OR 1=1 --", userpassword: 'x' })).text).return, false); checks++;
  for (const endpoint of ['/control/c_uploadFile_save.php', '/control/c_uploadFile_delete.php', '/control/c_adminSystem_updateTextParam.php', '/control/c_adminUser_CRUD.php']) {
    assert.equal((await visitor.post(endpoint, {})).status, 401, endpoint); checks++;
  }
  for (const endpoint of ['/ajax/a_renderAdmin_main.php', '/ajax/a_modalUser_renderContent.php', '/ajax/a_renderAdmin_users.php']) {
    assert.equal((await visitor.request(endpoint)).status, 401, endpoint); checks++;
  }
  const home = await visitor.request('/');
  assert.equal(home.status, 200);
  assert.ok(home.text.includes('&lt;/textarea&gt;&lt;script&gt;alert(1)&lt;/script&gt;'));
  assert.ok(!home.text.includes('href="javascript:')); checks++;
  const owner = new Browser();
  const admin = await owner.login('owner@example.invalid', 'test-password-owner-123');
  assert.equal(admin.status, 200);
  assert.ok(admin.text.includes('&lt;img src=x onerror=alert(1)&gt;'));
  assert.ok(!admin.text.includes('<img src=x onerror=alert(1)>')); checks++;
  const editor = new Browser();
  assert.equal((await editor.login('editor@example.invalid', 'test-password-editor-123')).status, 200);
  assert.equal((await editor.request('/ajax/a_renderAdmin_users.php')).status, 403);
  assert.equal((await editor.post('/control/c_adminUser_CRUD.php', { formAction: 'exclude', targetId: '1' })).status, 403); checks++;
  assert.equal((await owner.post('/control/c_adminUser_CRUD.php', { formAction: 'exclude', targetId: '2 OR 1=1' })).status, 400); checks++;
  assert.equal((await owner.post('/control/c_uploadFile_save.php', { reference: '../bin', localization: 'gallery' })).status, 400); checks++;
  const forged = new FormData();
  forged.set('reference', 'pictures'); forged.set('localization', 'gallery');
  forged.set('0', new Blob(['<?php echo "injected"; ?>'], { type: 'image/png' }), 'payload.png');
  assert.equal((await owner.post('/control/c_uploadFile_save.php', forged)).status, 400); checks++;
  const valid = new FormData();
  valid.set('reference', 'pictures'); valid.set('localization', 'gallery');
  const png = Buffer.from(fixture.png, 'base64');
  valid.set('0', new Blob([png, '<?php echo "injected"; ?>'], { type: 'image/png' }), '../../evil.php.png');
  const uploaded = await owner.post('/control/c_uploadFile_save.php', valid);
  assert.equal(uploaded.status, 200, uploaded.text);
  assert.equal(JSON.parse(uploaded.text).return, true);
  const images = run('images'); assert.equal(images.length, 1);
  const bytes = await readFile(path.join(root, 'assets/pictures', images[0].nome));
  assert.ok(!bytes.includes(Buffer.from('<?php')));
  assert.equal((await owner.post('/control/c_uploadFile_delete.php', { targetId: String(images[0].id_image) })).status, 200); checks++;
  const reset = new Browser();
  assert.equal((await reset.request('/recovery/' + fixture.token)).status, 200);
  assert.equal((await reset.post('/control/c_recoveryPassword_update.php', { formRaid: fixture.token, formNewPassword: 'new-test-password-123', formConfirmPassword: 'new-test-password-123' })).status, 200);
  assert.equal((await editor.request('/ajax/a_renderAdmin_main.php')).status, 401); checks++;
  await reset.request('/login');
  assert.equal((await reset.post('/control/c_recoveryPassword_update.php', { formRaid: fixture.token, formNewPassword: 'new-test-password-123', formConfirmPassword: 'new-test-password-123' })).status, 400); checks++;
  assert.equal((await owner.request('/logout')).status, 405);
  assert.equal((await owner.post('/logout', {})).status, 200);
  assert.equal((await owner.request('/ajax/a_renderAdmin_main.php')).status, 401); checks++;
  console.log('MariaDB + HTTP: ' + checks + ' security checks passed (schema, seed, SQLi, XSS, auth/roles, CSRF, uploads, reset and session revocation).');
} catch (error) {
  console.error(output.slice(-12000).replace(/test-password-[^ \n]+/g, '[test password]'));
  throw error;
} finally {
  if (server) { const stopped = once(server, 'exit'); server.kill(); await stopped; }
  run('cleanup');
  await rm(state, { recursive: true, force: true });
}
