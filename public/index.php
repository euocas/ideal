<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('America/Sao_Paulo');

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = \Dotenv\Dotenv::createImmutable(__DIR__ . '/..');

$dotenv->safeLoad();
$rotas = require_once __DIR__ . '/../Routes/web.php';

define('BASE_URL', rtrim($_ENV['APP_URL'] ?? '', '/'));

$url = $_GET['url'] ?? 'login';

if (array_key_exists($url, $rotas)) {
    [$classe, $metodo] = $rotas[$url];
    $controller = new $classe();
    $controller->$metodo();
} else {
    http_response_code(404);
    echo "Página não encontrada";

}

?>

