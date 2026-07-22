<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Models\Obra;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\FinanceiroObra;

class FinanceiroObraController // ✅ NOME CORRETO
{
    public function __construct()
    {
        Auth::verificar();
    }

    // =========================================================
    //  INDEX — exibe a tela com a aba ativa
    // =========================================================

    public function index()
    {
        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    // =========================================================
    //  ── FINANCEIRO OBRA 
    // =========================================================

    public function buscarObra()
    {
        $obraModel = new Obra();
        // Retorno após cadastrar um gasto
        if (!empty($_GET['idObra'])) {
            $obra = $obraModel->findById(
                (int) $_GET['idObra']
            );
        } else {
            // Busca normal pelo formulário
            $termo = trim($_POST['buscaObra'] ?? '');
            if ($termo === '') {
                $_SESSION['mensagem_erro'] = 'Digite o código ou contrato da obra.';
                header('Location: /ideal/public/index.php?url=financeiros&aba=obra');
                exit;
            }
            if (ctype_digit($termo)) {
                $obra = $obraModel->findById((int) $termo);
            } else {
                $obra = $obraModel->findByContrato($termo);
            }
        }

        // Busca o cliente vinculado à obra

        $cliente = null;
        if ($obra) {
            $clienteModel = new Cliente();
            $cliente = $clienteModel->findById($obra->getIdCliente());
        }
       
        // Dados financeiros da obra
        $gastoAtual = 0;
        $saldoDisponivel = 0;
        $lancamentosObra = [];
        $categoriasObra = [];

        if ($obra) {
            $financeiroObraModel = new FinanceiroObra();
            $categoriasObra = $financeiroObraModel->listarCategorias();

            $gastoAtual = $financeiroObraModel->calcularGastoAtual(
                $obra->getIdObra()

            );

            $saldoDisponivel =
                $obra->getValorContratado() - $gastoAtual;

            $lancamentosObra = $financeiroObraModel->findUltimosByIdObra(
                $obra->getIdObra(),
                4
            );

        }

        // Mantém a aba Obra ativa
        $aba = 'obra';

        require_once __DIR__ . '/../Views/financeiros/index.php';
    }
    public function visualizar()
    {
        $idFinanceiroObra = !empty($_GET['id'])
            ? (int) $_GET['id']
            : null;

        if (!$idFinanceiroObra) {
            $_SESSION['mensagem_erro'] = 'Lançamento inválido.';

            header(
                'Location: /ideal/public/index.php?url=financeiros&aba=obra'
            );

            exit;
        }

        $financeiroObraModel = new FinanceiroObra();

        $financeiroObra = $financeiroObraModel->findById(
            $idFinanceiroObra
        );
        $categoriasObra = $financeiroObraModel->listarCategorias();

        if (!$financeiroObra) {
            $_SESSION['mensagem_erro'] = 'Lançamento não localizado.';

            header(
                'Location: /ideal/public/index.php?url=financeiros&aba=obra'
            );

            exit;
        }

        $idObra = $financeiroObra->getIdObra();

        // Busca a obra
        $obraModel = new Obra();

        $obra = $obraModel->buscarPorId(
            $idObra
        );

        // Busca o cliente
        $cliente = null;

        if ($obra) {
            $clienteModel = new Cliente();

            $cliente = $clienteModel->findById(
                $obra->getIdCliente()
            );
        }

        // Busca o responsável
        $responsavel = null;

        if ($obra && $obra->getIdResponsavel()) {
            $funcionarioModel = new Funcionario();

            $responsavel = $funcionarioModel->findById(
                $obra->getIdResponsavel()
            );
        }

        // Calcula resumo financeiro
        $gastoAtual = $financeiroObraModel->calcularGastoAtual(
            $idObra
        );

        $saldoDisponivel =
            $obra->getValorContratado() - $gastoAtual;

        // Últimos 4 lançamentos
        $lancamentosObra = $financeiroObraModel
            ->findUltimosByIdObra($idObra, 4);

        $aba = 'obra';

        require_once __DIR__ . '/../Views/financeiros/index.php';
    }
    public function historico()
    {
        $idObra = !empty($_GET["idObra"])
            ? (int) $_GET["idObra"]
            : null;

        if (!$idObra) {
            $_SESSION["mensagem_erro"] = "Obra inválida.";
            header(
                "Location: /ideal/public/index.php?url=financeiros&aba=obra"
            );
            exit;
        }

        $obraModel = new Obra();
        $obra = $obraModel->buscarPorId($idObra);

        if (!$obra) {
            $_SESSION["mensagem_erro"] = "Obra não localizada.";
            header(
                "Location: /ideal/public/index.php?url=financeiros&aba=obra"
            );
            exit;
        }
        $financeiroObraModel = new FinanceiroObra();
        $acao = $_GET["acao"] ?? "ultimos";

        if ($acao === "historico") {
            $lancamentosObra = $financeiroObraModel->findByIdObra($idObra);
        } else {
            $lancamentosObra = $financeiroObraModel->findUltimosByIdObra($idObra);
        }
        $aba = "obra";
        require_once __DIR__ . "/../Views/financeiros/index.php";
    }
    public function store()
    {

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $idObra = !empty($_POST['idObra'])
                ? (int) $_POST['idObra']
                : null;

            if (!$idObra) {
                $_SESSION['mensagem_erro'] = 'Nenhuma obra foi selecionada.';

                header('Location: /ideal/public/index.php?url=financeiros&aba=obra');
                exit;
            }

            $financeiroObra = new FinanceiroObra();
            $this->popularObra($financeiroObra, $_POST);

            $salvou = $financeiroObra->save();

            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = 'Gasto registrado com sucesso!';
            } else {
                $_SESSION['mensagem_erro'] = 'Erro ao registrar o gasto.';
            }

            // Volta para a aba Obra mantendo a obra selecionada

            header(

                'Location: /ideal/public/index.php?url=financeiro-obra/buscar&aba=obra&idObra=' . $idObra

            );

            exit;
        }
    }
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $idFinanceiroObra = !empty($_GET['id'])
                ? (int) $_GET['id']
                : null;

            if (!$idFinanceiroObra) {
                $_SESSION['mensagem_erro'] = 'Lançamento financeiro inválido.';

                header(
                    'Location: /ideal/public/index.php?url=financeiros&aba=obra'
                );
                exit;
            }

            $financeiroObraModel = new FinanceiroObra();

            $financeiroObra = $financeiroObraModel->findById(
                $idFinanceiroObra
            );

            if (!$financeiroObra) {
                $_SESSION['mensagem_erro'] = 'Lançamento financeiro não localizado.';

                header(
                    'Location: /ideal/public/index.php?url=financeiros&aba=obra'
                );
                exit;
            }

            $idObra = $financeiroObra->getIdObra();

            $this->popularObra(
                $financeiroObra,
                $_POST
            );

            $alterou = $financeiroObra->update();

            if ($alterou) {
                $_SESSION['mensagem_sucesso'] = 'Gasto alterado com sucesso!';
            } else {
                $_SESSION['mensagem_erro'] = 'Erro ao alterar o gasto.';
            }

            header(
                'Location: /ideal/public/index.php?url=financeiro-obra/buscar&aba=obra&idObra=' . $idObra
            );

            exit;
        }
    }
    public function delete()
    {
        $idFinanceiroObra = !empty($_GET['id'])
            ? (int) $_GET['id']
            : null;

        if (!$idFinanceiroObra) {
            $_SESSION['mensagem_erro'] = 'Lançamento financeiro inválido.';

            header(
                'Location: /ideal/public/index.php?url=financeiros&aba=obra'
            );
            exit;
        }

        $financeiroObraModel = new FinanceiroObra();

        $financeiroObra = $financeiroObraModel->findById(
            $idFinanceiroObra
        );

        if (!$financeiroObra) {
            $_SESSION['mensagem_erro'] = 'Lançamento financeiro não localizado.';

            header(
                'Location: /ideal/public/index.php?url=financeiros&aba=obra'
            );
            exit;
        }

        $idObra = $financeiroObra->getIdObra();

        $excluiu = $financeiroObraModel->delete(
            $idFinanceiroObra
        );

        if ($excluiu) {
            $_SESSION['mensagem_sucesso'] = 'Gasto excluído com sucesso!';
        } else {
            $_SESSION['mensagem_erro'] = 'Erro ao excluir o gasto.';
        }

        header(
            'Location: /ideal/public/index.php?url=financeiro-obra/buscar&aba=obra&idObra=' . $idObra
        );

        exit;
    }
    private function popularObra(FinanceiroObra $obj, array $dados): void
    {
        $obj->setIdObra($dados['idObra'] ?? null);
        $obj->setDescricao($dados['descricao'] ?? null);
        $obj->setIdCategoriaFinanceiroObra($dados['idCategoriaFinanceiroObra'] ?? null);
        $obj->setValor($dados['valor'] ?? null);
        $obj->setDataGasto($dados['dataGasto'] ?? null);
        $obj->setFormaPagamento($dados['formaPagamento'] ?? null);
        $obj->setObservacao($dados['observacao'] ?? null);
    }

}