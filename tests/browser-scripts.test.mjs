import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';
test('admin upload initializes even when the optional animation CDN fails', () => {
  const context = vm.createContext({ window: {}, document: {} });
  vm.runInContext(readFileSync('scripts/_admin.js', 'utf8'), context);
  assert.equal(vm.runInContext('imageUploadInProgress', context), false);
  assert.equal(typeof context.uploadImage, 'function');
});
test('login, recovery and contact work without the retired domain captcha', async () => {
  for (const [file, action, handler] of [['_admin_login.js', 'login', 'processLogin'], ['_admin_login.js', 'recuperarSenha', 'runRecuperarSenha'], ['_main.js', 'enviarContato', 'processarEnvio']]) {
    const context = vm.createContext({ window: { addEventListener() {} }, document: {} });
    vm.runInContext(readFileSync('scripts/' + file, 'utf8'), context);
    let called = false;
    const button = {};
    context[handler] = async value => { assert.equal(value, button); called = true; };
    if (action === 'login') await context[action]({ preventDefault() {} }, button);
    else await context[action](button);
    assert.equal(called, true, action);
  }
});
for (const file of ['_admin.js', '_admin_login.js', '_admin_recovery.js', '_main.js']) {
  test(file + ' parses and toast messages remain text', () => {
    const nodes = [];
    const context = {
      AOS: { init() {} }, window: { addEventListener() {} }, setTimeout() {},
      document: {
        createElement() { const node = { classList: { add() {} }, appendChild(child) { this.child = child; } }; nodes.push(node); return node; },
        createTextNode(text) { return { text }; },
        getElementById() { return { appendChild() {} }; },
      },
    };
    vm.createContext(context);
    vm.runInContext(readFileSync('scripts/' + file, 'utf8'), context);
    if (context.showToast) {
      context.showToast('<img src=x onerror=alert(1)>', false);
      assert.ok(!nodes[0].innerHTML.includes('onerror'));
      assert.equal(nodes[0].child.text, ' <img src=x onerror=alert(1)>');
    }
  });
}
