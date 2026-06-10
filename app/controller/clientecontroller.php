<?php

namespace App\Controllers;

use App\Models\Cliente;
use App\Core\Auth;

class ClientesController
{
    public function __construct()
    {
        // Protege a rota, exigindo que o usuário esteja logado
        Auth::verificar();
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->buscar();
        }

        $mensagem = null;
        require_once __DIR__ . '/../view/clientes/index.php';
    }

    /**
     * Executa a lógica de pesquisa de CPF/CNPJ no banco de dados
     */
    private function buscar()
    {
        // Pega o campo 'documento' do formulário de busca superior
        $documentoDigitado = (string) ($_POST['documento'] ?? '');
        $documentoLimpo = preg_replace('/[^0-9]/', '', $documentoDigitado);

        if (!$this->validarDocumento($documentoLimpo)) {
            $mensagem = "O documento informado é inválido. Verifique a quantidade de números e tente novamente.";
            require_once __DIR__ . '/../view/clientes/index.php';
            return;
        }

        $clienteModel = new Cliente();
        $cliente = $clienteModel->findByDocumento($documentoLimpo);

        if ($cliente) {
            header("Location: /ideal/public/index.php?url=clientes/edit&id=" . $cliente->getIdCliente());
            exit;
        } else {
            // Se não encontrou, redireciona para a criação passando o documento buscado na URL
            header("Location: /ideal/public/index.php?url=clientes/create&documento=" . $documentoLimpo . "&novo=1");
            exit;
        }
    }

    /**
     * Valida o formato básico do Documento (Tamanho do CPF ou CNPJ limpos)
     */
    private function validarDocumento(string $documento): bool
    {
        $tamanho = strlen($documento);
        // CPF tem 11 números, CNPJ tem 14 números
        return $tamanho === 11 || $tamanho === 14;
    }

    public function create()
    {
        $documentoBusca = $_GET['documento'] ?? '';
        $mensagem = null;

        if (isset($_GET['novo'])) {
            $mensagem = "Cliente não cadastrado. Preencha os dados abaixo para registrar um novo cliente.";
        }

        require_once __DIR__ . '/../view/clientes/index.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: /ideal/public/index.php?url=clientes");
            exit;
        }

        $clienteModel = new Cliente();
        $cliente = $clienteModel->findById((int) $id);

        if (!$cliente) {
            header("Location: /ideal/public/index.php?url=clientes");
            exit;
        }

        require_once __DIR__ . '/../view/clientes/index.php';
    }

    /**
     * Helper privado para preencher os dados do objeto Cliente
     * Isso evita repetir código no Store e no Update
     */
    private function popularObjeto(Cliente $cliente, array $dados): void
    {
        $cliente->setNomeCliente($dados['nomeCliente'] ?? null);
        
        // Verifica se os campos vieram preenchidos do form inferior antes de popular
        $cliente->setCpf(!empty($dados['cpf']) ? $dados['cpf'] : null);
        $cliente->setCnpj(!empty($dados['cnpj']) ? $dados['cnpj'] : null);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = new Cliente();
            
            // Popula o objeto com os dados do formulário inferior
            $this->popularObjeto($cliente, $_POST);

            // Validação de segurança baseada no seu banco de dados
            if (empty($cliente->getCpf()) && empty($cliente->getCnpj())) {
                if (session_status() === PHP_SESSION_NONE) { session_start(); }
                $_SESSION['mensagem_erro'] = "É obrigatório preencher o CPF ou o CNPJ do cliente.";
                header("Location: /ideal/public/index.php?url=clientes");
                exit;
            }

            // O objeto salva a si mesmo
            $salvou = $cliente->save();

            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "O cliente foi cadastrado com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Ocorreu um erro ao cadastrar no banco de dados. Verifique se o CPF/CNPJ já existe.";
            }

            header("Location: /ideal/public/index.php?url=clientes");
            exit;
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? null;

            if ($id) {
                // Primeiro, buscamos o cliente existente para garantir que ele existe
                $cliente = (new Cliente())->findById((int) $id);

                if ($cliente) {
                    // Atualizamos o objeto com os novos dados
                    $this->popularObjeto($cliente, $_POST);
                    
                    // Validação de segurança
                    if (empty($cliente->getCpf()) && empty($cliente->getCnpj())) {
                        if (session_status() === PHP_SESSION_NONE) { session_start(); }
                        $_SESSION['mensagem_erro'] = "É obrigatório manter o CPF ou o CNPJ preenchido.";
                        header("Location: /ideal/public/index.php?url=clientes/edit&id=" . $id);
                        exit;
                    }

                    // O objeto atualiza a si mesmo
                    $atualizou = $cliente->update();

                    if ($atualizou) {
                         $_SESSION['mensagem_sucesso'] = "Cadastro do cliente atualizado com sucesso!";
                    } else {
                         $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados do cliente. Verifique se o documento já está em uso.";
                    }
                }
            }

            header("Location: /ideal/public/index.php?url=clientes");
            exit;
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $clienteModel = new Cliente();
            $deletou = $clienteModel->delete((int) $id);

            if ($deletou) {
                 $_SESSION['mensagem_sucesso'] = "Cliente excluído com sucesso!";
            } else {
                 $_SESSION['mensagem_erro'] = "Erro ao tentar excluir o cliente. Verifique se ele não possui obras vinculadas.";
            }
        }

        header("Location: /ideal/public/index.php?url=clientes");
        exit;
    }
}

