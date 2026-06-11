<?php

namespace App\Controllers;

use App\Models\Credencial;
use App\Core\Auth;

class CredenciaisController
{
    private Credencial $model;

    public function __construct()
    {
        // Adicionado para proteger a rota de credenciais também
        Auth::verificar();
        $this->model = new Credencial();
    }

    public function index()
    {
        require '../app/Views/credenciais/index.php';
    }

    public function buscar()
    {
        $login = $_POST['login'] ?? '';
        $usuario = $this->model->buscarUsuario($login);

        // Define o cabeçalho como JSON para o JavaScript entender corretamente
        header('Content-Type: application/json');
        echo json_encode($usuario);
        exit; // CRÍTICO: Impede que o restante do HTML da página seja impresso junto com os dados!
    }

    public function alterarSenha()
    {
        $idUsuario = $_POST['idUsuario'] ?? null;
        $novaSenha = $_POST['novaSenha'] ?? '';
        $confirmar = $_POST['confirmarSenha'] ?? '';

        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        // Validação de Segurança
        if (!$idUsuario) {
            $_SESSION['mensagem_erro'] = "Selecione um usuário primeiro clicando em 'Buscar'.";
            header('Location: /ideal/public/index.php?url=credenciais');
            exit;
        }

        if (empty($novaSenha) || $novaSenha !== $confirmar) {
            $_SESSION['mensagem_erro'] = "As senhas informadas não coincidem ou estão em branco.";
            header('Location: /ideal/public/index.php?url=credenciais');
            exit;
        }

        $this->model->setId((int) $idUsuario);
        $this->model->setSenha($novaSenha);

        if ($this->model->alterarSenha()) {
            $_SESSION['mensagem_sucesso'] = "A senha foi alterada com sucesso!";
        } else {
            $_SESSION['mensagem_erro'] = "Ocorreu um erro interno ao tentar alterar a senha.";
        }

        header('Location: /ideal/public/index.php?url=credenciais');
        exit;
    }

    public function alterarEmail()
    {
        $idUsuario = $_POST['idUsuario'] ?? null;
        $novoEmail = $_POST['novoEmail'] ?? '';
        $confirmar = $_POST['confirmarEmail'] ?? '';

        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        // Validação de Segurança
        if (!$idUsuario) {
            $_SESSION['mensagem_erro'] = "Selecione um usuário primeiro clicando em 'Buscar'.";
            header('Location: /ideal/public/index.php?url=credenciais');
            exit;
        }

        if (empty($novoEmail) || $novoEmail !== $confirmar || !filter_var($novoEmail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['mensagem_erro'] = "Os e-mails informados não coincidem ou o formato é inválido.";
            header('Location: /ideal/public/index.php?url=credenciais');
            exit;
        }

        $this->model->setId((int) $idUsuario);
        $this->model->setEmail($novoEmail);

        if ($this->model->alterarEmail()) {
            $_SESSION['mensagem_sucesso'] = "O E-mail foi atualizado com sucesso!";
        } else {
            $_SESSION['mensagem_erro'] = "Ocorreu um erro interno ao tentar alterar o e-mail.";
        }

        header('Location: /ideal/public/index.php?url=credenciais');
        exit;
    }
}