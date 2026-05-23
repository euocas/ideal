<?php

namespace App\Controllers;

use App\Core\Auth;

require_once __DIR__ . '/../core/Auth.php';

class FinanceiroController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        require_once __DIR__ . '/../views/financeiro/index.php';

    }
}