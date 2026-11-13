# Migração para Nilo Vesper Fotografia

Identidade fictícia para um projeto demonstrativo da Spartun Digital. O nome não representa a biografia, os clientes ou a autoria fotográfica de uma pessoa real. A composição tipográfica do site foi preservada.

## Fotografias

O responsável pelo projeto confirmou em 13/11/2026 o uso das fotografias atuais, exceto o retrato cinza e os logos de parceiros. Foram mantidos 52 arquivos raster; removidos três exemplares idênticos do retrato, a composição de logos e o favicon antigo. O novo favicon SVG contém as iniciais NV. Ícones funcionais de redes sociais permanecem.

Os arquivos retirados são:

- `assets/paulo-barete.png`
- `assets/about/about_paulo-barete.png`
- `assets/pictures/pictures_gallery_paulo-barete.png`
- `assets/parceiros.png`
- `assets/favicon.ico`

O HTML antigo `index.html.old` também foi retirado. O histórico Git e backups anteriores ainda podem conter esses arquivos; esta alteração não reescreve histórico.

## Aplicação no HelioHost

1. Faça backup do banco e dos arquivos do site. Confirme o caminho absoluto do document root no Plesk. O caminho exibido pelo gerenciador de arquivos pode ser relativo à conta. Não altere `HELIOHOST_REMOTE_DIR` com base apenas no nome do domínio.
2. Revise o PR e valide o destino, as credenciais SFTP e os marcadores conforme `heliohost-deploy.md`. Publicar o código não executa SQL automaticamente.
3. Execute `database/rebrand-nilo-vesper.sql` no banco correto pelo phpMyAdmin. É repetível: inativa os cadastros das imagens retiradas e substitui textos/contatos com a identidade anterior; preserva usuários, senhas, leads e outras fotos. Não execute novamente os scripts antigos que recriam o banco. Revise no painel os textos e contatos restantes, especialmente números de telefone e descrições que não mencionam o nome antigo.
4. Publique o código validado. Os filtros PHP também escondem os arquivos conhecidos antes da migração SQL; o seed de retratos contém apenas 12 imagens aprovadas.
5. **Retire os cinco arquivos acima e `index.html.old` da pasta pública**, movendo-os para um backup privado fora do document root. O deploy SFTP preserva arquivos de produção e não remove arquivos antigos automaticamente. Procure cópias adicionais que tenham sido enviadas com outros nomes. Integre as duas novas regras de bloqueio do `.htaccess` sem perder as regras privadas do servidor. O nginx pode servir imagens sem passar pelo Apache: confirme que cada URL antiga deixa de servir o arquivo e limpe caches/CDN.
6. A configuração preferida agora é `nilo-vesper.private.ini`, no diretório pai do site; `APP_CONFIG_FILE` continua tendo prioridade. O arquivo privado antigo continua aceito como fallback para evitar indisponibilidade. Para migrar, renomeie o arquivo no servidor, mantenha os valores privados e ajuste `SMTP_FROM_NAME="Nilo Vesper Fotografia"`. Não coloque credenciais no Git. `APP_STATE_DIR` continua válido; se renomear a pasta de estado, mova seu conteúdo e atualize esse valor junto.
7. Para usar `nilovesper.spartun.com.br`, crie e valide o domínio no Plesk/DNS, associe o document root correto e emita o certificado HTTPS. Só então atualize `APP_URL` no INI e `HELIOHOST_PUBLIC_URL` nas variáveis do GitHub. Teste antes de redirecionar o endereço anterior. Renomear arquivos ou o repositório não cria DNS nem altera o domínio no HelioHost.
8. Se renomear o repositório no GitHub para `nilo-vesper-fotografia`, atualize a origem Git no Plesk e nos clones. Os marcadores e manifestos antigos de deploy continuam aceitos **somente para este projeto**, com a mesma validação de `targetId` e diretórios. Não crie marcadores para contornar uma divergência de destino.
9. Verifique `/`, `/retratos`, `/personalidades`, `/moda`, `/corporativo`, `/clientes`, `/sobre`, `/contato` e `/login`; confira as fotos, o favicon, a nova marca, o rodapé demonstrativo e os contatos. Teste SMTP separadamente com uma caixa autorizada. Atualize o cadastro do portfólio da Spartun apenas quando o novo endereço estiver funcionando, usando a categoria **Projeto demonstrativo**, sem apresentar uma empresa fictícia como cliente.

## Compatibilidade e rollback

As únicas referências técnicas à identidade anterior ficam nas regras de retirada, testes de regressão, migração de banco e compatibilidade de configuração/backups. Não são exibidas como marca. A pasta local, o nome do repositório e o domínio são recursos externos à substituição de conteúdo.

Para reverter o código, use o rollback documentado. Não restaure o retrato ou os logos para a pasta pública, nem reative os seus cadastros ao restaurar o banco. Se um backup SQL for necessário, reaplique `rebrand-nilo-vesper.sql` antes de liberar o tráfego. Preserve a configuração privada que estiver ativa.

## Validação

`npm test`, `npm run check:php`, `npm run test:security` (MariaDB de teste) e `npm run deploy:plan`. Os testes verificam compatibilidade de configuração e rollback, a migração SQL repetida, páginas com dados antigos, retirada de mídia antes da paginação e as proteções de SQL injection, XSS, autenticação, CSRF e uploads. Testes locais não comprovam DNS, regras nginx/Apache ou configuração de produção.
