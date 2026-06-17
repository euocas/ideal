<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class Credencial
{
    // =====================================================
    // ATRIBUTOS
    // =====================================================

    private ?int $id = null;
    private ?string $nome = null;
    private ?string $email = null;
    private ?string $senha = null;

    public string $dbError = '';

    private PDO $pdo;

    // =====================================================
    // CONSTRUTOR
    // =====================================================

    public function __construct()
    {
        $banco = new Conexao();
        $this->pdo = $banco->getConnection();
    }

    // =====================================================
    // GETTERS E SETTERS
    // =====================================================

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(?string $nome): void
    {
        $this->nome = $nome;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getSenha(): ?string
    {
        return $this->senha;
    }

    public function setSenha(?string $senha): void
    {
        $this->senha = $senha;
    }

    // =====================================================
    // HYDRATE
    // =====================================================

    private function hydrate(array $dados): self
    {
        $usuario = new self();

        $usuario->setId($dados['id'] ?? null);
        $usuario->setNome($dados['nome'] ?? null);
        $usuario->setEmail($dados['email'] ?? null);
        $usuario->setSenha($dados['senha'] ?? null);

        return $usuario;
    }

    // =====================================================
    // CONSULTAS
    // =====================================================

    public function findByEmail(string $email): ?self
    {
        $sql = "SELECT * 
                FROM usuario
                WHERE email = :email";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->hydrate($dados) : null;
    }

    public function findById(int $id): ?self
    {
        $sql = "SELECT *
                FROM usuario
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ? $this->hydrate($dados) : null;
    }

    // =====================================================
    // ALTERAÇÃO DE SENHA
    // =====================================================

    public function alterarSenha(): bool
    {
        if (!$this->getId()) {
            return false;
        }

        try {

            $sql = "UPDATE usuario
                    SET senha = :senha
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(
                ':senha',
                password_hash($this->getSenha(), PASSWORD_DEFAULT),
                PDO::PARAM_STR
            );

            $stmt->bindValue(
                ':id',
                $this->getId(),
                PDO::PARAM_INT
            );

            return $stmt->execute();

        } catch (\Exception $e) {

            $this->dbError = $e->getMessage();
            return false;
        }
    }

    // =====================================================
    // ALTERAÇÃO DE E-MAIL
    // =====================================================

    public function alterarEmail(): bool
    {
        if (!$this->getId()) {
            return false;
        }

        try {

            $sql = "UPDATE usuario
                    SET email = :email
                    WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(
                ':email',
                $this->getEmail(),
                PDO::PARAM_STR
            );

            $stmt->bindValue(
                ':id',
                $this->getId(),
                PDO::PARAM_INT
            );

            return $stmt->execute();

        } catch (\Exception $e) {

            $this->dbError = $e->getMessage();
            return false;
        }
    }

    public function buscarUsuario(string $login): ?array
    {
        $sql = "SELECT
                id,
                nome,
                email
            FROM usuario
            WHERE nome LIKE :login
            LIMIT 1";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindValue(
            ':login',
            '%' . $login . '%',
            PDO::PARAM_STR
        );

        $stmt->execute();

        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        return $dados ?: null;
    }

//
}