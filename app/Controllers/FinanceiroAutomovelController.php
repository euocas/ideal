<?php

namespace App\Controllers;
use App\Models\FinanceiroAutomovel;
use App\Models\Veiculo;
use App\Core\Auth;

class FinanceiroAutomovelController // ✅ NOME CORRETO
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

    public function buscarVeiculo()
    {
        $veiculo = null;
        $veiculoBusca = null;

        $placaBusca = strtoupper(trim($_POST['placa'] ?? $_GET['placa'] ?? ''));
        $mesBusca = $_POST['mes'] ?? $_GET['mes'] ?? date('n');
        $anoBusca = $_POST['ano'] ?? $_GET['ano'] ?? date('Y');
        $tipo = $_GET['tipo'] ?? $_POST['tipo'] ?? 'entrada';



        $veiculoModel = new Veiculo();

        // Retorno após cadastrar/editar uma movimentação
        if (!empty($_GET['idVeiculo'])) {
            $veiculo = $veiculoModel->findById((int) $_GET['idVeiculo']);
            if ($veiculo) {
                $placaBusca = $veiculo->getPlaca();
            }
        } else {

            // Busca pela placa ou ID
            if ($placaBusca === '') {
                $_SESSION['mensagem_erro'] = 'Digite a placa do veículo.';
                header('Location: /ideal/public/index.php?url=financeiros&aba=automovel');
                exit;
            }

            if (ctype_digit($placaBusca)) {
                $veiculo = $veiculoModel->findById((int) $placaBusca);
            } else {
                $veiculo = $veiculoModel->findByPlaca($placaBusca);
            }
        }

        $gastoAtual = 0;
        $lancamentosAutomovel = [];
        $resumo = [
            'entradas' => 0,
            'saidas' => 0,
            'saldo' => 0

        ];

        $lancamentos = [];

        if ($veiculo) {

            $veiculoBusca = $veiculo;

            $financeiroAutomovelModel = new FinanceiroAutomovel();

            $gastoAtual = $financeiroAutomovelModel->calcularGastoAtual(
                $veiculo->getIdVeiculo()
            );

            $lancamentosAutomovel = $financeiroAutomovelModel->findUltimosByIdVeiculo(
                $veiculo->getIdVeiculo(),
                4
            );

            if ($tipo === 'periodo') {

                $lancamentos = $financeiroAutomovelModel->buscarPorVeiculoEPeriodo(
                    $veiculo->getIdVeiculo(),
                    (int) $mesBusca,
                    (int) $anoBusca
                );

                foreach ($lancamentos as $l) {

                    if ($l['tipo'] === 'Entrada') {
                        $resumo['entradas'] += $l['valor'];
                    } else {
                        $resumo['saidas'] += $l['valor'];
                    }
                }

                $resumo['saldo'] =
                    $resumo['entradas'] - $resumo['saidas'];
            }


        } else {
            $_SESSION['mensagem_erro'] = 'Veículo não encontrado.';
        }

        $aba = 'automovel';

        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    public function create()
    {
        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    public function visualizar()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: /ideal/public/index.php?url=financeiros&aba=automovel");
            exit;
        }

        $model = new FinanceiroAutomovel();
        $financeiroAutomovel = $model->findById($id);
  

        if (!$financeiroAutomovel) {
            header("Location: /ideal/public/index.php?url=financeiros&aba=automovel");
            exit;
        }

        $veiculoModel = new Veiculo();
        $veiculo = $veiculoModel->findById(
            $financeiroAutomovel->getIdVeiculo()
        );
        $veiculoBusca = $veiculo;

        $gastoAtual = $model->calcularGastoAtual(
            $veiculo->getIdVeiculo()
        );

        $lancamentosAutomovel = $model->findUltimosByIdVeiculo(
            $veiculo->getIdVeiculo(),
            4
        );

        $editar = isset($_GET['editar']);

        if ($editar) {
            $tipo = match ($financeiroAutomovel->getTipo()) {
                'Entrada' => 'entrada',
                'Saida', 'Saída' => 'saida',
                default => 'entrada',
            };
        } else {
            $tipo = 'periodo';
        }

        $aba = 'automovel';

        require_once __DIR__ . '/../Views/financeiros/index.php';
    }
    private function popularAutomovel(FinanceiroAutomovel $obj, array $dados): void
    {
        $obj->setIdVeiculo($dados['idVeiculo'] ?? null);
        $obj->setTipo($dados['tipo'] ?? null);
        $obj->setCategoria($dados['categoria'] ?? null);
        $obj->setDescricao($dados['descricao'] ?? null);
        $obj->setValor($dados['valor'] ?? null);
        $obj->setDataMovimentacao($dados['dataMovimentacao'] ?? null);
        $obj->setFormaPagamento($dados['formaPagamento'] ?? null);
        $obj->setFornecedor($dados['fornecedor'] ?? null);
        $obj->setDocumentoFiscal($dados['documentoFiscal'] ?? null);
        $obj->setObservacao($dados['observacao'] ?? null);
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $obj = new FinanceiroAutomovel();
            $this->popularAutomovel($obj, $_POST);



            $salvou = $obj->save();

            if ($salvou) {
                if ($_POST['tipo'] === 'entrada') {
                    $_SESSION['mensagem_sucesso'] = "Recebimento cadastrado com sucesso!";
                } else {
                    $_SESSION['mensagem_sucesso'] = "Gasto cadastrado com sucesso!";
                }
            } else {
                $_SESSION['mensagem_erro'] = "Ocorreu um erro ao cadastrar no banco de dados.";
            }

            header("Location: /ideal/public/index.php?url=financeiros&aba=automovel");
            exit;
        }
    }


    public function edit()
{

}
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_POST['idFinanceiroAutomovel'] ?? null;

            if ($id) {
                $model = new FinanceiroAutomovel();
                $obj = $model->findById($id);

                if ($obj) {
                    $this->popularAutomovel($obj, $_POST);

                    if ($obj->update()) {
                        $_SESSION['mensagem_sucesso'] = "Financeiro do automóvel atualizado com sucesso!";
                    } else {
                        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados.";
                    }

                    header("Location: /ideal/public/index.php?url=financeiro-automovel/visualizar&id={$id}");
                    exit;
                }
            }

            $_SESSION['mensagem_erro'] = "Lançamento não encontrado.";
            header("Location: /ideal/public/index.php?url=financeiros&aba=automovel");
            exit;
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new FinanceiroAutomovel();
            $deletou = $model->delete($id);

            if ($deletou) {
                $_SESSION['mensagem_sucesso'] = "Item excluído com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao tentar excluir o registro.";
            }
        }

        header("Location: /ideal/public/index.php?url=financeiros&aba=automovel");
        exit;
    }
}