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

    private ?int $idCategoria = null;

    private ?string $descricao = null;

    private ?float $valor = null;

    private ?string $dataReferencia = null;

    private ?string $formaPagamento = null;

    private ?string $contaPagamento = null;

    private ?string $observacao = null;
    private ?string $categoria = null;
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
    public function getIdFinanceiroFuncionario(): ?int
    {
        return $this->idFinanceiroFuncionario;
    }

    public function setIdFinanceiroFuncionario(?int $id): void
    {
        $this->idFinanceiroFuncionario = $id;
    }

    public function getIdFuncionario(): ?int
    {
        return $this->idFuncionario;
    }

    public function setIdFuncionario(?int $id): void
    {
        $this->idFuncionario = $id;
    }

    public function getIdCategoria(): ?int
    {
        return $this->idCategoria;
    }

    public function setIdCategoria(?int $idCategoria): void
    {
        $this->idCategoria = $idCategoria;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): void
    {
        $this->descricao = $descricao;
    }

    public function getValor(): ?float
    {
        return $this->valor;
    }

    public function setValor($valor): void
    {
        $this->valor = $valor !== null && $valor !== ''
            ? (float) $valor
            : null;
    }

    public function getDataReferencia(): ?string
    {
        return $this->dataReferencia;
    }

    public function setDataReferencia(?string $data): void
    {
        $this->dataReferencia = $data;
    }

    public function getFormaPagamento(): ?string
    {
        return $this->formaPagamento;
    }

    public function setFormaPagamento(?string $forma): void
    {
        $this->formaPagamento = $forma;
    }

    public function getContaPagamento(): ?string
    {
        return $this->contaPagamento;
    }

    public function setContaPagamento(?string $conta): void
    {
        $this->contaPagamento = $conta;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }

    public function setObservacao(?string $observacao): void
    {
        $this->observacao = $observacao;
    }

    public function getCategoria(): ?string
    {
        return $this->categoria;
    }

    public function setCategoria(?string $categoria): void
    {
        $this->categoria = $categoria;
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
        $obj->setIdFinanceiroFuncionario($dados['idFinanceiroFuncionario'] ?? null);
        $obj->setIdFuncionario($dados['idFuncionario'] ?? null);
        $obj->setIdCategoria($dados['idCategoria'] ?? null);
        $obj->setDescricao($dados['descricao'] ?? null);
        $obj->setValor($dados['valor'] ?? null);
        $obj->setDataReferencia($dados['dataReferencia'] ?? null);
        $obj->setFormaPagamento($dados['formaPagamento'] ?? null);
        $obj->setContaPagamento($dados['contaPagamento'] ?? null);
        $obj->setObservacao($dados['observacao'] ?? null);
        $obj->setCategoria($dados['categoria'] ?? null);
        $obj->setTipo($dados['tipo'] ?? null);
        return $obj;
    }
    // =====================================================
