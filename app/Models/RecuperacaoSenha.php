<?php

namespace App\Models;

use App\Config\Conexao;
use PDO;

class RecuperacaoSenha
{
    private PDO $pdo;

    /** Duração de validade do código, em minutos */
    private const VALIDADE_MINUTOS = 10;

    public function __construct()
    {
        $conexao = new Conexao();
        $this->pdo = $conexao->getConnection();
    }

    /**
     * Gera um novo código de 6 dígitos, invalida códigos antigos
     * do mesmo e-mail e salva o novo no banco.
     */
    public function gerarCodigo(string $email): string
    {
        $this->invalidarCodigosAnteriores($email);

        $codigo = (string) random_int(100000, 999999);
        $expiraEm = date('Y-m-d H:i:s', strtotime('+' . self::VALIDADE_MINUTOS . ' minutes'));

        $stmt = $this->pdo->prepare("
            INSERT INTO recuperacaoSenha (email, codigo, expiraEm)
            VALUES (:email, :codigo, :expiraEm)
        ");

        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':codigo', $codigo);
        $stmt->bindValue(':expiraEm', $expiraEm);
        $stmt->execute();

        return $codigo;
    }

    /**
     * Verifica se o código informado é válido (existe, não expirou e não foi usado).
     */
    public function validarCodigo(string $email, string $codigo): bool
    {
        $stmt = $this->pdo->prepare("
            SELECT idRecuperacao
            FROM recuperacaoSenha
            WHERE email = :email
              AND codigo = :codigo
              AND usado = FALSE
              AND expiraEm >= NOW()
            ORDER BY idRecuperacao DESC
            LIMIT 1
        ");

        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':codigo', $codigo);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    /**
     * Marca todos os códigos do e-mail como usados (chamado após a troca de senha).
     */
    public function marcarComoUsado(string $email): void
    {
        $stmt = $this->pdo->prepare("
            UPDATE recuperacaoSenha
            SET usado = TRUE
            WHERE email = :email
        ");

        $stmt->bindValue(':email', $email);
        $stmt->execute();
    }

    /**
     * Invalida (marca como usado) qualquer código anterior ainda ativo do e-mail,
     * para que apenas o código mais recente funcione.
     */
    private function invalidarCodigosAnteriores(string $email): void
    {
        $this->marcarComoUsado($email);
    }
}