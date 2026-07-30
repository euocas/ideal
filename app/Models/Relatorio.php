<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class Relatorio
{
    private PDO $pdo;

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->getConnection();
    }

    // Lista todos os lançamentos financeiros (obras, funcionários, veículos) já unificados no formato do relatório.
     
    public function listarFinanceiro(): array
    {
        $resultados = [];

        try {
            // financeiroObra: não tem ENTRADA/SAIDA na categoria, é sempre gasto (SAIDA)
            $sqlObra = "
                SELECT
                    fo.idFinanceiroObra AS id,
                    'Obra' AS origem,
                    'SAIDA' AS tipo,
                    co.nome AS categoria,
                    fo.descricao,
                    fo.valor,
                    fo.dataGasto AS data
                FROM financeiroObra fo
                INNER JOIN categoriaFinanceiroObra co
                    ON co.idCategoriaFinanceiroObra = fo.idCategoriaFinanceiroObra
            ";
            $stmtObra = $this->pdo->prepare($sqlObra);
            $stmtObra->execute();
            $resultados = array_merge($resultados, $stmtObra->fetchAll(PDO::FETCH_ASSOC));

            // financeiroFuncionario: ENTRADA/SAIDA vem da categoria vinculada
            $sqlFunc = "
                SELECT
                    ff.idFinanceiroFuncionario AS id,
                    'Funcionário' AS origem,
                    cf.tipo,
                    cf.nome AS categoria,
                    ff.descricao,
                    ff.valor,
                    ff.dataReferencia AS data
                FROM financeiroFuncionario ff
                INNER JOIN categoriaFinanceiroFuncionario cf
                    ON cf.idCategoria = ff.idCategoria
            ";
            $stmtFunc = $this->pdo->prepare($sqlFunc);
            $stmtFunc->execute();
            $resultados = array_merge($resultados, $stmtFunc->fetchAll(PDO::FETCH_ASSOC));

            // financeiroVeiculo: ENTRADA/SAIDA vem da categoria vinculada
            $sqlVeiculo = "
                SELECT
                    fv.idFinanceiroVeiculo AS id,
                    'Veículo' AS origem,
                    cv.tipo,
                    cv.nome AS categoria,
                    fv.descricao,
                    fv.valor,
                    fv.dataMovimentacao AS data
                FROM financeiroVeiculo fv
                INNER JOIN categoriaFinanceiroVeiculo cv
                    ON cv.idCategoriaFinanceiroVeiculo = fv.idCategoriaFinanceiroVeiculo
            ";
            $stmtVeiculo = $this->pdo->prepare($sqlVeiculo);
            $stmtVeiculo->execute();
            $resultados = array_merge($resultados, $stmtVeiculo->fetchAll(PDO::FETCH_ASSOC));

            usort($resultados, function ($a, $b) {
                return strtotime($b['data'] ?? '1970-01-01') - strtotime($a['data'] ?? '1970-01-01');
            });

            return $resultados;
        } catch (\Exception $e) {
            error_log('Erro ao listar financeiro: ' . $e->getMessage());
            return [];
        }
    }

      // Busca lançamentos financeiros com filtros de tipo (entrada/saída) e período.
     
    public function buscarFinanceiroComFiltros(string $tipoFinanceiro = '', string $dataInicio = '', string $dataFim = ''): array
    {
        $resultados = $this->listarFinanceiro();

        if (!empty($tipoFinanceiro)) {
            $tipoFinanceiro = strtoupper($tipoFinanceiro);
            $resultados = array_filter($resultados, function ($item) use ($tipoFinanceiro) {
                return strtoupper($item['tipo'] ?? '') === $tipoFinanceiro;
            });
        }

        if (!empty($dataInicio)) {
            $resultados = array_filter($resultados, function ($item) use ($dataInicio) {
                return !empty($item['data']) && $item['data'] >= $dataInicio;
            });
        }

        if (!empty($dataFim)) {
            $resultados = array_filter($resultados, function ($item) use ($dataFim) {
                return !empty($item['data']) && $item['data'] <= $dataFim;
            });
        }

        return array_values($resultados);
    }
}