// 5. CONSULTAS
// =====================================================

    public function buscarIdCategoriaPorNome(string $nome): ?int
    {
        $stmt = $this->pdo->prepare("
        SELECT idCategoria
        FROM categoriaFinanceiroFuncionario
        WHERE nome = :nome
        LIMIT 1
    ");

        $stmt->bindValue(':nome', $nome);
        $stmt->execute();

        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }
    public function findById(int $id): ?self
    {

        $stmt = $this->pdo->prepare("
        SELECT
            ff.*,
            c.nome AS categoria,
            c.tipo
        FROM financeiroFuncionario ff
        INNER JOIN categoriaFinanceiroFuncionario c
            ON c.idCategoria = ff.idCategoria
        WHERE ff.idFinanceiroFuncionario = :id
    ");

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->hydrate($dados) : null;
    }

    public function buscarPorFuncionarioEPeriodo(int $idFuncionario, int $mes, int $ano): array
    {
        $stmt = $this->pdo->prepare("
        SELECT
            ff.*,
            c.nome AS categoria,
            c.tipo
        FROM financeiroFuncionario ff
        INNER JOIN categoriaFinanceiroFuncionario c
            ON c.idCategoria = ff.idCategoria
        WHERE ff.idFuncionario = :idFuncionario
          AND MONTH(ff.dataReferencia) = :mes
          AND YEAR(ff.dataReferencia) = :ano
        ORDER BY ff.dataReferencia DESC
    ");

        $stmt->bindValue(':idFuncionario', $idFuncionario, PDO::PARAM_INT);
        $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
        $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);

        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => $this->hydrate($row), $rows);
    }
    public function findUltimosByIdFuncionario(int $idFuncionario, int $limite = 4): array
    {
        $limite = max(1, $limite);
        $sql = "
        SELECT ff.*,c.nome AS categoria, c.tipo
        FROM financeiroFuncionario ff
        INNER JOIN categoriaFinanceiroFuncionario c
            ON c.idCategoria = ff.idCategoria
        WHERE ff.idFuncionario = :idFuncionario
        ORDER BY ff.dataReferencia DESC,
                 ff.idFinanceiroFuncionario DESC LIMIT {$limite}";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':idFuncionario', $idFuncionario, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(
            fn($row) => $this->hydrate($row),
            $rows
        );
    }

    public function calcularEntradas(int $idFuncionario, int $mes, int $ano): float
    {
        $sql = "
        SELECT COALESCE(SUM(ff.valor), 0)
        FROM financeiroFuncionario ff
        INNER JOIN categoriaFinanceiroFuncionario c
            ON c.idCategoria = ff.idCategoria
        WHERE ff.idFuncionario = :idFuncionario
          AND c.tipo = 'ENTRADA'
          AND MONTH(ff.dataReferencia) = :mes
          AND YEAR(ff.dataReferencia) = :ano
    ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':idFuncionario', $idFuncionario, PDO::PARAM_INT);
        $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
        $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);

        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }

    public function calcularSaidas(int $idFuncionario, int $mes, int $ano): float
    {
        $sql = "
        SELECT COALESCE(SUM(ff.valor), 0)
        FROM financeiroFuncionario ff
        INNER JOIN categoriaFinanceiroFuncionario c
            ON c.idCategoria = ff.idCategoria
        WHERE ff.idFuncionario = :idFuncionario
          AND c.tipo = 'SAIDA'
          AND MONTH(ff.dataReferencia) = :mes
          AND YEAR(ff.dataReferencia) = :ano
    ";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(':idFuncionario', $idFuncionario, PDO::PARAM_INT);
        $stmt->bindValue(':mes', $mes, PDO::PARAM_INT);
        $stmt->bindValue(':ano', $ano, PDO::PARAM_INT);

        $stmt->execute();

        return (float) $stmt->fetchColumn();
    }
    // =====================================================
    // 6. CRUD
    // =====================================================
    
    public function save(): bool
    {
        try {

            $sql = "
            INSERT INTO financeiroFuncionario (idFuncionario,idCategoria,descricao,valor,dataReferencia,formaPagamento,contaPagamento,observacao)
             VALUES (:idFuncionario,:idCategoria,:descricao,:valor,:dataReferencia,:formaPagamento, :contaPagamento,:observacao)";
            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':idFuncionario', $this->getIdFuncionario(), PDO::PARAM_INT);
            $stmt->bindValue(':idCategoria', $this->getIdCategoria(), PDO::PARAM_INT);
            $stmt->bindValue(':descricao', $this->getDescricao(), PDO::PARAM_STR);
            $stmt->bindValue(':valor', $this->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(':dataReferencia', $this->getDataReferencia(), PDO::PARAM_STR);
            $stmt->bindValue(':formaPagamento', $this->getFormaPagamento(), PDO::PARAM_STR);
            $stmt->bindValue(':contaPagamento', $this->getContaPagamento(), PDO::PARAM_STR);
            $stmt->bindValue(':observacao', $this->getObservacao(), PDO::PARAM_STR);

            $stmt->execute();

            $this->setIdFinanceiroFuncionario(
                (int) $this->pdo->lastInsertId()
            );

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

            $sql = "
            UPDATE financeiroFuncionario
            SET
            idFuncionario = :idFuncionario, idCategoria = :idCategoria, descricao = :descricao, valor = :valor, dataReferencia  = :dataReferencia,formaPagamento = :formaPagamento,contaPagamento = :contaPagamento, observacao = :observacao
            WHERE idFinanceiroFuncionario = :id ";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':idFuncionario', $this->getIdFuncionario(), PDO::PARAM_INT);
            $stmt->bindValue(':idCategoria', $this->getIdCategoria(), PDO::PARAM_INT);
            $stmt->bindValue(':descricao', $this->getDescricao(), PDO::PARAM_STR);
            $stmt->bindValue(':valor', $this->getValor(), PDO::PARAM_STR);
            $stmt->bindValue(':dataReferencia', $this->getDataReferencia(), PDO::PARAM_STR);
            $stmt->bindValue(':formaPagamento', $this->getFormaPagamento(), PDO::PARAM_STR);
            $stmt->bindValue(':contaPagamento', $this->getContaPagamento(), PDO::PARAM_STR);
            $stmt->bindValue(':observacao', $this->getObservacao(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->getIdFinanceiroFuncionario(), PDO::PARAM_INT);

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

