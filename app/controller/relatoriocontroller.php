<?php

namespace App\Controllers;

use App\Models\Relatorio;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Obra;
use App\Models\Veiculo;
use App\Core\Auth;

class RelatoriosController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        $tiposRelatorios = [
            'clientes' => 'Clientes',
            'funcionarios' => 'Funcionários',
            'obras' => 'Obras',
            'veiculos' => 'Veículos',
            'financeiro' => 'Financeiro'
        ];

        $relatorioSelecionado = $_GET['relatorio'] ?? 'funcionarios';
        
        if (!array_key_exists($relatorioSelecionado, $tiposRelatorios)) {
            $relatorioSelecionado = 'funcionarios';
        }

        $dados = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = $this->buscarRelatorio($relatorioSelecionado);
        }

        require_once __DIR__ . '/../view/relatorios/index.php';
    }

    /**
     * Busca os dados do relatório selecionado baseado nos filtros
     */
    private function buscarRelatorio(string $tipo): array
    {
        $dados = [];

        switch ($tipo) {
            case 'clientes':
                $dados = $this->gerarRelatorioClientes();
                break;
            case 'funcionarios':
                $dados = $this->gerarRelatorioFuncionarios();
                break;
            case 'obras':
                $dados = $this->gerarRelatorioObras();
                break;
            case 'veiculos':
                $dados = $this->gerarRelatorioVeiculos();
                break;
            case 'financeiro':
                $dados = $this->gerarRelatorioFinanceiro();
                break;
        }

        return $dados;
    }

    /**
     * Gera o relatório de Clientes com filtros
     */
    private function gerarRelatorioClientes(): array
    {
        $clienteModel = new Cliente();
        $nomeFiltro = $_POST['nomeCliente'] ?? '';
        $documentoFiltro = $_POST['documento'] ?? '';

        // Se houver filtros, aplica; caso contrário, lista todos
        if (!empty($nomeFiltro) || !empty($documentoFiltro)) {
            $clientes = $clienteModel->buscarComFiltros($nomeFiltro, $documentoFiltro);
        } else {
            $clientes = $clienteModel->listar();
        }

        return [
            'tipo' => 'clientes',
            'dados' => $clientes ?? [],
            'total' => count($clientes ?? [])
        ];
    }

    /**
     * Gera o relatório de Funcionários com filtros
     */
    private function gerarRelatorioFuncionarios(): array
    {
        $funcionarioModel = new Funcionario();
        $nomeFiltro = $_POST['nome'] ?? '';
        $cargoFiltro = $_POST['cargoFuncao'] ?? '';
        $statusFiltro = $_POST['status'] ?? '';

        // Se houver filtros, aplica; caso contrário, lista todos
        if (!empty($nomeFiltro) || !empty($cargoFiltro) || !empty($statusFiltro)) {
            $funcionarios = $funcionarioModel->buscarComFiltros($nomeFiltro, $cargoFiltro, $statusFiltro);
        } else {
            $funcionarios = $funcionarioModel->listar();
        }

        return [
            'tipo' => 'funcionarios',
            'dados' => $funcionarios ?? [],
            'total' => count($funcionarios ?? [])
        ];
    }

    /**
     * Gera o relatório de Obras com filtros
     */
    private function gerarRelatorioObras(): array
    {
        $obraModel = new Obra();
        $nomeFiltro = $_POST['nomeObra'] ?? '';
        $statusFiltro = $_POST['statusObra'] ?? '';

        // Se houver filtros, aplica; caso contrário, lista todos
        if (!empty($nomeFiltro) || !empty($statusFiltro)) {
            $obras = $obraModel->buscarComFiltros($nomeFiltro, $statusFiltro);
        } else {
            $obras = $obraModel->listar();
        }

        return [
            'tipo' => 'obras',
            'dados' => $obras ?? [],
            'total' => count($obras ?? [])
        ];
    }

    /**
     * Gera o relatório de Veículos com filtros
     */
    private function gerarRelatorioVeiculos(): array
    {
        $veiculoModel = new Veiculo();
        $placaFiltro = $_POST['placa'] ?? '';
        $statusFiltro = $_POST['statusVeiculo'] ?? '';

        // Se houver filtros, aplica; caso contrário, lista todos
        if (!empty($placaFiltro) || !empty($statusFiltro)) {
            $veiculos = $veiculoModel->buscarComFiltros($placaFiltro, $statusFiltro);
        } else {
            $veiculos = $veiculoModel->listar();
        }

        return [
            'tipo' => 'veiculos',
            'dados' => $veiculos ?? [],
            'total' => count($veiculos ?? [])
        ];
    }

    /**
     * Gera o relatório Financeiro com filtros
     */
    private function gerarRelatorioFinanceiro(): array
    {
        $relatorioModel = new Relatorio();
        $tipoFiltro = $_POST['tipoFinanceiro'] ?? '';
        $dataInicio = $_POST['dataInicio'] ?? '';
        $dataFim = $_POST['dataFim'] ?? '';

        // Se houver filtros, aplica; caso contrário, lista todos
        if (!empty($tipoFiltro) || !empty($dataInicio) || !empty($dataFim)) {
            $financeiros = $relatorioModel->buscarFinanceiroComFiltros($tipoFiltro, $dataInicio, $dataFim);
        } else {
            $financeiros = $relatorioModel->listarFinanceiro();
        }

        return [
            'tipo' => 'financeiro',
            'dados' => $financeiros ?? [],
            'total' => count($financeiros ?? [])
        ];
    }

    /**
     * Exporta o relatório em CSV
     */
    public function exportarCsv()
    {
        $relatorio = $_GET['relatorio'] ?? 'funcionarios';
        $dados = $this->buscarRelatorio($relatorio);

        if (empty($dados['dados'])) {
            header('Location: /ideal/public/index.php?url=relatorios&erro=sem_dados');
            exit;
        }

        // Define o header para download de arquivo CSV
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="relatorio_' . $relatorio . '_' . date('d_m_Y') . '.csv"');

        // Abre o output como um arquivo
        $output = fopen('php://output', 'w');

        // Escreve o BOM UTF-8 para Excel ler corretamente
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Cabeçalho do CSV
        if (!empty($dados['dados']) && is_array($dados['dados'][0] ?? null)) {
            fputcsv($output, array_keys($dados['dados'][0]), ';');

            // Dados do CSV
            foreach ($dados['dados'] as $linha) {
                fputcsv($output, $linha, ';');
            }
        }

        fclose($output);
        exit;
    }

    /**
     * Exporta o relatório em PDF (requer biblioteca como TCPDF ou mPDF)
     * Por enquanto apenas redireciona com mensagem
     */
    public function exportarPdf()
    {
        $relatorio = $_GET['relatorio'] ?? 'funcionarios';
        header('Location: /ideal/public/index.php?url=relatorios&relatorio=' . $relatorio . '&aviso=pdf_em_desenvolvimento');
        exit;
    }
}

