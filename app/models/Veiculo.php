<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class Veiculo
{
    // =====================================================
    // 1. ATRIBUTOS DA CLASSE (Representam as colunas do banco)
    // =====================================================
    private ?int $idVeiculo = null;
    private ?int $idFuncionario = null;
    private ?string $renavam = null;
    private ?string $placa = null;
    private ?string $chassi = null;
    private ?string $marca = null;
    private ?string $modelo = null;
    private ?int $anoFabricacao = null;
    private ?int $anoModelo = null;
    private ?string $cor = null;
    private string $statusVeiculo = 'ATIVO'; // Padrão do ENUM
    private string $tipoPosse = 'PROPRIO'; // Padrão do ENUM
    private int $quilometragem = 0; // Padrão do Banco
    private ?string $dataUltimaRevisao = null;
    private ?string $proximaRevisao = null;
    private ?string $propriedadeVeiculo = null;
    private ?string $responsavelVeiculo = null;
    private int $quantidade = 1; // Padrão do Banco
    private ?string $observacoes = null;
    private ?string $dataCadastro = null; // Gerenciado pelo banco (CURRENT_TIMESTAMP)

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

    public function getIdVeiculo(): ?int { return $this->idVeiculo; }
    public function setIdVeiculo(?int $id): void { $this->idVeiculo = $id; }

    public function getIdFuncionario(): ?int { return $this->idFuncionario; }
    public function setIdFuncionario(?int $id): void { $this->idFuncionario = $id; }

    public function getRenavam(): ?string { return $this->renavam; }
    public function setRenavam(?string $renavam): void 
    { 
        $this->renavam = $renavam ? preg_replace('/[^0-9]/', '', $renavam) : null; 
    }

    public function getPlaca(): ?string { return $this->placa; }
    public function setPlaca(?string $placa): void 
    { 
        $this->placa = $placa ? strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $placa)) : null; 
    }

    public function getChassi(): ?string { return $this->chassi; }
    public function setChassi(?string $chassi): void 
    { 
        $this->chassi = $chassi ? strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $chassi)) : null; 
    }
    public function getMarca(): ?string { return $this->marca; }
    public function setMarca(?string $marca): void { $this->marca = $marca; }

    public function getModelo(): ?string { return $this->modelo; }
    public function setModelo(?string $modelo): void { $this->modelo = $modelo; }

    public function getAnoFabricacao(): ?int { return $this->anoFabricacao; }
    public function setAnoFabricacao(?string $ano): void 
    { 
        $this->anoFabricacao = !empty($ano) ? (int) substr($ano, 0, 4) : null; 
    }
    public function getAnoModelo(): ?int { return $this->anoModelo; }
    public function setAnoModelo(?string $ano): void 
    { 
        $this->anoModelo = !empty($ano) ? (int) substr($ano, 0, 4) : null; 
    }

    public function getCor(): ?string { return $this->cor; }
    public function setCor(?string $cor): void { $this->cor = $cor; }

    public function getStatusVeiculo(): string { return $this->statusVeiculo; }
    public function setStatusVeiculo(?string $status): void

    
    {  $this->statusVeiculo = in_array($statusFormatado, $valoresPermitidos) ? $statusFormatado : 'ATIVO';}


    public function getTipoPosse(): string { return $this->tipoPosse; }
    public function setTipoPosse(?string $posse): void 

   // =====================================================
    // VALIDAÇÃO DE VALORES PERMITIDOS (ENUMS DO BANCO)
    // Garante que apenas dados tabelados sejam aceitos
    // =====================================================

    {$valoresPermitidos = ['PROPRIO', 'ALUGADO', 'EMPRESTADO', 'TERCEIRIZADO'];
        $posseFormatada = strtoupper(trim($posse ?? ''));
        
        $this->tipoPosse = in_array($posseFormatada, $valoresPermitidos) ? $posseFormatada : 'PROPRIO';}


    public function getQuilometragem(): int { return $this->quilometragem; }
    public function setQuilometragem(?int $km): void { $this->quilometragem = $km ?: 0; }

    public function getDataUltimaRevisao(): ?string { return $this->dataUltimaRevisao; }
    public function setDataUltimaRevisao(?string $data): void { $this->dataUltimaRevisao = $data ?: null; }

    public function getProximaRevisao(): ?string { return $this->proximaRevisao; }
    public function setProximaRevisao(?string $data): void { $this->proximaRevisao = $data ?: null; }

    public function getPropriedadeVeiculo(): ?string { return $this->propriedadeVeiculo; }
    public function setPropriedadeVeiculo(?string $propriedade): void { $this->propriedadeVeiculo = $propriedade ?: null; }

    public function getResponsavelVeiculo(): ?string { return $this->responsavelVeiculo; }
    public function setResponsavelVeiculo(?string $responsavel): void { $this->responsavelVeiculo = $responsavel ?: null; }

    public function getQuantidade(): int { return $this->quantidade; }
    public function setQuantidade(?int $qtd): void { $this->quantidade = $qtd ?: 1; }

    public function getObservacoes(): ?string { return $this->observacoes; }
    public function setObservacoes(?string $obs): void { $this->observacoes = $obs ?: null; }

    public function getDataCadastro(): ?string { return $this->dataCadastro; }
    public function setDataCadastro(?string $data): void { $this->dataCadastro = $data; }

    // =====================================================
    // 4. MÉTODOS DE BANCO DE DADOS (CRUD)
    // =====================================================

    /**
     * Helper privado para transformar o array do banco em um Objeto Veiculo
     */
    
    private function hydrate(array $dados): self
    {
        $veiculo = new self();
        $veiculo->setIdVeiculo($dados['idVeiculo'] ?? null);
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
        $veiculo->setQuilometragem($dados['quilometragem'] ?? null);
        $veiculo->setDataUltimaRevisao($dados['dataUltimaRevisao'] ?? null);
        $veiculo->setProximaRevisao($dados['proximaRevisao'] ?? null);
        $veiculo->setResponsavelVeiculo($dados['responsavelVeiculo'] ?? null);
        $veiculo->setObservacoes($dados['observacoes'] ?? null);

        return $veiculo;
    }
}
