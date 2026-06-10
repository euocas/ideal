<?php

namespace App\Controllers;

use App\Models\Veiculo;
use App\Core\Auth;
require_once '../app/models/trocarSenha.php';

class trocarSenhaController

{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new trocarSenha($pdo);
    }

    public function index()
    {
        require '../app/Views/trocarSenha.php';
    }

    public function buscar()
    {
        $login = $_POST['login'] ?? '';

        $usuario = $this->model->buscarUsuario($login);

        echo json_encode($usuario);
    }

    public function alterarSenha()
    {
        $idUsuario = $_POST['idUsuario'];
        $novaSenha = $_POST['novaSenha'];

        $this->model->alterarSenha(
            $idUsuario,
            $novaSenha
        );

        header(
            'Location: index.php?pagina=trocarSenha'
        );
    }

    public function alterarEmail()
    {
        $idUsuario = $_POST['idUsuario'];
        $novoEmail = $_POST['novoEmail'];

        $this->model->alterarEmail(
            $idUsuario,
            $novoEmail
        );

        header(
            'Location: index.php?pagina=trocarSenha'
        );
    }


}
?>