<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard</title>

    <link rel="stylesheet" href="assets/css/dashboard.css">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

    <div class="layout">

        <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

        <div class="content">

            <h1>
                Bem-vindo,
                <?= $_SESSION['usuario']['nome']; ?>
            </h1>

            <h2 class="subtitulo-pagina">
                SISTEMA - IDEAL - Soluções elétricas
            </h2>
            <br>
            <!-- ÁREA DOS CARDS -->

            <section class="painel-opcoes">

                <div class="painel-titulo">
                    Selecione uma opção
                </div>

                <!-- cards estilo botões para o usuário selecionar -->
                <div class="cards-dashboard">


                    <a href="index.php?url=clientes" class="card-dashboard card-clientes">
                        <div class="card-icon">
                            👥
                        </div>
                        <h3>Clientes</h3>
                        <p>Gerencie os clientes cadastrados.</p>
                    </a>

                    <a href="index.php?url=funcionarios" class="card-dashboard card-funcionarios">
                        <div class="card-icon">
                            👷
                        </div>
                        <h3>Funcionários</h3>
                        <p>Controle de funcionários da empresa.</p>
                    </a>

                    <a href="index.php?url=obras" class="card-dashboard card-obras">
                        <div class="card-icon">
                            🏢
                        </div>
                        <h3>Obras</h3>
                        <p> Acompanhe o andamento das obras.</p>
                    </a>

                    <a href="index.php?url=financeiro" class="card-dashboard card-financeiro">
                        <div class="card-icon">
                            💰
                        </div>
                        <h3>Financeiro</h3>
                        <p>Controle financeiro e pagamentos.</p>
                    </a>

                </div>
            </section>
        </div>