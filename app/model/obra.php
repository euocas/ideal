<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;
use DateTime;

class Obra
{

    // =====================================================
    // 1. ATRIBUTOS DA CLASSE (Representam as colunas do banco)
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
    // 2. CONSTRUTOR
    // =====================================================

public function __construct()
{
    $banco = new Conexao();
    $this->pdo = $banco->getConnection();
}

    // =====================================================
    // 3. GETTERS E SETTERS (Regras de negócio e sanitização)
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

//=============================================================
// VALIDAÇÃO DE VALORES PERMITIDOS (ENUMS DO BANCO)
// Garante que apenas dados tabelados sejam aceitos
//=============================================================
public function getStatus(): ?string
{
    return $this->status;
}

public function setStatus(?string $status): void
{
    $this->status = $status;
}
//=============================================================
//=============================================================

public function getEstado(): ?string
{
    return $this->estado;
}

public function setEstado(?string $estado): void
{
    $this->estado = $estado;
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

}

