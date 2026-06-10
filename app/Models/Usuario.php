<?php
//importação da classe
namespace App\Models;

use App\Config\Conexao;
use PDO;

class Usuario
{
    private $conn;

    public function __construct()
    {
        $conexao = new Conexao();

        $this->conn = $conexao->getConnection();
    }

    public function buscarPorEmail($email)
    {
        $sql = "SELECT * FROM usuario WHERE email = :email";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':email', $email);

        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizarSenha($email, $senha)
    {
        $sql = "UPDATE usuario
                SET senha = :senha
                WHERE email = :email";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':senha', $senha);

        $stmt->bindValue(':email', $email);

        return $stmt->execute();
    }
}
