<!--HEADER PHP  -->
<?php
// TÍTULO DA PÁGINA
$titulo = 'Relatórios';
require_once __DIR__ . '/../includes/header.php';
?>
    <link rel="shortcut icon" href="/ideal/public/assets/icons/financeiro3.png" type="image/x-icon">
    <link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
    <link rel="stylesheet" href="/ideal/public/assets/css/relatorios.css?v=<?= time() ?>"">

</head>

<body>

    <div class="dashboard-container">

        <!-- SIDEBAR -->
        <?php include __DIR__ . '/../includes/sidebar.php'; ?>

        <!-- CONTEÚDO DA PÁGINA -->
        <main class="main-content">

            <section class="card-construcao">

                <img src="/ideal/public/assets/icons/relatorio.png" alt="Relatorio">

                <h1>EM CONSTRUÇÃO</h1>

            </section>

        </main>

    </div>