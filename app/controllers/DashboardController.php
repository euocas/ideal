<?php

namespace App\Controllers;

use App\Core\Auth;

class DashboardController
{

   public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        // Proteção de rota: redireciona para login se não autenticado
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['usuario'])) {
            //header com caminho mais curto - simplificado
            header('Location: index.php?url=login');
            // header('Location: ../public/index.php?url=login');
            exit;
        }

        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}