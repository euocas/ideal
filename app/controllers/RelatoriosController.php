<?php
namespace App\Controllers;

use App\Core\Auth;

class RelatoriosController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        require_once __DIR__ . '/../views/relatorios/index.php';

    }
}