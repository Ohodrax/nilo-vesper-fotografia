# Publicação HelioHost por SFTP

## Estado e bloqueios

Nenhum deploy foi executado em produção. `npm run deploy:validate` retornou **BLOCKED: configure HELIOHOST_REMOTE_DIR**. Host, usuário, credencial SFTP, fingerprint SSH, raiz remota e versão PHP do servidor não foram disponibilizados. Os testes locais usam um servidor SFTP simulado em memória, não comprovam acesso real ao HelioHost. PHP local validado: 8.2.12; a matriz GitHub Actions verifica 8.2, 8.3, 8.4 e 8.5, e o preflight verifica a versão realmente selecionada no servidor.

Havia credenciais de banco **e SMTP** no código versionado. Foram removidas dos arquivos atuais, mas permanecem no histórico e em clones anteriores. **Rotacione ambas no provedor antes da ativação**, revogue os valores antigos e preencha apenas a configuração privada. Não restaure um commit antigo para recuperar credenciais. A limpeza do histórico exige coordenação e não é feita automaticamente por este PR.

## Estrutura e requisitos

- Apache com `mod_rewrite`, `AllowOverride` para as regras aplicáveis e PHP 8.2–8.5. Não presumir que o document root se chame `httpdocs`; confirmar no painel e por SFTP. A aplicação usa `DOCUMENT_ROOT` e URLs absolutas, portanto deve ocupar a raiz do domínio, não um subdiretório.
- Extensões: `pdo_mysql`, `fileinfo`, `mbstring`, `openssl`, `gd`. Selecionar e conferir no PHP do domínio; a versão CLI do provedor pode ser diferente da versão FPM/Apache. Desativar `display_errors` e `display_startup_errors` no painel PHP; configurar diretórios privados graváveis para sessões e uploads temporários.
- Provisionar o banco pelo procedimento em [database-setup.md](database-setup.md). O deploy não executa SQL. A aplicação atual usa transações PDO e não depende de stored procedures.
- `index.php` encaminha rotas a `Router.php`/`RouteSwitch.php`. Rotas: `/`, `/home`, `/retratos`, `/personalidades`, `/moda`, `/corporativo`, `/clientes`, `/sobre`, `/contato`, `/login`, `/logout`, `/recovery` e `/admin`. Rotas desconhecidas retornam 404, inclusive nomes de métodos internos. Query strings e barra final são aceitas.
- `assets/` contém mídia de produção, inclusive uploads. Arquivos existentes são preservados; arquivos versionados ausentes podem ser provisionados sem sobrescrever mídia.
- PHPMailer atualizado para 7.2.0, com `composer.lock` e auditoria na CI. Os testes de segurança usam MariaDB isolado e HTTP para exercitar SQL injection, escape de HTML, autorização, CSRF, uploads e recuperação de senha. A homologação no servidor real continua obrigatória.

## Configuração privada da aplicação

`bin/config.php` lê variáveis de processo primeiro. Como alternativa, lê um INI privado **fora da raiz pública**, cujo caminho absoluto pode ser informado por `APP_CONFIG_FILE`. Sem esta variável, procura `paulo-bareta.private.ini` no diretório pai da raiz da aplicação. Nunca coloca o arquivo privado no pacote ou no Git. `.env.example` documenta os nomes, mas o PHP **não carrega `.env` automaticamente**.

Exemplo fictício de INI, a ser criado pelo administrador no servidor:

```ini
APP_URL="https://portfolio.example.com"
APP_STATE_DIR="/example/private/paulo-bareta-state"
DB_HOST="db.example.com"
DB_PORT="3306"
DB_NAME="example_database"
DB_USER="example_user"
DB_PASSWORD="replace-with-rotated-secret"
SMTP_HOST="smtp.example.com"
SMTP_PORT="587"
SMTP_SECURITY="tls"
SMTP_USER="mailbox@example.com"
SMTP_PASSWORD="replace-with-rotated-secret"
SMTP_FROM="mailbox@example.com"
SMTP_FROM_NAME="Portfolio Example"
```

Restrinja leitura ao usuário PHP/SFTP necessário (por exemplo, 0600/0640 conforme o provedor) e permita o caminho no `open_basedir` do domínio. Se o provedor não permitir variável de processo nem leitura fora do webroot, registre o bloqueio e ajuste o ambiente; não copie o segredo para arquivo público. Banco usa `utf8mb4`; Host HTTP não seleciona credenciais nem URL pública. SMTP exige certificado válido e 587/STARTTLS ou 465/TLS. Logs não exibem senha, DSN ou resposta SMTP bruta.

