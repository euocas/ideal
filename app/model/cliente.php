<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class Cliente
{
    // =====================================================
    // 1. ATRIBUTOS DA CLASSE
    // =====================================================
    private ?int $idCliente = null;
    private ?string $nomeCliente = null;
    private ?string $cpf = null;
    private ?string $cnpj = null;
    private ?string $email = null;
    private ?string $tipoCliente = null;
    private ?string $tipoLogradouro = null;
    private ?string $nomeLogradouro = null;
    private ?string $numero = null;
    private ?string $complemento = null;
    private ?string $cidade = null;
    private ?string $cep = null;
    private ?string $estado = null;
    private ?string $observacoes = null;
    
    private ?string $telefone = null;

    public string $dbError = ''; // Adicionado para capturar o erro real do BD

    private PDO $pdo;

    // =====================================================
    // 2. CONSTRUTOR
    // =====================================================
    public function __construct()
    {
        $banco = new Conexao();
        $this->pdo = $banco->getConnection();
    }

    // =====================================================
    // 3. GETTERS E SETTERS
    // =====================================================

    public function getIdCliente(): ?int { return $this->idCliente; }
    public function setIdCliente(?int $id): void { $this->idCliente = $id; }

    public function getNomeCliente(): ?string { return $this->nomeCliente; }
    public function setNomeCliente(?string $nome): void { $this->nomeCliente = $nome; }

    public function getCpf(): ?string { return $this->cpf; }
    public function setCpf(?string $cpf): void { 
        $this->cpf = $cpf ? preg_replace('/[^0-9]/', '', $cpf) : null; 
    }

    public function getCnpj(): ?string { return $this->cnpj; }
    public function setCnpj(?string $cnpj): void { 
        $this->cnpj = $cnpj ? preg_replace('/[^0-9]/', '', $cnpj) : null; 
    }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(?string $email): void { $this->email = $email; }

    public function getTipoCliente(): ?string { return $this->tipoCliente; }
    public function setTipoCliente(?string $tipoCliente): void { $this->tipoCliente = $tipoCliente; }

    public function getTipoLogradouro(): ?string { return $this->tipoLogradouro; }
    public function setTipoLogradouro(?string $tipoLogradouro): void { $this->tipoLogradouro = $tipoLogradouro; }

    public function getNomeLogradouro(): ?string { return $this->nomeLogradouro; }
    public function setNomeLogradouro(?string $nomeLogradouro): void { $this->nomeLogradouro = $nomeLogradouro; }

    public function getNumero(): ?string { return $this->numero; }
    public function setNumero(?string $numero): void { $this->numero = $numero; }

    public function getComplemento(): ?string { return $this->complemento; }
    public function setComplemento(?string $complemento): void { $this->complemento = $complemento; }

    public function getCidade(): ?string { return $this->cidade; }
    public function setCidade(?string $cidade): void { $this->cidade = $cidade; }

    public function getCep(): ?string { return $this->cep; }
    public function setCep(?string $cep): void { 
        $this->cep = $cep ? preg_replace('/[^0-9]/', '', $cep) : null; 
    }

    public function getEstado(): ?string { return $this->estado; }
    public function setEstado(?string $estado): void { $this->estado = $estado; }

    public function getObservacoes(): ?string { return $this->observacoes; }
    public function setObservacoes(?string $observacoes): void { $this->observacoes = $observacoes; }

    public function getTelefone(): ?string { return $this->telefone; }
    public function setTelefone(?string $telefone): void { $this->telefone = $telefone; }

    // =====================================================
    // 4. MÉTODOS DE BANCO DE DADOS (CRUD)
    // =====================================================

    private function hydrate(array $dados): self
    {
        $cliente = new self();
        $cliente->setIdCliente($dados['idCliente'] ?? null);
        $cliente->setNomeCliente($dados['nomeCliente'] ?? null);
        $cliente->setCpf($dados['cpf'] ?? null);
        $cliente->setCnpj($dados['cnpj'] ?? null);
        $cliente->setEmail($dados['email'] ?? null);
        $cliente->setTipoCliente($dados['tipoCliente'] ?? null);
        $cliente->setTipoLogradouro($dados['tipoLogradouro'] ?? null);
        $cliente->setNomeLogradouro($dados['nomeLogradouro'] ?? null);
        $cliente->setNumero($dados['numero'] ?? null);
        $cliente->setComplemento($dados['complemento'] ?? null);
        $cliente->setCidade($dados['cidade'] ?? null);
        $cliente->setCep($dados['cep'] ?? null);
        $cliente->setEstado($dados['estado'] ?? null);
        $cliente->setObservacoes($dados['observacoes'] ?? null);

        if (isset($dados['idCliente'])) {
            $stmtTel = $this->pdo->prepare("SELECT telefone FROM contatoCliente WHERE idCliente = :id LIMIT 1");
            $stmtTel->bindValue(':id', $dados['idCliente'], PDO::PARAM_INT);
            $stmtTel->execute();
            $contato = $stmtTel->fetch(PDO::FETCH_ASSOC);
            if ($contato) {
                $cliente->setTelefone($contato['telefone']);
            }
        }

        return $cliente;
    }

    public function save(): bool
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO cliente (
                        nomeCliente, cpf, cnpj, email, tipoCliente, tipoLogradouro, 
                        nomeLogradouro, numero, complemento, cidade, cep, estado, observacoes
                    ) VALUES (
                        :nomeCliente, :cpf, :cnpj, :email, :tipoCliente, :tipoLogradouro, 
                        :nomeLogradouro, :numero, :complemento, :cidade, :cep, :estado, :observacoes
                    )";

            $stmt = $this->pdo->prepare($sql);
            
            $stmt->bindValue(':nomeCliente', $this->getNomeCliente() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':cpf', $this->getCpf(), $this->getCpf() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':cnpj', $this->getCnpj(), $this->getCnpj() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':email', $this->getEmail() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':tipoCliente', $this->getTipoCliente() ?? 'Pessoa Física', PDO::PARAM_STR);
            $stmt->bindValue(':tipoLogradouro', $this->getTipoLogradouro(), $this->getTipoLogradouro() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':nomeLogradouro', $this->getNomeLogradouro(), $this->getNomeLogradouro() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':numero', $this->getNumero(), $this->getNumero() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':complemento', $this->getComplemento(), $this->getComplemento() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':cidade', $this->getCidade() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':cep', $this->getCep() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':estado', $this->getEstado() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':observacoes', $this->getObservacoes(), $this->getObservacoes() ? PDO::PARAM_STR : PDO::PARAM_NULL);

            $stmt->execute();
            $this->idCliente = (int) $this->pdo->lastInsertId();

            if ($this->getTelefone()) {
                $sqlTel = "INSERT INTO contatoCliente (idCliente, telefone) VALUES (:idCliente, :telefone)";
                $stmtTel = $this->pdo->prepare($sqlTel);
                $stmtTel->bindValue(':idCliente', $this->idCliente, PDO::PARAM_INT);
                $stmtTel->bindValue(':telefone', $this->getTelefone(), PDO::PARAM_STR);
                $stmtTel->execute();
            }

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            $this->dbError = $e->getMessage(); // Captura o erro aqui
            return false;
        }
    }

    public function update(): bool
    {
        if (!$this->getIdCliente()) return false; 

        try {
            $this->pdo->beginTransaction();

            $sql = "UPDATE cliente SET 
                        nomeCliente = :nomeCliente, cpf = :cpf, cnpj = :cnpj, email = :email, 
                        tipoCliente = :tipoCliente, tipoLogradouro = :tipoLogradouro, nomeLogradouro = :nomeLogradouro, 
                        numero = :numero, complemento = :complemento, cidade = :cidade, cep = :cep, 
                        estado = :estado, observacoes = :observacoes 
                    WHERE idCliente = :id";

            $stmt = $this->pdo->prepare($sql);
            
            $stmt->bindValue(':nomeCliente', $this->getNomeCliente() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':cpf', $this->getCpf(), $this->getCpf() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':cnpj', $this->getCnpj(), $this->getCnpj() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':email', $this->getEmail() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':tipoCliente', $this->getTipoCliente() ?? 'Pessoa Física', PDO::PARAM_STR);
            $stmt->bindValue(':tipoLogradouro', $this->getTipoLogradouro(), $this->getTipoLogradouro() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':nomeLogradouro', $this->getNomeLogradouro(), $this->getNomeLogradouro() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':numero', $this->getNumero(), $this->getNumero() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':complemento', $this->getComplemento(), $this->getComplemento() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':cidade', $this->getCidade() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':cep', $this->getCep() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':estado', $this->getEstado() ?? '', PDO::PARAM_STR);
            $stmt->bindValue(':observacoes', $this->getObservacoes(), $this->getObservacoes() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':id', $this->getIdCliente(), PDO::PARAM_INT);

            $stmt->execute();

            $stmtDel = $this->pdo->prepare("DELETE FROM contatoCliente WHERE idCliente = :id");
            $stmtDel->bindValue(':id', $this->getIdCliente(), PDO::PARAM_INT);
            $stmtDel->execute();

            if ($this->getTelefone()) {
                $sqlTel = "INSERT INTO contatoCliente (idCliente, telefone) VALUES (:idCliente, :telefone)";
                $stmtTel = $this->pdo->prepare($sqlTel);
                $stmtTel->bindValue(':idCliente', $this->getIdCliente(), PDO::PARAM_INT);
                $stmtTel->bindValue(':telefone', $this->getTelefone(), PDO::PARAM_STR);
                $stmtTel->execute();
            }

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            $this->dbError = $e->getMessage(); // Captura o erro aqui
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM cliente WHERE idCliente = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function findByDocumento(string $documento): ?self
    {
        $docLimpo = preg_replace('/[^0-9]/', '', $documento);
        $sql = "SELECT * FROM cliente WHERE cpf = :doc OR cnpj = :doc";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':doc', $docLimpo, PDO::PARAM_STR);
        $stmt->execute();
        
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }

    public function findById(int $id): ?self
    {
        $sql = "SELECT * FROM cliente WHERE idCliente = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }

    public function findAll(): array
    {
        $sql = "SELECT * FROM cliente";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $clientes = [];
        foreach ($resultados as $linha) {
            $clientes[] = $this->hydrate($linha);
        }
        return $clientes;
    }
}
