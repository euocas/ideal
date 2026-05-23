<?php

namespace App\Controllers;

use App\Models\Funcionario;
use App\Core\Auth;
class FuncionariosController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->buscar();
        }

        $mensagem = null;
        require_once __DIR__ . '/../views/funcionarios/index.php';
    }


    /**
     * Executa a lógica de pesquisa de CPF no banco de dados
     */
    private function buscar()
    {
        $cpfDigitado = (string) ($_POST['cpf'] ?? '');

        if (!$this->validarCpf($cpfDigitado)) {
            $mensagem = "O CPF informado é inválido. Verifique os números e tente novamente.";
            require_once __DIR__ . '/../views/funcionarios/index.php';
            return;
        }

        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpfDigitado);

        $funcionarioModel = new Funcionario();
        $funcionario = $funcionarioModel->findByCpf($cpfLimpo);

        if ($funcionario) {
            header("Location: /ideal/public/index.php?url=funcionarios/edit&id=" . $funcionario['idFuncionario']);
            exit;
        } else {
            // header("Location: /ideal/public/index.php?url=funcionarios/create&cpf=" . $cpfLimpo);
            header("Location: /ideal/public/index.php?url=funcionarios/create&cpf=" . $cpfLimpo . "&novo=1");
            exit;
        }
    }

    /**
     * Valida matematicamente um CPF
     */
    private function validarCpf($cpf)
    {
        $cpf = (string) $cpf;
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        if (strlen($cpf) != 11) {
            return false;
        }

        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    // public function create()
    // {
    //     $cpfBusca = $_GET['cpf'] ?? '';
    //     require_once __DIR__ . '/../views/funcionarios/index.php';
    // }

    public function create()
{
    $cpfBusca = $_GET['cpf'] ?? '';

    $mensagem = null;

    if (isset($_GET['novo'])) {
        $mensagem = "CPF não cadastrado. Preencha os dados para criar um novo funcionário.";
    }

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

            $_POST['cep'] = preg_replace('/[^0-9]/', '', $_POST['cep'] ?? '');
            $_POST['cpf'] = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');

            $salvou = $funcionarioModel->save($_POST);
            
            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "O funcionário foi cadastrado com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Ocorreu um erro ao cadastrar no banco de dados.";
            }

            header("Location: http://localhost/ideal/public/index.php?url=funcionarios");
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