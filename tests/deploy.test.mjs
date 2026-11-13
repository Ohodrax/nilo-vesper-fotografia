import test from 'node:test';
import assert from 'node:assert/strict';
import { PROJECT, buildPlan, deploy, deployable, hash, remoteRoot, rollback, verifyTarget, withLock } from '../deploy/engine.mjs';
const config = { remoteDir: '/hosting/site', backupDir: '/hosting/backups', targetId: 'test-target' };
function mock() {
  const files = new Map(), dirs = new Set(['/hosting', config.remoteDir, config.backupDir]), links = new Set();
  for (const [dir, name] of [[config.remoteDir, '.deploy-target.json'], [config.backupDir, '.deploy-backups.json']]) files.set(`${dir}/${name}`, Buffer.from(JSON.stringify({ project: PROJECT, targetId: config.targetId })));
  return { files, dirs, links, async exists(p) { return links.has(p) ? 'l' : files.has(p) ? '-' : dirs.has(p) ? 'd' : false; }, async realPath(p) { return p; }, async stat(p) { return { size: files.get(p).length }; }, async get(p) { return files.get(p); },
    async mkdir(p, recursive) { if (dirs.has(p) && !recursive) throw new Error('exists'); dirs.add(p); }, async rmdir(p) { dirs.delete(p); },
    async put(data, p) { files.set(p, Buffer.from(data)); }, async posixRename(a, b) { files.set(b, files.get(a)); files.delete(a); }, async delete(p) { files.delete(p); } };
}
const file = (path, text, preserve = false) => ({ path, content: Buffer.from(text), hash: hash(Buffer.from(text)), preserve });
test('deploy allowlist excludes secrets, Git, tests, docs, samples and unreviewed private config', () => {
  for (const path of ['.env', '.git/config', 'tests/test.php', 'CODEX.md', 'deploy/runtime-probe.php', 'vendor/library/docs/sample.php', 'bin/config.local.php', 'bin/private.php', 'control/c_proccess_data.php', 'assets/evil.php', 'package.json']) assert.equal(deployable(path), false, path);
  for (const path of ['index.php', 'bin/config.php', 'control/c_loginValidation.php', 'vendor/autoload.php', 'assets/home/photo.jpg']) assert.equal(deployable(path), true, path);
  for (const path of ['/', 'relative', '/site/../other', '/site/', '/site//nested']) assert.throws(() => remoteRoot(path));
  assert.throws(() => deployable('../index.php'));
});
test('preflight refuses wrong markers, roots, overlapping backups and symlinks without writes', async () => {
  let client = mock(); client.files.set('/hosting/site/.deploy-target.json', Buffer.from('{}'));
  await assert.rejects(verifyTarget(client, config), /Marcador/);
  client = mock(); client.links.add('/hosting/site'); await assert.rejects(verifyTarget(client, config), /Symlink/);
  await assert.rejects(verifyTarget(mock(), { ...config, backupDir: '/hosting/site/backups' }), /Backups/);
  client = mock(); client.realPath = async () => '/different'; await assert.rejects(verifyTarget(client, config), /canônico/);
});
test('plan preserves production assets, private files and existing webserver configuration', async () => {
  const client = mock(); client.files.set('/hosting/site/assets/photo.jpg', Buffer.from('production upload')); client.files.set('/hosting/site/.htaccess', Buffer.from('private directives')); client.files.set('/hosting/site/.env', Buffer.from('private'));
  const plan = await buildPlan(client, config.remoteDir, [file('assets/photo.jpg', 'repo image', true), file('.htaccess', 'repo directives', true), file('index.php', 'new')]);
  assert.deepEqual(plan.preserved, ['assets/photo.jpg', '.htaccess']); assert.equal(plan.changes.length, 1);
  assert.equal(client.files.get('/hosting/site/.env').toString(), 'private');
});
test('deploy keeps backups outside webroot and rollback restores overwritten files, removes only new managed files', async () => {
  const client = mock(); client.files.set('/hosting/site/index.php', Buffer.from('old')); client.files.set('/hosting/site/uploads/private.jpg', Buffer.from('upload'));
  const result = await deploy(client, config, [file('index.php', 'new'), file('Router.php', 'router')], 'release-1');
  assert.equal(result.changed, 2); assert.equal(client.files.get('/hosting/backups/release-1/files/index.php').toString(), 'old');
  await rollback(client, config, 'release-1');
  assert.equal(client.files.get('/hosting/site/index.php').toString(), 'old'); assert.equal(client.files.has('/hosting/site/Router.php'), false); assert.equal(client.files.get('/hosting/site/uploads/private.jpg').toString(), 'upload');
});
test('health failure automatically rolls back and is never reported as deploy success', async () => {
  const client = mock(); client.files.set('/hosting/site/index.php', Buffer.from('old'));
  await assert.rejects(deploy(client, config, [file('index.php', 'new')], 'failed-release', async () => { throw new Error('HTTP 500'); }), /arquivos restaurados/);
  assert.equal(client.files.get('/hosting/site/index.php').toString(), 'old');
});
test('rollback refuses corrupt backups or production edits after deployment', async () => {
  for (const corruptBackup of [false, true]) {
    const client = mock(); client.files.set('/hosting/site/index.php', Buffer.from('old'));
    await deploy(client, config, [file('index.php', 'new')], 'release-1');
    client.files.set(corruptBackup ? '/hosting/backups/release-1/files/index.php' : '/hosting/site/index.php', Buffer.from('modified'));
    await assert.rejects(rollback(client, config, 'release-1'), corruptBackup ? /corrompido/ : /mudou/);
  }
});
test('remote locks reject concurrent or interrupted operations and release on errors', async () => {
  const client = mock(); client.dirs.add('/hosting/backups/deploy.lock');
  await assert.rejects(withLock(client, config, () => {}), /pendente/); client.dirs.delete('/hosting/backups/deploy.lock');
  await assert.rejects(withLock(client, config, () => { throw new Error('failure'); }), /failure/); assert.equal(client.dirs.has('/hosting/backups/deploy.lock'), false);
});
test('symlinked descendants are rejected before reading or replacing live files', async () => {
  const client = mock(); client.links.add('/hosting/site/bin');
  await assert.rejects(buildPlan(client, config.remoteDir, [file('bin/config.php', 'new')]), /Symlink/);
});
test('rollback refuses an older non-active release even if some hashes still match', async () => {
  const client = mock(); client.files.set('/hosting/site/index.php', Buffer.from('old'));
  await deploy(client, config, [file('index.php', 'new')], 'first');
  await deploy(client, config, [file('Router.php', 'router')], 'second');
  await assert.rejects(rollback(client, config, 'first'), /release ativa/);
  await rollback(client, config, 'second'); await rollback(client, config, 'first');
  assert.equal(client.files.get('/hosting/site/index.php').toString(), 'old');
});
test('backup failure never touches live application files', async () => {
  const client = mock(); client.files.set('/hosting/site/index.php', Buffer.from('old'));
  client.put = async () => { throw new Error('disk full'); };
  await assert.rejects(deploy(client, config, [file('index.php', 'new')], 'release-1'), /disk full/);
  assert.equal(client.files.get('/hosting/site/index.php').toString(), 'old');
});
