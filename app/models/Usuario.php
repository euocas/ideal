<?php
require_once __DIR__ . '/../../config/conexao.php';

class Usuario{

private $conn;

public function __construct(){

$database = new Conexao();

$this->conn = $database->conectar();

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