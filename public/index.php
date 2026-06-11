<?php

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$rotas = require_once __DIR__ . '/../Routes/web.php';

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

