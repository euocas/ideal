<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class FinanceiroFuncionario
{
    // =====================================================
    // 1. ATRIBUTOS (Alinhados com a tabela do banco)
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
    public function setIdFuncionario($id): void
    {
        $this->idFuncionario = $id ? (int) $id : null;
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
    public function setDataReferencia(?string $dataReferencia): void
    {
        $this->dataReferencia = $dataReferencia;
    }

    public function getFormaPagamento(): ?string
    {
        return $this->formaPagamento;
    }
    public function setFormaPagamento(?string $formaPagamento): void
    {
        $this->formaPagamento = $formaPagamento;
    }

    public function getContaPagamento(): ?string
    {
        return $this->contaPagamento;
    }
    public function setContaPagamento(?string $contaPagamento): void
    {
        $this->contaPagamento = $contaPagamento;
    }

    public function getObservacao(): ?string
    {
        return $this->observacao;
    }
    public function setObservacao(?string $observacao): void
    {
        $this->observacao = $observacao;
    }

    // =====================================================
    // 4. FUNÇÕES AUXILIARES E CRUD
    // =====================================================

    // Busca o ID da categoria a partir do Nome. Se não existir, auto-cadastra.
    public function buscarIdCategoriaPorNome(string $nome, string $tipo = 'ENTRADA'): ?int
    {
        $nomeLimpo = trim($nome);
        if (empty($nomeLimpo))
            return null;

        $stmt = $this->pdo->prepare("SELECT idCategoria FROM categoriaFinanceiroFuncionario WHERE LOWER(nome) = LOWER(:nome) LIMIT 1");
        $stmt->execute([':nome' => $nomeLimpo]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($res) {
            return (int) $res['idCategoria'];
        }

        // Se não achou, cadastra automaticamente para evitar que a tela quebre
        try {
            $stmtInsert = $this->pdo->prepare("INSERT INTO categoriaFinanceiroFuncionario (nome, tipo, tipoContrato, ativo) VALUES (:nome, :tipo, 'TODOS', 1)");
            $stmtInsert->execute([
                ':nome' => $nomeLimpo,
                ':tipo' => strtoupper($tipo)
            ]);
            return (int) $this->pdo->lastInsertId();
        } catch (\Exception $e) {
            return null;
        }
    }

    // Busca lançamentos com JOIN na Categoria para a tela de Período
    public function buscarPorFuncionarioEPeriodo(int $idFuncionario, $mes, $ano): array
    {
        $sql = "SELECT f.*, c.nome as categoriaNome, c.tipo as categoriaTipo 
                FROM financeiroFuncionario f
                INNER JOIN categoriaFinanceiroFuncionario c ON f.idCategoria = c.idCategoria
                WHERE f.idFuncionario = :idFuncionario 
                  AND MONTH(f.dataReferencia) = :mes 
                  AND YEAR(f.dataReferencia) = :ano
                ORDER BY f.dataReferencia DESC, f.idFinanceiroFuncionario DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':idFuncionario' => $idFuncionario,
            ':mes' => $mes,
            ':ano' => $ano
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save(): bool
    {
        try {
            $sql = "INSERT INTO financeiroFuncionario (idFuncionario, idCategoria, descricao, valor, dataReferencia, formaPagamento, contaPagamento, observacao, dataCadastro)
                    VALUES (:idFuncionario, :idCategoria, :descricao, :valor, :dataReferencia, :formaPagamento, :contaPagamento, :observacao, NOW())";

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

            $this->idFinanceiroFuncionario = (int) $this->pdo->lastInsertId();
            return true;

        } catch (\Exception $e) {
            error_log($e->getMessage());
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