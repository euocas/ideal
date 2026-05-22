<?php

namespace App\Controllers;

use App\Models\Funcionario;

use App\Core\Auth;

require_once __DIR__ . '/../core/Auth.php';

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
        // O operador ?? '' garante que se o POST vier vazio, ele será uma string vazia e não "null"
        $cpfDigitado = (string) ($_POST['cpf'] ?? '');

        // Passa o CPF digitado pela validação matemática
        if (!$this->validarCpf($cpfDigitado)) {
            $mensagem = "O CPF informado é inválido. Verifique os números e tente novamente.";
            require_once __DIR__ . '/../views/funcionarios/index.php';
            return;
        }

        // Se o CPF é válido, limpamos a formatação (pontos e traços) para buscar no banco
        $cpfLimpo = preg_replace('/[^0-9]/', '', $cpfDigitado);

        $funcionarioModel = new Funcionario();
        $funcionario = $funcionarioModel->findByCpf($cpfLimpo);

        if ($funcionario) {
            header("Location: /ideal/public/index.php?url=funcionarios/edit&id=" . $funcionario['idFuncionario']);
            exit;
        } else {
            header("Location: /ideal/public/index.php?url=funcionarios/create&cpf=" . $cpfLimpo);
            exit;
        }
    }

    /**
     * Valida matematicamente um CPF
     */
    private function validarCpf($cpf)
    {
        // Garante que o valor seja tratado como string para evitar o erro "Deprecated"
        $cpf = (string) $cpf;

        // Extrai somente os números
        $cpf = preg_replace('/[^0-9]/is', '', $cpf);

        // Verifica se a quantidade de dígitos está correta após limpar
        if (strlen($cpf) != 11) {
            return false;
        }

        // Bloqueia CPFs com sequências repetidas (ex: 11111111111)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Calcula e verifica os dois dígitos verificadores
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

            // O cep vem do form com a formatação, limpamos para gravar só números no BD
            $_POST['cep'] = preg_replace('/[^0-9]/', '', $_POST['cep'] ?? '');

            // O CPF vem do form com a formatação (ex: 111.222.333-44), limpamos para gravar só números no BD
            $_POST['cpf'] = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');

            $salvou = $funcionarioModel->save($_POST);

            //header("Location: /ideal/public/index.php?url=funcionarios");
            //exit;
            
            if ($salvou) {
                // Cria a mensagem de sucesso
                $_SESSION['mensagem_sucesso'] = "O funcionário foi cadastrado com sucesso!";
            } else {
                // Opcional: Criar uma mensagem de erro se falhar
                $_SESSION['mensagem_erro'] = "Ocorreu um erro ao cadastrar no banco de dados.";
            }

            // 6. Recarrega a página (Redirecionamento)
            // Ajuste a URL para o caminho exato que aparece no seu navegador na tela do formulário
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
