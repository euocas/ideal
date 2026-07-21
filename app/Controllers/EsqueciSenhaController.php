<?php

namespace App\Controllers;

use App\Models\Usuario;
use App\Models\RecuperacaoSenha;
use App\Config\Mailer;

class EsqueciSenhaController
{
    private function iniciarSessao(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * ETAPA 1 - Tela para informar o e-mail
     */
    public function index()
    {
        $this->iniciarSessao();
        $etapa = 'email';

        require_once __DIR__ . '/../Views/auth/esqueceuSenha.php';
    }

    /**
     * ETAPA 1 (POST) - Gera o código e envia por e-mail
     */
    public function enviarCodigo()
    {
        $this->iniciarSessao();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=esqueci-senha');
            exit;
        }

        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Location: index.php?url=esqueci-senha&erro=campos');
            exit;
        }

        // Rate limit simples: no máximo 1 envio a cada 60 segundos por e-mail
        $chaveLimite = 'recuperacao_ultimo_envio_' . md5($email);
        if (isset($_SESSION[$chaveLimite]) && (time() - $_SESSION[$chaveLimite]) < 60) {
            $_SESSION['recuperacao_email'] = $email;
            header('Location: index.php?url=esqueci-senha/verificar&erro=aguarde');
            exit;
        }

        $usuarioModel = new Usuario();
        $usuario = $usuarioModel->buscarPorEmail($email);

        // Não revelamos se o e-mail existe ou não (evita enumeração de contas).
        if ($usuario) {
            $recuperacaoModel = new RecuperacaoSenha();
            $codigo = $recuperacaoModel->gerarCodigo($email);

            Mailer::enviarCodigoRecuperacao($email, $codigo);

            $_SESSION[$chaveLimite] = time();
        }

        $_SESSION['recuperacao_email'] = $email;
        unset($_SESSION['recuperacao_verificado']);

        header('Location: index.php?url=esqueci-senha/verificar');
        exit;
    }

    /**
     * ETAPA 2 - Tela para digitar o código recebido
     */
    public function telaVerificarCodigo()
    {
        $this->iniciarSessao();

        if (empty($_SESSION['recuperacao_email'])) {
            header('Location: index.php?url=esqueci-senha');
            exit;
        }

        $etapa = 'codigo';
        $email = $_SESSION['recuperacao_email'];

        require_once __DIR__ . '/../Views/auth/esqueceuSenha.php';
    }

    /**
     * ETAPA 2 (POST) - Reenvia um novo código para o mesmo e-mail
     */
    public function reenviarCodigo()
    {
        $this->iniciarSessao();

        if (empty($_SESSION['recuperacao_email'])) {
            header('Location: index.php?url=esqueci-senha');
            exit;
        }

        $_POST['email'] = $_SESSION['recuperacao_email'];
        $this->enviarCodigo();
    }

    /**
     * ETAPA 2 (POST) - Valida o código digitado
     */
    public function validarCodigo()
    {
        $this->iniciarSessao();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['recuperacao_email'])) {
            header('Location: index.php?url=esqueci-senha');
            exit;
        }

        $email = $_SESSION['recuperacao_email'];
        $codigo = trim($_POST['codigo'] ?? '');

        if (empty($codigo)) {
            header('Location: index.php?url=esqueci-senha/verificar&erro=campos');
            exit;
        }

        $recuperacaoModel = new RecuperacaoSenha();

        if (!$recuperacaoModel->validarCodigo($email, $codigo)) {
            header('Location: index.php?url=esqueci-senha/verificar&erro=codigo');
            exit;
        }

        $_SESSION['recuperacao_verificado'] = true;

        header('Location: index.php?url=esqueci-senha/nova-senha');
        exit;
    }

    /**
     * ETAPA 3 - Tela para definir a nova senha
     */
    public function telaNovaSenha()
    {
        $this->iniciarSessao();

        if (empty($_SESSION['recuperacao_email']) || empty($_SESSION['recuperacao_verificado'])) {
            header('Location: index.php?url=esqueci-senha');
            exit;
        }

        $etapa = 'nova-senha';

        require_once __DIR__ . '/../Views/auth/esqueceuSenha.php';
    }

    /**
     * ETAPA 3 (POST) - Salva a nova senha
     */
    public function redefinir()
    {
        $this->iniciarSessao();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=esqueci-senha');
            exit;
        }

        if (empty($_SESSION['recuperacao_email']) || empty($_SESSION['recuperacao_verificado'])) {
            header('Location: index.php?url=esqueci-senha');
            exit;
        }

        $email = $_SESSION['recuperacao_email'];
        $novaSenha = trim($_POST['nova_senha'] ?? '');
        $confirmarSenha = trim($_POST['confirmar_senha'] ?? '');

        if (empty($novaSenha) || empty($confirmarSenha)) {
            header('Location: index.php?url=esqueci-senha/nova-senha&erro=campos');
            exit;
        }

        if ($novaSenha !== $confirmarSenha) {
            header('Location: index.php?url=esqueci-senha/nova-senha&erro=diferentes');
            exit;
        }

        if (strlen($novaSenha) < 6) {
            header('Location: index.php?url=esqueci-senha/nova-senha&erro=tamanho');
            exit;
        }

        $usuarioModel = new Usuario();
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
        $usuarioModel->atualizarSenha($email, $senhaHash);

        $recuperacaoModel = new RecuperacaoSenha();
        $recuperacaoModel->marcarComoUsado($email);

        unset($_SESSION['recuperacao_email'], $_SESSION['recuperacao_verificado']);

        header('Location: index.php?url=esqueci-senha&sucesso=senha');
        exit;
    }
}