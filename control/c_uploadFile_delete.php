<?php
require_once dirname(__DIR__) . '/bin/_environment.php';
require_post();
require_admin();
$id = filter_var($_POST['targetId'] ?? '', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) json_result(false, 'Imagem inválida.', 400);
$db = new db_crud();
$db->table = 'tb_image';
$db->filters = [['id_image', '=', $id]];
$image = $db->default_select_data()[0] ?? null;
if (!$image) json_result(false, 'Imagem não encontrada.', 404);
$path = asset_path($image['referencia'], $image['nome']);
$db->id = $id;
if (!$db->default_delete_data()) json_result(false, 'Não foi possível excluir a imagem.', 500);
if (is_file($path) && !unlink($path)) json_result(true, 'Referência removida; o arquivo precisa de limpeza manual.');
json_result(true, 'Imagem excluída.');
