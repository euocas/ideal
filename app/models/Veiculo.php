<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class Veiculo
{
    private $conexao;

    public function __construct()
    {
        $banco = new Conexao();
        $this->conexao = $banco->getConnection();
    }

    public function findByRenavam(string $renavam)
    {
        $stmt = $this->conexao->prepare("SELECT * FROM veiculo WHERE renavam = :renavam");
        $stmt->bindValue(':renavam', $renavam, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById(int $id)
    {
        $stmt = $this->conexao->prepare("SELECT * FROM veiculo WHERE idVeiculo = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save(array $dados)
    {
        $renavam = preg_replace('/[^0-9]/', '', $dados['renavam'] ?? '');
        $placa = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $dados['placa'] ?? ''));
        $chassi = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $dados['chassi'] ?? ''));
        $anoFab = !empty($dados['anoFabricacao']) ? (int) substr($dados['anoFabricacao'], 0, 4) : (int)date('Y');
        $anoMod = !empty($dados['anoModelo']) ? (int) substr($dados['anoModelo'], 0, 4) : (int)date('Y');

        $sql = "INSERT INTO veiculo (
                    renavam, placa, chassi, marca, modelo, anoFabricacao, anoModelo, cor, 
                    statusVeiculo, tipoPosse, quilometragem, dataUltimaRevisao, proximaRevisao, 
                    responsavelVeiculo, observacoes
                ) VALUES (
                    :renavam, :placa, :chassi, :marca, :modelo, :anoFabricacao, :anoModelo, :cor, 
                    :statusVeiculo, :tipoPosse, :quilometragem, :dataUltimaRevisao, :proximaRevisao, 
                    :responsavelVeiculo, :observacoes
                )";

        $stmt = $this->conexao->prepare($sql);
        
        $stmt->bindValue(':renavam', $renavam ?: '00000000000', PDO::PARAM_STR);
        $stmt->bindValue(':placa', $placa ?: 'XXX0000', PDO::PARAM_STR);
        $stmt->bindValue(':chassi', $chassi ?: 'NAOINFORMADO', PDO::PARAM_STR);
        $stmt->bindValue(':marca', !empty($dados['marca']) ? $dados['marca'] : 'Não informada', PDO::PARAM_STR);
        $stmt->bindValue(':modelo', !empty($dados['modelo']) ? $dados['modelo'] : 'Não informado', PDO::PARAM_STR);
        $stmt->bindValue(':anoFabricacao', $anoFab, PDO::PARAM_INT);
        $stmt->bindValue(':anoModelo', $anoMod, PDO::PARAM_INT);
        $stmt->bindValue(':cor', !empty($dados['cor']) ? $dados['cor'] : 'Branco', PDO::PARAM_STR);
        $stmt->bindValue(':statusVeiculo', !empty($dados['status']) ? $dados['status'] : 'ATIVO', PDO::PARAM_STR);
        $stmt->bindValue(':tipoPosse', !empty($dados['posse']) ? $dados['posse'] : 'PROPRIO', PDO::PARAM_STR);
        $stmt->bindValue(':quilometragem', !empty($dados['quilometragem']) ? (int)$dados['quilometragem'] : 0, PDO::PARAM_INT);
        
        $ultimaRev = !empty($dados['ultimaRevisao']) ? $dados['ultimaRevisao'] : null;
        $stmt->bindValue(':dataUltimaRevisao', $ultimaRev, $ultimaRev ? PDO::PARAM_STR : PDO::PARAM_NULL);
        
        $proxRev = !empty($dados['proximaRevisao']) ? $dados['proximaRevisao'] : null;
        $stmt->bindValue(':proximaRevisao', $proxRev, $proxRev ? PDO::PARAM_STR : PDO::PARAM_NULL);
        
        $stmt->bindValue(':responsavelVeiculo', !empty($dados['responsavel']) ? $dados['responsavel'] : null, PDO::PARAM_STR);
        $stmt->bindValue(':observacoes', !empty($dados['observacoes']) ? $dados['observacoes'] : null, PDO::PARAM_STR);

        if (!$stmt->execute()) {
            $erro = $stmt->errorInfo();
            throw new \Exception($erro[2]);
        }
        return true;
    }

    public function update(int $id, array $dados)
    {
        $placa = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $dados['placa'] ?? ''));
        $chassi = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $dados['chassi'] ?? ''));
        
        // CORREÇÃO: Trata o ano enviado garantindo o corte correto independente do formato recebido
        $anoFab = !empty($dados['anoFabricacao']) ? (int) substr($dados['anoFabricacao'], 0, 4) : (int)date('Y');
        $anoMod = !empty($dados['anoModelo']) ? (int) substr($dados['anoModelo'], 0, 4) : (int)date('Y');

        $sql = "UPDATE veiculo SET 
                    placa = :placa, chassi = :chassi, marca = :marca, modelo = :modelo, 
                    anoFabricacao = :anoFabricacao, anoModelo = :anoModelo, cor = :cor, 
                    statusVeiculo = :statusVeiculo, tipoPosse = :tipoPosse, quilometragem = :quilometragem, 
                    dataUltimaRevisao = :dataUltimaRevisao, proximaRevisao = :proximaRevisao, 
                    responsavelVeiculo = :responsavelVeiculo, observacoes = :observacoes
                WHERE idVeiculo = :id";

        $stmt = $this->conexao->prepare($sql);
        
        $stmt->bindValue(':placa', $placa ?: 'XXX0000', PDO::PARAM_STR);
        $stmt->bindValue(':chassi', $chassi ?: 'NAOINFORMADO', PDO::PARAM_STR);
        $stmt->bindValue(':marca', !empty($dados['marca']) ? $dados['marca'] : 'Não informada', PDO::PARAM_STR);
        $stmt->bindValue(':modelo', !empty($dados['modelo']) ? $dados['modelo'] : 'Não informado', PDO::PARAM_STR);
        $stmt->bindValue(':anoFabricacao', $anoFab, PDO::PARAM_INT);
        $stmt->bindValue(':anoModelo', $anoMod, PDO::PARAM_INT);
        $stmt->bindValue(':cor', !empty($dados['cor']) ? $dados['cor'] : 'Branco', PDO::PARAM_STR);
        $stmt->bindValue(':statusVeiculo', !empty($dados['status']) ? $dados['status'] : 'ATIVO', PDO::PARAM_STR);
        $stmt->bindValue(':tipoPosse', !empty($dados['posse']) ? $dados['posse'] : 'PROPRIO', PDO::PARAM_STR);
        $stmt->bindValue(':quilometragem', !empty($dados['quilometragem']) ? (int)$dados['quilometragem'] : 0, PDO::PARAM_INT);
        
        $ultimaRev = !empty($dados['ultimaRevisao']) ? $dados['ultimaRevisao'] : null;
        $stmt->bindValue(':dataUltimaRevisao', $ultimaRev, $ultimaRev ? PDO::PARAM_STR : PDO::PARAM_NULL);
        
        $proxRev = !empty($dados['proximaRevisao']) ? $dados['proximaRevisao'] : null;
        $stmt->bindValue(':proximaRevisao', $proxRev, $proxRev ? PDO::PARAM_STR : PDO::PARAM_NULL);
        
        $stmt->bindValue(':responsavelVeiculo', !empty($dados['responsavel']) ? $dados['responsavel'] : null, PDO::PARAM_STR);
        $stmt->bindValue(':observacoes', !empty($dados['observacoes']) ? $dados['observacoes'] : null, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            $erro = $stmt->errorInfo();
            throw new \Exception($erro[2]);
        }
        return true;
    }

    public function delete(int $id)
    {
        $stmt = $this->conexao->prepare("DELETE FROM veiculo WHERE idVeiculo = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}