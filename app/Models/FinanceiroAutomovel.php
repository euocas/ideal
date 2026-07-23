<?php
namespace App\Models;

use App\Config\Conexao;
use PDO;

class FinanceiroAutomovel
{
    // =====================================================
// 1. ATRIBUTOS
// =====================================================
    private ?int $idFinanceiroAutomovel = null;

    private ?int $idVeiculo = null;

    private ?int $idCategoriaFinanceiroVeiculo = null;

    // Apenas para exibição (JOIN)
    private ?string $categoria = null;

    private ?float $valor = null;

    private ?string $dataMovimentacao = null;

    private ?string $formaPagamento = null;

    private ?string $descricao = null;

    private ?string $observacao = null;

    private ?string $tipo = null;

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

    public function getIdFinanceiroAutomovel(): ?int
    {
        return $this->idFinanceiroAutomovel;
    }
    public function setIdFinanceiroAutomovel(?int $id): void
    {
        $this->idFinanceiroAutomovel = $id;
    }

    public function getIdVeiculo(): ?int
    {
        return $this->idVeiculo;
    }
    public function setIdVeiculo($id): void
    {
        $this->idVeiculo = $id ? (int) $id : null;
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
    public function getIdCategoriaFinanceiroVeiculo(): ?int
    {
        return $this->idCategoriaFinanceiroVeiculo;
    }

    public function setIdCategoriaFinanceiroVeiculo($id): void
    {
        $this->idCategoriaFinanceiroVeiculo = $id ? (int) $id : null;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }
    public function setValor($valor): void
    {
        $this->valor = $valor !== null && $valor !== '' ? (float) $valor : null;
    }

    public function getDataMovimentacao(): ?string
    {
        return $this->dataMovimentacao;
    }
    public function setDataMovimentacao(?string $data): void
    {
        $this->dataMovimentacao = $data;
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

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): void
    {
        $this->tipo = $tipo;
    }

    // =====================================================
// 4. HYDRATE
// =====================================================

    private function hydrate(array $dados): self
    {
        $obj = new self();

        $obj->setIdFinanceiroAutomovel($dados['idFinanceiroVeiculo'] ?? null);
        $obj->setIdVeiculo($dados['idVeiculo'] ?? null);
        $obj->setCategoria($dados['categoria'] ?? null);
        $obj->setIdCategoriaFinanceiroVeiculo($dados['idCategoriaFinanceiroVeiculo'] ?? null);
        $obj->setDescricao($dados['descricao'] ?? null);
        $obj->setValor($dados['valor'] ?? null);
        $obj->setDataMovimentacao($dados['dataMovimentacao'] ?? null);
        $obj->setFormaPagamento($dados['formaPagamento'] ?? null);
        $obj->setObservacao($dados['observacao'] ?? null);
        $obj->setCategoria($dados['categoria'] ?? null);
        $obj->setTipo($dados['tipo'] ?? null);

        return $obj;
    }
    // =====================================================
    // 5. CRUD
    // =====================================================

    public function findById(int $id): ?self
    {
        $sql = "SELECT
                fa.*,
                cfv.nome AS categoria,
                 cfv.tipo
            FROM financeiroVeiculo fa
            INNER JOIN categoriaFinanceiroVeiculo cfv
                ON fa.idCategoriaFinanceiroVeiculo = cfv.idCategoriaFinanceiroVeiculo
            WHERE fa.idFinanceiroVeiculo = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->hydrate($dados) : null;
    }

    public function findByIdVeiculo(int $idVeiculo): array
    {
        $sql = "SELECT
                fa.*,
                cfv.nome AS categoria,
                 cfv.tipo
            FROM financeiroVeiculo fa
            INNER JOIN categoriaFinanceiroVeiculo cfv
                ON fa.idCategoriaFinanceiroVeiculo = cfv.idCategoriaFinanceiroVeiculo
            WHERE fa.idVeiculo = :idVeiculo
            ORDER BY fa.dataMovimentacao DESC, fa.idFinanceiroVeiculo DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idVeiculo', $idVeiculo, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => $this->hydrate($row),
            $rows
        );
    }

    public function buscarPorVeiculoEPeriodo(int $idVeiculo, int $mes, int $ano): array
    {
        $sql = "
        SELECT
            fa.*,
            cfv.nome AS categoria,
            cfv.tipo
        FROM financeiroVeiculo fa
        INNER JOIN categoriaFinanceiroVeiculo cfv
            ON fa.idCategoriaFinanceiroVeiculo = cfv.idCategoriaFinanceiroVeiculo
        WHERE fa.idVeiculo = :idVeiculo
          AND MONTH(fa.dataMovimentacao) = :mes
          AND YEAR(fa.dataMovimentacao) = :ano
        ORDER BY fa.dataMovimentacao DESC, fa.idFinanceiroVeiculo DESC";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':idVeiculo', $idVeiculo, PDO::PARAM_INT);
        $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
        $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findUltimosByIdVeiculo(int $idVeiculo, int $limite = 4): array
    {
        $limite = max(1, $limite);

        $sql = "
        SELECT
            fa.*,
            cfv.nome AS categoria,
             cfv.tipo
        FROM financeiroVeiculo fa
        INNER JOIN categoriaFinanceiroVeiculo cfv
            ON fa.idCategoriaFinanceiroVeiculo = cfv.idCategoriaFinanceiroVeiculo
        WHERE fa.idVeiculo = :idVeiculo
        ORDER BY fa.dataMovimentacao DESC,
                 fa.idFinanceiroVeiculo DESC
        LIMIT {$limite}
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idVeiculo', $idVeiculo, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => $this->hydrate($row),
            $rows
        );
    }

