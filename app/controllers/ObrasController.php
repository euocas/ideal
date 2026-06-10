<?php

namespace App\Controllers;

use App\Models\Obra;
use App\Core\Auth;

class ObrasController
{
    public function __construct()
    {
        Auth::verificar();
    }

    public function index()
    {
        $obraModel = new Obra();
        $obras = $obraModel->listar();

        require_once __DIR__ . '/../views/obras/index.php';
    }

    public function create()
{
    $actionUrl = "/ideal/public/index.php?url=obras/store";

    require_once __DIR__ . '/../views/obras/index.php';
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

    require_once __DIR__ . '/../views/obras/index.php'; 
}

    private function popularObjeto(Obra $obra, array $dados): void
    {
        if (!empty($dados['dataInicio'])) {
            $obra->setDataInicio(new \DateTime($dados['dataInicio']));
        }

        if (!empty($dados['dataFim'])) {
            $obra->setDataFim(new \DateTime($dados['dataFim']));
        }

        $obra->setStatus($dados['status'] ?? null);
        $obra->setEstado($dados['estado'] ?? null);
        $obra->setCidade($dados['cidade'] ?? null);
        $obra->setCep($dados['cep'] ?? null);
        $obra->setLogradouro($dados['logradouro'] ?? null);
        $obra->setEndereco($dados['endereco'] ?? null);
        $obra->setNumero($dados['numero'] ?? null);
        $obra->setComplemento($dados['complemento'] ?? null);
        $obra->setContrato($dados['contrato'] ?? null);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $obra = new Obra();

            $this->popularObjeto($obra, $_POST);

            $salvou = $obra->cadastrar();

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

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
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $id = $_GET['id'] ?? null;

            if ($id) {

                $obra = new Obra();

                $obra->setIdObra($id);

                $this->popularObjeto($obra, $_POST);

                $atualizou = $obra->atualizar();

                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }

                if ($atualizou) {
                    $_SESSION['mensagem_sucesso'] = "Obra atualizada com sucesso!";
                } else {
                    $_SESSION['mensagem_erro'] = "Erro ao atualizar a obra.";
                }
            }

            header("Location: /ideal/public/index.php?url=obras");
            exit;
        }
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