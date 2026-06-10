<?php

namespace App\Controllers;

use App\Models\Credencial;

class CredenciaisController
{
    private Credencial $model;

    public function __construct()
    {
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

        echo json_encode($usuario);
    }

    public function alterarSenha()
    {
        $this->model->setId((int) $_POST['idUsuario']);
        $this->model->setSenha($_POST['novaSenha']);

        $this->model->alterarSenha();

        header('Location: index.php?url=credenciais');
        exit;
    }

    public function alterarEmail()
    {
        $this->model->setId((int) $_POST['idUsuario']);
        $this->model->setEmail($_POST['novoEmail']);

        $this->model->alterarEmail();

        header('Location: index.php?url=credenciais');
        exit;
    }
}