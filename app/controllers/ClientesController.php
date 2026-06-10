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
        require_once __DIR__ . '/../views/clientes/index.php';
    }

    private function buscar()
    {
        $documentoDigitado = (string) ($_POST['documento'] ?? '');
        $documentoLimpo = preg_replace('/[^0-9]/', '', $documentoDigitado);

        if (!$this->validarDocumento($documentoLimpo)) {
            $mensagem = "O documento informado é inválido. Verifique a quantidade de números e tente novamente.";
            require_once __DIR__ . '/../views/clientes/index.php';
            return;
        }

        $clienteModel = new Cliente();
        $cliente = $clienteModel->findByDocumento($documentoLimpo);

        if ($cliente) {
            header("Location: /ideal/public/index.php?url=clientes/edit&id=" . $cliente->getIdCliente());
            exit;
        } else {
            header("Location: /ideal/public/index.php?url=clientes/create&documento=" . $documentoLimpo . "&novo=1");
            exit;
        }
    }

    private function validarDocumento(string $documento): bool
    {
        $tamanho = strlen($documento);
        return $tamanho === 11 || $tamanho === 14;
    }

    public function create()
    {
        $documentoBusca = $_GET['documento'] ?? '';
        $mensagem = null;

        if (isset($_GET['novo'])) {
            $mensagem = "Cliente não cadastrado. Preencha os dados abaixo para registrar um novo cliente.";
        }

        require_once __DIR__ . '/../views/clientes/index.php';
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

        require_once __DIR__ . '/../views/clientes/index.php';
    }

    private function popularObjeto(Cliente $cliente, array $dados): void
    {
        $cliente->setNomeCliente(!empty($dados['nomeCliente']) ? $dados['nomeCliente'] : '');
        $cliente->setCpf(!empty($dados['cpf']) ? $dados['cpf'] : null);
        $cliente->setCnpj(!empty($dados['cnpj']) ? $dados['cnpj'] : null);
        $cliente->setEmail(!empty($dados['email']) ? $dados['email'] : '');
        
        $tipoForm = $dados['tipoCliente'] ?? '';
        if ($tipoForm === 'PESSOA_FISICA') {
            $cliente->setTipoCliente('Pessoa Física');
        } elseif ($tipoForm === 'PESSOA_JURIDICA') {
            $cliente->setTipoCliente('Pessoa Jurídica');
        } else {
            $cliente->setTipoCliente('Pessoa Física'); 
        }

        $cliente->setCidade(!empty($dados['cidade']) ? $dados['cidade'] : '');
        $cliente->setCep(!empty($dados['cep']) ? $dados['cep'] : '');
        $cliente->setEstado(!empty($dados['estado']) ? $dados['estado'] : '');
        $cliente->setObservacoes(!empty($dados['observacoes']) ? $dados['observacoes'] : null);
        $cliente->setTelefone(!empty($dados['telefone']) ? $dados['telefone'] : null);

        $cliente->setTipoLogradouro(null);
        $cliente->setNomeLogradouro(null);
        $cliente->setNumero(null);
        $cliente->setComplemento(null);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = new Cliente();
            
            $this->popularObjeto($cliente, $_POST);

            if (empty($cliente->getCpf()) && empty($cliente->getCnpj())) {
                if (session_status() === PHP_SESSION_NONE) { session_start(); }
                $_SESSION['mensagem_erro'] = "É obrigatório preencher o CPF ou o CNPJ do cliente.";
                header("Location: /ideal/public/index.php?url=clientes");
                exit;
            }

            $salvou = $cliente->save();
            
            if (session_status() === PHP_SESSION_NONE) { session_start(); }

            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "O cliente foi cadastrado com sucesso!";
            } else {
                // AGORA EXIBIMOS O ERRO DIRETO DO BANCO DE DADOS
                $_SESSION['mensagem_erro'] = "Erro BD: " . $cliente->dbError;
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
                $cliente = (new Cliente())->findById((int) $id);

                if ($cliente) {
                    $this->popularObjeto($cliente, $_POST);
                    
                    if (empty($cliente->getCpf()) && empty($cliente->getCnpj())) {
                        if (session_status() === PHP_SESSION_NONE) { session_start(); }
                        $_SESSION['mensagem_erro'] = "É obrigatório manter o CPF ou o CNPJ preenchido.";
                        header("Location: /ideal/public/index.php?url=clientes/edit&id=" . $id);
                        exit;
                    }

                    $atualizou = $cliente->update();

                    if (session_status() === PHP_SESSION_NONE) { session_start(); }

                    if ($atualizou) {
                         $_SESSION['mensagem_sucesso'] = "Cadastro do cliente atualizado com sucesso!";
                    } else {
                         // AGORA EXIBIMOS O ERRO DIRETO DO BANCO DE DADOS
                         $_SESSION['mensagem_erro'] = "Erro BD: " . $cliente->dbError;
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

            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            
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