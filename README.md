# Nilo Vesper Fotografia

Projeto demonstrativo da Spartun Digital com identidade fictícia. Para aplicar a troca de marca e retirar os arquivos antigos do HelioHost, siga [a migração de identidade](docs/rebrand-nilo-vesper.md).

Aplicação PHP com roteamento Apache, páginas em `views/`, endpoints em `control/` e `ajax/`, PDO/MySQL em `bin/classes/` e PHPMailer em `vendor/`. Os uploads da administração ficam em `assets/<referência>/`; não são um diretório descartável de build.

Banco, URL pública e SMTP são configurados externamente. Veja os nomes fictícios em `.env.example` e o procedimento completo em [docs/heliohost-deploy.md](docs/heliohost-deploy.md).

Ferramentas Node são usadas somente em desenvolvimento e no GitHub Actions; Node e Composer não são necessários no servidor para este fluxo. A implantação usa SFTP, sem executar comandos SSH no servidor.

```text
npm ci --ignore-scripts
npm test
npm run check:php
npm run deploy:plan
```

`deploy:plan` é local e não acessa o servidor. A publicação permanece desativada até revisar o PR, rotacionar as credenciais antigas e validar o destino e os secrets. O projeto é independente do Spartun Digital.

Configuração do banco e criação segura do administrador: [docs/database-setup.md](docs/database-setup.md).

Preparação automática de fotos grandes e limites da hospedagem: [docs/image-uploads.md](docs/image-uploads.md).
