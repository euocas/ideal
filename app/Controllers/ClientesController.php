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
        require_once __DIR__ . '/../Views/clientes/index.php';
    }

    private function buscar()
    {
        $documentoDigitado = (string) ($_POST['documento'] ?? '');
        $documentoLimpo = preg_replace('/[^0-9]/', '', $documentoDigitado);

        if (empty($documentoLimpo)) {
            $mensagem = "Por favor, digite um documento para realizar a busca.";
            require_once __DIR__ . '/../Views/clientes/index.php';
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

    public function create()
    {
        $documentoBusca = $_GET['documento'] ?? '';
        $mensagem = null;

        if (isset($_GET['novo'])) {
            $mensagem = "Cliente não cadastrado. Preencha os dados abaixo para registrar um novo cliente.";
        }

        require_once __DIR__ . '/../Views/clientes/index.php';
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

        require_once __DIR__ . '/../Views/clientes/index.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $cliente = new Cliente();
            
            $this->popularObjeto($cliente, $_POST);

            // 1. VALIDAÇÃO DE SEGURANÇA: Obrigatoriedade de ao menos um documento
            if (empty($cliente->getCpf()) && empty($cliente->getCnpj())) {
                $this->retornarComErro("É obrigatório preencher o CPF ou o CNPJ do cliente.", "clientes");
            }

            // 2. VALIDAÇÃO DE SEGURANÇA: Estrutura matemática do CPF (se preenchido)
            if (!empty($cliente->getCpf()) && !$this->validarCPF($cliente->getCpf())) {
                $this->retornarComErro("O CPF informado é inválido. Verifique os dígitos.", "clientes");
            }

            // 3. VALIDAÇÃO DE SEGURANÇA: Estrutura matemática do CNPJ (se preenchido)
            if (!empty($cliente->getCnpj()) && !$this->validarCNPJ($cliente->getCnpj())) {
                $this->retornarComErro("O CNPJ informado é inválido. Verifique os dígitos.", "clientes");
            }

            // 4. VALIDAÇÃO DE SEGURANÇA: Estrutura do E-mail
            if (!empty($cliente->getEmail()) && !$this->validarEmail($cliente->getEmail())) {
                $this->retornarComErro("O formato do e-mail informado é inválido.", "clientes");
            }

            // 5. VALIDAÇÃO DE SEGURANÇA: Estrutura do CEP
            if (!empty($cliente->getCep()) && !$this->validarCEP($cliente->getCep())) {
                $this->retornarComErro("O CEP informado deve conter exatamente 8 números.", "clientes");
            }

            $salvou = $cliente->save();

            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "O cliente foi cadastrado com sucesso!";
            } else {
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

            if (!$id) {
                $this->retornarComErro("Identificador do cliente não encontrado.", "clientes");
            }

            $cliente = (new Cliente())->findById((int) $id);

            if ($cliente) {
                $this->popularObjeto($cliente, $_POST);
                
                // 1. VALIDAÇÃO: Obrigatoriedade de ao menos um documento
                if (empty($cliente->getCpf()) && empty($cliente->getCnpj())) {
                    $this->retornarComErro("É obrigatório manter o CPF ou o CNPJ preenchido.", "clientes/edit&id=" . $id);
                }

                // 2. VALIDAÇÃO: Estrutura matemática do CPF
                if (!empty($cliente->getCpf()) && !$this->validarCPF($cliente->getCpf())) {
                    $this->retornarComErro("O CPF informado é inválido. Verifique os dígitos.", "clientes/edit&id=" . $id);
                }

                // 3. VALIDAÇÃO: Estrutura matemática do CNPJ
                if (!empty($cliente->getCnpj()) && !$this->validarCNPJ($cliente->getCnpj())) {
                    $this->retornarComErro("O CNPJ informado é inválido. Verifique os dígitos.", "clientes/edit&id=" . $id);
                }

                // 4. VALIDAÇÃO: Estrutura do E-mail
                if (!empty($cliente->getEmail()) && !$this->validarEmail($cliente->getEmail())) {
                    $this->retornarComErro("O formato do e-mail informado é inválido.", "clientes/edit&id=" . $id);
                }

                // 5. VALIDAÇÃO: Estrutura do CEP
                if (!empty($cliente->getCep()) && !$this->validarCEP($cliente->getCep())) {
                    $this->retornarComErro("O CEP informado deve conter exatamente 8 números.", "clientes/edit&id=" . $id);
                }

                $atualizou = $cliente->update();

                if (session_status() === PHP_SESSION_NONE) { session_start(); }
                if ($atualizou) {
                     $_SESSION['mensagem_sucesso'] = "Cadastro do cliente atualizado com sucesso!";
                } else {
                     $_SESSION['mensagem_erro'] = "Erro BD: " . $cliente->dbError;
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

    // =====================================================
    // METODOS AUXILIARES E ALGORITMOS DE VALIDAÇÃO
    // =====================================================

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

    private function retornarComErro(string $mensagem, string $rota): void
    {
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
        $_SESSION['mensagem_erro'] = $mensagem;
        header("Location: /ideal/public/index.php?url=" . $rota);
        exit;
    }

    private function validarEmail(string $email): bool
    {
        return (bool) filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    private function validarCEP(string $cep): bool
    {
        $cepLimpo = preg_replace('/[^0-9]/', '', $cep);
        return strlen($cepLimpo) === 8;
    }

    private function validarCPF(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        if (strlen($cpf) !== 11) return false;
        if (preg_match('/(\d)\1{10}/', $cpf)) return false; // Bloqueia sequências repetidas (11111111111...)

        // Cálculo dos dígitos verificadores
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }
        return true;
    }

    private function validarCNPJ(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (strlen($cnpj) !== 14) return false;
        if (preg_match('/(\d)\1{13}/', $cnpj)) return false; // Bloqueia sequências repetidas

        // Cálculo dos dígitos verificadores
        $tamanhos = [12, 13];
        foreach ($tamanhos as $tamanho) {
            $numeros = substr($cnpj, 0, $tamanho);
            $digitos = substr($cnpj, $tamanho);
            $soma = 0;
            $pos = $tamanho - 7;
            for ($i = $tamanho; $i >= 1; $i--) {
                $soma += $numeros[$tamanho - $i] * $pos--;
                if ($pos < 2) $pos = 9;
            }
            $resultado = $soma % 11 < 2 ? 0 : 11 - ($soma % 11);
            if ($resultado != $digitos[0]) return false;
        }
        return true;
    }

// ✅ ADICIONE ESTE MÉTODO DENTRO DA CLASSE ClientesController
    public function buscarPorCnpj()
    {
        // Define que a resposta será em JSON para o Javascript ler corretamente
        header('Content-Type: application/json');
        
        $cnpj = $_GET['cnpj'] ?? '';
        $cnpjLimpo = preg_replace('/[^0-9]/', '', $cnpj);

        if (empty($cnpjLimpo)) {
            echo json_encode(['erro' => 'CNPJ/CPF inválido']);
            exit;
        }

        try {
            // Conecta ao banco de dados diretamente para a busca rápida
            $banco = new \App\Config\Conexao();
            $pdo = $banco->getConnection();

            // Busca os dados do cliente (serve tanto para CPF quanto CNPJ devido à máscara da view)
            $sql = "SELECT idCliente, nomeCliente, cpf, cnpj FROM cliente WHERE cnpj = :cnpj OR cpf = :cpf LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':cnpj', $cnpjLimpo, \PDO::PARAM_STR);
            $stmt->bindValue(':cpf', $cnpjLimpo, \PDO::PARAM_STR);
            $stmt->execute();

            $cliente = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($cliente) {
                // Se achou o cliente, busca o WhatsApp na tabela de contato
                $sqlContato = "SELECT whatsapp FROM contatoCliente WHERE idCliente = :idCliente LIMIT 1";
                $stmtContato = $pdo->prepare($sqlContato);
                $stmtContato->bindValue(':idCliente', $cliente['idCliente'], \PDO::PARAM_INT);
                $stmtContato->execute();
                $contato = $stmtContato->fetch(\PDO::FETCH_ASSOC);
                
                $cliente['whatsapp'] = $contato['whatsapp'] ?? '-';

                // Retorna os dados para a tela
                echo json_encode($cliente);
            } else {
                echo json_encode(['erro' => 'Cliente não encontrado']);
            }
        } catch (\Exception $e) {
            echo json_encode(['erro' => 'Erro no banco de dados: ' . $e->getMessage()]);
        }
        
        exit;
    }

}