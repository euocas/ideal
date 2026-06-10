<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class FinanceiroFuncionario
{
    // =====================================================
    // 1. ATRIBUTOS
    // =====================================================
    private ?int $idFinanceiroFuncionario = null;
    private ?int $idFuncionario = null;
    private ?float $salario = null;
    private ?float $ferias = null;
    private ?float $inss = null;
    private ?float $decimoTerceiro = null;

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
    public function getIdFinanceiroFuncionario(): ?int { return $this->idFinanceiroFuncionario; }
    public function setIdFinanceiroFuncionario(?int $id): void { $this->idFinanceiroFuncionario = $id; }

    public function getIdFuncionario(): ?int { return $this->idFuncionario; }
    public function setIdFuncionario($id): void { $this->idFuncionario = $id ? (int) $id : null; }

    public function getSalario(): ?float { return $this->salario; }
    public function setSalario($valor): void { $this->salario = $valor !== null && $valor !== '' ? (float) $valor : null; }

    public function getFerias(): ?float { return $this->ferias; }
    public function setFerias($valor): void { $this->ferias = $valor !== null && $valor !== '' ? (float) $valor : null; }

    public function getInss(): ?float { return $this->inss; }
    public function setInss($valor): void { $this->inss = $valor !== null && $valor !== '' ? (float) $valor : null; }

    public function getDecimoTerceiro(): ?float { return $this->decimoTerceiro; }
    public function setDecimoTerceiro($valor): void { $this->decimoTerceiro = $valor !== null && $valor !== '' ? (float) $valor : null; }

    // =====================================================
    // 4. HYDRATE
    // =====================================================
    private function hydrate(array $dados): self
    {
        $obj = new self();
        $obj->setIdFinanceiroFuncionario($dados['idFinanceiroFuncionario'] ?? null);
        $obj->setIdFuncionario($dados['idFuncionario'] ?? null);
        $obj->setSalario($dados['salario'] ?? null);
        $obj->setFerias($dados['ferias'] ?? null);
        $obj->setInss($dados['inss'] ?? null);
        $obj->setDecimoTerceiro($dados['decimoTerceiro'] ?? null);
        return $obj;
    }

    // =====================================================
    // 5. CRUD
    // =====================================================
    public function findById(int $id): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM financeiroFuncionario WHERE idFinanceiroFuncionario = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }

    public function findByIdFuncionario(int $idFuncionario): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM financeiroFuncionario WHERE idFuncionario = :idFuncionario");
        $stmt->bindValue(':idFuncionario', $idFuncionario, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }

    public function save(): bool
    {
        try {
            $sql = "INSERT INTO financeiroFuncionario (idFuncionario, salario, ferias, inss, decimoTerceiro)
                    VALUES (:idFuncionario, :salario, :ferias, :inss, :decimoTerceiro)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idFuncionario',   $this->getIdFuncionario(),   PDO::PARAM_INT);
            $stmt->bindValue(':salario',          $this->getSalario(),         PDO::PARAM_STR);
            $stmt->bindValue(':ferias',           $this->getFerias(),          PDO::PARAM_STR);
            $stmt->bindValue(':inss',             $this->getInss(),            PDO::PARAM_STR);
            $stmt->bindValue(':decimoTerceiro',   $this->getDecimoTerceiro(),  PDO::PARAM_STR);
            $stmt->execute();

            $this->idFinanceiroFuncionario = (int) $this->pdo->lastInsertId();
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function update(): bool
    {
        if (!$this->getIdFinanceiroFuncionario()) {
            return false;
        }

        try {
            $sql = "UPDATE financeiroFuncionario SET
                        idFuncionario  = :idFuncionario,
                        salario        = :salario,
                        ferias         = :ferias,
                        inss           = :inss,
                        decimoTerceiro = :decimoTerceiro
                    WHERE idFinanceiroFuncionario = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idFuncionario',   $this->getIdFuncionario(),              PDO::PARAM_INT);
            $stmt->bindValue(':salario',          $this->getSalario(),                    PDO::PARAM_STR);
            $stmt->bindValue(':ferias',           $this->getFerias(),                     PDO::PARAM_STR);
            $stmt->bindValue(':inss',             $this->getInss(),                       PDO::PARAM_STR);
            $stmt->bindValue(':decimoTerceiro',   $this->getDecimoTerceiro(),             PDO::PARAM_STR);
            $stmt->bindValue(':id',               $this->getIdFinanceiroFuncionario(),    PDO::PARAM_INT);
            $stmt->execute();

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM financeiroFuncionario WHERE idFinanceiroFuncionario = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
