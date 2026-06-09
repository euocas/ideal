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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Rate limiting: máximo de 5 tentativas por IP a cada 5 minutos
        $ip      = $_SERVER['REMOTE_ADDR'] ?? 'desconhecido';
        $chave   = 'login_tentativas_' . md5($ip);
        $limite  = 5;
        $janela  = 5 * 60; // segundos

        $tentativas  = $_SESSION[$chave]['count']        ?? 0;
        $bloqueadoAte = $_SESSION[$chave]['bloqueado_ate'] ?? 0;

        if (time() < $bloqueadoAte) {
            $restam = ceil(($bloqueadoAte - time()) / 60);
            header('Location: index.php?url=login&erro=bloqueado&min=' . $restam);
            exit;
        }

        // Reseta contador se a janela de tempo expirou
        if (isset($_SESSION[$chave]['desde']) && (time() - $_SESSION[$chave]['desde']) > $janela) {
            unset($_SESSION[$chave]);
            $tentativas = 0;
        }

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

        // Usuário não encontrado ou senha incorreta — mesma mensagem para não revelar qual falhou
        if (!$usuario || !password_verify($senha, $usuario['senha'])) {
            $tentativas++;
            $_SESSION[$chave]['count']  = $tentativas;
            $_SESSION[$chave]['desde'] ??= time();

            if ($tentativas >= $limite) {
                $_SESSION[$chave]['bloqueado_ate'] = time() + $janela;
                header('Location: index.php?url=login&erro=bloqueado&min=5');
                exit;
            }

            header('Location: index.php?url=login&erro=credenciais');
            exit;
        }

        // Login bem-sucedido: zera o contador
        unset($_SESSION[$chave]);

        // Remover dados sensíveis antes de armazenar na sessão
        unset($usuario['senha']);

        // Regenerar ID da sessão para evitar session fixation
        session_regenerate_id(true);

        $_SESSION['usuario'] = $usuario;
        header('Location: index.php?url=dashboard');
        exit;
    }


    public function logout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // limpa sessão
        $_SESSION = [];

        session_unset();

        // remove cookie da sessão
        if (ini_get("session.use_cookies")) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        // destrói sessão
        session_destroy();

        // impede cache
        header("Cache-Control: no-cache, no-store, must-revalidate, max-age=0");
        header("Pragma: no-cache");
        header("Expires: 0");

        // redireciona
        header('Location: index.php?url=login');

        exit;
    }


}