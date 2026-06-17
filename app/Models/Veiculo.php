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

    // =====================================================
    // VALIDAÇÃO DE VALORES PERMITIDOS (ENUMS DO BANCO)
    // Garante que apenas dados tabelados sejam aceitos
    // =====================================================

    public function getStatusVeiculo(): string { return $this->statusVeiculo; }
    public function setStatusVeiculo(?string $status): void 
    { 
        $valoresPermitidos = ['ATIVO', 'EM MANUTENCAO', 'INATIVO', 'VENDIDO'];
        $statusFormatado = strtoupper(trim($status ?? ''));
        
        $this->statusVeiculo = in_array($statusFormatado, $valoresPermitidos) ? $statusFormatado : 'ATIVO';
    }

    public function getTipoPosse(): string { return $this->tipoPosse; }
    public function setTipoPosse(?string $posse): void 
    {
        $valoresPermitidos = ['PROPRIO', 'ALUGADO', 'EMPRESTADO', 'TERCEIRIZADO'];
        $posseFormatada = strtoupper(trim($posse ?? ''));
        
        $this->tipoPosse = in_array($posseFormatada, $valoresPermitidos) ? $posseFormatada : 'PROPRIO';
    }

    // =====================================================

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

    private function hydrate(array $dados): self
    {
        $veiculo = new self();
        $veiculo->setIdVeiculo($dados['idVeiculo'] ?? null);
        $veiculo->setIdFuncionario($dados['idFuncionario'] ?? null);
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
        $veiculo->setPropriedadeVeiculo($dados['propriedadeVeiculo'] ?? null);
        $veiculo->setResponsavelVeiculo($dados['responsavelVeiculo'] ?? null);
        $veiculo->setQuantidade($dados['quantidade'] ?? null);
        $veiculo->setObservacoes($dados['observacoes'] ?? null);
        $veiculo->setDataCadastro($dados['dataCadastro'] ?? null);

        return $veiculo;
    }

    public function save(): bool
    {
        try {
            $this->pdo->beginTransaction();

            // INSERT atualizado com todas as colunas da tabela
            $sql = "INSERT INTO veiculo (
                        idFuncionario, renavam, placa, chassi, marca, modelo, anoFabricacao, anoModelo, cor, 
                        statusVeiculo, tipoPosse, quilometragem, dataUltimaRevisao, proximaRevisao, 
                        propriedadeVeiculo, responsavelVeiculo, quantidade, observacoes
                    ) VALUES (
                        :idFuncionario, :renavam, :placa, :chassi, :marca, :modelo, :anoFabricacao, :anoModelo, :cor, 
                        :statusVeiculo, :tipoPosse, :quilometragem, :dataUltimaRevisao, :proximaRevisao, 
                        :propriedadeVeiculo, :responsavelVeiculo, :quantidade, :observacoes
                    )";

            $stmt = $this->pdo->prepare($sql);
            
            $stmt->bindValue(':idFuncionario', $this->getIdFuncionario(), $this->getIdFuncionario() ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindValue(':renavam', $this->getRenavam(), PDO::PARAM_STR);
            $stmt->bindValue(':placa', $this->getPlaca(), PDO::PARAM_STR);
            $stmt->bindValue(':chassi', $this->getChassi(), PDO::PARAM_STR);
            $stmt->bindValue(':marca', $this->getMarca(), PDO::PARAM_STR);
            $stmt->bindValue(':modelo', $this->getModelo(), PDO::PARAM_STR);
            $stmt->bindValue(':anoFabricacao', $this->getAnoFabricacao(), PDO::PARAM_INT);
            $stmt->bindValue(':anoModelo', $this->getAnoModelo(), PDO::PARAM_INT);
            $stmt->bindValue(':cor', $this->getCor(), PDO::PARAM_STR);
            $stmt->bindValue(':statusVeiculo', $this->getStatusVeiculo(), PDO::PARAM_STR);
            $stmt->bindValue(':tipoPosse', $this->getTipoPosse(), PDO::PARAM_STR);
            $stmt->bindValue(':quilometragem', $this->getQuilometragem(), PDO::PARAM_INT);
            $stmt->bindValue(':dataUltimaRevisao', $this->getDataUltimaRevisao(), $this->getDataUltimaRevisao() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':proximaRevisao', $this->getProximaRevisao(), $this->getProximaRevisao() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':propriedadeVeiculo', $this->getPropriedadeVeiculo(), PDO::PARAM_STR);
            $stmt->bindValue(':responsavelVeiculo', $this->getResponsavelVeiculo(), PDO::PARAM_STR);
            $stmt->bindValue(':quantidade', $this->getQuantidade(), PDO::PARAM_INT);
            $stmt->bindValue(':observacoes', $this->getObservacoes(), PDO::PARAM_STR);

            $stmt->execute();
            
            // =====================================================
            // CAPTURA DO ID GERADO
            // Isso garante que o objeto atual saiba qual foi o ID 
            // que o banco de dados acabou de criar para ele.
            // =====================================================
            $this->idVeiculo = (int) $this->pdo->lastInsertId();

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function update(): bool
    {
        if (!$this->getIdVeiculo()) {
            return false; 
        }

        try {
            $this->pdo->beginTransaction();

            // UPDATE atualizado com todas as colunas da tabela
            $sql = "UPDATE veiculo SET 
                        idFuncionario = :idFuncionario, placa = :placa, chassi = :chassi, marca = :marca, modelo = :modelo, 
                        anoFabricacao = :anoFabricacao, anoModelo = :anoModelo, cor = :cor, 
                        statusVeiculo = :statusVeiculo, tipoPosse = :tipoPosse, quilometragem = :quilometragem, 
                        dataUltimaRevisao = :dataUltimaRevisao, proximaRevisao = :proximaRevisao, 
                        propriedadeVeiculo = :propriedadeVeiculo, responsavelVeiculo = :responsavelVeiculo, 
                        quantidade = :quantidade, observacoes = :observacoes
                    WHERE idVeiculo = :id";

            $stmt = $this->pdo->prepare($sql);
            
            $stmt->bindValue(':idFuncionario', $this->getIdFuncionario(), $this->getIdFuncionario() ? PDO::PARAM_INT : PDO::PARAM_NULL);
            $stmt->bindValue(':placa', $this->getPlaca(), PDO::PARAM_STR);
            $stmt->bindValue(':chassi', $this->getChassi(), PDO::PARAM_STR);
            $stmt->bindValue(':marca', $this->getMarca(), PDO::PARAM_STR);
            $stmt->bindValue(':modelo', $this->getModelo(), PDO::PARAM_STR);
            $stmt->bindValue(':anoFabricacao', $this->getAnoFabricacao(), PDO::PARAM_INT);
            $stmt->bindValue(':anoModelo', $this->getAnoModelo(), PDO::PARAM_INT);
            $stmt->bindValue(':cor', $this->getCor(), PDO::PARAM_STR);
            $stmt->bindValue(':statusVeiculo', $this->getStatusVeiculo(), PDO::PARAM_STR);
            $stmt->bindValue(':tipoPosse', $this->getTipoPosse(), PDO::PARAM_STR);
            $stmt->bindValue(':quilometragem', $this->getQuilometragem(), PDO::PARAM_INT);
            $stmt->bindValue(':dataUltimaRevisao', $this->getDataUltimaRevisao(), $this->getDataUltimaRevisao() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':proximaRevisao', $this->getProximaRevisao(), $this->getProximaRevisao() ? PDO::PARAM_STR : PDO::PARAM_NULL);
            $stmt->bindValue(':propriedadeVeiculo', $this->getPropriedadeVeiculo(), PDO::PARAM_STR);
            $stmt->bindValue(':responsavelVeiculo', $this->getResponsavelVeiculo(), PDO::PARAM_STR);
            $stmt->bindValue(':quantidade', $this->getQuantidade(), PDO::PARAM_INT);
            $stmt->bindValue(':observacoes', $this->getObservacoes(), PDO::PARAM_STR);
            $stmt->bindValue(':id', $this->getIdVeiculo(), PDO::PARAM_INT);

            $stmt->execute();

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM veiculo WHERE idVeiculo = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function findByPlaca(string $placa): ?self
    {
        $sql = "SELECT * FROM veiculo WHERE placa = :placa";
        $stmt = $this->pdo->prepare($sql);
        
        // Remove tudo que não for letra ou número e deixa maiúsculo
        $placaLimpa = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $placa));
        
        $stmt->bindValue(':placa', $placaLimpa, PDO::PARAM_STR);
        $stmt->execute();
        
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }

    public function findById(int $id): ?self
    {
        $sql = "SELECT * FROM veiculo WHERE idVeiculo = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $this->hydrate($dados) : null;
    }
    public function findAll(): array
    {
        $sql = "SELECT * FROM veiculo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        
        // Busca todas as linhas como um array associativo
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $veiculos = [];
        
        // Itera sobre cada linha retornada pelo banco
        foreach ($resultados as $linha) {
            // Hidrata a linha transformando-a em um Objeto Veiculo e adiciona ao array final
            $veiculos[] = $this->hydrate($linha);
        }
        
        return $veiculos;
    }

    /**
     * Retorna todos os veículos como array associativo
     */
    public function listar(): array
    {
        $sql = "SELECT * FROM veiculo";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca veículos com filtros
     */
    public function buscarComFiltros(string $placa = '', string $statusVeiculo = ''): array
    {
        $sql = "SELECT * FROM veiculo WHERE 1=1";
        
        if (!empty($placa)) {
            $sql .= " AND placa LIKE :placa";
        }
        
        if (!empty($statusVeiculo)) {
            $sql .= " AND statusVeiculo = :statusVeiculo";
        }
        
        $stmt = $this->pdo->prepare($sql);
        
        if (!empty($placa)) {
            $stmt->bindValue(':placa', '%' . $placa . '%', PDO::PARAM_STR);
        }
        
        if (!empty($statusVeiculo)) {
            $stmt->bindValue(':statusVeiculo', $statusVeiculo, PDO::PARAM_STR);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
