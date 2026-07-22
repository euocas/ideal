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
    private ?int $idCategoriaFinanceiroObra = null;
    private ?string $categoria = null; // apenas para exibição (JOIN)
    private ?string $descricao = null;
    private ?float $valor = null;
    private ?string $dataGasto = null;
    private ?string $formaPagamento = null;
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

    public function getCategoria(): ?string
    {

        return $this->categoria;

    }

    public function setCategoria(?string $categoria): void
    {

        $this->categoria = $categoria;

    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }
    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getIdCategoriaFinanceiroObra(): ?int
    {
        return $this->idCategoriaFinanceiroObra;
    }

    public function setIdCategoriaFinanceiroObra($id): void
    {
        $this->idCategoriaFinanceiroObra = $id ? (int) $id : null;
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
        $obj->setCategoria($dados['categoria'] ?? null);
        $obj->setDescricao($dados['descricao'] ?? null);
        $obj->setIdCategoriaFinanceiroObra($dados['idCategoriaFinanceiroObra'] ?? null);
        $obj->setValor($dados['valor'] ?? null);
        $obj->setDataGasto($dados['dataGasto'] ?? null);
        $obj->setFormaPagamento($dados['formaPagamento'] ?? null);
        $obj->setObservacao($dados['observacao'] ?? null);
        return $obj;
    }

    // =====================================================
    // 5. CRUD
    // =====================================================

    public function findById(int $id): ?self
    {
        $sql = "SELECT
                fo.*,
                cfo.nome AS categoria
            FROM financeiroObra fo
            INNER JOIN categoriaFinanceiroObra cfo
                ON fo.idCategoriaFinanceiroObra = cfo.idCategoriaFinanceiroObra
            WHERE fo.idFinanceiroObra = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->hydrate($dados) : null;
    }

    public function findByIdObra(int $idObra): array
    {
        $sql = "SELECT
                fo.*,
                cfo.nome AS categoria
            FROM financeiroObra fo
            INNER JOIN categoriaFinanceiroObra cfo
                ON fo.idCategoriaFinanceiroObra = cfo.idCategoriaFinanceiroObra
            WHERE fo.idObra = :idObra
            ORDER BY fo.dataGasto DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idObra', $idObra, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->hydrate($row), $rows);
    }

    public function findUltimosByIdObra(int $idObra, int $limite = 4): array
    {
        $limite = max(1, $limite);

        $sql = "
        SELECT
            fo.*,
            cfo.nome AS categoria
        FROM financeiroObra fo
        INNER JOIN categoriaFinanceiroObra cfo
            ON fo.idCategoriaFinanceiroObra = cfo.idCategoriaFinanceiroObra
        WHERE fo.idObra = :idObra
        ORDER BY fo.dataGasto DESC, fo.idFinanceiroObra DESC
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

    public function listarCategorias(): array
    {
        $sql = "SELECT
                idCategoriaFinanceiroObra,
                nome
            FROM categoriaFinanceiroObra
            ORDER BY nome ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            $sql = "INSERT INTO financeiroObra (
                    idObra,
                    idCategoriaFinanceiroObra,
                    descricao,
                    valor,
                    dataGasto,
                    formaPagamento,
                    observacao
                ) VALUES (
                    :idObra,
                    :idCategoriaFinanceiroObra,
                    :descricao,
                    :valor,
                    :dataGasto,
                    :formaPagamento,
                    :observacao
                )";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':idObra', $this->getIdObra(), PDO::PARAM_INT);
            $stmt->bindValue(
                ':idCategoriaFinanceiroObra',
                $this->getIdCategoriaFinanceiroObra(),
                PDO::PARAM_INT
            );
            $stmt->bindValue(':descricao', $this->getDescricao(), PDO::PARAM_STR);
            $stmt->bindValue(':valor', $this->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(':dataGasto', $this->getDataGasto(), PDO::PARAM_STR);
            $stmt->bindValue(':formaPagamento', $this->getFormaPagamento(), PDO::PARAM_STR);
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
                    idObra = :idObra,
                    idCategoriaFinanceiroObra = :idCategoriaFinanceiroObra,
                    descricao = :descricao,
                    valor = :valor,
                    dataGasto = :dataGasto,
                    formaPagamento = :formaPagamento,
                    observacao = :observacao
                WHERE idFinanceiroObra = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':idObra', $this->getIdObra(), PDO::PARAM_INT);
            $stmt->bindValue(
                ':idCategoriaFinanceiroObra',
                $this->getIdCategoriaFinanceiroObra(),
                PDO::PARAM_INT
            );
            $stmt->bindValue(':descricao', $this->getDescricao(), PDO::PARAM_STR);
            $stmt->bindValue(':valor', $this->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(':dataGasto', $this->getDataGasto(), PDO::PARAM_STR);
            $stmt->bindValue(':formaPagamento', $this->getFormaPagamento(), PDO::PARAM_STR);
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

