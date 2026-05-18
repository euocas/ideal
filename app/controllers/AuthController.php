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
        // Validação básica dos campos
        if (empty($email) || empty($senha)) {
            header('Location: index.php?url=login&erro=campos');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: index.php?url=login&erro=campos');
            exit;
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorEmail($email);

        // Usuário não encontrado
        if (!$usuario) {
            header('Location: index.php?url=login&erro=usuario&email=' . urlencode($email));
            exit;
        }

        // Senha incorreta
        if (!password_verify($senha, $usuario['senha'])) {
            header('Location: index.php?url=login&erro=senha&email=' . urlencode($email));
            exit;
        }

        // Remover dados sensíveis antes de armazenar na sessão
        unset($usuario['senha']);

        // Regenerar ID da sessão para evitar session fixation
        session_regenerate_id(true);

        $_SESSION['usuario'] = $usuario;
        header('Location: index.php?url=dashboard');
        exit;
    }

    public function logout(){
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
            
        }
        session_destroy();

        header('Location: index.php?url=login');
        exit;
    }


}