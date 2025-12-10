<?php
require_once '../config/routes.php';

$uri = $_SERVER['REQUEST_URI'];
$uri = str_replace('/index.php', '', $uri);
$uri = parse_url($uri, PHP_URL_PATH);

route($uri);
?>
