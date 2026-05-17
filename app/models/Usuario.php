<?php

namespace App\Models;
use App\Config\Conexao;
use PDO;

class Usuario{

private $conn;

public function __construct(){

$conexao = new Conexao();

$this->conn = $conexao->getConnection();

}
public function buscarPorEmail($email)
{
$sql = "SELECT * FROM usuarios WHERE email = :email";

$stmt = $this->conn->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();

return $stmt -> fetch (PDO::FETCH_ASSOC);

}

}