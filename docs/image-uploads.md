# Upload de fotografias

Megapixels medem largura × altura; MiB medem o tamanho do arquivo. Uma foto de 6000 × 4000 tem 24 megapixels mesmo quando seu JPEG ocupa menos de 8 MiB. A validação anterior recusava essa resolução antes de preparar a foto para a web.

O painel agora cria uma cópia otimizada no navegador, antes do envio:

- Aceita originais JPEG/PNG de até 40 MiB e 60 megapixels; arquivos maiores precisam ser exportados em tamanho menor. HEIC, RAW e SVG precisam ser convertidos.
- Limita a maior dimensão a 3200 pixels e a área a até 8 megapixels, preservando proporção e orientação EXIF. Não amplia fotos pequenas.
- Preserva transparência PNG; JPEG é recodificado com qualidade 90%. Se necessário, reduz mais a resolução para caber no limite de bytes.
- Lê os limites efetivos de `upload_max_filesize`, `post_max_size` e memória PHP para preparar uma cópia compatível com a hospedagem, com margem para o formulário e processamento.
- Mantém o arquivo original no dispositivo. A cópia enviada perde metadados embutidos; autoria, descrição e arquivo original devem ser preservados separadamente pelo responsável.

O servidor continua verificando autenticação, CSRF, tamanho, dimensões, MIME real e decodificação, e recodifica a imagem com GD antes de gravar. A preparação no navegador não substitui a validação do servidor. Mensagens de falha do servidor agora aparecem no painel; sucesso só aparece após confirmação de gravação.

## HelioHost

Publique juntos `bin/image-upload.php`, a alteração em `bin/_environment.php`, `control/c_uploadFile_save.php`, `scripts/image-upload.js`, `scripts/_admin.js` e `views/admin/administration_view.php` (o fluxo SFTP já os inclui). Depois atualize a página administrativa; ela recebe os limites do PHP atual. As extensões GD e Fileinfo devem estar habilitadas.

Se ocorrer HTTP 413 mesmo com a cópia otimizada, confira os limites de envio do nginx/Apache no Plesk. O PHP não consegue consultar uma restrição menor imposta pelo proxy. Não é necessário remover as proteções de resolução para aceitar fotografias grandes.

## Testes

`npm test`, `npm run check:php` e `npm run test:security` verificam o fluxo e as proteções do servidor. Execute `npx playwright install chromium` e `npm run test:images` para testar num navegador real: JPEG de 24 MP, transparência PNG, orientação EXIF, originais pequenos, limites menores de PHP e formatos inválidos. Localmente, `PLAYWRIGHT_CHANNEL=chrome` permite usar Chrome instalado. O CI executa esses testes automaticamente.
