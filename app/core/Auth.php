<?php

namespace App\Core;

class Auth
{
    public static function verificar()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // Remove cache completamente
        header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");

        // HTTP 1.1
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");

        // HTTP 1.0
        header("Pragma: no-cache");

        // Validação da sessão
        if (!isset($_SESSION['usuario'])) {

            header('Location: /ideal/public/index.php?url=login');

            exit;


        }
    }
}

// explicação: Porque Auth é uma funcionalidade central do sistema. 
//A pasta core normalmente guarda componentes essenciais da aplicação.