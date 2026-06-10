<?php
namespace App\Controllers;

use App\Core\Auth;

class EmpresasController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        require_once __DIR__ . '/../Views/empresas/index.php';

    }
}

