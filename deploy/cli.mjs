import SftpClient from 'ssh2-sftp-client';
import { createHash, randomBytes } from 'node:crypto';
import { readFile, mkdir, writeFile } from 'node:fs/promises';
import { fileURLToPath } from 'node:url';
import { buildPlan, deploy, localFiles, remoteRoot, rollback, verifyTarget, withLock } from './engine.mjs';

const repository = fileURLToPath(new URL('../', import.meta.url));
function required(key) { const value = process.env[key]; if (!value?.trim()) throw new Error(`BLOCKED: configure ${key}.`); return value; }
export function settings() {
  const remoteDir = remoteRoot(required('HELIOHOST_REMOTE_DIR')), backupDir = remoteRoot(required('HELIOHOST_BACKUP_DIR'));
  const publicUrl = new URL(required('HELIOHOST_PUBLIC_URL'));
  if (publicUrl.protocol !== 'https:' || publicUrl.pathname !== '/' || publicUrl.search || publicUrl.hash || publicUrl.username || publicUrl.password) throw new Error('Use a origem HTTPS pública, sem caminho ou credenciais.');
  const php = required('HELIOHOST_PHP_VERSION');
  if (!['8.2', '8.3', '8.4', '8.5'].includes(php)) throw new Error('Versão PHP fora da matriz validada.');
  const fingerprint = required('HELIOHOST_HOST_KEY_SHA256');
  if (!/^SHA256:[A-Za-z0-9+/]{43}=?$/.test(fingerprint)) throw new Error('Fingerprint SSH SHA256 inválido.');
  const port = Number(required('HELIOHOST_PORT'));
  if (!Number.isInteger(port) || port < 1 || port > 65535) throw new Error('Porta SFTP inválida.');
  const password = process.env.HELIOHOST_PASSWORD, privateKey = process.env.HELIOHOST_SSH_KEY;
  if (Boolean(password) === Boolean(privateKey)) throw new Error('Configure somente uma credencial: senha SFTP ou chave privada.');
  return { remoteDir, backupDir, publicUrl, php, targetId: required('HELIOHOST_TARGET_ID'), connection: {
    host: required('HELIOHOST_HOST'), port, username: required('HELIOHOST_USERNAME'), ...(password ? { password } : { privateKey, passphrase: process.env.HELIOHOST_KEY_PASSPHRASE }), readyTimeout: 20000,
    hostVerifier: key => `SHA256:${createHash('sha256').update(key).digest('base64').replace(/=+$/, '')}` === fingerprint.replace(/=+$/, ''),
  } };
}
async function probe(sftp, config) {
  const token = randomBytes(32).toString('hex'), filename = `runtime-${randomBytes(16).toString('hex')}.php`;
  const target = `${config.remoteDir}/${filename}`;
  if (await sftp.exists(target)) throw new Error('Nome temporário já existe.');
  const template = await readFile(new URL('./runtime-probe.php', import.meta.url), 'utf8');
  try {
    await sftp.put(Buffer.from(template.replaceAll('__PROBE_TOKEN__', token)), target);
    const response = await fetch(new URL(filename, config.publicUrl), { headers: { 'X-Deploy-Token': token }, redirect: 'error', signal: AbortSignal.timeout(20000) });
    const data = response.ok && await response.json();
    if (!data || data.token !== token || data.php !== config.php || !['pdo_mysql', 'fileinfo', 'mbstring', 'openssl'].every(extension => data.extensions?.includes(extension)) || data.missing?.length) throw new Error('BLOCKED: versão PHP, extensões, domínio ou configuração externa não confirmados pelo servidor.');
    return { php: data.php, extensions: data.extensions };
  } finally { if (await sftp.exists(target)) await sftp.delete(target); }
}
async function smoke(config) {
  for (const [route, expected] of [['/', 200], ['/retratos', 200], ['/login', 200], ['/__deploy_not_found__', 404]]) {
    const response = await fetch(new URL(route, config.publicUrl), { redirect: 'error', signal: AbortSignal.timeout(20000), headers: { 'Cache-Control': 'no-cache' } });
    await response.body?.cancel();
    if (response.status !== expected) throw new Error('Verificação HTTP pós-deploy falhou.');
  }
}
async function main() {
  const action = process.argv[2] || 'plan';
  if (!['plan', 'validate', 'deploy', 'rollback'].includes(action)) throw new Error('Operação inválida.');
  const files = await localFiles(repository);
  if (action === 'plan') {
    await mkdir(new URL('../.deploy-artifacts/', import.meta.url), { recursive: true });
    await writeFile(new URL('../.deploy-artifacts/manifest.json', import.meta.url), JSON.stringify(files.map(({ path, hash, preserve }) => ({ path, hash, preserve })), null, 2));
    console.log(`Plano local: ${files.length} arquivos permitidos. assets/ e .htaccess existentes serão preservados. Nenhuma conexão remota.`);
    return;
  }
  const config = settings();
  if (['deploy', 'rollback'].includes(action)) {
    if (process.env.HELIOHOST_ENABLED !== 'true' || process.env.HELIOHOST_DESTINATION_VERIFIED !== 'true' || process.env.HELIOHOST_HTACCESS_REVIEWED !== 'true') throw new Error('BLOCKED: implantação e revisão do destino/configuração ainda não habilitadas.');
    if (process.env.GITHUB_EVENT_NAME !== 'push' && process.env.HELIOHOST_CONFIRM_TARGET !== config.remoteDir) throw new Error('Confirme o diretório remoto exato para deploy/rollback.');
  }
  const sftp = new SftpClient();
  try {
    await sftp.connect(config.connection);
    await verifyTarget(sftp, config);
    const result = await withLock(sftp, config, async () => {
      if (action === 'rollback') return rollback(sftp, config, required('HELIOHOST_ROLLBACK_RELEASE'));
      const runtime = await probe(sftp, config);
      if (action === 'validate') {
        const plan = await buildPlan(sftp, config.remoteDir, files);
        // Prove backup write permissions and atomic rename support before application deployment.
        const source = `${config.backupDir}/probe-${randomBytes(16).toString('hex')}`, destination = source + '-renamed';
        try { await sftp.put(Buffer.from('probe'), source); await sftp.posixRename(source, destination); }
        finally { for (const file of [source, destination]) if (await sftp.exists(file)) await sftp.delete(file); }
        return { validated: true, runtime, changes: plan.changes.map(file => file.path), preserved: plan.preserved };
      }
      const release = `${new Date().toISOString().replace(/[^0-9]/g, '')}-${(process.env.GITHUB_SHA || 'local').slice(0, 12)}-${process.env.GITHUB_RUN_ID || randomBytes(4).toString('hex')}`;
      return deploy(sftp, config, files, release, () => smoke(config));
    });
    console.log(JSON.stringify(result, null, 2));
  } finally { await sftp.end(); }
}
if (process.argv[1] && fileURLToPath(import.meta.url) === process.argv[1]) {
  main().catch(error => {
    // Never dump transport objects, raw server errors, credentials or file contents to Actions logs.
    const safe = /^(?:BLOCKED|Configure|Confirme|Use |Versão |Porta |Fingerprint |Diretório |Backups |Destino |Marcador |Outro |Deploy falhou|Rollback recusado|Manifesto |Backup |Arquivo |Release )/.test(error.message);
    console.error(safe ? error.message : 'Operação falhou. Verifique SFTP/TLS, fingerprint, diretórios, permissões e configuração; nenhum sucesso foi presumido.');
    process.exitCode = 1;
  });
}
