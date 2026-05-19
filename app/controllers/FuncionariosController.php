<?php

namespace App\Controllers;

use App\Models\Funcionario;

class FuncionariosController
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->buscar();
        }

        $mensagem = null; 
        require_once __DIR__ . '/../views/funcionarios/index.php';
    }

    private function buscar()
    {
        $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');

        if (empty($cpf) || strlen($cpf) !== 11) {
            $mensagem = "Por favor, digite um CPF válido contendo 11 dígitos.";
            require_once __DIR__ . '/../views/funcionarios/index.php';
            return;
        }

        $funcionarioModel = new Funcionario();
        $funcionario = $funcionarioModel->findByCpf($cpf);

        if ($funcionario) {
            header("Location: /ideal/public/index.php?url=funcionarios/edit&id=" . $funcionario['idFuncionario']);
            exit;
        } else {
            header("Location: /ideal/public/index.php?url=funcionarios/create&cpf=" . $cpf);
            exit;
        }
    }

    public function create()
    {
        $cpfBusca = $_GET['cpf'] ?? '';
        require_once __DIR__ . '/../views/funcionarios/index.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: /ideal/public/index.php?url=funcionarios");
            exit;
        }

        $funcionarioModel = new Funcionario();
        $funcionario = $funcionarioModel->findById($id);

        if (!$funcionario) {
            header("Location: /ideal/public/index.php?url=funcionarios");
            exit;
        }

        require_once __DIR__ . '/../views/funcionarios/index.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $funcionarioModel = new Funcionario();
            
            // O CPF vem do form com a formatação (ex: 111.222.333-44), limpamos para gravar só números no BD
            $_POST['cpf'] = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
            
            $funcionarioModel->save($_POST);

            header("Location: /ideal/public/index.php?url=funcionarios");
            exit;
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? null;

            if ($id) {
                $funcionarioModel = new Funcionario();
                $funcionarioModel->update($id, $_POST);
            }

            header("Location: /ideal/public/index.php?url=funcionarios");
            exit;
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $funcionarioModel = new Funcionario();
            $funcionarioModel->delete($id);
        }

        header("Location: /ideal/public/index.php?url=funcionarios");
        exit;
    }
}