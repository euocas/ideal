<?php 
// TÍTULO DA PÁGINA
$titulo = 'Home';
$favicon = '/ideal/public/assets/icon/home.png';

require_once __DIR__ . '/../includes/header.php'; 
?>

<link rel="shortcut icon" href="assets/icons/home.png" type="image/x-icon">
<link rel="stylesheet" href="assets/css/dashboard.css?v=<?= time() ?>">
<link rel="stylesheet" href="assets/css/variables.css">


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


                    <!-- CARD PARA  ACESSO A PÁGINA CLIENTES -->
                    <a href="index.php?url=clientes" class="card-dashboard card-clientes">
                        <div class="card-icon">
                            👥
                        </div>
                        <h3>Clientes</h3>
                        <p>Gerencie os clientes cadastrados.</p>
                    </a>

                    <!-- CARD PARA  ACESSO A PÁGINA FUNCIONÁRIOS -->
                    <a href="index.php?url=funcionarios" class="card-dashboard card-funcionarios">
                        <div class="card-icon">
                            👷
                        </div>
                        <h3>Funcionários</h3>
                        <p>Gerencie os funcionários.</p>
                    </a>

                    <!-- CARD PARA  ACESSO A PÁGINA OBRAS-->

                    <a href="index.php?url=obras" class="card-dashboard card-obras">
                        <div class="card-icon">
                            🏢
                        </div>
                        <h3>Obras</h3>
                        <p> Acompanhe o andamento das obras.</p>
                    </a>

                    <!-- CARD PARA  ACESSO A PÁGINA FINANCEIROS -->
                    <a href="index.php?url=financeiros" class="card-dashboard card-financeiros">
                        <div class="card-icon">
                            💰
                        </div>
                        <h3>Financeiro</h3>
                        <p>Controle financeiro e pagamentos.</p>
                    </a>

                    <!-- CARD PARA  ACESSO A PÁGINA VEÍCULOS-->
                    <a href="index.php?url=veiculos" class="card-dashboard card-veiculos">
                        <div class="card-icon">
                            🚗
                        </div>
                        <h3>Veículos</h3>
                        <p> Controle da frota e veículos.</p>
                    </a>

                </div>
            </section>
        </div>