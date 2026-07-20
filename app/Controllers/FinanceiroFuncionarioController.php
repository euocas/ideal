<?php

namespace App\Controllers;
use App\Models\FinanceiroFuncionario;
use App\Models\Funcionario;
use App\Core\Auth;

class FinanceiroFuncionarioController // ✅ NOME CORRETO
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

    public function buscarFuncionario()
    {
        $funcionario = null;
        $funcionarioBusca = null;

        $cpfBusca = preg_replace('/\D/', '', $_POST['cpf'] ?? $_GET['cpf'] ?? '');
        $mesBusca = $_POST['mes'] ?? $_GET['mes'] ?? date('n');
        $anoBusca = $_POST['ano'] ?? $_GET['ano'] ?? date('Y');
        $tipo = $_GET['tipo'] ?? $_POST['tipo'] ?? 'entrada';

        $funcionarioModel = new Funcionario();

        // Retorno após cadastrar/editar uma movimentação
        if (!empty($_GET['idFuncionario'])) {
            $funcionario = $funcionarioModel->findById((int) $_GET['idFuncionario']);
            if ($funcionario) {
                $cpfBusca = preg_replace('/\D/', '', $funcionario->getCpf());
            }
        } else {
            // Busca pelo CPF
            if ($cpfBusca === '') {
                $_SESSION['mensagem_erro'] = 'Digite o CPF do funcionário.';
                header('Location: /ideal/public/index.php?url=financeiros&aba=funcionario');
                exit;
            }
            $funcionario = $funcionarioModel->findByCpf($cpfBusca);
        }
        $ultimosLancamentos = [];

        $resumo = [
            'entradas' => 0,
            'saidas' => 0,
            'saldo' => 0

        ];
        $lancamentos = [];

        if ($funcionario) {
            $funcionarioBusca = $funcionario;

            $financeiroFuncionarioModel = new FinanceiroFuncionario();

            $ultimosLancamentos = $financeiroFuncionarioModel->findUltimosByIdFuncionario(
                $funcionario->getIdFuncionario(),
                4
            );
            $resumo['entradas'] = $financeiroFuncionarioModel->calcularEntradas(
                $funcionario->getIdFuncionario(),
                (int) $mesBusca,
                (int) $anoBusca
            );
            $resumo['saidas'] = $financeiroFuncionarioModel->calcularSaidas(
                $funcionario->getIdFuncionario(),
                (int) $mesBusca,
                (int) $anoBusca
            );
            $resumo['saldo'] = $resumo['entradas'] - $resumo['saidas'];

            if ($tipo === 'periodo') {
                $lancamentos = $financeiroFuncionarioModel->buscarPorFuncionarioEPeriodo(
                    $funcionario->getIdFuncionario(),
                    (int) $mesBusca,
                    (int) $anoBusca
                );
            }
        } else {
            $_SESSION['mensagem_erro'] = 'Funcionário não encontrado.';
        }
        $aba = 'funcionario';
        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    public function visualizar()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: /ideal/public/index.php?url=financeiros&aba=funcionario");
            exit;
        }
        $model = new FinanceiroFuncionario();
        $financeiroFuncionario = $model->findById($id);


        if (!$financeiroFuncionario) {
            header("Location: /ideal/public/index.php?url=financeiros&aba=funcionario");
            exit;
        }

        $funcionarioModel = new Funcionario();

        $funcionario = $funcionarioModel->findById(
            $financeiroFuncionario->getIdFuncionario()
        );

        $funcionarioBusca = $funcionario;

        $cpfBusca = preg_replace('/\D/', '', $funcionario->getCpf());

        $mesBusca = date(
            'n',
            strtotime($financeiroFuncionario->getDataReferencia())
        );

        $anoBusca = date(
            'Y',
            strtotime($financeiroFuncionario->getDataReferencia())
        );
        $lancamentos = $model->buscarPorFuncionarioEPeriodo(
            $funcionario->getIdFuncionario(),
            (int) $mesBusca,
            (int) $anoBusca
        );

        $resumo = [
            'entradas' => $model->calcularEntradas($funcionario->getIdFuncionario(), (int) $mesBusca, (int) $anoBusca),
            'saidas' => $model->calcularSaidas($funcionario->getIdFuncionario(), (int) $mesBusca, (int) $anoBusca),
        ];
        $resumo['saldo'] = $resumo['entradas'] - $resumo['saidas'];

        $ultimosLancamentos = $model->findUltimosByIdFuncionario($funcionario->getIdFuncionario(), 4);
        $editar = isset($_GET['editar']);

        if ($editar) {
            $tipo = match ($financeiroFuncionario->getTipo()) {
                'ENTRADA' => 'entrada',
                'SAIDA' => 'saida',
                default => 'entrada',
            };
        } else {
            $tipo = 'periodo';
        }


        $aba = 'funcionario';

        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    // public function create()
    // {
    //     require_once __DIR__ . '/../Views/financeiros/index.php';
    // }

    private function popularFuncionario(FinanceiroFuncionario $obj, array $dados): void
    {
        $obj->setIdFuncionario($dados['idFuncionario'] ?? null);

        $idCategoria = $obj->buscarIdCategoriaPorNome($dados['categoria'] ?? '');
        $obj->setIdCategoria($idCategoria);

        $obj->setDescricao($dados['descricao'] ?? null);
        $obj->setValor($dados['valor'] ?? null);
        $obj->setDataReferencia($dados['dataReferencia'] ?? null);
        $obj->setFormaPagamento($dados['formaPagamento'] ?? null);
        $obj->setContaPagamento($dados['contaPagamento'] ?? null);
        $obj->setObservacao($dados['observacao'] ?? null);
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $obj = new FinanceiroFuncionario();
            $this->popularFuncionario($obj, $_POST);

            $salvou = $obj->save();

            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "Lançamento financeiro cadastrado com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Ocorreu um erro ao cadastrar no banco de dados.";
            }

            $cpf = preg_replace('/\D/', '', $_POST['cpf_hidden'] ?? '');
            $mes = $_POST['mes_hidden'] ?? date('n');
            $ano = $_POST['ano_hidden'] ?? date('Y');

            header(
                "Location: /ideal/public/index.php?url=financeiro-funcionario/buscar"
                . "&tipo=periodo"
                . "&cpf={$cpf}"
                . "&mes={$mes}"
                . "&ano={$ano}"
            );
            exit;
        }
    }
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['idFinanceiroFuncionario'] ?? null;

            if ($id) {
                $model = new FinanceiroFuncionario();
                $obj = $model->findById((int) $id);

                if ($obj) {
                    $this->popularFuncionario($obj, $_POST);

                    if ($obj->update()) {
                        $_SESSION['mensagem_sucesso'] = "Registro financeiro atualizado com sucesso!";
                    } else {
                        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados.";
                    }


                    $cpf = preg_replace('/\D/', '', $_POST['cpf_hidden'] ?? '');
                    $mes = $_POST['mes_hidden'] ?? date('n');
                    $ano = $_POST['ano_hidden'] ?? date('Y');

                    header(
                        "Location: /ideal/public/index.php?url=financeiro-funcionario/buscar"
                        . "&tipo=periodo"
                        . "&cpf={$cpf}"
                        . "&mes={$mes}"
                        . "&ano={$ano}"
                    );
                    exit;
                }
            }

            $_SESSION['mensagem_erro'] = "Lançamento não encontrado.";
            header("Location: /ideal/public/index.php?url=financeiros&aba=funcionario");
            exit;
        }
    }



    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {

            $cpf = preg_replace('/\D/', '', $_POST['cpf_hidden'] ?? '');
            $mes = $_POST['mes_hidden'] ?? date('n');
            $ano = $_POST['ano_hidden'] ?? date('Y');

            $model = new FinanceiroFuncionario();
            $deletou = $model->delete((int) $id);

            if ($deletou) {
                $_SESSION['mensagem_sucesso'] = "Lançamento financeiro excluído com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao excluir o lançamento financeiro.";
            }

            header(
                "Location: /ideal/public/index.php?url=financeiro-funcionario/buscar"
                . "&tipo=periodo"
                . "&cpf={$cpf}"
                . "&mes={$mes}"
                . "&ano={$ano}"
            );
            exit;
        }

        $_SESSION['mensagem_erro'] = "Lançamento não encontrado.";
        header("Location: /ideal/public/index.php?url=financeiros&aba=funcionario");
        exit;
    }
}