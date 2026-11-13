import { test } from 'node:test';
import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import vm from 'node:vm';
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
