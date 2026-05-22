<?php

namespace App\Models;

use App\Config\Conexao; 
use PDO;

class Funcionario
{
    public function findByCpf($cpf)
    {
        $banco = new Conexao();
        $pdo = $banco->getConnection();
        
        $stmt = $pdo->prepare("SELECT * FROM funcionario WHERE cpf = :cpf");
        $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $banco = new Conexao();
        $pdo = $banco->getConnection();
        
        $stmt = $pdo->prepare("SELECT * FROM funcionario WHERE idFuncionario = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($dados)
    {
        $banco = new Conexao();
        $pdo = $banco->getConnection();
        
        $sql = "INSERT INTO funcionario (nome, cpf, sexo, dataNascimento, naturalidade, estadoNascimento, tipoLogradouro, nomeLogradouro, numero, complemento, cidade, cep, estado, email, cargoFuncao, tipoContrato, status, observacoes) 
                VALUES (:nome, :cpf, :sexo, :dataNascimento, :naturalidade, :estadoNascimento, 'Rua', :nomeLogradouro, :numero, :complemento, :cidade, :cep, :estado, :email, :cargoFuncao, :tipoContrato, :status, :observacoes)";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $dados['nome'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':cpf', $dados['cpf'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':sexo', $dados['sexo'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':dataNascimento', $dados['dataNascimento'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':naturalidade', $dados['naturalidade'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':estadoNascimento', $dados['estadoNascimento'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':nomeLogradouro', $dados['nomeLogradouro'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':numero', $dados['numero'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':complemento', $dados['complemento'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':cidade', $dados['cidade'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':cep', $dados['cep'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':estado', $dados['estado'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':email', $dados['email'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':cargoFuncao', $dados['cargoFuncao'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':tipoContrato', $dados['tipoContrato'] ?? 'CLT', PDO::PARAM_STR);
        $stmt->bindValue(':status', $dados['status'] ?? 'ativo', PDO::PARAM_STR);
        $stmt->bindValue(':observacoes', $dados['observacoes'] ?? '', PDO::PARAM_STR);
        
        return $stmt->execute();
    }

    public function update($id, $dados)
    {
        $banco = new Conexao();
        $pdo = $banco->getConnection();
        
        $sql = "UPDATE funcionario SET 
                nome = :nome, sexo = :sexo, dataNascimento = :dataNascimento, naturalidade = :naturalidade, estadoNascimento = :estadoNascimento, 
                nomeLogradouro = :nomeLogradouro, numero = :numero, complemento = :complemento, cidade = :cidade, cep = :cep, estado = :estado, 
                email = :email, cargoFuncao = :cargoFuncao, tipoContrato = :tipoContrato, status = :status, observacoes = :observacoes 
                WHERE idFuncionario = :id";
        
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':nome', $dados['nome'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':sexo', $dados['sexo'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':dataNascimento', $dados['dataNascimento'] ?? null, PDO::PARAM_STR);
        $stmt->bindValue(':naturalidade', $dados['naturalidade'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':estadoNascimento', $dados['estadoNascimento'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':nomeLogradouro', $dados['nomeLogradouro'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':numero', $dados['numero'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':complemento', $dados['complemento'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':cidade', $dados['cidade'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':cep', $dados['cep'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':estado', $dados['estado'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':email', $dados['email'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':cargoFuncao', $dados['cargoFuncao'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':tipoContrato', $dados['tipoContrato'] ?? 'CLT', PDO::PARAM_STR);
        $stmt->bindValue(':status', $dados['status'] ?? 'ativo', PDO::PARAM_STR);
        $stmt->bindValue(':observacoes', $dados['observacoes'] ?? '', PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        
        return $stmt->execute();
    }

    public function delete($id)
    {
        $banco = new Conexao();
        $pdo = $banco->getConnection();
        
        $stmt = $pdo->prepare("DELETE FROM funcionario WHERE idFuncionario = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}