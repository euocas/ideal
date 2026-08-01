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
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


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

    // Exporta o relatório em Excel 
    public function exportarExcel()
    {
        $relatorio = $_GET['relatorio'] ?? 'funcionarios';


        switch ($relatorio) {
            case 'clientes':
                return $this->exportarExcelClientes();

            case 'funcionarios':
                return $this->exportarExcelFuncionarios();

            case 'obras':
                return $this->exportarExcelObras();

            case 'veiculos':
                return $this->exportarExcelVeiculos();

            case 'financeiro':
                return $this->exportarExcelFinanceiros();
            default:
                header('Location: /ideal/public/index.php?url=relatorios&erro=relatorio_invalido');
                exit;


        }
    }
    private function criarPlanilha(string $titulo, string $ultimaColuna): array
    {
        $planilha = new Spreadsheet();
        $sheet = $planilha->getActiveSheet();

        $sheet->setCellValue('A1', $titulo);
        $sheet->mergeCells("A1:{$ultimaColuna}1");

        $sheet->getRowDimension(1)->setRowHeight(35);
        $sheet->getStyle('A1')->getFont()->setSize(16);

        return [$planilha, $sheet];
    }

    private function formatarCabecalho(Worksheet $sheet, string $ultimaColuna): void
    {
        $sheet->getStyle("A1:{$ultimaColuna}1")->getFont()->setBold(true);
        $sheet->getStyle("A2:{$ultimaColuna}2")->getFont()->setBold(true);

        $sheet->getStyle("A1:{$ultimaColuna}1")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('4472C4');

        $sheet->getStyle("A2:{$ultimaColuna}2")
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('4472C4');

        $sheet->getStyle("A1:{$ultimaColuna}2")
            ->getFont()
            ->getColor()
            ->setARGB('FFFFFF');

        $sheet->getStyle("A1:{$ultimaColuna}1")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle("A2:{$ultimaColuna}2")
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);
    }

    public function exportarExcelFuncionarios()
    {
        $relatorio = 'funcionarios';
        $dados = $this->buscarRelatorio($relatorio);

        // Cria a planilha
        [$planilha, $sheet] = $this->criarPlanilha(
            'Relatório de Funcionários - Sistema IDEAL • Soluções Elétricas',
            'E'
        );

        // Cabeçalho da tabela
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Nome');
        $sheet->setCellValue('C2', 'CPF');
        $sheet->setCellValue('D2', 'Cargo');
        $sheet->setCellValue('E2', 'Status');

        // Aplica toda a formatação do cabeçalho
        $this->formatarCabecalho($sheet, 'E');

        // Tamanho das colunas
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);

        // Congela o cabeçalho
        $sheet->freezePane('A3');

        // Aplica filtro
        $sheet->setAutoFilter('A2:E2');

        $linha = 3;

        foreach ($dados['dados'] as $funcionario) {

            $sheet->setCellValue('A' . $linha, $funcionario['idFuncionario']);
            $sheet->setCellValue('B' . $linha, $funcionario['nome']);

            $cpf = preg_replace(
                "/(\d{3})(\d{3})(\d{3})(\d{2})/",
                "$1.$2.$3-$4",
                $funcionario['cpf']
            );

            $sheet->setCellValue('C' . $linha, $cpf);
            $sheet->setCellValue('D' . $linha, $funcionario['cargoFuncao']);
            $sheet->setCellValue('E' . $linha, $funcionario['status']);

            $linha++;
        }

        // Alinhamentos
        $sheet->getStyle('A3:A' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C3:C' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('E3:E' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Borda
        $sheet->getStyle('A1:E' . ($linha - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Finalização (por enquanto continua igual)
        if (empty($dados['dados'])) {
            header('Location: /ideal/public/index.php?url=relatorios&erro=sem_dados');
            exit;
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="relatorio_' . $relatorio . '_' . date('d_m_Y') . '.xlsx"');

        $writer = new Xlsx($planilha);
        $writer->save('php://output');
        exit;
    }
    public function exportarExcelClientes()
    {
        $relatorio = 'clientes';
        $dados = $this->buscarRelatorio($relatorio);

        // Cria a planilha
        [$planilha, $sheet] = $this->criarPlanilha(
            'Relatório de Clientes - Sistema IDEAL • Soluções Elétricas',
            'D'
        );

        // Cabeçalho da tabela
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Nome');
        $sheet->setCellValue('C2', 'CPF');
        $sheet->setCellValue('D2', 'CNPJ');

        // Formatação padrão do cabeçalho
        $this->formatarCabecalho($sheet, 'D');

        // Tamanho das colunas
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);

        // Congela o cabeçalho
        $sheet->freezePane('A3');

        // Aplica filtro
        $sheet->setAutoFilter('A2:D2');

        $linha = 3;

        foreach ($dados['dados'] as $cliente) {

            $sheet->setCellValue('A' . $linha, $cliente['idCliente']);
            $sheet->setCellValue('B' . $linha, $cliente['nomeCliente']);

            $cpf = '';

            if (!empty($cliente['cpf'])) {
                $cpf = preg_replace(
                    "/(\d{3})(\d{3})(\d{3})(\d{2})/",
                    "$1.$2.$3-$4",
                    $cliente['cpf']
                );
            }

            $cnpj = '';

            if (!empty($cliente['cnpj'])) {
                $cnpj = preg_replace(
                    "/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/",
                    "$1.$2.$3/$4-$5",
                    $cliente['cnpj']
                );
            }

            $sheet->setCellValue('C' . $linha, $cpf);
            $sheet->setCellValue('D' . $linha, $cnpj);

            $linha++;
        }

        // Alinhamentos específicos
        $sheet->getStyle('A3:A' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C3:C' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('D3:D' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Borda
        $sheet->getStyle('A1:D' . ($linha - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Finalização (por enquanto continua igual)
        if (empty($dados['dados'])) {
            header('Location: /ideal/public/index.php?url=relatorios&erro=sem_dados');
            exit;
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="relatorio_' . $relatorio . '_' . date('d_m_Y') . '.xlsx"');

        $writer = new Xlsx($planilha);
        $writer->save('php://output');
        exit;
    }
    public function exportarExcelObras()
    {
        $relatorio = 'obras';
        $dados = $this->buscarRelatorio($relatorio);

        // Cria a planilha
        [$planilha, $sheet] = $this->criarPlanilha(
            'Relatório de Obras - Sistema IDEAL • Soluções Elétricas',
            'G'
        );

        // Cabeçalho
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Contrato');
        $sheet->setCellValue('C2', 'Cliente');
        $sheet->setCellValue('D2', 'Cidade');
        $sheet->setCellValue('E2', 'Início');
        $sheet->setCellValue('F2', 'Fim');
        $sheet->setCellValue('G2', 'Status');

        // Formatação padrão do cabeçalho
        $this->formatarCabecalho($sheet, 'G');

        // Largura das colunas
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        // Congela o cabeçalho
        $sheet->freezePane('A3');

        // Filtro
        $sheet->setAutoFilter('A2:G2');

        $linha = 3;

        foreach ($dados['dados'] as $obra) {

            $sheet->setCellValue('A' . $linha, $obra['idObra']);
            $sheet->setCellValue('B' . $linha, $obra['contrato']);
            $sheet->setCellValue('C' . $linha, $obra['nomeCliente']);
            $sheet->setCellValue('D' . $linha, $obra['cidade']);

            $sheet->setCellValue(
                'E' . $linha,
                !empty($obra['dataInicio'])
                ? date('d/m/Y', strtotime($obra['dataInicio']))
                : ''
            );

            $sheet->setCellValue(
                'F' . $linha,
                !empty($obra['dataFim'])
                ? date('d/m/Y', strtotime($obra['dataFim']))
                : ''
            );

            $sheet->setCellValue('G' . $linha, $obra['status']);

            $linha++;
        }

        // Alinhamentos
        $sheet->getStyle('A3:A' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B3:B' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('D3:D' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('E3:F' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('G3:G' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Borda
        $sheet->getStyle('A1:G' . ($linha - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Finalização (continua igual por enquanto)
        if (empty($dados['dados'])) {
            header('Location: /ideal/public/index.php?url=relatorios&erro=sem_dados');
            exit;
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="relatorio_' . $relatorio . '_' . date('d_m_Y') . '.xlsx"');

        $writer = new Xlsx($planilha);
        $writer->save('php://output');
        exit;
    }
    public function exportarExcelVeiculos()
    {
        $relatorio = 'veiculos';
        $dados = $this->buscarRelatorio($relatorio);

        // Cria a planilha
        [$planilha, $sheet] = $this->criarPlanilha(
            'Relatório de Veículos - Sistema IDEAL • Soluções Elétricas',
            'F'
        );

        // Cabeçalho
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Placa');
        $sheet->setCellValue('C2', 'Renavam');
        $sheet->setCellValue('D2', 'Modelo');
        $sheet->setCellValue('E2', 'Marca');
        $sheet->setCellValue('F2', 'Status');

        // Formatação padrão do cabeçalho
        $this->formatarCabecalho($sheet, 'F');

        // Tamanho das colunas
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);

        // Congela o cabeçalho
        $sheet->freezePane('A3');

        // Aplica filtro
        $sheet->setAutoFilter('A2:F2');

        $linha = 3;

        foreach ($dados['dados'] as $veiculo) {

            $sheet->setCellValue('A' . $linha, $veiculo['idVeiculo']);
            $sheet->setCellValue('B' . $linha, $veiculo['placa']);
            $sheet->setCellValue('C' . $linha, $veiculo['renavam']);
            $sheet->setCellValue('D' . $linha, $veiculo['modelo']);
            $sheet->setCellValue('E' . $linha, $veiculo['marca']);
            $sheet->setCellValue('F' . $linha, $veiculo['statusVeiculo']);

            $linha++;
        }

        // Alinhamentos
        $sheet->getStyle('A3:A' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B3:B' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C3:C' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('D3:D' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('E3:E' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('F3:F' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Bordas
        $sheet->getStyle('A1:F' . ($linha - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Finalização (por enquanto continua igual)
        if (empty($dados['dados'])) {
            header('Location: /ideal/public/index.php?url=relatorios&erro=sem_dados');
            exit;
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="relatorio_' . $relatorio . '_' . date('d_m_Y') . '.xlsx"');

        $writer = new Xlsx($planilha);
        $writer->save('php://output');
        exit;
    }
    public function exportarExcelFinanceiros()
    {
        $relatorio = 'financeiro';
        $dados = $this->buscarRelatorio($relatorio);

        // Cria a planilha
        [$planilha, $sheet] = $this->criarPlanilha(
            'Relatório do Financeiro da Empresa - Sistema IDEAL • Soluções Elétricas',
            'G'
        );

        // Cabeçalho
        $sheet->setCellValue('A2', 'ID');
        $sheet->setCellValue('B2', 'Origem');
        $sheet->setCellValue('C2', 'Categoria');
        $sheet->setCellValue('D2', 'Descrição');
        $sheet->setCellValue('E2', 'Tipo');
        $sheet->setCellValue('F2', 'Valor');
        $sheet->setCellValue('G2', 'Data');

        // Formatação padrão do cabeçalho
        $this->formatarCabecalho($sheet, 'G');

        // Tamanho das colunas
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setWidth(45);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setAutoSize(true);

        // Congela o cabeçalho
        $sheet->freezePane('A3');

        // Aplica filtro
        $sheet->setAutoFilter('A2:G2');

        $linha = 3;

        foreach ($dados['dados'] as $financeiro) {

            $sheet->setCellValue('A' . $linha, $financeiro['id']);
            $sheet->setCellValue('B' . $linha, $financeiro['origem']);
            $sheet->setCellValue('C' . $linha, $financeiro['categoria']);
            $sheet->setCellValue('D' . $linha, $financeiro['descricao']);
            $sheet->setCellValue('E' . $linha, $financeiro['tipo']);

            $sheet->setCellValue(
                'F' . $linha,
                'R$ ' . number_format($financeiro['valor'], 2, ',', '.')
            );

            $sheet->setCellValue(
                'G' . $linha,
                !empty($financeiro['data'])
                ? date('d/m/Y', strtotime($financeiro['data']))
                : ''
            );

            $linha++;
        }

        // Formatar valor como moeda
        $sheet->getStyle('F3:F' . ($linha - 1))
            ->getNumberFormat()
            ->setFormatCode('"R$" #,##0.00');

        // Alinhamentos
        $sheet->getStyle('A3:A' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B3:B' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C3:C' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('D3:D' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('E3:E' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('F3:F' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('G3:G' . ($linha - 1))
            ->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Bordas
        $sheet->getStyle('A1:G' . ($linha - 1))
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // Finalização (por enquanto continua igual)
        if (empty($dados['dados'])) {
            header('Location: /ideal/public/index.php?url=relatorios&erro=sem_dados');
            exit;
        }

        if (ob_get_length()) {
            ob_end_clean();
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="relatorio_' . $relatorio . '_' . date('d_m_Y') . '.xlsx"');

        $writer = new Xlsx($planilha);
        $writer->save('php://output');
        exit;
    }

    public function loading()
{
    require_once __DIR__ . '/../Views/relatorios/loading.php';
}

    //Exporta o relatório em PDF
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

