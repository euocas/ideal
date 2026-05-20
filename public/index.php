<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\EsqueciSenhaController;
use App\Controllers\FuncionariosController;
use App\Controllers\VeiculosController;
use App\Controllers\ClientesController;
use App\Controllers\ObrasController;
use App\Controllers\FinanceiroController;

// ini_set('display_errors', 1);
// error_reporting(E_ALL);

$url = $_GET['url'] ?? 'login';

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

    // --- ROTAS DE FUNCIONÁRIOS ---
    case 'funcionarios':
        $controller = new FuncionariosController();
        $controller->index();
        break;

    case 'funcionarios/create':
        $controller = new FuncionariosController();
        $controller->create();
        break;

    case 'funcionarios/edit':
        $controller = new FuncionariosController();
        $controller->edit();
        break;

    case 'funcionarios/store':
        $controller = new FuncionariosController();
        $controller->store();
        break;

    case 'funcionarios/update':
        $controller = new FuncionariosController();
        $controller->update();
        break;

    case 'funcionarios/delete':
        $controller = new FuncionariosController();
        $controller->delete();
        break;
    // ------------------------------

    // --- ROTA DE VEÍCULOS ---
    case 'veiculos':
        $controller = new VeiculosController();
        $controller->index();
        break;

    // --- ROTA DE CLIENTES---
    case 'clientes':
        $controller = new ClientesController();
        $controller->index();
        break;

       // --- ROTA DE OBRAS---
        case 'obras':
        $controller = new ObrasController();
        $controller->index();
        break;

        // --- ROTA DE OBRAS---
        case 'financeiro':
        $controller = new FinanceiroController();
        $controller->index();
        break;

    default:
        echo "Página não encontrada";
        break;
}
?>