<?php

namespace App\Controllers;

use App\Models\Veiculo;

class VeiculosController
{
    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->buscar();
        }
        $mensagem = null; 
        require_once __DIR__ . '/../views/veiculos/index.php';
    }

    private function buscar()
    {
        $renavamDigitado = (string) ($_POST['renavam'] ?? '');
        $renavamLimpo = preg_replace('/[^0-9]/', '', $renavamDigitado);

        if (strlen($renavamLimpo) !== 11) {
            $mensagem = "O RENAVAM informado é inválido. Verifique os números e tente novamente.";
            require_once __DIR__ . '/../views/veiculos/index.php';
            return;
        }

        $veiculoModel = new Veiculo();
        $veiculo = $veiculoModel->findByRenavam($renavamLimpo);

        if ($veiculo) {
            header("Location: /ideal/public/index.php?url=veiculos/edit&id=" . $veiculo['idVeiculo']);
            exit;
        } else {
            header("Location: /ideal/public/index.php?url=veiculos/create&renavam=" . $renavamLimpo);
            exit;
        }
    }

    public function create()
    {
        $renavamBusca = $_GET['renavam'] ?? '';
        require_once __DIR__ . '/../views/veiculos/index.php';
    }

    public function edit()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: /ideal/public/index.php?url=veiculos");
            exit;
        }

        $veiculoModel = new Veiculo();
        $veiculo = $veiculoModel->findById((int)$id);

        if (!$veiculo) {
            header("Location: /ideal/public/index.php?url=veiculos");
            exit;
        }
        require_once __DIR__ . '/../views/veiculos/index.php';
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $veiculoModel = new Veiculo();
                $veiculoModel->save($_POST);

                header("Location: /ideal/public/index.php?url=veiculos");
                exit;
            } catch (\Exception $e) {
                echo "<div style='background: #ffe6e6; color: #cc0000; padding: 20px; font-family: Arial; border: 2px solid #cc0000; margin: 20px;'>";
                echo "<h1>❌ ERRO AO SALVAR NO BANCO!</h1>";
                echo "<h3>O MySQL respondeu: " . $e->getMessage() . "</h3>";
                echo "<b>Dados que o botão enviou:</b><pre>";
                print_r($_POST);
                echo "</pre><br><a href='javascript:history.back()' style='color: blue;'>Voltar</a></div>";
                die();
            }
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? null;
            if ($id) {
                try {
                    $veiculoModel = new Veiculo();
                    $veiculoModel->update((int)$id, $_POST);
                    
                    // --- ALTERAÇÃO AQUI: Redireciona de volta para a mesma página de edição ---
                    header("Location: /ideal/public/index.php?url=veiculos/edit&id=" . $id);
                    exit;
                    // ------------------------------------------------------------------------
                } catch (\Exception $e) {
                    echo "<div style='background: #ffe6e6; color: #cc0000; padding: 20px; font-family: Arial; border: 2px solid #cc0000; margin: 20px;'>";
                    echo "<h1>❌ ERRO AO ALTERAR NO BANCO!</h1>";
                    echo "<h3>O MySQL respondeu: " . $e->getMessage() . "</h3>";
                    echo "<b>Dados que o botão enviou:</b><pre>";
                    print_r($_POST);
                    echo "</pre><br><a href='javascript:history.back()' style='color: blue;'>Voltar</a></div>";
                    die();
                }
            }
            header("Location: /ideal/public/index.php?url=veiculos");
            exit;
        }
    }

    public function delete()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            $veiculoModel = new Veiculo();
            $veiculoModel->delete((int)$id);
        }
        header("Location: /ideal/public/index.php?url=veiculos");
        exit;
    }
}