    public function listarCategorias(string $tipo): array
    {
        $sql = "
        SELECT
            idCategoriaFinanceiroVeiculo,
            nome
        FROM categoriaFinanceiroVeiculo
        WHERE tipo = :tipo
          AND ativo = TRUE
        ORDER BY nome";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':tipo', strtoupper($tipo), PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function calcularGastoAtual(int $idVeiculo): float
    {
        $sql = "
        SELECT COALESCE(SUM(valor), 0) AS total
        FROM financeiroVeiculo
        WHERE idVeiculo = :idVeiculo
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idVeiculo', $idVeiculo, PDO::PARAM_INT);
        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    public function calcularRecebimentos(int $idVeiculo): float
    {
        $sql = "
        SELECT COALESCE(SUM(fv.valor), 0)
        FROM financeiroVeiculo fv
        INNER JOIN categoriaFinanceiroVeiculo cfv
            ON fv.idCategoriaFinanceiroVeiculo = cfv.idCategoriaFinanceiroVeiculo
        WHERE fv.idVeiculo = :idVeiculo
          AND cfv.tipo = 'ENTRADA'
    ";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idVeiculo', $idVeiculo, PDO::PARAM_INT);
        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    public function save(): bool
    {
        try {

            $sql = "INSERT INTO financeiroVeiculo (
                    idVeiculo,
                    idCategoriaFinanceiroVeiculo,
                    descricao,
                    valor,
                    dataMovimentacao,
                    formaPagamento,
                    observacao
                ) VALUES (
                    :idVeiculo,
                    :idCategoriaFinanceiroVeiculo,
                    :descricao,
                    :valor,
                    :dataMovimentacao,
                    :formaPagamento,
                    :observacao
                )";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':idVeiculo', $this->getIdVeiculo(), PDO::PARAM_INT);
            $stmt->bindValue(
                ':idCategoriaFinanceiroVeiculo',
                $this->getIdCategoriaFinanceiroVeiculo(),
                PDO::PARAM_INT
            );
            $stmt->bindValue(':descricao', $this->getDescricao(), PDO::PARAM_STR);
            $stmt->bindValue(':valor', $this->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(':dataMovimentacao', $this->getDataMovimentacao(), PDO::PARAM_STR);
            $stmt->bindValue(':formaPagamento', $this->getFormaPagamento(), PDO::PARAM_STR);
            $stmt->bindValue(':observacao', $this->getObservacao(), PDO::PARAM_STR);

            $stmt->execute();

            $this->idFinanceiroAutomovel = (int) $this->pdo->lastInsertId();

            return true;

        } catch (\Exception $e) {

            var_dump($e->getMessage());
            exit;
        }
    }

    public function update(): bool
    {
        if (!$this->getIdFinanceiroAutomovel()) {
            return false;
        }

        try {

            $sql = "UPDATE financeiroVeiculo SET
                    idVeiculo = :idVeiculo,
                    idCategoriaFinanceiroVeiculo = :idCategoriaFinanceiroVeiculo,
                    descricao = :descricao,
                    valor = :valor,
                    dataMovimentacao = :dataMovimentacao,
                    formaPagamento = :formaPagamento,
                    observacao = :observacao
                WHERE idFinanceiroVeiculo = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':idVeiculo', $this->getIdVeiculo(), PDO::PARAM_INT);
            $stmt->bindValue(
                ':idCategoriaFinanceiroVeiculo',
                $this->getIdCategoriaFinanceiroVeiculo(),
                PDO::PARAM_INT
            );
            $stmt->bindValue(':descricao', $this->getDescricao(), PDO::PARAM_STR);
            $stmt->bindValue(':valor', $this->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(':dataMovimentacao', $this->getDataMovimentacao(), PDO::PARAM_STR);
            $stmt->bindValue(':formaPagamento', $this->getFormaPagamento(), PDO::PARAM_STR);
            $stmt->bindValue(':observacao', $this->getObservacao(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->getIdFinanceiroAutomovel(), PDO::PARAM_INT);

            $stmt->execute();

            return true;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM financeiroVeiculo WHERE idFinanceiroVeiculo = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}

