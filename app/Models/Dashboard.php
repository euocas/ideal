<?php

namespace App\Models;
use App\Config\Conexao;
use PDO;

class Dashboard
{
    // =====================================================
    // 1. ATRIBUTOS
    // =====================================================
    private PDO $pdo;
    // =====================================================
    // 2. CONSTRUTOR
    // =====================================================

    public function __construct()
    {
        $banco = new Conexao();
        $this->pdo = $banco->getConnection();
    }

    public function contarClientes(): int
    {
        $sql = "SELECT COUNT(*) FROM cliente";
        return (int) $this->pdo->query($sql)->fetchColumn();
    }

    public function contarFuncionarios(): int
    {
        $sql = "SELECT COUNT(*) FROM funcionario";
        return (int) $this->pdo->query($sql)->fetchColumn();
    }

    public function contarObras(): int
    {
        $sql = "SELECT COUNT(*) FROM obra";
        return (int) $this->pdo->query($sql)->fetchColumn();
    }
    public function contarVeiculos(): int
    {
        $sql = "SELECT COUNT(*) FROM veiculo";
        return (int) $this->pdo->query($sql)->fetchColumn();
    }
    public function getIndicadores(): array
{
    return [
    [
        'icone' => 'fa-solid fa-users',
        'valor' => $this->contarClientes(),
        'titulo' => 'Clientes cadastrados',
        'descricao' => 'Total de clientes'
    ],
    [
       'icone' => 'fa-solid fa-helmet-safety',
        'valor' => $this->contarFuncionarios(),
        'titulo' => 'Funcionários',
        'descricao' => 'Total de funcionários'
    ],
    [
        'icone' => 'fa-solid fa-building',
        'valor' => $this->contarObras(),
        'titulo' => 'Obras',
        'descricao' => 'Total de obras'
    ],
    [
        'icone' => 'fa-solid fa-car',
        'valor' => $this->contarVeiculos(),
        'titulo' => 'Veículos',
        'descricao' => 'Total de veículos'
    ]
];
}

}
