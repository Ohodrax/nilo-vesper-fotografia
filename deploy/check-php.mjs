import { readdir } from 'node:fs/promises';
import { spawnSync } from 'node:child_process';
import path from 'node:path';
const php = process.env.PHP_BINARY || 'php';
const files = [];
async function walk(directory) {
  for (const item of await readdir(directory, { withFileTypes: true })) {
    if (item.name.startsWith('.') || item.name === 'node_modules') continue;
    const name = path.join(directory, item.name);
    if (item.isDirectory()) await walk(name);
    else if (item.name.endsWith('.php')) files.push(name);
  }
}
await walk('.');
for (const file of files) {
  const result = spawnSync(php, ['-l', file], { encoding: 'utf8' });
  if (result.status !== 0) { console.error(`PHP lint falhou em ${file}: ${result.stderr || result.error?.message}`); process.exit(1); }
}
const runtime = spawnSync(php, ['-d', 'error_reporting=32767', 'tests/php.php'], { encoding: 'utf8' });
process.stdout.write(runtime.stdout || ''); process.stderr.write(runtime.stderr || '');
if (runtime.status !== 0) process.exit(1);
console.log(`PHP lint: ${files.length} arquivos aprovados.`);
