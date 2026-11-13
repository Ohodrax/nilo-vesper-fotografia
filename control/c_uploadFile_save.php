<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_post();
require_admin();
rate_limit('upload', 30, 60);
$reference = image_reference(post_text('reference', 30));
$location = post_text('localization', 30);
if (!in_array($location, ['carrossel_principal', 'gallery', 'capa_principal', 'banner_1', 'banner_2'], true)) json_result(false, 'Localização inválida.', 400);
$file = $_FILES[0] ?? null;
$limits = image_upload_limits();
$postLimit = ini_size_bytes((string) ini_get('post_max_size'));
if ($postLimit > 0 && (int) ($_SERVER['CONTENT_LENGTH'] ?? 0) > $postLimit) json_result(false, 'O envio excedeu post_max_size no PHP. Atualize a página ou revise o limite na hospedagem.', 413);
if (in_array($file['error'] ?? null, [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE], true)) json_result(false, 'O arquivo excedeu o limite de envio do PHP na hospedagem. Atualize a página para adaptar a foto ao limite atual.', 413);
if (!$file || !is_string($file['tmp_name'] ?? null) || ($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK || !is_uploaded_file($file['tmp_name'])) json_result(false, 'O arquivo não chegou completo. Selecione a foto novamente e tente enviar.', 400);
if (filesize($file['tmp_name']) > $limits['maxBytes']) json_result(false, 'A cópia enviada ultrapassa o limite de tamanho da hospedagem. Atualize a página para otimizar a foto.', 413);
$info = @getimagesize($file['tmp_name']);
if (!$info || !in_array($info[2], [IMAGETYPE_JPEG, IMAGETYPE_PNG], true) || $info[0] < 1 || $info[1] < 1) json_result(false, 'Formato inválido. Envie uma imagem JPEG ou PNG; converta HEIC, RAW e SVG antes.', 400);
if ($info[0] * $info[1] > $limits['maxPixels']) json_result(false, 'A resolução da foto (' . $info[0] . ' × ' . $info[1] . ' pixels) excede o limite de processamento. Atualize a página para usar a adaptação automática.', 400);
$expectedMime = $info[2] === IMAGETYPE_PNG ? 'image/png' : 'image/jpeg';
if ((new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']) !== $expectedMime) json_result(false, 'Tipo de imagem inválido.', 400);
if (!extension_loaded('gd')) json_result(false, 'Processamento de imagem indisponível.', 503);
$image = $info[2] === IMAGETYPE_PNG ? @imagecreatefrompng($file['tmp_name']) : @imagecreatefromjpeg($file['tmp_name']);
if (!$image) json_result(false, 'Imagem inválida.', 400);
$name = $reference . '_' . $location . '_' . bin2hex(random_bytes(16)) . ($info[2] === IMAGETYPE_PNG ? '.png' : '.jpg');
$target = asset_path($reference, $name);
if ($info[2] === IMAGETYPE_PNG) { imagealphablending($image, false); imagesavealpha($image, true); }
$pdo = (new mySqlConnection())->newConnection();
$saved = $info[2] === IMAGETYPE_PNG ? imagepng($image, $target, 6) : imagejpeg($image, $target, 90);
unset($image);
if (!$saved) json_result(false, 'Não foi possível salvar a imagem.', 500);
try {
    $pdo->beginTransaction();
    if (!in_array($location, ['gallery', 'carrossel_principal'], true)) {
        $stmt = $pdo->prepare('DELETE FROM tb_image WHERE referencia = ? AND localizacao = ?');
        $stmt->execute([$reference, $location]);
    }
    $stmt = $pdo->prepare('INSERT INTO tb_image (nome, referencia, localizacao, criado_por, alterado_por) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$name, $reference, $location, $_SESSION['userName'], $_SESSION['userName']]);
    $pdo->commit();
} catch (Throwable $error) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    unlink($target);
    throw new RuntimeException('Não foi possível registrar a imagem.');
}
// Old files are preserved for backup/manual cleanup after replacement.
json_result(true, 'Imagem salva com sucesso.');
