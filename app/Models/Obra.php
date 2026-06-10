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
        $this->estado = strtoupper($estado);
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

    public function getContrato(): ?string
    {
        return $this->contrato;
    }

    public function setContrato(?string $contrato): void
    {
        $this->contrato = $contrato;
    }

   private function hydrate(array $dados): self
{
    $obra = new self();

    $obra->setIdObra($dados['idObra'] ?? null);

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
            ':contrato' => $this->contrato
        ]);

        if ($sucesso) {
            $this->idObra = (int)$this->pdo->lastInsertId();
        }

        return $sucesso;
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
                    contrato = :contrato
                WHERE idObra = :idObra";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
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
            ':idObra' => $this->idObra
        ]);
    }

    public function excluir(int $id): bool
    {
        $sql = "DELETE FROM obra WHERE idObra = :id";

        $stmt = $this->pdo->prepare($sql);

        return $stmt->execute([
            ':id' => $id
        ]);
    }

 
}
// <<<<<<< HEAD:app/models/Obra.php
// =======

// public function setDataInicio(?DateTime $dataInicio): void
// {
//     $this->dataInicio = $dataInicio;
// }

// public function getDataFim(): ?DateTime
// {
//     return $this->dataFim;
// }

// public function setDataFim(?DateTime $dataFim): void
// {
//     $this->dataFim = $dataFim;
// }

// //=============================================================
// // VALIDAÇÃO DE VALORES PERMITIDOS (ENUMS DO BANCO)
// // Garante que apenas dados tabelados sejam aceitos
// //=============================================================
// public function getStatus(): ?string
// {
//     return $this->status;
// }

// public function setStatus(?string $status): void
// {
//     $this->status = $status;
// }
// //=============================================================
// //=============================================================

// public function getEstado(): ?string
// {
//     return $this->estado;
// }

// public function setEstado(?string $estado): void
// {
//     $this->estado = $estado;
// }

// public function getCidade(): ?string
// {
//     return $this->cidade;
// }

// public function setCidade(?string $cidade): void
// {
//     $this->cidade = $cidade;
// }

// public function getCep(): ?string
// {
//     return $this->cep;
// }

// public function setCep(?string $cep): void
// {
//     $this->cep = $cep;
// }

// public function getLogradouro(): ?string
// {
//     return $this->logradouro;
// }

// public function setLogradouro(?string $logradouro): void
// {
//     $this->logradouro = $logradouro;
// }

// public function getEndereco(): ?string
// {
//     return $this->endereco;
// }

// public function setEndereco(?string $endereco): void
// {
//     $this->endereco = $endereco;
// }

// public function getNumero(): ?string
// {
//     return $this->numero;
// }

// public function setNumero(?string $numero): void
// {
//     $this->numero = $numero;
// }

// public function getComplemento(): ?string
// {
//     return $this->complemento;
// }

// public function setComplemento(?string $complemento): void
// {
//     $this->complemento = $complemento;
// }

// public function getContrato(): ?string
// {
//     return $this->contrato;
// }

// public function setContrato(?string $contrato): void
// {
//     $this->contrato = $contrato;
// }

// }

// >>>>>>> 5a1e86830450ed3111bf0d5f5aa49be1bdc5ed96:app/model/obra.php
