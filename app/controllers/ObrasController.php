<?php

namespace App\Controllers;

use App\Core\Auth;
class ObrasController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        require_once __DIR__ . '/../views/obras/index.php';

    }
}