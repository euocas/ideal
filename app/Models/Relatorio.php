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
            // Buscar em financeiroObra
            $sqlObra = "SELECT idFinanceiroObra as id, 'obra' as tipo, valor, data FROM financeiroObra ORDER BY data DESC";
            $stmtObra = $this->pdo->prepare($sqlObra);
            $stmtObra->execute();
            $resultados = array_merge($resultados, $stmtObra->fetchAll(PDO::FETCH_ASSOC));
            
            // Buscar em financeiroFuncionario
            $sqlFunc = "SELECT idFinanceiroFuncionario as id, 'funcionario' as tipo, valor, data FROM financeiroFuncionario ORDER BY data DESC";
            $stmtFunc = $this->pdo->prepare($sqlFunc);
            $stmtFunc->execute();
            $resultados = array_merge($resultados, $stmtFunc->fetchAll(PDO::FETCH_ASSOC));
            
            // Buscar em financeiroAutomovel
            $sqlAuto = "SELECT idFinanceiroAutomovel as id, 'automovel' as tipo, valor, data FROM financeiroAutomovel ORDER BY data DESC";
            $stmtAuto = $this->pdo->prepare($sqlAuto);
            $stmtAuto->execute();
            $resultados = array_merge($resultados, $stmtAuto->fetchAll(PDO::FETCH_ASSOC));
            
            // Ordenar todos por data DESC
            usort($resultados, function($a, $b) {
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
        $resultados = [];
        
        try {
            // Buscar em financeiroObra
            $sqlObra = "SELECT idFinanceiroObra as id, 'obra' as tipo, valor, data FROM financeiroObra WHERE 1=1";
            if (!empty($dataInicio)) {
                $sqlObra .= " AND data >= :dataInicio";
            }
            if (!empty($dataFim)) {
                $sqlObra .= " AND data <= :dataFim";
            }
            $sqlObra .= " ORDER BY data DESC";
            
            $stmtObra = $this->pdo->prepare($sqlObra);
            if (!empty($dataInicio)) {
                $stmtObra->bindValue(':dataInicio', $dataInicio, PDO::PARAM_STR);
            }
            if (!empty($dataFim)) {
                $stmtObra->bindValue(':dataFim', $dataFim, PDO::PARAM_STR);
            }
            $stmtObra->execute();
            $resultados = array_merge($resultados, $stmtObra->fetchAll(PDO::FETCH_ASSOC));
            
            // Buscar em financeiroFuncionario
            $sqlFunc = "SELECT idFinanceiroFuncionario as id, 'funcionario' as tipo, valor, data FROM financeiroFuncionario WHERE 1=1";
            if (!empty($dataInicio)) {
                $sqlFunc .= " AND data >= :dataInicio";
            }
            if (!empty($dataFim)) {
                $sqlFunc .= " AND data <= :dataFim";
            }
            $sqlFunc .= " ORDER BY data DESC";
            
            $stmtFunc = $this->pdo->prepare($sqlFunc);
            if (!empty($dataInicio)) {
                $stmtFunc->bindValue(':dataInicio', $dataInicio, PDO::PARAM_STR);
            }
            if (!empty($dataFim)) {
                $stmtFunc->bindValue(':dataFim', $dataFim, PDO::PARAM_STR);
            }
            $stmtFunc->execute();
            $resultados = array_merge($resultados, $stmtFunc->fetchAll(PDO::FETCH_ASSOC));
            
            // Buscar em financeiroAutomovel
            $sqlAuto = "SELECT idFinanceiroAutomovel as id, 'automovel' as tipo, valor, data FROM financeiroAutomovel WHERE 1=1";
            if (!empty($dataInicio)) {
                $sqlAuto .= " AND data >= :dataInicio";
            }
            if (!empty($dataFim)) {
                $sqlAuto .= " AND data <= :dataFim";
            }
            $sqlAuto .= " ORDER BY data DESC";
            
            $stmtAuto = $this->pdo->prepare($sqlAuto);
            if (!empty($dataInicio)) {
                $stmtAuto->bindValue(':dataInicio', $dataInicio, PDO::PARAM_STR);
            }
            if (!empty($dataFim)) {
                $stmtAuto->bindValue(':dataFim', $dataFim, PDO::PARAM_STR);
            }
            $stmtAuto->execute();
            $resultados = array_merge($resultados, $stmtAuto->fetchAll(PDO::FETCH_ASSOC));
            
            // Filtrar por tipo se especificado
            if (!empty($tipoFinanceiro)) {
                $resultados = array_filter($resultados, function($item) use ($tipoFinanceiro) {
                    return $item['tipo'] === $tipoFinanceiro;
                });
            }
            
            // Ordenar por data DESC
            usort($resultados, function($a, $b) {
                return strtotime($b['data'] ?? '1970-01-01') - strtotime($a['data'] ?? '1970-01-01');
            });
            
            return $resultados;
        } catch (\Exception $e) {
            error_log('Erro ao buscar financeiro com filtros: ' . $e->getMessage());
            return [];
        }
    }
}

