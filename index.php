<?php
require_once __DIR__ . '/bin/_environment.php';
require_once __DIR__ . '/Router.php';
(new Router())->run($_SERVER['REQUEST_URI']);
