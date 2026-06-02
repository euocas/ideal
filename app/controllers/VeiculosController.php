<?php

namespace App\Controllers;

use App\Models\Veiculo;
use App\Core\Auth;

class VeiculosController
{
    public function __construct()
    {
        // Protege a rota, exigindo que o usuário esteja logado
        Auth::verificar();
    }

    public function index()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->buscar();
        }

        $mensagem = null;
        require_once __DIR__ . '/../views/veiculos/index.php';
    }

    /**
     * Executa a lógica de pesquisa de Placa no banco de dados
     */
    private function buscar()
    {
        $placaDigitada = (string) ($_POST['placa'] ?? '');
        $placaLimpa = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $placaDigitada));

        if (!$this->validarPlaca($placaLimpa)) {
            $mensagem = "A placa informada é inválida. Verifique os caracteres e tente novamente.";
            require_once __DIR__ . '/../views/veiculos/index.php';
            return;
        }

        $veiculoModel = new Veiculo();
        $veiculo = $veiculoModel->findByPlaca($placaLimpa);

        if ($veiculo) {
            header("Location: /ideal/public/index.php?url=veiculos/edit&id=" . $veiculo->getIdVeiculo());
            exit;
        } else {
            header("Location: /ideal/public/index.php?url=veiculos/create&placa=" . $placaLimpa . "&novo=1");
            exit;
        }
    }

    /**
     * Valida o formato da Placa (Padrão Antigo ou Mercosul)
     */
    private function validarPlaca(string $placa): bool
    {
        // Verifica se tem 7 caracteres e se segue o padrão ABC1234 ou ABC1D23
        return preg_match('/^[A-Z]{3}[0-9][A-Z0-9][0-9]{2}$/', $placa) === 1;
    }

    public function create()
    {
        $placaBusca = $_GET['placa'] ?? '';
        $mensagem = null;

        if (isset($_GET['novo'])) {
            $mensagem = "Placa não cadastrada. Preencha os dados para registrar um novo veículo.";
        }

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
        $veiculo = $veiculoModel->findById((int) $id);

        if (!$veiculo) {
            header("Location: /ideal/public/index.php?url=veiculos");
            exit;
        }

        require_once __DIR__ . '/../views/veiculos/index.php';
    }

    /**
     * Helper privado para preencher os dados do objeto Veiculo
     * Isso evita repetir código no Store e no Update
     */
    private function popularObjeto(Veiculo $veiculo, array $dados): void
    {
        // O ternário verifica se não está vazio para evitar passar string vazia em campos numéricos
        $veiculo->setIdFuncionario(!empty($dados['idFuncionario']) ? (int) $dados['idFuncionario'] : null);
        $veiculo->setRenavam($dados['renavam'] ?? null);
        $veiculo->setPlaca($dados['placa'] ?? null);
        $veiculo->setChassi($dados['chassi'] ?? null);
        $veiculo->setMarca($dados['marca'] ?? null);
        $veiculo->setModelo($dados['modelo'] ?? null);
        $veiculo->setAnoFabricacao($dados['anoFabricacao'] ?? null);
        $veiculo->setAnoModelo($dados['anoModelo'] ?? null);
        $veiculo->setCor($dados['cor'] ?? null);
        $veiculo->setStatusVeiculo($dados['statusVeiculo'] ?? null);
        $veiculo->setTipoPosse($dados['tipoPosse'] ?? null);
        $veiculo->setQuilometragem(!empty($dados['quilometragem']) ? (int) $dados['quilometragem'] : 0);
        $veiculo->setDataUltimaRevisao($dados['dataUltimaRevisao'] ?? null);
        $veiculo->setProximaRevisao($dados['proximaRevisao'] ?? null);
        $veiculo->setPropriedadeVeiculo($dados['propriedadeVeiculo'] ?? null);
        $veiculo->setResponsavelVeiculo($dados['responsavelVeiculo'] ?? null);
        $veiculo->setQuantidade(!empty($dados['quantidade']) ? (int) $dados['quantidade'] : 1);
        $veiculo->setObservacoes($dados['observacoes'] ?? null);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $veiculo = new Veiculo();
            
            // Popula o objeto com os dados do formulário
            $this->popularObjeto($veiculo, $_POST);

            // O objeto salva a si mesmo
            $salvou = $veiculo->save();
            
            if (session_status() === PHP_SESSION_NONE) { session_start(); }

            if ($salvou) {
                $_SESSION['mensagem_sucesso'] = "O veículo foi cadastrado com sucesso!";
            } else {
                $_SESSION['mensagem_erro'] = "Ocorreu um erro ao cadastrar no banco de dados.";
            }

            header("Location: /ideal/public/index.php?url=veiculos");
            exit;
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_GET['id'] ?? null;

            if ($id) {
                // Primeiro, buscamos o veículo existente para garantir que ele existe
                $veiculo = (new Veiculo())->findById((int) $id);

                if ($veiculo) {
                    // Atualizamos o objeto com os novos dados
                    $this->popularObjeto($veiculo, $_POST);
                    
                    // O objeto atualiza a si mesmo
                    $atualizou = $veiculo->update();

                    if (session_status() === PHP_SESSION_NONE) { session_start(); }

                    if ($atualizou) {
                         $_SESSION['mensagem_sucesso'] = "Cadastro do veículo atualizado com sucesso!";
                    } else {
                         $_SESSION['mensagem_erro'] = "Erro ao atualizar os dados do veículo.";
                    }
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
            $deletou = $veiculoModel->delete((int) $id);

            if (session_status() === PHP_SESSION_NONE) { session_start(); }
            
            if ($deletou) {
                 $_SESSION['mensagem_sucesso'] = "Veículo excluído com sucesso!";
            } else {
                 $_SESSION['mensagem_erro'] = "Erro ao tentar excluir o veículo. Verifique se ele não possui vínculos.";
            }
        }

        header("Location: /ideal/public/index.php?url=veiculos");
        exit;
    }
}