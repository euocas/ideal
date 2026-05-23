<?php

namespace App\Controllers;

use App\Core\Auth;

require_once __DIR__ . '/../core/Auth.php';

class VeiculosController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        require_once __DIR__ . '/../views/veiculos/index.php';

    }
}

    // public function index()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         return $this->buscar();
    //     }

    //     $mensagem = null;
    //     require_once __DIR__ . '/../views/funcionarios/index.php';
    // }

