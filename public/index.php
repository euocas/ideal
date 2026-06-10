<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


require_once __DIR__ . '/../vendor/autoload.php';

$rotas = require_once __DIR__ . '/../routes/web.php';

$url = $_GET['url'] ?? 'login';

// <<<<<<< HEAD
// $authController = new AuthController();

// switch ($url) {

//     case 'login':
//         $authController->index();
//         break;

//     case 'logar':
//         $authController->login();
//         break;

//     case 'logout':
//         $authController->logout();
//         break;

//     case 'dashboard':
//         $dashboardController = new DashboardController();
//         $dashboardController->index();
//         break;

//     case 'esqueci-senha':
//         $controller = new EsqueciSenhaController();
//         $controller->index();
//         break;

//     case 'redefinir-senha':
//         $controller = new EsqueciSenhaController();
//         $controller->redefinir();
//         break;

//     // --- ROTAS DE FUNCIONÁRIOS ---
//     case 'funcionarios':
//         $controller = new FuncionariosController();
//         $controller->index();
//         break;

//     case 'funcionarios/create':
//         $controller = new FuncionariosController();
//         $controller->create();
//         break;

//     case 'funcionarios/edit':
//         $controller = new FuncionariosController();
//         $controller->edit();
//         break;

//     case 'funcionarios/store':
//         $controller = new FuncionariosController();
//         $controller->store();
//         break;

//     case 'funcionarios/update':
//         $controller = new FuncionariosController();
//         $controller->update();
//         break;

//     case 'funcionarios/delete':
//         $controller = new FuncionariosController();
//         $controller->delete();
//         break;
//     // ------------------------------

//     // --- ROTA DE VEÍCULOS ---
//     case 'veiculos':
//         $controller = new VeiculosController();
//         $controller->index();
//         break;

//         case 'veiculos/create':
//         $controller = new VeiculosController();
//         $controller->create();
//         break;

//     case 'veiculos/edit':
//         $controller = new VeiculosController();
//         $controller->edit();
//         break;

//     case 'veiculos/store':
//         $controller = new VeiculosController();
//         $controller->store();
//         break;

//     case 'veiculos/update':
//         $controller = new VeiculosController();
//         $controller->update();
//         break;

//     case 'veiculos/delete':
//         $controller = new VeiculosController();
//         $controller->delete();
//         break;

//     // --- ROTA DE CLIENTES---
//     case 'clientes':
//         $controller = new ClientesController();
//         $controller->index();
//         break;

//     case 'clientes/create':
//         $controller = new ClientesController();
//         $controller->create();
//         break;

//     case 'clientes/edit':
//         $controller = new ClientesController();
//         $controller->edit();
//         break;

//     case 'clientes/store':
//         $controller = new ClientesController();
//         $controller->store();
//         break;

//     case 'clientes/update':
//         $controller = new ClientesController();
//         $controller->update();
//         break;

//     case 'clientes/delete':
//         $controller = new ClientesController();
//         $controller->delete();
//         break;

//     // --- ROTA DE OBRAS---
//     case 'obras':
//         $controller = new ObrasController();
//         $controller->index();
//         break;

//     case 'obras/create':
//         $controller = new ObrasController();
//         $controller->create();
//         break;

//     case 'obras/edit':
//         $controller = new ObrasController();
//         $controller->edit();
//         break;

//     case 'obras/store':
//         $controller = new ObrasController();
//         $controller->store();
//         break;

//     case 'obras/update':
//         $controller = new ObrasController();
//         $controller->update();
//         break;

//     case 'obras/delete':
//         $controller = new ObrasController();
//         $controller->delete();
//         break;

//     // --- ROTA DE FINANCEIRO--
//     case 'financeiro':
//         $controller = new FinanceiroController();
//         $controller->index();
//         break;

//     // --- ROTA DE RELATORIOS--
//     case 'relatorios':
//         $controller = new RelatoriosController();
//         $controller->index();
//         break;

//     // --- ROTA DE EMPRESAS--
//     case 'empresas':
//         $controller = new EmpresasController();
//         $controller->index();
//         break;

//     default:
//         echo "Página não encontrada";
//         break;
// =======
if (array_key_exists($url, $rotas)) {
    [$classe, $metodo] = $rotas[$url];
    $controller = new $classe();
    $controller->$metodo();
} else {
    http_response_code(404);
    echo "Página não encontrada";
// >>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96
}
?>