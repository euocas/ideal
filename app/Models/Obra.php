<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;
use DateTime;
use InvalidArgumentException;

class Obra
{
    // =====================================================
    // ATRIBUTOS
    // =====================================================

    private ?int $idObra = null;
    private ?int $idCliente = null;
    private ?int $idResponsavel = null;  // ✅ ADICIONADO
    private ?DateTime $dataInicio = null;
    private ?DateTime $dataFim = null;
    private ?string $status = null;
    private ?string $estado = null;
    private ?string $cidade = null;
    private ?string $cep = null;
    private ?string $logradouro = null;
    private ?string $endereco = null;
    private ?string $numero = null;
    private ?string $complemento = null;
    private ?string $observacoes = null;
    private ?string $contrato = null;
    private ?float $valorContratado = null;
    private array $funcionariosVinculados = []; // ✅ ADICIONADO

    private PDO $pdo;

    // =====================================================
    // CONSTRUTOR
    // =====================================================

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->getConnection();
    }

    // =====================================================
    // GETTERS E SETTERS
    // =====================================================
    public function getFuncionariosVinculados(): array
    {
        return $this->funcionariosVinculados;
    }

    public function setFuncionariosVinculados(array $funcionarios): void
    {
        $this->funcionariosVinculados = $funcionarios;

    }


    public function getIdObra(): ?int
    {
        return $this->idObra;
    }

    public function setIdObra(?int $idObra): void
    {
        $this->idObra = $idObra;
    }


    public function getIdCliente(): ?int
    {
        return $this->idCliente;
    }

    public function setIdCliente(?int $idCliente): void
    {
        $this->idCliente = $idCliente;
    }
    // ✅ ADICIONADO
    public function getIdResponsavel(): ?int
    {
        return $this->idResponsavel;
    }
    // ✅ ADICIONADO
    public function setIdResponsavel(?int $idResponsavel): void
    {
        $this->idResponsavel = $idResponsavel;
    }

    public function getDataInicio(): ?DateTime
    {
        return $this->dataInicio;
    }

    public function setDataInicio(?DateTime $dataInicio): void
    {
        $this->dataInicio = $dataInicio;
    }

    public function getDataFim(): ?DateTime
    {
        return $this->dataFim;
    }

    public function setDataFim(?DateTime $dataFim): void
    {
        $this->dataFim = $dataFim;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $permitidos = [
            'Em andamento',
            'Concluida',
            'Cancelada'
        ];

        if ($status !== null && !in_array($status, $permitidos)) {
            throw new InvalidArgumentException('Status inválido.');
        }

        $this->status = $status;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): void
    {
        $this->estado = $estado ? strtoupper($estado) : null;
    }

    public function getCidade(): ?string
    {
        return $this->cidade;
    }

    public function setCidade(?string $cidade): void
    {
        $this->cidade = $cidade;
    }

    public function getCep(): ?string
    {
        return $this->cep;
    }

    public function setCep(?string $cep): void
    {
        $this->cep = $cep;
    }

    public function getLogradouro(): ?string
    {
        return $this->logradouro;
    }

    public function setLogradouro(?string $logradouro): void
    {
        $this->logradouro = $logradouro;
    }

    public function getEndereco(): ?string
    {
        return $this->endereco;
    }

    public function setEndereco(?string $endereco): void
    {
        $this->endereco = $endereco;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): void
    {
        $this->numero = $numero;
    }

    public function getComplemento(): ?string
    {
        return $this->complemento;
    }

    public function setComplemento(?string $complemento): void
    {
        $this->complemento = $complemento;
    }

    public function getObservacoes(): ?string
    {
        return $this->observacoes;
    }

    public function setObservacoes(?string $observacoes): void
    {
        $this->observacoes = $observacoes;
    }

    public function getContrato(): ?string
    {
        return $this->contrato;
    }

    public function setContrato(?string $contrato): void
    {
        if ($contrato === null) {
            $this->contrato = null;
            return;
        }

        $contrato = trim($contrato);
        $contrato = mb_strtolower($contrato, 'UTF-8');

        if ($contrato === '') {
            throw new InvalidArgumentException('Contrato não pode estar vazio.');
        }

        $this->contrato = $contrato;
    }

    public function getValorContratado(): ?float
    {
        return $this->valorContratado;
    }

    public function setValorContratado(?float $valorContratado): void
    {
        $this->valorContratado = $valorContratado;
    }

    // =====================================================
    // HYDRATE
    // =====================================================

    private function hydrate(array $dados): self
    {
        $obra = new self();

        $obra->setIdObra($dados['idObra'] ?? null);
        $obra->setIdCliente(isset($dados['idCliente']) ? (int) $dados['idCliente'] : null); // ✅ ADICIONADO
        $obra->setIdResponsavel(isset($dados['idResponsavel']) ? (int) $dados['idResponsavel'] : null);

        if (!empty($dados['dataInicio'])) {
            $obra->setDataInicio(new DateTime($dados['dataInicio']));
        }

        if (!empty($dados['dataFim'])) {
            $obra->setDataFim(new DateTime($dados['dataFim']));
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
        $obra->setValorContratado(isset($dados['valorContratado']) ? (float) $dados['valorContratado'] : null);
        $obra->setObservacoes($dados['observacoes'] ?? null);


        return $obra;
    }

    public function carregarFuncionariosVinculados(): void
    {
        if (!$this->idObra)
            return;

        // Faz um JOIN para buscar o funcionário e o veículo vinculado a ele nesta obra
        $sql = "SELECT 
                    of.idFuncionario, 
                    f.nome as nomeFuncionario, 
                    f.cargoFuncao as funcao, 
                    f.dataAdmissao, 
                    f.dataDesligamento, 
                    f.status as statusFuncionario,
                    ofv.idVeiculo, 
                    v.modelo, 
                    v.placa
                FROM obraFuncionario of
                INNER JOIN funcionario f ON of.idFuncionario = f.idFuncionario
                LEFT JOIN obraFuncionarioVeiculo ofv ON of.idObraFuncionario = ofv.idObraFuncionario
                LEFT JOIN veiculo v ON ofv.idVeiculo = v.idVeiculo
                WHERE of.idObra = :idObra";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idObra' => $this->idObra]);

        $this->funcionariosVinculados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // =====================================================
    // CRUD
    // =====================================================

    public function cadastrar(): bool
    {
        try {
            // Inicia a transação com o banco de dados
            $this->pdo->beginTransaction();

            // 1. SALVA A OBRA
            $sql = "INSERT INTO obra (
                        idCliente,idResponsavel, dataInicio, dataFim, status, estado, cidade, cep, 
                        logradouro, endereco, numero, complemento,contrato,valorContratado, observacoes
                    ) VALUES (
                        :idCliente,:idResponsavel,:dataInicio, :dataFim, :status, :estado, :cidade, :cep, 
                        :logradouro, :endereco, :numero, :complemento, :contrato,:valorContratado,:observacoes 
                    )";

            $stmt = $this->pdo->prepare($sql);

            $sucesso = $stmt->execute([
                ':idCliente' => $this->idCliente,
                ':idResponsavel' => $this->idResponsavel,
                ':dataInicio' => $this->dataInicio?->format('Y-m-d H:i:s'),
                ':dataFim' => $this->dataFim?->format('Y-m-d H:i:s'),
                ':status' => $this->status,
                ':estado' => $this->estado,
                ':cidade' => $this->cidade,
                ':cep' => $this->cep,
                ':logradouro' => $this->logradouro,
                ':endereco' => $this->endereco,
                ':numero' => $this->numero,
                ':complemento' => $this->complemento,
                ':contrato' => $this->contrato,
                ':valorContratado' => $this->valorContratado,
                ':observacoes' => $this->observacoes
            ]);

            if (!$sucesso) {
                $this->pdo->rollBack();
                return false;
            }

            // Pega o ID da obra recém criada
            $this->idObra = (int) $this->pdo->lastInsertId();

            // 2. SALVA OS FUNCIONÁRIOS VINCULADOS
            if (!empty($this->funcionariosVinculados)) {

                $sqlFunc = "INSERT INTO obraFuncionario (idObra, idFuncionario) VALUES (:idObra, :idFuncionario)";
                $stmtFunc = $this->pdo->prepare($sqlFunc);

                $sqlVeic = "INSERT INTO obraFuncionarioVeiculo (idObraFuncionario, idVeiculo) VALUES (:idObraFuncionario, :idVeiculo)";
                $stmtVeic = $this->pdo->prepare($sqlVeic);

                foreach ($this->funcionariosVinculados as $func) {
                    if (empty($func['idFuncionario']))
                        continue;

                    // Salva na tabela obraFuncionario
                    $stmtFunc->execute([
                        ':idObra' => $this->idObra,
                        ':idFuncionario' => $func['idFuncionario']
                    ]);

                    // Pega o ID do vínculo Obra-Funcionario gerado
                    $idObraFunc = (int) $this->pdo->lastInsertId();

                    // Se houver veículo selecionado, salva na tabela obraFuncionarioVeiculo
                    if (!empty($func['idVeiculo'])) {
                        $stmtVeic->execute([
                            ':idObraFuncionario' => $idObraFunc,
                            ':idVeiculo' => $func['idVeiculo']
                        ]);
                    }
                }
            }

            // Confirma a gravação de tudo no banco
            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            // Se algo der errado, desfaz tudo
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Erro ao cadastrar obra: " . $e->getMessage());
            return false;
        }
    }
    public function listar(): array
    {
        $sql = "SELECT * FROM obra ORDER BY idObra DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(int $id): ?self
    {
        $sql = "SELECT * FROM obra WHERE idObra = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            $obra = $this->hydrate($dados);
            $obra->carregarFuncionariosVinculados(); // Carrega a tabela
            return $obra;
        }
        return null;
    }

    public function buscarPorContrato(string $contrato): ?self
    {
        $sql = "SELECT * FROM obra WHERE contrato = :contrato LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':contrato' => $contrato]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($dados) {
            $obra = $this->hydrate($dados);
            $obra->carregarFuncionariosVinculados(); // Carrega a tabela
            return $obra;
        }
        return null;
    }

    public function atualizar(): bool
    {
        try {
            // Iniciamos a transação (se der erro, nada é salvo)
            $this->pdo->beginTransaction();

            $sql = "UPDATE obra SET
                        idCliente     = :idCliente,
                        idResponsavel   = :idResponsavel,
                        dataInicio    = :dataInicio,
                        dataFim       = :dataFim,
                        status        = :status,
                        estado        = :estado,
                        cidade        = :cidade,
                        cep           = :cep,
                        logradouro    = :logradouro,
                        endereco      = :endereco,
                        numero        = :numero,
                        complemento   = :complemento,
                        contrato      = :contrato,
                        valorContratado = :valorContratado,
                        observacoes   = :observacoes
                    WHERE idObra = :idObra";

            $stmt = $this->pdo->prepare($sql);

            $sucesso = $stmt->execute([
                ':idCliente' => $this->idCliente,
                ':idResponsavel' => $this->idResponsavel,
                ':dataInicio' => $this->dataInicio?->format('Y-m-d H:i:s'),
                ':dataFim' => $this->dataFim?->format('Y-m-d H:i:s'),
                ':status' => $this->status,
                ':estado' => $this->estado,
                ':cidade' => $this->cidade,
                ':cep' => $this->cep,
                ':logradouro' => $this->logradouro,
                ':endereco' => $this->endereco,
                ':numero' => $this->numero,
                ':complemento' => $this->complemento,
                ':contrato' => $this->contrato,
                ':valorContratado' => $this->valorContratado,
                ':observacoes' => $this->observacoes,
                ':idObra' => $this->idObra
            ]);

            if (!$sucesso) {
                $this->pdo->rollBack();
                return false;
            }


            $stmtFind = $this->pdo->prepare("SELECT idObraFuncionario FROM obraFuncionario WHERE idObra = :idObra");
            $stmtFind->execute([':idObra' => $this->idObra]);
            $ids = $stmtFind->fetchAll(PDO::FETCH_COLUMN);

            if (!empty($ids)) {
                $in = str_repeat('?,', count($ids) - 1) . '?';
                $stmtDelVeic = $this->pdo->prepare("DELETE FROM obraFuncionarioVeiculo WHERE idObraFuncionario IN ($in)");
                $stmtDelVeic->execute($ids);
            }

            // Agora limpa os funcionários
            $stmtDelFunc = $this->pdo->prepare("DELETE FROM obraFuncionario WHERE idObra = :idObra");
            $stmtDelFunc->execute([':idObra' => $this->idObra]);

            // Re-insere o que veio da tela atualizado
            if (!empty($this->funcionariosVinculados)) {
                $sqlFunc = "INSERT INTO obraFuncionario (idObra, idFuncionario) VALUES (:idObra, :idFuncionario)";
                $stmtFunc = $this->pdo->prepare($sqlFunc);

                $sqlVeic = "INSERT INTO obraFuncionarioVeiculo (idObraFuncionario, idVeiculo) VALUES (:idObraFuncionario, :idVeiculo)";
                $stmtVeic = $this->pdo->prepare($sqlVeic);

                foreach ($this->funcionariosVinculados as $func) {
                    if (empty($func['idFuncionario']))
                        continue;

                    $stmtFunc->execute([
                        ':idObra' => $this->idObra,
                        ':idFuncionario' => $func['idFuncionario']
                    ]);

                    $idObraFunc = (int) $this->pdo->lastInsertId();

                    if (!empty($func['idVeiculo'])) {
                        $stmtVeic->execute([
                            ':idObraFuncionario' => $idObraFunc,
                            ':idVeiculo' => $func['idVeiculo']
                        ]);
                    }
                }
            }

            $this->pdo->commit();
            return true;


        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            error_log("Erro no Update de Obra: " . $e->getMessage());
            return false;
        }
    }

    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM obra WHERE idObra = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }



    public function buscarComFiltros(string $contrato = '', string $statusObra = ''): array
    {
        $sql = "SELECT * FROM obra WHERE 1=1";

        if (!empty($contrato)) {
            $sql .= " AND contrato LIKE :contrato";
        }

        if (!empty($statusObra)) {
            $sql .= " AND status = :statusObra";
        }

        $sql .= " ORDER BY idObra DESC";

        $stmt = $this->pdo->prepare($sql);

        if (!empty($contrato)) {
            $stmt->bindValue(':contrato', '%' . $contrato . '%', PDO::PARAM_STR);
        }

        if (!empty($statusObra)) {
            $stmt->bindValue(':statusObra', $statusObra, PDO::PARAM_STR);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}