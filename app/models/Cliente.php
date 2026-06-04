<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class Cliente
{
    // =====================================================
    // 1. ATRIBUTOS DA CLASSE (Representam as colunas do banco)
    // =====================================================
    private ?int $idCliente = null;
    private ?string $nomeCliente = null;
    private ?string $cpf = null;
    private ?string $cnpj = null;

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
    // 3. GETTERS E SETTERS (Regras de negócio e sanitização)
    // =====================================================

    public function getIdCliente(): ?int { return $this->idCliente; }
    public function setIdCliente(?int $id): void { $this->idCliente = $id; }

    public function getNomeCliente(): ?string { return $this->nomeCliente; }
    public function setNomeCliente(?string $nome): void { $this->nomeCliente = $nome; }

    public function getCpf(): ?string { return $this->cpf; }
    public function setCpf(?string $cpf): void 
    { 
        // Remove tudo que não for número (limpa a máscara do JS)
        $this->cpf = $cpf ? preg_replace('/[^0-9]/', '', $cpf) : null; 
    }

    public function getCnpj(): ?string { return $this->cnpj; }
    public function setCnpj(?string $cnpj): void 
    { 
        // Remove tudo que não for número (limpa a máscara do JS)
        $this->cnpj = $cnpj ? preg_replace('/[^0-9]/', '', $cnpj) : null; 
    }

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

        return $cliente;
    }

    public function save(): bool
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO cliente (nomeCliente, cpf, cnpj) 
                    VALUES (:nomeCliente, :cpf, :cnpj)";

            $stmt = $this->pdo->prepare($sql);
            
            $stmt->bindValue(':nomeCliente', $this->getNomeCliente(), PDO::PARAM_STR);
            $stmt->bindValue(':cpf', $this->getCpf(), $this->getCpf() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':cnpj', $this->getCnpj(), $this->getCnpj() ? PDO::PARAM_STR : PDO::PARAM_NULL);

            $stmt->execute();
            
            // Captura o ID gerado pelo banco
            $this->idCliente = (int) $this->pdo->lastInsertId();

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function update(): bool
    {
        if (!$this->getIdCliente()) {
            return false; 
        }

        try {
            $this->pdo->beginTransaction();

            $sql = "UPDATE cliente SET 
                        nomeCliente = :nomeCliente, 
                        cpf = :cpf, 
                        cnpj = :cnpj 
                    WHERE idCliente = :id";

            $stmt = $this->pdo->prepare($sql);
            
            $stmt->bindValue(':nomeCliente', $this->getNomeCliente(), PDO::PARAM_STR);
            $stmt->bindValue(':cpf', $this->getCpf(), $this->getCpf() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':cnpj', $this->getCnpj(), $this->getCnpj() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':id', $this->getIdCliente(), PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM cliente WHERE idCliente = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    /**
     * Busca um cliente baseando-se no documento (CPF ou CNPJ)
     */
    public function findByDocumento(string $documento): ?self
    {
        // Limpa a máscara recebida da view
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