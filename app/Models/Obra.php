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

    public function getIdObra(): ?int
    {
        return $this->idObra;
    }

    public function setIdObra(?int $idObra): void
    {
        $this->idObra = $idObra;
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
            'Concluída',
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

        // Remove espaços extras
        $contrato = trim($contrato);

        // Converte para minúsculo
        $contrato = mb_strtolower($contrato, 'UTF-8');

        // Verifica se ficou vazio
        if ($contrato === '') {
            throw new InvalidArgumentException('Contrato não pode estar vazio.');
        }

        $this->contrato = $contrato;
    }
    private function hydrate(array $dados): self
    {
        $obra = new self();

        $obra->setIdObra($dados['idObra'] ?? null);

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
        $obra->setObservacoes($dados['observacoes'] ?? null);
        $obra->setContrato($dados['contrato'] ?? null);

        return $obra;
    }

    // =====================================================
    // CRUD
    // =====================================================

    public function cadastrar(): bool
{
    $sql = "INSERT INTO obra (
                dataInicio,
                dataFim,
                status,
                estado,
                cidade,
                cep,
                logradouro,
                endereco,
                numero,
                complemento,
                observacoes,
                contrato
            ) VALUES (
                :dataInicio,
                :dataFim,
                :status,
                :estado,
                :cidade,
                :cep,
                :logradouro,
                :endereco,
                :numero,
                :complemento,
                :observacoes,
                :contrato
            )";

    $stmt = $this->pdo->prepare($sql);

    $sucesso = $stmt->execute([
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
        ':observacoes' => $this->observacoes,
        ':contrato' => $this->contrato
    ]);

    if ($sucesso) {
        $this->idObra = (int) $this->pdo->lastInsertId();
        return true;
    }

    // ajuda MUITO no debug quando der erro
    error_log(print_r($stmt->errorInfo(), true));

    return false;
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

        return $dados ? $this->hydrate($dados) : null;
    }

public function atualizar(): bool
{
    $sql = "UPDATE obra SET
                dataInicio = :dataInicio,
                dataFim = :dataFim,
                status = :status,
                estado = :estado,
                cidade = :cidade,
                cep = :cep,
                logradouro = :logradouro,
                endereco = :endereco,
                numero = :numero,
                complemento = :complemento,
                observacoes = :observacoes,
                contrato = :contrato
            WHERE idObra = :idObra";

    $stmt = $this->pdo->prepare($sql);

    $sucesso = $stmt->execute([
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
        ':observacoes' => $this->observacoes,
        ':contrato' => $this->contrato,
        ':idObra' => $this->idObra
    ]);

    if (!$sucesso) {
        error_log(print_r($stmt->errorInfo(), true));
        return false;
    }

    return true;
}

    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM obra WHERE idObra = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

    public function buscarPorContrato(string $contrato): ?self
    {
        $sql = "SELECT * FROM obra WHERE contrato = :contrato LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':contrato' => $contrato
        ]);

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->hydrate($dados) : null;
    }

    /**
     * Busca obras com filtros
     */
    public function buscarComFiltros(string $nomeObra = '', string $statusObra = ''): array
    {
        $sql = "SELECT * FROM obra WHERE 1=1";
        
        if (!empty($nomeObra)) {
            $sql .= " AND cidade LIKE :nomeObra";
        }
        
        if (!empty($statusObra)) {
            $sql .= " AND status = :statusObra";
        }
        
        $sql .= " ORDER BY idObra DESC";
        
        $stmt = $this->pdo->prepare($sql);
        
        if (!empty($nomeObra)) {
            $stmt->bindValue(':nomeObra', '%' . $nomeObra . '%', PDO::PARAM_STR);
        }
        
        if (!empty($statusObra)) {
            $stmt->bindValue(':statusObra', $statusObra, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // public function buscarCliente(): ?Cliente
    // {
    //     if (!$this->idObra) {
    //         return null;
    //     }

    //     $sql = "
    //     SELECT c.*
    //     FROM cliente c
    //     INNER JOIN obraCliente oc
    //         ON oc.idCliente = c.idCliente
    //     WHERE oc.idObra = :idObra
    //     LIMIT 1
    // ";

    //     $stmt = $this->pdo->prepare($sql);
    //     $stmt->execute([
    //         ':idObra' => $this->idObra
    //     ]);

    //     $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    //     if (!$dados) {
    //         return null;
    //     }

    //     $clienteModel = new Cliente();

    //     return $clienteModel->findById(
    //         (int) $dados['idCliente']
    //     );
    // }
}
