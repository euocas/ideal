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
                    if ($l->getTipo() === 'ENTRADA') {
                        $resumo['entradas'] += $l->getValor();
                    } else {
                        $resumo['saidas'] += $l->getValor();
                    }
                }
                $resumo['saldo'] = $resumo['entradas'] - $resumo['saidas'];

            } else {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    if (session_status() === PHP_SESSION_NONE)
                        session_start();
                    $_SESSION['mensagem_erro'] = "Funcionário não encontrado com este CPF.";
                }
            }
        }

        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    
}