<?php

namespace App\Core;

class Auth
{
    public static function verificar()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

           // Impede que o navegador armazene páginas privadas em cache
        // Isso evita que o usuário consiga voltar após logout
        header("Cache-Control: no-cache, no-store, must-revalidate");

        // Compatibilidade com navegadores antigos
        header("Pragma: no-cache");

        // Define expiração imediata da página
        header("Expires: 0");

        if (!isset($_SESSION['usuario'])) {
            header('Location: /ideal/public/index.php?url=login');
            exit;
        }
    }
}

// explicação: Porque Auth é uma funcionalidade central do sistema. 
//A pasta core normalmente guarda componentes essenciais da aplicação.