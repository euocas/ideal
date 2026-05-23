<?php

namespace App\Controllers;

use App\Core\Auth;

require_once __DIR__ . '/../core/Auth.php';

class ClientesController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        require_once __DIR__ . '/../views/clientes/index.php';

    }
}