<?php

namespace App\Controllers;

use App\Models\FinanceiroFuncionario;
use App\Models\FinanceiroAutomovel;
use App\Core\Auth;
use App\Models\Obra;
use App\Models\Cliente;
use App\Models\Funcionario;
use App\Models\FinanceiroObra;

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
}