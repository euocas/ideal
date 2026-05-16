<?php

namespace App\Config;
class Database
{
    private $host = 'localhost';
    private $db_name = 'empreiteira';
    private $username = 'root';
    private $password = '';
    private $port = '3306';

    public $conn = null;

    public function getConnection()
    {
        $this->conn = null;
        try {
            //DSB (Data Source Name) - string de conexão
            $dsn = 'mysql:host=' . $this -> host . ';port=' . $this -> port . ';dbname=' . $this -> db_name .';charset=utf8';
            $this -> conn = new PDO($dsn, $this -> username, $this -> password);
            $this -> conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }catch(PDOException $e){
            echo 'Erro de conexão: ' . $e ->getMessage();
        }catch (Exception $e){
            echo 'Erro de Conexão: ' . $e -> getMessage();
        }catch (Throwable $e){
            echo 'Erro genérico: ' . $e -> getMessage();
        }
        return $this -> conn;
    }
}