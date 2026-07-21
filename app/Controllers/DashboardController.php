<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Dashboard;

class DashboardController
{

    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        $dashboard = new Dashboard();

        $indicadores = $dashboard->getIndicadores();

        require_once __DIR__ . '/../Views/dashboard/index.php';
    }



}

