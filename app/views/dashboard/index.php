<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link rel="stylesheet"
          href="assets/css/dashboard.css">

</head>

<body>

<div class="layout">

    <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

    <div class="content">

    <h1>
        Bem-vindo,<br>
         <?= $_SESSION['usuario']['nome']; ?>
    </h1>

    <h2 class="titulo-pagina">
        IDEAL - Soluções elétricas
    </h2>
    <h3> Seleciona uma opção</h3>
    <div class="cards">



        <a href="index.php?url=clientes" class="card">

            <h3>Clientes</h3>

            <p>
                Gerencie os clientes cadastrados.
            </p>

        </a>

        <a href="index.php?url=funcionarios" class="card">

            <h3>Funcionários</h3>

            <p>
                Controle de funcionários da empresa.
            </p>

        </a>

        <a href="index.php?url=obras" class="card">

            <h3>Obras</h3>

            <p>
                Acompanhe o andamento das obras.
            </p>

        </a>

        <a href="index.php?url=financeiro" class="card">

            <h3>Financeiro</h3>

            <p>
                Controle financeiro e pagamentos.
            </p>

        </a>

    </div>

</div>