`APP_STATE_DIR` guarda contadores de tentativas fora da raiz pública; precisa ser gravável pelo PHP e ter acesso restrito (0700). Sem a variável, usa `paulo-bareta-state` no diretório pai da aplicação. O limitador é compartilhado entre sessões no mesmo servidor. Não substitui uma proteção de borda contra ataques distribuídos. A aplicação usa cookies HttpOnly/SameSite=Lax, Secure quando APP_URL é HTTPS, timeout de sessão de 30 minutos e revalidação do usuário no banco a cada request. Apenas perfil 1 administra usuários. Troca de senha invalida sessões anteriores.

Uploads aceitam PNG/JPEG até 8 MiB e 8 megapixels. Os bytes são decodificados e regravados pelo GD, com nome aleatório; nomes e caminhos enviados pelo navegador não determinam o destino. A substituição preserva arquivos antigos para backup/limpeza manual. Atualize e verifique as regras de `.htaccess` antes da ativação: seu arquivo remoto existente é preservado pelo deploy, portanto essa revisão precisa ser manual.

## Configurar GitHub Actions

Crie o Environment **heliohost-production**, com revisão obrigatória e branches limitadas a `main`. O workflow roda testes em PRs, mas o job com secrets só pode executar em `main`. Não use `pull_request_target`.

Secrets do Environment:

| Nome | Valor |
| --- | --- |
| `HELIOHOST_HOST` | Host SFTP confirmado no provedor. |
| `HELIOHOST_USERNAME` | Usuário SFTP autorizado. |
| `HELIOHOST_PASSWORD` | Senha SFTP; usar somente se não houver chave. |
| `HELIOHOST_SSH_KEY` | Chave privada alternativa, se o provedor aceitar; não usar junto com senha. |
| `HELIOHOST_KEY_PASSPHRASE` | Opcional para chave protegida. |
| `HELIOHOST_HOST_KEY_SHA256` | Fingerprint SSH no formato `SHA256:...`, verificado por canal confiável. Nunca aceitar automaticamente a primeira chave recebida. |

Variáveis do Environment:

| Nome | Configuração |
| --- | --- |
| `HELIOHOST_PORT` | Porta SFTP informada pelo provedor. Não há porta padrão no código. |
| `HELIOHOST_REMOTE_DIR` | Raiz absoluta e canônica do domínio, confirmada manualmente. Não usar `/`, caminhos relativos ou symlinks. |
| `HELIOHOST_BACKUP_DIR` | Diretório absoluto existente, separado e fora da raiz pública, com escrita SFTP. Não pode ser pai/filho da raiz publicada. Não deve ser publicado por outro domínio. |
| `HELIOHOST_TARGET_ID` | Identificador exclusivo escolhido para este destino. |
| `HELIOHOST_PUBLIC_URL` | Origem HTTPS do domínio, sem caminho, query ou redirecionamento. |
| `HELIOHOST_PHP_VERSION` | Versão menor confirmada, como `8.2`; deve pertencer à matriz de CI. |
| `HELIOHOST_ENABLED` | Inicialmente `false`. Habilita escrita da aplicação somente após validação. |
| `HELIOHOST_DESTINATION_VERIFIED` | Inicialmente `false`; `true` somente após conferir domínio e raiz. |
| `HELIOHOST_HTACCESS_REVIEWED` | Inicialmente `false`; confirme que as regras de rotas e proteção foram conciliadas com a configuração do servidor. |

Variável **do repositório** `HELIOHOST_AUTO_DEPLOY`: ausente/`false` por padrão. Só definir `true` depois da homologação para publicar automaticamente em pushes para `main`, sujeito ao Environment. O job ainda exige as três habilitações acima e repete o preflight. Secrets da aplicação PHP ficam no servidor, não são transportados pelo workflow.

## Confirmar diretórios e testar acesso

1. Confira no Plesk o document root exato, a versão PHP selecionada e a URL HTTPS. Faça backup independente de arquivos, uploads e banco existentes. Não use o backup de código como backup do banco.
2. Conecte por SFTP com as mesmas credenciais e fingerprint verificadas. Na raiz do domínio crie **manualmente** `.deploy-target.json`; no diretório privado de backups crie `.deploy-backups.json`, ambos com:

   ```json
   { "project": "Ohodrax/paulo-bareta-fotografia", "targetId": "example-confirmed-target" }
   ```

   O `targetId` deve corresponder à variável. O workflow nunca cria estes marcadores nem escolhe a raiz por você.
