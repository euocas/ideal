<?php
namespace App\Controllers;
use App\Models\Usuario;

class AuthController
{
    public function index()
    {
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        session_start();
        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        $usuarioModel = new Usuario();

        $usuario = $usuarioModel->buscarPorEmail($email);

        if ($usuario) {

            if (password_verify($senha, $usuario['senha'])) {

                session_start();

                $_SESSION['usuario'] = $usuario;

                header('Location: index.php?url=dashboard');
                exit;
            }
        }

        $_SESSION['erro'] = 'Email ou senha inválidos';

        header('Location: index.php?url=login');
        exit;
    }

}