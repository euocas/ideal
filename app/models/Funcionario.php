<?php

namespace App\Models;

use App\Config\Conexao; 
use PDO;

class Funcionario
{

    private $pdo;

    public function __construct()
    {
        $banco = new Conexao();
        $this->pdo = $banco->getConnection();
    }

    public function findByCpf($cpf)
    {
        $sql = "SELECT f.*, c.telefone, c.whatsapp 
                FROM funcionario f 
                LEFT JOIN contatoFuncionario c ON f.idFuncionario = c.idFuncionario 
                WHERE f.cpf = :cpf";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':cpf', $cpf, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $sql = "SELECT f.*, c.telefone, c.whatsapp 
                FROM funcionario f 
                LEFT JOIN contatoFuncionario c ON f.idFuncionario = c.idFuncionario 
                WHERE f.idFuncionario = :id";
                
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function save($dados)
    { 
        try {
            $this->pdo->beginTransaction();

            $sql = "INSERT INTO funcionario (nome, cpf, sexo, dataNascimento, naturalidade, estadoNascimento, tipoLogradouro, nomeLogradouro, numero, complemento, cidade, cep, estado, email, cargoFuncao, tipoContrato, status, observacoes) 
                    VALUES (:nome, :cpf, :sexo, :dataNascimento, :naturalidade, :estadoNascimento, 'Rua', :nomeLogradouro, :numero, :complemento, :cidade, :cep, :estado, :email, :cargoFuncao, :tipoContrato, :status, :observacoes)";
            
            $stmt = $this->pdo->prepare($sql);
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
            
            $stmt->execute();

            $idFuncionario = $this->pdo->lastInsertId();

            if (!empty($dados['telefone']) || !empty($dados['whatsapp'])) {
                $sqlContato = "INSERT INTO contatoFuncionario (idFuncionario, telefone, whatsapp) 
                               VALUES (:idFuncionario, :telefone, :whatsapp)";
                $stmtContato = $this->pdo->prepare($sqlContato);
                $stmtContato->bindValue(':idFuncionario', $idFuncionario, PDO::PARAM_INT);
                $stmtContato->bindValue(':telefone', $dados['telefone'] ?? '', PDO::PARAM_STR);
                $stmtContato->bindValue(':whatsapp', $dados['whatsapp'] ?? '', PDO::PARAM_STR);
                $stmtContato->execute();
            }

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function update($id, $dados)
    {
        try {
            $this->pdo->beginTransaction();

            $sql = "UPDATE funcionario SET 
                    nome = :nome, sexo = :sexo, dataNascimento = :dataNascimento, naturalidade = :naturalidade, estadoNascimento = :estadoNascimento, 
                    nomeLogradouro = :nomeLogradouro, numero = :numero, complemento = :complemento, cidade = :cidade, cep = :cep, estado = :estado, 
                    email = :email, cargoFuncao = :cargoFuncao, tipoContrato = :tipoContrato, status = :status, observacoes = :observacoes 
                    WHERE idFuncionario = :id";
            
            $stmt = $this->pdo->prepare($sql);
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
            $stmt->execute();

            $stmtCheck = $this->pdo->prepare("SELECT idContato FROM contatoFuncionario WHERE idFuncionario = :id");
            $stmtCheck->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtCheck->execute();

            if ($stmtCheck->rowCount() > 0) {
                $sqlContato = "UPDATE contatoFuncionario SET telefone = :telefone, whatsapp = :whatsapp WHERE idFuncionario = :id";
            } else {
                $sqlContato = "INSERT INTO contatoFuncionario (idFuncionario, telefone, whatsapp) VALUES (:id, :telefone, :whatsapp)";
            }

            $stmtContato = $this->pdo->prepare($sqlContato);
            $stmtContato->bindValue(':id', $id, PDO::PARAM_INT);
            $stmtContato->bindValue(':telefone', $dados['telefone'] ?? '', PDO::PARAM_STR);
            $stmtContato->bindValue(':whatsapp', $dados['whatsapp'] ?? '', PDO::PARAM_STR);
            $stmtContato->execute();

            $this->pdo->commit();
            return true;

        } catch (\Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }

    public function delete($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM funcionario WHERE idFuncionario = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}