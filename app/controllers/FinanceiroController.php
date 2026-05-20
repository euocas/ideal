<?php

namespace App\controllers;

use App\core\Auth;

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