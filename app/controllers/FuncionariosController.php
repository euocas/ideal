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
        // Na POO, o findByCpf retorna o Objeto inteiro do funcionário
        $funcionario = $funcionarioModel->findByCpf($cpfLimpo);

        if ($funcionario) {
            // Como agora é um objeto, pegamos o ID usando o Getter
            header("Location: /ideal/public/index.php?url=funcionarios/edit&id=" . $funcionario->getIdFuncionario());
            exit;
        } else {
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

    /**
     * Helper privado para preencher os dados do objeto Funcionario
     * Isso evita repetir código no Store e no Update
     */
    private function popularObjeto(Funcionario $funcionario, array $dados): void
    {
        $funcionario->setNome($dados['nome'] ?? null);
        $funcionario->setCpf($dados['cpf'] ?? null); // A máscara do CPF é limpa lá na Model agora!
        $funcionario->setSexo($dados['sexo'] ?? null);
        $funcionario->setDataNascimento($dados['dataNascimento'] ?? null);
        $funcionario->setNaturalidade($dados['naturalidade'] ?? null);
        $funcionario->setEstadoNascimento($dados['estadoNascimento'] ?? null);
        $funcionario->setTipoLogradouro($dados['tipoLogradouro'] ?? 'Rua');
        $funcionario->setNomeLogradouro($dados['nomeLogradouro'] ?? null);
        $funcionario->setNumero($dados['numero'] ?? null);
        $funcionario->setComplemento($dados['complemento'] ?? null);
        $funcionario->setCidade($dados['cidade'] ?? null);
        $funcionario->setCep($dados['cep'] ?? null); // A máscara do CEP é limpa lá na Model agora!
        $funcionario->setEstado($dados['estado'] ?? null);
        $funcionario->setEmail($dados['email'] ?? null);
        $funcionario->setCargoFuncao($dados['cargoFuncao'] ?? null);
        $funcionario->setTipoContrato($dados['tipoContrato'] ?? null);
        $funcionario->setStatus($dados['status'] ?? null);
        $funcionario->setDataAdmissao(
            !empty($dados['dataAdmissao']) ? $dados['dataAdmissao'] : null
        );
        $funcionario->setDataDesligamento(
            !empty($dados['dataDesligamento']) ? $dados['dataDesligamento'] : null
        );
        $funcionario->setFeriasProgramadas(
            !empty($dados['feriasProgramadas']) ? $dados['feriasProgramadas'] : null
        );

        $funcionario->setObservacoes($dados['observacoes'] ?? null);
        $funcionario->setTelefone($dados['telefone'] ?? null);
        $funcionario->setWhatsapp($dados['whatsapp'] ?? null);
    }


    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $funcionario = new Funcionario();

            // Popula o objeto com os dados do formulário
            $this->popularObjeto($funcionario, $_POST);

            // O objeto salva a si mesmo
            $salvou = $funcionario->save();

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

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
                // Primeiro, buscamos o funcionário existente para garantir que ele existe
                $funcionario = (new Funcionario())->findById($id);

                if ($funcionario) {
                    // Atualizamos o objeto com os novos dados
                    $this->popularObjeto($funcionario, $_POST);

                    // O objeto atualiza a si mesmo
                    $atualizou = $funcionario->update();

                    if (session_status() === PHP_SESSION_NONE) {
                        session_start();
                    }

                    if ($atualizou) {
                        $_SESSION['mensagem_sucesso'] = "Cadastro atualizado com sucesso!";
                    } else {
                        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados.";
                    }
                }
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
            $deletou = $funcionarioModel->delete($id);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if ($deletou) {
                $_SESSION['mensagem_sucesso'] = "Funcionário excluído com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao tentar excluir o funcionário.";
            }
        }

        header("Location: /ideal/public/index.php?url=funcionarios");
        exit;
    }
}