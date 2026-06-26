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

    /**
     * Lista todos os registros financeiros (obras, funcionários, veículos)
     */
    public function listarFinanceiro(): array
    {
        $resultados = [];

        try {
            // financeiroObra: valor = valor do gasto, data = dataGasto
            $sqlObra = "SELECT idFinanceiroObra as id, 'obra' as tipo, descricao, valor, dataGasto as data
                        FROM financeiroObra ORDER BY dataGasto DESC";
            $stmtObra = $this->pdo->prepare($sqlObra);
            $stmtObra->execute();
            $resultados = array_merge($resultados, $stmtObra->fetchAll(PDO::FETCH_ASSOC));

            // financeiroFuncionario: soma salario + ferias + inss + decimoTerceiro como "valor"
            $sqlFunc = "SELECT idFinanceiroFuncionario as id, 'funcionario' as tipo,
                        'Despesas com funcionário' as descricao,
                        (COALESCE(salario,0) + COALESCE(ferias,0) + COALESCE(inss,0) + COALESCE(decimoTerceiro,0)) as valor,
                        dataRegistro as data
                        FROM financeiroFuncionario";
            $stmtFunc = $this->pdo->prepare($sqlFunc);
            $stmtFunc->execute();
            $resultados = array_merge($resultados, $stmtFunc->fetchAll(PDO::FETCH_ASSOC));

            // financeiroAutomovel: soma combustivel + manutencao + ipva como "valor"
            $sqlAuto = "SELECT idFinanceiroAutomovel as id, 'automovel' as tipo,
                        'Despesas com veículo' as descricao,
                        (COALESCE(combustivel,0) + COALESCE(manutencao,0) + COALESCE(ipva,0)) as valor,
                        dataRegistro as data
                        FROM financeiroAutomovel";
            $stmtAuto = $this->pdo->prepare($sqlAuto);
            $stmtAuto->execute();
            $resultados = array_merge($resultados, $stmtAuto->fetchAll(PDO::FETCH_ASSOC));

            usort($resultados, function ($a, $b) {
                return strtotime($b['data'] ?? '1970-01-01') - strtotime($a['data'] ?? '1970-01-01');
            });

            return $resultados;
        } catch (\Exception $e) {
            error_log('Erro ao listar financeiro: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca registros financeiros com filtros
     */
    public function buscarFinanceiroComFiltros(string $tipoFinanceiro = '', string $dataInicio = '', string $dataFim = ''): array
    {
        $resultados = $this->listarFinanceiro();

        if (!empty($tipoFinanceiro)) {
            $resultados = array_filter($resultados, function ($item) use ($tipoFinanceiro) {
                return $item['tipo'] === $tipoFinanceiro;
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