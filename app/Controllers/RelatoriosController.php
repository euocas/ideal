<?php

namespace App\Controllers;

use App\Models\Relatorio;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\Obra;
use App\Models\Veiculo;
use App\Core\Auth;
// Abaixo para o PDF
use App\Services\PdfService;
// Abaixo para o Excel
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;


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

        $relatorio = $_GET['relatorio'] ?? 'funcionarios';

        if (!array_key_exists($relatorio, $tiposRelatorios)) {
            $relatorio = 'funcionarios';
        }

        $tipoSelecionado = $tiposRelatorios[$relatorio];

        // Busca dados sempre (não apenas em POST)
        $dados = $this->buscarRelatorio($relatorio);

        require_once __DIR__ . '/../Views/relatorios/index.php';
    }
    //Busca os dados do relatório selecionado baseado nos filtros 
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
    // Gera o relatório de Clientes com filtros 
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
    // Gera o relatório de Funcionários com filtros
    private function gerarRelatorioFuncionarios(): array
    {
        $funcionarioModel = new Funcionario();
        $nomeFiltro = $_POST['nome'] ?? '';
        $cargoFiltro = $_POST['cargoFuncao'] ?? '';
        $statusFiltro = $_POST['status'] ?? '';
        $cpfFiltro = $_POST['cpf'] ?? '';

        // Se houver filtros, aplica; caso contrário, lista todos
        if (!empty($nomeFiltro) || !empty($cargoFiltro) || !empty($statusFiltro) || !empty($cpfFiltro)) {
            $funcionarios = $funcionarioModel->buscarComFiltros($nomeFiltro, $cargoFiltro, $statusFiltro, $cpfFiltro);
        } else {
            $funcionarios = $funcionarioModel->listar();
        }

        return [
            'tipo' => 'funcionarios',
            'dados' => $funcionarios ?? [],
            'total' => count($funcionarios ?? [])
        ];
    }

    //Gera o relatório de Obras com filtros
    private function gerarRelatorioObras(): array
    {
        $obraModel = new Obra();
        $nomeFiltro = $_POST['nomeObra'] ?? '';
        $statusFiltro = $_POST['statusObra'] ?? '';

        // Se houver filtros, aplica; caso contrário, lista todos
        if (!empty($nomeFiltro) || !empty($statusFiltro)) {
            $obras = $obraModel->findByFilters($nomeFiltro, $statusFiltro);
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
    public function exportarExcel()
    {
        $relatorio = $_GET['relatorio'] ?? 'funcionarios';
        $dados = $this->buscarRelatorio($relatorio);

        $planilha = new Spreadsheet(); //cria uma planilha vazia.
        $sheet = $planilha->getActiveSheet(); //pega a primeira aba da planilha.

        $sheet->setCellValue('A1', 'Relatório de Funcionários - Sistema IDEAL • Soluções Elétricas');
        // Junta as células A1 até E1 em uma única célula (mesclar)
        $sheet->mergeCells('A1:E1');
        //largura do cabeçalho em 35
        $sheet->getRowDimension(1)->setRowHeight(35);
        // Fonte do cabeçalho em 16
        $sheet->getStyle('A1')->getFont()->setSize(16);

        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Nome');
        $sheet->setCellValue('C2', 'CPF');
        $sheet->setCellValue('D2', 'Cargo');
        $sheet->setCellValue('E2', 'Status');

        // Colocar as linhas iniciais em negrito (cabeçalho)
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);
        $sheet->getStyle('A2:E2')->getFont()->setBold(true);

        // Colorir primeira linha em azul (cabeçalho)
        $sheet->getStyle('A1:E1')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID) // pinta a célula inteira
            ->getStartColor()
            ->setARGB('4472C4'); // cor azul

        // Colorir segunda linha em azul (cabeçalho)
        $sheet->getStyle('A2:E2')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID) // pinta a célula inteira
            ->getStartColor()
            ->setARGB('4472C4'); // cor azul

        // Colorir texto da primeira linha de branco (cabeçalho)
        $sheet->getStyle('A1:E1')
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFF');

        // Colorir texto da segunda linha de branco 
        $sheet->getStyle('A2:E2')
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFF');

        // Centralizar primeira linha (cabeçalho)
        $sheet->getStyle('A1:E1')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        // Centralizar segunda linha
        $sheet->getStyle('A2:E2')
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // tamanho da célula no excel
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        // Congela o cabeçalho
        $sheet->freezePane('A3');

        //Aplica filtro
        $sheet->setAutoFilter('B2:E2');

        $linha = 3;

        foreach ($dados['dados'] as $funcionario) {

            $sheet->setCellValue('A' . $linha, $funcionario['idFuncionario']);
            $sheet->setCellValue('b' . $linha, $funcionario['nome']);
            $cpf = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $funcionario['cpf']);
            $sheet->setCellValue('C' . $linha, $cpf);
            $sheet->setCellValue('D' . $linha, $funcionario['cargoFuncao']);
            $sheet->setCellValue('E' . $linha, $funcionario['status']);

            $linha++;

        }

        //Formatação da planilha: A2,C2 e E2 estão centralizadas
        $sheet->getStyle('A3:A' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C3:C' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('E3:E' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Adicionar borda
        $sheet->getStyle('A1:E' . ($linha - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);


        if (empty($dados['dados'])) {
            header('Location: /ideal/public/index.php?url=relatorios&erro=sem_dados');
            exit;
        }

        // Define o header para download de arquivo Excel
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="relatorio_' . $relatorio . '_' . date('d_m_Y') . '.xlsx"');

        // Cria o responsável por gravar a planilha
        $writer = new Xlsx($planilha);


        $writer->save('php://output');
        exit;
    }
    /**
     * Exporta o relatório em PDF (requer biblioteca como TCPDF ou mPDF)
     * Por enquanto apenas redireciona com mensagem
     */

    public function exportarPdf()
    {
        $tipo = $_GET['relatorio'] ?? 'funcionarios';

        $relatorio = $this->buscarRelatorio($tipo);

        $view = $relatorio['tipo'];

        if ($view === 'financeiro') {
            $view = 'financeiros';
        }

        PdfService::gerar(
            'relatorios/pdf/' . $view,
            $relatorio,
            'relatorio-' . $relatorio['tipo'] . '.pdf'
        );
    }
}

