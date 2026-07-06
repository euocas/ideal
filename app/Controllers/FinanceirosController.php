<?php

namespace App\Controllers;

use App\Models\FinanceiroFuncionario;
use App\Models\FinanceiroObra;
use App\Models\FinanceiroAutomovel;
use App\Core\Auth;

class FinanceirosController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        $aba = $_GET['aba'] ?? 'funcionario';
        $tipo = $_GET['tipo'] ?? 'entrada';

        $funcionarioBusca = null;
        $lancamentos = [];
        $resumo = ['entradas' => 0, 'saidas' => 0, 'saldo' => 0];

        $cpfBusca = $_GET['cpf'] ?? $_POST['cpf'] ?? '';
        $mesBusca = $_GET['mes'] ?? $_POST['mes'] ?? date('m');
        $anoBusca = $_GET['ano'] ?? $_POST['ano'] ?? date('Y');

        if ($aba === 'funcionario' && $cpfBusca !== '') {
            $cpfLimpo = preg_replace('/[^0-9]/', '', $cpfBusca);
            
            $funcModel = new \App\Models\Funcionario();
            $funcionarioBusca = $funcModel->findByCpf($cpfLimpo);

            if ($funcionarioBusca) {
                $finModel = new FinanceiroFuncionario();
                $lancamentos = $finModel->buscarPorFuncionarioEPeriodo($funcionarioBusca->getIdFuncionario(), $mesBusca, $anoBusca);

                // Calcula os totais do Salário Líquido e Descontos
                foreach ($lancamentos as $l) {
                    if ($l['categoriaTipo'] === 'ENTRADA') {
                        $resumo['entradas'] += $l['valor'];
                    } else {
                        $resumo['saidas'] += $l['valor'];
                    }
                }
                $resumo['saldo'] = $resumo['entradas'] - $resumo['saidas'];

            } else {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (session_status() === PHP_SESSION_NONE) session_start();
                    $_SESSION['mensagem_erro'] = "Funcionário não encontrado com este CPF.";
                }
            }
        }

        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    public function storeFuncionario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (session_status() === PHP_SESSION_NONE) session_start();

            // Preserva a busca para não apagar a tela
            $cpf = $_POST['cpf_hidden'] ?? '';
            $mes = $_POST['mes_hidden'] ?? date('m');
            $ano = $_POST['ano_hidden'] ?? date('Y');
            $tipo = $_POST['tipo'] ?? 'entrada';

            $urlRetorno = "/ideal/public/index.php?url=financeiros&aba=funcionario&tipo={$tipo}&mes={$mes}&ano={$ano}&cpf=" . urlencode($cpf);

            if (empty($_POST['idFuncionario'])) {
                $_SESSION['mensagem_erro'] = "É necessário localizar um funcionário primeiro.";
                header("Location: " . $urlRetorno);
                exit;
            }

            $model = new FinanceiroFuncionario();
            
            // Passa a categoria e o tipo (ENTRADA/SAIDA) para a model auto-cadastrar se faltar
            $idCategoria = $model->buscarIdCategoriaPorNome($_POST['categoria'] ?? '', $tipo);

            $model->setIdFuncionario($_POST['idFuncionario']);
            $model->setIdCategoria($idCategoria);
            $model->setDescricao($_POST['descricao'] ?? null);
            $model->setValor($_POST['valor'] ?? null);
            $model->setDataReferencia($_POST['dataReferencia'] ?? null);
            $model->setFormaPagamento($_POST['formaPagamento'] ?? null);
            $model->setContaPagamento($_POST['contaPagamento'] ?? null);
            $model->setObservacao($_POST['observacao'] ?? null);

            $salvou = $model->save();

            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "Lançamento registrado com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Ocorreu um erro ao registrar no banco de dados.";
            }

            header("Location: " . $urlRetorno);
            exit;
        }
    }

    public function deleteFuncionario()
    {
        $id = $_GET['id'] ?? null;
        
        $cpf = $_GET['cpf'] ?? '';
        $mes = $_GET['mes'] ?? date('m');
        $ano = $_GET['ano'] ?? date('Y');
        
        $urlRetorno = "/ideal/public/index.php?url=financeiros&aba=funcionario&tipo=periodo&mes={$mes}&ano={$ano}&cpf=" . urlencode($cpf);

        if ($id) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $model = new FinanceiroFuncionario();
            $deletou = $model->delete($id);

            if ($deletou) {
                $_SESSION['mensagem_sucesso'] = "Registro excluído com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao tentar excluir o registro.";
            }
        }
        
        header("Location: " . $urlRetorno);
        exit;
    }
    
    // MANTENHA O RESTO DO SEU CONTROLLER (Obra e Automóvel) AQUI PARA BAIXO...
}