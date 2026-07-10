<?php

namespace App\Controllers;

use App\Models\Obra;
use App\Models\Cliente;
use App\Core\Auth;
use App\Config\Conexao;
use PDO;

class ObrasController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        $obra = null;
        $cliente = null;

        $actionUrl = "/ideal/public/index.php?url=obras/store";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $contrato = trim($_POST['contratoBusca'] ?? '');

            if ($contrato !== '') {

                $obraModel = new Obra();
                $obra = $obraModel->buscarPorContrato($contrato);

                if ($obra) {
                    $actionUrl = "/ideal/public/index.php?url=obras/update&id=" . $obra->getIdObra();

                    if ($obra->getIdCliente()) {
                        $clienteModel = new Cliente();
                        $cliente = $clienteModel->findById($obra->getIdCliente());
                    }

                } else {

                    header("Location: /ideal/public/index.php?url=obras/create&contrato=" . urlencode($contrato) . "&novo=1");
                    exit;

                }
            }
        }

        require_once __DIR__ . '/../Views/obras/index.php';
    }

    public function create()
    {
        $contratoBusca = $_GET['contrato'] ?? '';
        $mensagem = null;

        if (isset($_GET['novo'])) {
            $mensagem = "Obra não cadastrada. Preencha os dados abaixo para registrar uma nova obra.";
        }

        require_once __DIR__ . '/../Views/obras/index.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            header("Location: /ideal/public/index.php?url=obras");
            exit;
        }

        $obraModel = new Obra();
        $obra = $obraModel->buscarPorId($id);

        if (!$obra) {
            header("Location: /ideal/public/index.php?url=obras");
            exit;
        }

        $actionUrl = "/ideal/public/index.php?url=obras/update&id=" . $obra->getIdObra();

        require_once __DIR__ . '/../Views/obras/index.php';
    }

    // ✅ ADICIONADO setIdCliente
    private function popularObjeto(Obra $obra, array $dados): void
    {
        $obra->setIdCliente(!empty($dados['idCliente']) ? (int) $dados['idCliente'] : null);

        if (!empty($dados['dataInicio'])) {
            $obra->setDataInicio(new \DateTime($dados['dataInicio']));
        }

        if (!empty($dados['dataFim'])) {
            $obra->setDataFim(new \DateTime($dados['dataFim']));
        }

        $obra->setStatus($dados['status'] ?? null);
        $obra->setEstado($dados['estado'] ?? null);
        $obra->setCidade($dados['cidade'] ?? null);
        $cep = preg_replace('/\D/', '', $dados['cep'] ?? '');
        $obra->setCep($cep);
        $obra->setLogradouro($dados['logradouro'] ?? null);
        $obra->setEndereco($dados['endereco'] ?? null);
        $obra->setNumero($dados['numero'] ?? null);
        $obra->setComplemento($dados['complemento'] ?? null);
        $obra->setObservacoes($dados['observacoes'] ?? null);
        $obra->setContrato($dados['contrato'] ?? null);
        $valorContratado = str_replace(',', '.', str_replace('.', '', $dados['valorContratado'] ?? ''));
        $obra->setValorContratado((float) $valorContratado);

        // ✅ ADICIONADO: Pega a array que o JavaScript mandou via inputs hidden
        $obra->setFuncionariosVinculados($dados['funcionariosObra'] ?? []);
    }
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            // ✅ Valida se o cliente foi selecionado
            if (empty($_POST['idCliente'])) {
                $_SESSION['mensagem_erro'] = "Selecione um cliente válido antes de cadastrar a obra.";
                header("Location: /ideal/public/index.php?url=obras");
                exit;
            }

            // Valida valor contratado
            $valorContratado = str_replace(',', '.', str_replace('.', '', $_POST['valorContratado'] ?? ''));

            if ((float) $valorContratado <= 0) {
                $_SESSION['mensagem_erro'] = "Informe um valor contratado maior que zero.";
                header("Location: /ideal/public/index.php?url=obras");
                exit;
            }


            $obra = new Obra();
            $this->popularObjeto($obra, $_POST);

            $salvou = $obra->cadastrar();

            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "Obra cadastrada com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao cadastrar a obra.";
            }

            header("Location: /ideal/public/index.php?url=obras");
            exit;
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: /ideal/public/index.php?url=obras");
            exit;
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $id = $_GET['id'] ?? null;

        if (!$id) {
            $_SESSION['mensagem_erro'] = "ID da obra não informado.";
            header("Location: /ideal/public/index.php?url=obras");
            exit;
        }

        // ✅ Valida se o cliente foi selecionado
        if (empty($_POST['idCliente'])) {
            $_SESSION['mensagem_erro'] = "Selecione um cliente válido antes de atualizar a obra.";
            header("Location: /ideal/public/index.php?url=obras");
            exit;
        }

        // Valida valor contratado
        $valorContratado = str_replace(',', '.', str_replace('.', '', $_POST['valorContratado'] ?? ''));

        if ((float) $valorContratado <= 0) {
            $_SESSION['mensagem_erro'] = "Informe um valor contratado maior que zero.";
            header("Location: /ideal/public/index.php?url=obras");
            exit;
        }

        try {

            $obra = new Obra();
            $obra->setIdObra($id);
            $this->popularObjeto($obra, $_POST);

            $atualizou = $obra->atualizar();

            if ($atualizou) {
                $_SESSION['mensagem_sucesso'] = "Obra atualizada com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao atualizar a obra.";
            }

        } catch (\Throwable $e) {
            $_SESSION['mensagem_erro'] = $e->getMessage();
            error_log($e->getMessage());
        }

        header("Location: /ideal/public/index.php?url=obras");
        exit;
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;

        if ($id) {

            $obraModel = new Obra();
            $deletou = $obraModel->excluir($id);

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if ($deletou) {
                $_SESSION['mensagem_sucesso'] = "Obra excluída com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Erro ao excluir a obra.";
            }
        }

        header("Location: /ideal/public/index.php?url=obras");
        exit;
    }
}