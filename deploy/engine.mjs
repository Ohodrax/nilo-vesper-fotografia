import { createHash, randomUUID } from 'node:crypto';
import { readFile, readdir, lstat } from 'node:fs/promises';
import path from 'node:path';

export const PROJECT = 'Ohodrax/paulo-bareta-fotografia';
export const hash = data => createHash('sha256').update(data).digest('hex');
export function relativePath(value) {
  if (typeof value !== 'string' || !value || value.includes('\\') || value.startsWith('/') || value.split('/').some(part => !part || part === '.' || part === '..') || /[\x00-\x1f]/.test(value)) throw new Error('Caminho relativo inseguro.');
  return value;
}
export function remoteRoot(value) {
  if (typeof value !== 'string' || !value.startsWith('/') || value === '/' || value.includes('\\') || /[\x00-\x1f]/.test(value) || path.posix.normalize(value) !== value || value.endsWith('/')) throw new Error('Diretório remoto deve ser absoluto, normalizado e diferente de /.');
  return value;
}
export function deployable(value) {
  const name = relativePath(value);
  if (['.htaccess', 'index.php', 'Router.php', 'RouteSwitch.php'].includes(name)) return true;
  if (['bin/_environment.php', 'bin/_variables.php', 'bin/_functions.php', 'bin/config.php', 'bin/classes/mySqlConnection.php', 'bin/classes/db_crud.php'].includes(name)) return true;
  if (/(^|\/)(?:\.|tests?\/|docs?\/|examples?\/)/i.test(name) || /(?:secret|private|config\.local|\.env|\.bak|\.old)/i.test(name)) return false;
  if (name === 'control/c_proccess_data.php') return false;
  return /^(?:ajax|control)\/[^/]+\.php$/.test(name)
    || /^views\/(?:[^/]+\/)*[^/]+\.(?:php|html)$/.test(name)
    || /^(?:css|scripts)\/[^/]+\.(?:css|js)$/.test(name)
    || /^assets\/(?:[^/]+\/)*[^/]+\.(?:png|jpe?g|webp|gif|svg|ico|woff2?)$/i.test(name)
    || /^vendor\/(?:[^/]+\/)*[^/]+\.php$/.test(name)
    || /^vendor\/[^/]+\/[^/]+\/LICENSE(?:\.txt)?$/.test(name);
}
export async function localFiles(root) {
  const result = [];
  async function walk(directory, prefix = '') {
    for (const entry of await readdir(directory, { withFileTypes: true })) {
      const name = prefix + entry.name;
      if (entry.isSymbolicLink()) { if (deployable(name)) throw new Error('Symlink local recusado.'); continue; }
      if (entry.isDirectory()) { if (['ajax', 'control', 'views', 'bin', 'css', 'scripts', 'assets', 'vendor'].includes(name.split('/')[0])) await walk(path.join(directory, entry.name), `${name}/`); }
      else if (deployable(name)) {
        const source = path.join(root, name), stat = await lstat(source);
        if (stat.size > 10 * 1024 * 1024) throw new Error('Arquivo acima do limite de 10 MiB.');
        const content = await readFile(source);
        result.push({ path: name, content, hash: hash(content), preserve: name.startsWith('assets/') || name === '.htaccess' });
      }
    }
  }
  await walk(root);
  return result.sort((a, b) => a.path.localeCompare(b.path));
}
async function safePath(sftp, root, relative = '') {
  const target = relative ? `${remoteRoot(root)}/${relativePath(relative)}` : remoteRoot(root);
  let current = '';
  for (const part of target.split('/').filter(Boolean)) {
    current += `/${part}`;
    const kind = await sftp.exists(current);
    if (kind === 'l') throw new Error('Symlink remoto recusado.');
    if (kind && current !== target && kind !== 'd') throw new Error('Ancestral remoto não é diretório.');
  }
  return target;
}
async function readRemote(sftp, file, max = 10 * 1024 * 1024) {
  const kind = await sftp.exists(file);
  if (!kind) return null;
  if (kind !== '-') throw new Error('Era esperado um arquivo regular remoto.');
  if ((await sftp.stat(file)).size > max) throw new Error('Arquivo remoto acima do limite seguro.');
  return sftp.get(file);
}
async function writeAtomic(sftp, root, relative, content) {
  const target = await safePath(sftp, root, relative);
  const parent = path.posix.dirname(target);
  if (!await sftp.exists(parent)) await sftp.mkdir(parent, true);
  await safePath(sftp, root, relative);
  const temporary = `${target}.deploy-${randomUUID()}.tmp`;
  try {
    await sftp.put(content, temporary);
    if (hash(await readRemote(sftp, temporary)) !== hash(content)) throw new Error('Verificação de integridade falhou.');
    await sftp.posixRename(temporary, target);
  } finally { if (await sftp.exists(temporary)) await sftp.delete(temporary); }
}
export async function verifyTarget(sftp, config) {
  const root = remoteRoot(config.remoteDir), backups = remoteRoot(config.backupDir);
  if (backups === root || backups.startsWith(root + '/') || root.startsWith(backups + '/')) throw new Error('Backups devem ficar fora da raiz pública, em diretório separado.');
  for (const directory of [root, backups]) {
    await safePath(sftp, directory);
    if (await sftp.exists(directory) !== 'd' || await sftp.realPath(directory) !== directory) throw new Error('Destino canônico ausente ou diferente do configurado.');
  }
  for (const [directory, filename] of [[root, '.deploy-target.json'], [backups, '.deploy-backups.json']]) {
    const file = await safePath(sftp, directory, filename);
    const data = await readRemote(sftp, file, 4096);
    const marker = data && JSON.parse(data.toString());
    if (marker?.project !== PROJECT || marker.targetId !== config.targetId) throw new Error('Marcador de destino não confere. Valide o diretório manualmente.');
  }
  return { root, backups };
}
export async function buildPlan(sftp, root, files) {
  const changes = [], preserved = [];
  for (const file of files) {
    if (!deployable(file.path)) throw new Error('Arquivo fora da lista de implantação.');
    const target = await safePath(sftp, root, file.path);
    const kind = await sftp.exists(target);
    if (file.preserve && kind) { if (kind !== '-') throw new Error('Arquivo preservado não é regular.'); preserved.push(file.path); continue; }
    const old = await readRemote(sftp, target);
    if (old && hash(old) === file.hash) continue;
    changes.push({ path: file.path, newHash: file.hash, oldHash: old ? hash(old) : null, old, content: file.content });
  }
  return { changes, preserved };
}
const releaseName = value => { if (!/^[a-zA-Z0-9][a-zA-Z0-9_-]{0,100}$/.test(value)) throw new Error('Identificador de release inválido.'); return value; };
async function saveManifest(sftp, backups, release, manifest) {
  await writeAtomic(sftp, backups, `${release}/manifest.json`, Buffer.from(JSON.stringify(manifest, null, 2)));
}
export async function rollback(sftp, config, release) {
  releaseName(release);
  const { root, backups } = await verifyTarget(sftp, config);
  const data = await readRemote(sftp, await safePath(sftp, backups, `${release}/manifest.json`), 1024 * 1024);
  if (!data) throw new Error('Manifesto de rollback não encontrado.');
  const manifest = JSON.parse(data.toString());
  if (manifest.project !== PROJECT || manifest.targetId !== config.targetId || manifest.root !== root || !Array.isArray(manifest.files)) throw new Error('Manifesto não corresponde ao destino.');
  const activeData = await readRemote(sftp, await safePath(sftp, backups, 'active-release.json'), 4096);
  const active = activeData ? JSON.parse(activeData.toString()).release : null;
  if (manifest.state === 'rolled-back') return { restored: 0, release };
  if (manifest.state === 'deployed' && active !== release) throw new Error('Rollback recusado: somente a release ativa pode ser revertida.');
  if (manifest.state === 'ready' && active !== manifest.previousRelease && active !== release) throw new Error('Rollback recusado: outra release foi aplicada após a execução interrompida.');
  if (!['ready', 'deployed', 'rolled-back'].includes(manifest.state)) throw new Error('Manifesto de release incompleto.');
  // Verify every record before the first restore. Preserve files modified after deployment.
  const restore = [];
  for (const file of [...manifest.files].reverse()) {
    if (!deployable(file.path) || !/^[a-f0-9]{64}$/.test(file.newHash) || (file.oldHash !== null && !/^[a-f0-9]{64}$/.test(file.oldHash))) throw new Error('Manifesto inválido.');
    const target = await safePath(sftp, root, file.path), current = await readRemote(sftp, target);
    const currentHash = current ? hash(current) : null;
    if (currentHash === file.oldHash) continue;
    if (currentHash !== file.newHash) throw new Error('Rollback recusado: arquivo de produção mudou após o deploy.');
    const previous = file.oldHash ? await readRemote(sftp, await safePath(sftp, backups, `${release}/files/${file.path}`)) : null;
    if (file.oldHash && (!previous || hash(previous) !== file.oldHash)) throw new Error('Backup ausente ou corrompido.');
    restore.push({ file, target, previous });
  }
  for (const { file, target, previous } of restore) {
    const current = await readRemote(sftp, target);
    if (!current || hash(current) !== file.newHash) throw new Error('Conflito durante rollback.');
    if (previous) await writeAtomic(sftp, root, file.path, previous);
    else await sftp.delete(target);
  }
  manifest.state = 'rolled-back';
  await saveManifest(sftp, backups, release, manifest);
  if (active === release) await writeAtomic(sftp, backups, 'active-release.json', Buffer.from(JSON.stringify({ release: manifest.previousRelease || null })));
  return { restored: restore.length, release };
}
export async function deploy(sftp, config, files, release, health = async () => {}) {
  releaseName(release);
  const { root, backups } = await verifyTarget(sftp, config);
  if (await sftp.exists(`${backups}/${release}`)) throw new Error('Release já existe; não sobrescrever backups.');
  const plan = await buildPlan(sftp, root, files);
  const activeData = await readRemote(sftp, await safePath(sftp, backups, 'active-release.json'), 4096);
  const previousRelease = activeData ? JSON.parse(activeData.toString()).release : null;
  const manifest = { project: PROJECT, targetId: config.targetId, root, release, previousRelease, state: 'preparing', createdAt: new Date().toISOString(), files: [] };
  // Finish all backups before changing the first live application file.
  for (const file of plan.changes) {
    if (file.old) await writeAtomic(sftp, backups, `${release}/files/${file.path}`, file.old);
    manifest.files.push({ path: file.path, newHash: file.newHash, oldHash: file.oldHash });
  }
  manifest.state = 'ready'; await saveManifest(sftp, backups, release, manifest);
  try {
    for (const file of plan.changes) {
      const current = await readRemote(sftp, await safePath(sftp, root, file.path));
      if ((current ? hash(current) : null) !== file.oldHash) throw new Error('Arquivo mudou após o planejamento.');
      await writeAtomic(sftp, root, file.path, file.content);
    }
    await health();
    await writeAtomic(sftp, backups, 'active-release.json', Buffer.from(JSON.stringify({ release })));
    manifest.state = 'deployed'; await saveManifest(sftp, backups, release, manifest);
    return { changed: plan.changes.length, preserved: plan.preserved.length, release };
  } catch {
    try { await rollback(sftp, config, release); }
    catch { throw new Error(`Deploy falhou; rollback incompleto. Consulte o backup ${release} antes de outra tentativa.`); }
    throw new Error(`Deploy falhou; arquivos restaurados pelo rollback ${release}.`);
  }
}

export async function withLock(sftp, config, operation) {
  const { backups } = await verifyTarget(sftp, config);
  const lock = `${backups}/deploy.lock`;
  if (await sftp.exists(lock)) throw new Error('Outro deploy ou recuperação está pendente (deploy.lock).');
  // ssh2-sftp-client.mkdir is idempotent and can silently accept an existing directory.
  // The native SFTP primitive must atomically fail when a concurrent process won.
  await new Promise((resolve, reject) => sftp.sftp.mkdir(lock, { mode: 0o700 }, error => error ? reject(new Error('Outro deploy adquiriu o lock ou faltam permissões.')) : resolve()));
  try { return await operation(); }
  finally { await sftp.rmdir(lock, false); }
}
