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

        $login = '';
        $tipoAlteracao = 'senha';
        $usuario = null;

        // Se for administrador, permite buscar qualquer usuário
        if ($_SESSION['usuario']['perfil'] === 'Administrador') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $login = trim($_POST['login'] ?? '');
                $tipoAlteracao = $_POST['tipoAlteracao'] ?? 'senha';

                if (!empty($login)) {
                    $usuario = $this->model->buscarUsuario($login);
                    if (!$usuario) {
                        $_SESSION['mensagem_erro'] = 'Usuário não encontrado.';
                    }
                } else {
                    $_SESSION['mensagem_erro'] = 'Informe um usuário para realizar a busca.';
                }
            }

        } else {
            // Usuário comum
            $credencial = $this->model->findById($_SESSION['usuario']['idUsuario']);
            if ($credencial) {
                $usuario = [
                    'id' => $credencial->getId(),
                    'nome' => $credencial->getNome(),
                    'email' => $credencial->getEmail()
                ];
                $login = $credencial->getNome();
            }
        }

        require '../app/Views/credenciais/index.php';

    }
    public function alterar()
    {

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $isAdministrador = ($_SESSION['usuario']['perfil'] === 'Administrador');

        // $idUsuario = $_POST['idUsuario'] ?? null;
        if ($_SESSION['usuario']['perfil'] === 'Administrador') {
            $idUsuario = $_POST['idUsuario'] ?? null;
        } else {
            $idUsuario = $_SESSION['usuario']['idUsuario'];
        }


        $tipoAlteracao = $_POST['tipoAlteracao'] ?? '';


        if (!$isAdministrador) {

            $temSenha = !empty($_POST['novaSenha']);
            $temEmail = !empty($_POST['novoEmail']);

            if ($temSenha && $temEmail) {
                $tipoAlteracao = 'ambos';
            } elseif ($temSenha) {
                $tipoAlteracao = 'senha';
            } elseif ($temEmail) {
                $tipoAlteracao = 'email';
            }
        }
        // NOVA VALIDAÇÃO
        if (empty($tipoAlteracao)) {
            $_SESSION['mensagem_erro'] = 'Informe pelo menos uma alteração para continuar.';
            header('Location: /ideal/public/index.php?url=credenciais');
            exit;
        }

        // Validação básica
        if (!$idUsuario) {
            if ($_SESSION['usuario']['perfil'] === 'Administrador') {
                $_SESSION['mensagem_erro'] = "Selecione um usuário primeiro clicando em 'Buscar'.";
            } else {
                $_SESSION['mensagem_erro'] = "Não foi possível identificar o usuário logado.";
            }

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