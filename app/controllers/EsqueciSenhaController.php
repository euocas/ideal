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
            // Evita que o usuário coloque senhas diferentes nos campos para redefinição de senha
            if ($novaSenha !== $confirmarSenha) {

                echo "As senhas não coincidem.";
                exit;
            }

            // isso é instância da classe usuário que está em Model>Usuario.php
            $usuarioModel = new Usuario(); //objeto dessa classe

            //$usuarioModel → variável que guarda esse objeto

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