3. Concilie `.htaccess` com quaisquer diretivas próprias do provedor. O deploy não sobrescreve `.htaccess` existente. Garanta bloqueio HTTP de dotfiles, INI/segredos, `bin/`, `vendor/`, ferramentas, testes e execução de PHP em `assets/`. A versão no repositório é referência; não remova diretivas privadas de produção.
4. Configure as variáveis/INI privados, com credenciais rotacionadas. Verifique acesso de leitura pelo PHP e permissões de uploads em `assets/`.
5. Após merge/revisão, execute o workflow manual com **operation=validate**. Ele confirma autenticação e fingerprint, recusa symlinks, verifica marcadores e caminhos canônicos, coloca um pequeno probe PHP temporário com nome/token aleatórios e consulta-o por HTTPS. Isso comprova a correspondência domínio/diretório, versão PHP, extensões e presença de configuração privada. O probe não usa `phpinfo()`, não revela segredos e é removido no `finally`. Se o processo for interrompido abruptamente, remova apenas o probe aleatório daquela execução após conferência.
6. O validate também testa escrita/rename atômico no diretório privado de backup e apresenta a lista de arquivos a alterar/preservar. Ele não substitui arquivos da aplicação, mas **faz escritas temporárias de diagnóstico** nos diretórios já validados. Não é uma operação puramente de leitura. Sem `posix-rename`, o fluxo para e requer outro mecanismo validado; não há fallback inseguro que apague o arquivo original.
7. Homologue contato, login, recuperação, navegação pública, banco e um upload em ambiente de teste. Presença de variáveis não comprova senha/entrega SMTP nem funcionamento de todas as stored procedures. Registre as evidências; falhas não devem ser tratadas como aprovação.

## Deploy e rollback

Depois de revisar o plano e aprovar os testes, habilite as variáveis. Execute **operation=deploy**, repetindo o valor exato de `HELIOHOST_REMOTE_DIR` em `confirm_target`. O fluxo:

1. Valida destino/runtime, adquire um lock remoto no diretório de backups e calcula alterações por hash.
2. Copia todos os arquivos existentes que serão alterados para `<BACKUP_DIR>/<release>/files/`, com hashes e manifesto privado, antes de alterar a aplicação.
3. Envia cada arquivo para nome temporário, confere SHA-256 e faz rename atômico por arquivo. Não executa `mirror --delete`, não remove arquivos desconhecidos e não toca configs privadas ou uploads existentes. Git, testes, documentação, Node e arquivos de exemplo ficam fora da lista de deploy.
4. Confere HTTP `/`, `/retratos`, `/login` e uma rota 404. Falha de upload ou health check aciona rollback dos arquivos alterados; falha do próprio rollback é registrada como incompleta. O ID de release é exibido para recuperação.

Não há troca atômica de toda a árvore: use janela de manutenção para alterações incompatíveis entre arquivos. Não há migração/rollback de dados. Arquivos versionados removidos do Git não são apagados automaticamente da produção; descomissioná-los exige plano revisado separado. O diretório de backups contém código antigo que pode incluir segredos legados: acesso privado, rotação prévia e retenção administrada são obrigatórios. Nada disso é enviado como artifact público de Actions.

Para rollback manual, escolha **operation=rollback**, `rollback_release` e o mesmo `confirm_target`. Só a release ativa pode ser revertida normalmente. O processo verifica os hashes antes de escrever, restaura backups e remove apenas arquivos criados por aquela release que ainda correspondem ao hash publicado. Se houve edição posterior em produção, ele recusa sobrescrever e exige revisão manual. Backups não são apagados automaticamente.

Se uma execução for cancelada/interrompida, `deploy.lock` pode permanecer. Verifique que não há outro job rodando, inspecione o manifesto (`ready` pode indicar aplicação parcial), restaure a release interrompida e só então retome operações. Para permitir a recuperação pelo workflow, remova **somente** o diretório vazio `deploy.lock` após confirmar que o job terminou. Nunca faça exclusão recursiva da raiz/backups. Uma falha em preparar backups antes do primeiro arquivo da aplicação não altera o site; nesse caso o manifesto pode não existir.

## Testes

`npm test` cobre lista de arquivos permitidos, destino errado, path traversal, symlinks, preservação de uploads/configurações, integridade, lock, falhas e rollback. `npm run check:php` verifica sintaxe em todos os PHP e executa configuração sem segredos, DSN, isolamento do Host HTTP, autoload, propriedades compatíveis com PHP 8.2+ e rotas. `npm run deploy:plan` gera somente manifesto local ignorado. `npm run deploy:validate` requer configuração real; sem ela deve falhar.

Documentação de referência: [SFTP HelioHost](https://wiki.helionet.org/Uploading_Files), [PHP/extensões no HelioHost](https://wiki.helionet.org/PHP), [GitHub Environments](https://docs.github.com/en/actions/how-tos/deploy/configure-and-manage-deployments/manage-environments), [ssh2-sftp-client](https://github.com/theophilusx/ssh2-sftp-client).
