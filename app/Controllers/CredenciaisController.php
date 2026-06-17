<?php

namespace App\Controllers;

use App\Models\Credencial;
use App\Core\Auth;

class CredenciaisController
{
    private Credencial $model;

    public function __construct()
    {
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
        exit;
    }

    public function alterar()
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }

        $idUsuario = $_POST['idUsuario'] ?? null;
        $tipoAlteracao = $_POST['tipoAlteracao'] ?? '';

        // Validação básica
        if (!$idUsuario) {
            $_SESSION['mensagem_erro'] = "Selecione um usuário primeiro clicando em 'Buscar'.";
            header('Location: /ideal/public/index.php?url=credenciais');
            exit;
        }

        $this->model->setId((int) $idUsuario);
        
        $sucesso = false;
        $erro = false;
        $msgErro = "";

        // LÓGICA PARA ALTERAR SENHA
        if ($tipoAlteracao === 'senha' || $tipoAlteracao === 'ambos') {
            $novaSenha = $_POST['novaSenha'] ?? '';
            $confirmar = $_POST['confirmarSenha'] ?? '';

            if (empty($novaSenha) || $novaSenha !== $confirmar) {
                $erro = true;
                $msgErro .= "As senhas não coincidem ou estão vazias. ";
            } else {
                $this->model->setSenha($novaSenha);
                if ($this->model->alterarSenha()) {
                    $sucesso = true;
                } else {
                    $erro = true;
                    $msgErro .= "Erro ao alterar a senha. ";
                }
            }
        }

        // LÓGICA PARA ALTERAR E-MAIL
        if ($tipoAlteracao === 'email' || $tipoAlteracao === 'ambos') {
            $novoEmail = $_POST['novoEmail'] ?? '';
            $confirmar = $_POST['confirmarEmail'] ?? '';

            if (empty($novoEmail) || $novoEmail !== $confirmar || !filter_var($novoEmail, FILTER_VALIDATE_EMAIL)) {
                $erro = true;
                $msgErro .= "Os e-mails não coincidem ou formato é inválido. ";
            } else {
                $this->model->setEmail($novoEmail);
                if ($this->model->alterarEmail()) {
                    $sucesso = true;
                } else {
                    $erro = true;
                    $msgErro .= "Erro ao alterar e-mail. ";
                }
            }
        }

        // FEEDBACK FINAL
        if ($erro) {
            $_SESSION['mensagem_erro'] = trim($msgErro);
        } else if ($sucesso) {
            $_SESSION['mensagem_sucesso'] = "Dados atualizados com sucesso!";
        }

        header('Location: /ideal/public/index.php?url=credenciais');
        exit;
    }
}