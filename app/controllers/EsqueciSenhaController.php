<?php

namespace App\Controllers;

use App\Models\Usuario;
class EsqueciSenhaController
{
    public function index()
    {
        require '../app/views/auth/esqueceuSenha.php';
    }

    public function redefinir()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $email = trim($_POST['email']);
            $novaSenha = trim($_POST['nova_senha']);
            $confirmarSenha = trim($_POST['confirmar_senha']);

            if (empty($email) || empty($novaSenha) || empty($confirmarSenha)) {

                echo "Preencha todos os campos.";
                exit;
            }

            if ($novaSenha !== $confirmarSenha) {

                echo "As senhas não coincidem.";
                exit;
            }

            $usuarioModel = new Usuario();

            $usuario = $usuarioModel->buscarPorEmail($email);

            if (!$usuario) {

                echo "E-mail não encontrado.";
                exit;
            }

            $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

            $usuarioModel->atualizarSenha($email, $senhaHash);

            header('Location: index.php?url=esqueci-senha&sucesso=senha');
            exit;
        }
    }
}