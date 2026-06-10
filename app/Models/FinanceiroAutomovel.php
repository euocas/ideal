<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class FinanceiroAutomovel
{
    // =====================================================
    // 1. ATRIBUTOS
    // =====================================================
    private ?int   $idFinanceiroAutomovel = null;
    private ?int   $idVeiculo             = null;
    private ?float $combustivel           = null;
    private ?float $manutencao            = null;
    private ?float $ipva                  = null;

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
    public function getIdFinanceiroAutomovel(): ?int { return $this->idFinanceiroAutomovel; }
    public function setIdFinanceiroAutomovel(?int $id): void { $this->idFinanceiroAutomovel = $id; }

    public function getIdVeiculo(): ?int { return $this->idVeiculo; }
    public function setIdVeiculo($id): void { $this->idVeiculo = $id ? (int) $id : null; }

    public function getCombustivel(): ?float { return $this->combustivel; }
    public function setCombustivel($valor): void { $this->combustivel = $valor !== null && $valor !== '' ? (float) $valor : null; }

    public function getManutencao(): ?float { return $this->manutencao; }
    public function setManutencao($valor): void { $this->manutencao = $valor !== null && $valor !== '' ? (float) $valor : null; }

    public function getIpva(): ?float { return $this->ipva; }
    public function setIpva($valor): void { $this->ipva = $valor !== null && $valor !== '' ? (float) $valor : null; }

    // =====================================================
    // 4. HYDRATE
    // =====================================================
    private function hydrate(array $dados): self
    {
        $obj = new self();
        $obj->setIdFinanceiroAutomovel($dados['idFinanceiroAutomovel'] ?? null);
        $obj->setIdVeiculo($dados['idVeiculo'] ?? null);
        $obj->setCombustivel($dados['combustivel'] ?? null);
        $obj->setManutencao($dados['manutencao'] ?? null);
        $obj->setIpva($dados['ipva'] ?? null);
        return $obj;
    }

    // =====================================================
    // 5. CRUD
    // =====================================================
    public function findById(int $id): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM financeiroAutomovel WHERE idFinanceiroAutomovel = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }

    public function findByIdVeiculo(int $idVeiculo): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM financeiroAutomovel WHERE idVeiculo = :idVeiculo");
        $stmt->bindValue(':idVeiculo', $idVeiculo, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }

    public function save(): bool
    {
        try {
            $sql = "INSERT INTO financeiroAutomovel (idVeiculo, combustivel, manutencao, ipva)
                    VALUES (:idVeiculo, :combustivel, :manutencao, :ipva)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idVeiculo',    $this->getIdVeiculo(),    PDO::PARAM_INT);
            $stmt->bindValue(':combustivel',  $this->getCombustivel(),  PDO::PARAM_STR);
            $stmt->bindValue(':manutencao',   $this->getManutencao(),   PDO::PARAM_STR);
            $stmt->bindValue(':ipva',         $this->getIpva(),         PDO::PARAM_STR);
            $stmt->execute();

            $this->idFinanceiroAutomovel = (int) $this->pdo->lastInsertId();
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function update(): bool
    {
        if (!$this->getIdFinanceiroAutomovel()) {
            return false;
        }

        try {
            $sql = "UPDATE financeiroAutomovel SET
                        idVeiculo   = :idVeiculo,
                        combustivel = :combustivel,
                        manutencao  = :manutencao,
                        ipva        = :ipva
                    WHERE idFinanceiroAutomovel = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idVeiculo',    $this->getIdVeiculo(),             PDO::PARAM_INT);
            $stmt->bindValue(':combustivel',  $this->getCombustivel(),           PDO::PARAM_STR);
            $stmt->bindValue(':manutencao',   $this->getManutencao(),            PDO::PARAM_STR);
            $stmt->bindValue(':ipva',         $this->getIpva(),                  PDO::PARAM_STR);
            $stmt->bindValue(':id',           $this->getIdFinanceiroAutomovel(), PDO::PARAM_INT);
            $stmt->execute();

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM financeiroAutomovel WHERE idFinanceiroAutomovel = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

