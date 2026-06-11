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
<<<<<<< HEAD
// >>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96


}


?>
=======
}
>>>>>>> 80c5133353918af5de5d31c83433c9ed88e014d3
