<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class FinanceiroObra
{
    // =====================================================
    // 1. ATRIBUTOS
    // =====================================================
    private ?int $idFinanceiroObra = null;
    private ?int $idObra = null;
    private ?string $descricao = null;
    private ?string $categoria = null;
    private ?float $valor = null;
    private ?string $dataGasto = null;
    private ?string $formaPagamento = null;
    private ?string $fornecedor = null;

    private ?string $documentoFiscal = null;
    private ?string $observacao = null;

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
    public function getIdFinanceiroObra(): ?int
    {
        return $this->idFinanceiroObra;
    }
    public function setIdFinanceiroObra(?int $id): void
    {
        $this->idFinanceiroObra = $id;
    }

    public function getIdObra(): ?int
    {
        return $this->idObra;
    }
    public function setIdObra($id): void
    {
        $this->idObra = $id ? (int) $id : null;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }
    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }
    public function setCategoria(?string $categoria): void
    {
        $this->categoria = $categoria;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }
    public function setValor($valor): void
    {
        $this->valor = $valor !== null && $valor !== '' ? (float) $valor : null;
    }

    public function getDataGasto(): ?string
    {
        return $this->dataGasto;
    }
    public function setDataGasto(?string $data): void
    {
        $this->dataGasto = $data;
    }

    public function getFormaPagamento(): ?string
    {
        return $this->formaPagamento;
    }
    public function setFormaPagamento(?string $forma): void
    {
        $this->formaPagamento = $forma;
    }

    public function getFornecedor(): ?string
    {
        return $this->fornecedor;
    }

    public function setFornecedor(?string $fornecedor): void
    {
        $this->fornecedor = $fornecedor;
    }

    public function getDocumentoFiscal(): ?string
    {
        return $this->documentoFiscal;
    }

    public function setDocumentoFiscal(?string $documentoFiscal): void
    {
        $this->documentoFiscal = $documentoFiscal;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }
    public function setObservacao(?string $obs): void
    {
        $this->observacao = $obs;
    }

    // =====================================================
    // 4. HYDRATE
    // =====================================================
    private function hydrate(array $dados): self
    {
        $obj = new self();
        $obj->setIdFinanceiroObra($dados['idFinanceiroObra'] ?? null);
        $obj->setIdObra($dados['idObra'] ?? null);
        $obj->setDescricao($dados['descricao'] ?? null);
        $obj->setCategoria($dados['categoria'] ?? null);
        $obj->setValor($dados['valor'] ?? null);
        $obj->setDataGasto($dados['dataGasto'] ?? null);
        $obj->setFormaPagamento($dados['formaPagamento'] ?? null);
        $obj->setFornecedor($dados['fornecedor'] ?? null);
        $obj->setDocumentoFiscal($dados['documentoFiscal'] ?? null);
        $obj->setObservacao($dados['observacao'] ?? null);
        return $obj;
    }

    // =====================================================
    // 5. CRUD
    // =====================================================
    public function findById(int $id): ?self
    {
        $stmt = $this->pdo->prepare("SELECT * FROM financeiroObra WHERE idFinanceiroObra = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }

    public function findByIdObra(int $idObra): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM financeiroObra WHERE idObra = :idObra ORDER BY dataGasto DESC");
        $stmt->bindValue(':idObra', $idObra, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return array_map(fn($row) => $this->hydrate($row), $rows);
    }

    public function findUltimosByIdObra(
        int $idObra,
        int $limite = 4
    ): array {
        $limite = max(1, $limite);

        $sql = "
        SELECT *
        FROM financeiroObra
        WHERE idObra = :idObra
        ORDER BY dataGasto DESC, idFinanceiroObra DESC
        LIMIT {$limite}
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idObra', $idObra, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => $this->hydrate($row),
            $rows
        );
    }


    public function calcularGastoAtual(int $idObra): float
    {
        $sql = "SELECT COALESCE(SUM(valor), 0) AS total
            FROM financeiroObra
            WHERE idObra = :idObra";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idObra', $idObra, PDO::PARAM_INT);
        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }



    public function save(): bool
    {
        try {
            $sql = "INSERT INTO financeiroObra (idObra, descricao, categoria, valor, dataGasto, formaPagamento,fornecedor,documentoFiscal, observacao)
                    VALUES (:idObra, :descricao, :categoria, :valor, :dataGasto, :formaPagamento,:fornecedor,:documentoFiscal,:observacao)";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idObra', $this->getIdObra(), PDO::PARAM_INT);
            $stmt->bindValue(':descricao', $this->getDescricao(), PDO::PARAM_STR);
            $stmt->bindValue(':categoria', $this->getCategoria(), PDO::PARAM_STR);
            $stmt->bindValue(':valor', $this->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(':dataGasto', $this->getDataGasto(), PDO::PARAM_STR);
            $stmt->bindValue(':formaPagamento', $this->getFormaPagamento(), PDO::PARAM_STR);
            $stmt->bindValue(':fornecedor', $this->getFornecedor(), PDO::PARAM_STR);
            $stmt->bindValue(':documentoFiscal', $this->getDocumentoFiscal(), PDO::PARAM_STR);
            $stmt->bindValue(':observacao', $this->getObservacao(), PDO::PARAM_STR);
            $stmt->execute();

            $this->idFinanceiroObra = (int) $this->pdo->lastInsertId();
            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function update(): bool
    {
        if (!$this->getIdFinanceiroObra()) {
            return false;
        }

        try {
            $sql = "UPDATE financeiroObra SET
                        idObra         = :idObra,
                        descricao      = :descricao,
                        categoria      = :categoria,
                        valor          = :valor,
                        dataGasto      = :dataGasto,
                        formaPagamento = :formaPagamento,
                        fornecedor = :fornecedor,
                        documentoFiscal = :documentoFiscal,
                        observacao     = :observacao
                    WHERE idFinanceiroObra = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':idObra', $this->getIdObra(), PDO::PARAM_INT);
            $stmt->bindValue(':descricao', $this->getDescricao(), PDO::PARAM_STR);
            $stmt->bindValue(':categoria', $this->getCategoria(), PDO::PARAM_STR);
            $stmt->bindValue(':valor', $this->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(':dataGasto', $this->getDataGasto(), PDO::PARAM_STR);
            $stmt->bindValue(':formaPagamento', $this->getFormaPagamento(), PDO::PARAM_STR);
            $stmt->bindValue(':fornecedor', $this->getFornecedor(), PDO::PARAM_STR);
            $stmt->bindValue(':documentoFiscal', $this->getDocumentoFiscal(), PDO::PARAM_STR);
            $stmt->bindValue(':observacao', $this->getObservacao(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->getIdFinanceiroObra(), PDO::PARAM_INT);
            $stmt->execute();

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM financeiroObra WHERE idFinanceiroObra = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

