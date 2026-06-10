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

    // =========================================================
    //  INDEX — exibe a tela com a aba ativa
    // =========================================================

    public function index()
    {
        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    // =========================================================
    //  ── FINANCEIRO FUNCIONÁRIO ──
    // =========================================================

    public function createFuncionario()
    {
        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    public function editFuncionario()
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

        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    private function popularFuncionario(FinanceiroFuncionario $obj, array $dados): void
    {
        $obj->setIdFuncionario($dados['idFuncionario'] ?? null);
        $obj->setSalario($dados['salario'] ?? null);
        $obj->setFerias($dados['ferias'] ?? null);
        $obj->setInss($dados['inss'] ?? null);
        $obj->setDecimoTerceiro($dados['decimoTerceiro'] ?? null);
    }

    public function storeFuncionario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $obj = new FinanceiroFuncionario();
            $this->popularFuncionario($obj, $_POST);

            $salvou = $obj->save();

            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "Financeiro do funcionário cadastrado com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Ocorreu um erro ao cadastrar no banco de dados.";
            }

            header("Location: /ideal/public/index.php?url=financeiros&aba=funcionario");
            exit;
        }
    }

    public function updateFuncionario()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? null;

            if ($id) {
                $model = new FinanceiroFuncionario();
                $obj = $model->findById($id);

                if ($obj) {
                    $this->popularFuncionario($obj, $_POST);
                    $atualizou = $obj->update();

                    if ($atualizou) {
                        $_SESSION['mensagem_sucesso'] = "Financeiro do funcionário atualizado com sucesso!";
                    } else {
                        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados.";
                    }
                }
            }

            header("Location: /ideal/public/index.php?url=financeiros&aba=funcionario");
            exit;
        }
    }

    public function deleteFuncionario()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new FinanceiroFuncionario();
            $deletou = $model->delete($id);

            if ($deletou) {
                $_SESSION['mensagem_sucesso'] = "Registro financeiro do funcionário excluído com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao tentar excluir o registro.";
            }
        }

        header("Location: /ideal/public/index.php?url=financeiros&aba=funcionario");
        exit;
    }

    // =========================================================
    //  ── FINANCEIRO OBRA ──
    // =========================================================

    public function createObra()
    {
        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    public function editObra()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: /ideal/public/index.php?url=financeiros&aba=obra");
            exit;
        }

        $model = new FinanceiroObra();
        $financeiroObra = $model->findById($id);

        if (!$financeiroObra) {
            header("Location: /ideal/public/index.php?url=financeiros&aba=obra");
            exit;
        }

        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    private function popularObra(FinanceiroObra $obj, array $dados): void
    {
        $obj->setIdObra($dados['idObra'] ?? null);
        $obj->setDescricao($dados['descricao'] ?? null);
        $obj->setCategoria($dados['categoria'] ?? null);
        $obj->setValor($dados['valor'] ?? null);
        $obj->setDataGasto($dados['dataGasto'] ?? null);
        $obj->setFormaPagamento($dados['formaPagamento'] ?? null);
        $obj->setObservacao($dados['observacao'] ?? null);
    }

    public function storeObra()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $obj = new FinanceiroObra();
            $this->popularObra($obj, $_POST);

            $salvou = $obj->save();

            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "Financeiro da obra cadastrado com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Ocorreu um erro ao cadastrar no banco de dados.";
            }

            header("Location: /ideal/public/index.php?url=financeiros&aba=obra");
            exit;
        }
    }

    public function updateObra()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? null;

            if ($id) {
                $model = new FinanceiroObra();
                $obj = $model->findById($id);

                if ($obj) {
                    $this->popularObra($obj, $_POST);
                    $atualizou = $obj->update();

                    if ($atualizou) {
                        $_SESSION['mensagem_sucesso'] = "Financeiro da obra atualizado com sucesso!";
                    } else {
                        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados.";
                    }
                }
            }

            header("Location: /ideal/public/index.php?url=financeiros&aba=obra");
            exit;
        }
    }

    public function deleteObra()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new FinanceiroObra();
            $deletou = $model->delete($id);

            if ($deletou) {
                $_SESSION['mensagem_sucesso'] = "Registro financeiro da obra excluído com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao tentar excluir o registro.";
            }
        }

        header("Location: /ideal/public/index.php?url=financeiros&aba=obra");
        exit;
    }

    // =========================================================
    //  ── FINANCEIRO AUTOMÓVEL ──
    // =========================================================

    public function createAutomovel()
    {
        require_once __DIR__ . '/../Views/financeiros/index.php';
    }

    public function editAutomovel()
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

        require_once __DIR__ . '/../Views/financeiro/index.php';
    }

    private function popularAutomovel(FinanceiroAutomovel $obj, array $dados): void
    {
        $obj->setIdVeiculo($dados['idVeiculo'] ?? null);
        $obj->setCombustivel($dados['combustivel'] ?? null);
        $obj->setManutencao($dados['manutencao'] ?? null);
        $obj->setIpva($dados['ipva'] ?? null);
    }

    public function storeAutomovel()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $obj = new FinanceiroAutomovel();
            $this->popularAutomovel($obj, $_POST);

            $salvou = $obj->save();

            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "Financeiro do automóvel cadastrado com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Ocorreu um erro ao cadastrar no banco de dados.";
            }

            header("Location: /ideal/public/index.php?url=financeiros&aba=automovel");
            exit;
        }
    }

    public function updateAutomovel()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? null;

            if ($id) {
                $model = new FinanceiroAutomovel();
                $obj = $model->findById($id);

                if ($obj) {
                    $this->popularAutomovel($obj, $_POST);
                    $atualizou = $obj->update();

                    if ($atualizou) {
                        $_SESSION['mensagem_sucesso'] = "Financeiro do automóvel atualizado com sucesso!";
                    } else {
                        $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados.";
                    }
                }
            }

            header("Location: /ideal/public/index.php?url=financeiros&aba=automovel");
            exit;
        }
    }

    public function deleteAutomovel()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {
            $model = new FinanceiroAutomovel();
            $deletou = $model->delete($id);

            if ($deletou) {
                $_SESSION['mensagem_sucesso'] = "Registro financeiro do automóvel excluído com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao tentar excluir o registro.";
            }
        }

        header("Location: /ideal/public/index.php?url=financeiros&aba=automovel");
        exit;
    }
}



