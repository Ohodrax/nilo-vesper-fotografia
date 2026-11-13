<?php
// Development test router; Apache/.htaccess must still be verified on HelioHost.
$root = dirname(__DIR__);
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$file = realpath($root . $path);
if ($file && str_starts_with($file, $root . DIRECTORY_SEPARATOR) && is_file($file) && preg_match('#^/(assets|css|scripts|control|ajax)/#', $path)) return false;
require $root . '/index.php';
