<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\EsqueciSenhaController;

ini_set('display_errors', 1);
error_reporting(E_ALL);

$url = $_GET['url'] ?? 'login';

// $method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController();

switch ($url) {

    case 'login':
        $authController->index();
        break;

    case 'logar':
        $authController->login();
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'dashboard':
        $dashboardController = new DashboardController();
        $dashboardController->index();
        break;

    case 'esqueci-senha':
        $controller = new EsqueciSenhaController();
        $controller->index();
        break;

    case 'redefinir-senha':
        $controller = new EsqueciSenhaController();
        $controller->redefinir();

        break;

    default:
        echo "Página não encontrada";
        break;
}
?>