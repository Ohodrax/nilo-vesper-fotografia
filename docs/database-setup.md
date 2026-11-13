# Banco e credenciais no HelioHost

As credenciais do banco não devem ser editadas no PHP nem enviadas ao GitHub. Configure `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER` e `DB_PASSWORD` em variáveis do processo PHP ou em `nilo-vesper.private.ini` fora da raiz pública, conforme [heliohost-deploy.md](heliohost-deploy.md). O nome e o usuário devem ser copiados exatamente do painel, inclusive prefixos. A senha SMTP é independente da senha do banco.

## Se o banco ainda está vazio

1. Selecione o banco já criado no phpMyAdmin.
2. Importe [database/schema.sql](../database/schema.sql). Ele não executa DROP, não cria outro banco e não contém contas com senha padrão. Se já houver tabelas com esses nomes, pare e use a opção de migração abaixo.
3. Opcionalmente importe [database/seed-pictures.sql](../database/seed-pictures.sql). As 13 imagens referenciadas existem no repositório. O seed evita duplicação ao repetir; não modifica arquivos.
4. No computador local, na pasta do projeto, execute `php deploy/admin-setup.php`. Informe o e-mail do administrador. O comando gera uma senha aleatória e o arquivo privado `.deploy-artifacts/admin-setup.sql`; não acessa o banco.
5. Guarde a senha em um gerenciador, importe o SQL gerado pelo phpMyAdmin e apague o arquivo local depois. Ele contém um hash de senha e não deve ser compartilhado ou versionado. O comando cria ou atualiza apenas o usuário de ID 1.
6. Configure as credenciais privadas no servidor, entre no painel e preencha textos e contatos. Valide login, imagens e formulário antes de liberar o domínio.

## Se já importou script_heliohost.sql ou script.sql

Faça backup e importe somente [database/upgrade-security.sql](../database/upgrade-security.sql). A migração amplia o hash de recuperação para 64 caracteres e invalida links antigos; preserva contas, imagens, contatos e textos. Ela deve acompanhar esta versão do código. Gere uma nova senha para o administrador com o procedimento acima, substituindo a conta inicial do script antigo.

Os dois scripts anexados originalmente são alternativas de instalação, não etapas sequenciais. Ambos contêm DROP TABLE e apagam dados. `script.sql` ainda tenta criar/selecionar um banco de nome fixo. Não os reimporte sobre um banco em uso. O seed original insere as mesmas linhas novamente se repetido; prefira o seed revisado.

## Conferência antes de produção

- As senhas de banco e SMTP antes versionadas precisam ser rotacionadas/revogadas no provedor; tornar o repositório privado não revoga segredos do histórico.
- Valide no HelioHost o acesso do PHP à configuração privada, ao banco, às sessões e à pasta de contadores; teste também o SMTP e a recuperação por e-mail.
- Confira que URLs para `/bin/`, `/vendor/`, `/database/`, arquivos privados e executáveis dentro de `/assets/` estão bloqueadas.
- O rollback SFTP restaura arquivos; não restaura o banco. A ampliação CHAR(64) é compatível com o tamanho de hashes antigos, mas não se recomenda restaurar a versão vulnerável. Mantenha um backup do banco separado.

Os testes locais não alteraram o banco HelioHost e não validaram suas credenciais.
