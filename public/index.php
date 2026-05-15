<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../app/controllers/AuthController.php';
require_once '../app/controllers/DashboardController.php';

$url = $_GET['url'] ?? 'login';

$method = $_SERVER['REQUEST_METHOD'];

$authController = new AuthController();

switch ($url) {

    case 'login':

        if ($method === 'POST') {

            $authController->login();

        } else {

            $authController->index();
        }

        break;

         case 'dashboard':

        $dashboardController = new DashboardController();

        $dashboardController->index();

        break;

    default:

        echo "Página não encontrada";
}