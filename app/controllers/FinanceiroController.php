<?php

namespace App\Controllers;

use App\Core\Auth;

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