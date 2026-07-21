<?php
// TÍTULO DA PÁGINA
$titulo = 'Home';
$favicon = '/ideal/public/assets/icon/home.png';
/*-----------------------------------------------
        ARRAY DE BANNER
 * ----------------------------------------------*/
$bannerSlides = [
    [
        'imagem' => 'assets/img/banner/banner1.png',
        'titulo' => 'Bem-vindo ao',
        'destaque' => 'ERP IDEAL',
        'descricao' => 'Gerencie clientes, funcionários, obras, veículos e financeiro de forma integrada.',
        'botao' => 'Clientes',
        'link' => 'index.php?url=clientes'
    ],
    [
        'imagem' => 'assets/img/banner/banner2.png',
        'titulo' => 'Acompanhe suas',
        'destaque' => 'Obras',
        'descricao' => 'Tenha uma visão rápida do andamento dos projetos.',
        'botao' => 'Obras',
        'link' => 'index.php?url=obras'
    ],
    [
        'imagem' => 'assets/img/banner/banner3.png',
        'titulo' => 'Controle seu',
        'destaque' => 'Financeiro',
        'descricao' => 'Visualize receitas, despesas e indicadores em um único lugar.',
        'botao' => 'Financeiro',
        'link' => 'index.php?url=financeiros'
    ]
];



require_once __DIR__ . '/../includes/header.php';
?>


<body>
    <div class="layout">
        <?php include_once __DIR__ . '/../includes/sidebar.php'; ?>

        <div class="content">
            <div class="dashboard-header">

                <div class="dashboard-header-info">
                    <h1>
                        Bem-vindo,
                        <?= $_SESSION['usuario']['nome']; ?>
                    </h1>
                </div>

                <div class="dashboard-header-brand">
                    <h2>
                        SISTEMA - IDE<span class="logo-a">A</span>L
                    </h2>
                    <span class="subtitulo-sistema">
                        Soluções elétricas
                    </span>
                </div>
            </div>

            <!-- ÁREA DOS CARDS -->
            <section class="painel-opcoes">
                <h2 class="painel-titulo">
                    Selecione uma opção
                </h2>
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

            <!-- BANNER / CARROSSEL -->
            <section class="banner-carrossel" id="bannerCarrossel" aria-label="Destaques">

                <div class="banner-track" data-track>
                    <?php foreach ($bannerSlides as $i => $slide): ?>
                        <article class="banner-slide <?= $i === 0 ? 'is-active' : '' ?>" data-slide="<?= $i ?>"
                            style="background-image: url('<?= htmlspecialchars($slide['imagem'], ENT_QUOTES) ?>');">
                            <div class="banner-slide__overlay"></div>
                            <div class="banner-slide__content">
                                <h2 class="banner-slide__titulo">
                                    <?= htmlspecialchars($slide['titulo']) ?><br>
                                    <span class="banner-slide__destaque">
                                        <?= htmlspecialchars($slide['destaque']) ?>
                                    </span>
                                </h2>
                                <p class="banner-slide__descricao">
                                    <?= htmlspecialchars($slide['descricao']) ?>
                                </p>
                                <a href="<?= htmlspecialchars($slide['link']) ?>" class="banner-slide__botao">
                                    <?= htmlspecialchars($slide['botao']) ?>
                                </a>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- controles -->
                <button type="button" class="banner-nav banner-nav--prev" data-prev aria-label="Anterior">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button type="button" class="banner-nav banner-nav--next" data-next aria-label="Próximo">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

                <!-- indicadores -->
                <div class="banner-dots" data-dots>
                    <?php foreach ($bannerSlides as $i => $slide): ?>
                        <button type="button" class="banner-dot <?= $i === 0 ? 'is-active' : '' ?>" data-goto="<?= $i ?>"
                            aria-label="Slide <?= $i + 1 ?>"></button>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- INDICADORES -->
            <section class="indicadores">

                <?php foreach ($indicadores as $ind): ?>

                    <article class="indicador">

                        <div class="indicador__icone">
                            <i class="<?= $ind['icone'] ?>"></i>
                        </div>

                        <div class="indicador__dados">

                            <span class="indicador__valor">
                                <?= htmlspecialchars((string) $ind['valor']) ?>
                            </span>

                            <span class="indicador__titulo">
                                <?= htmlspecialchars($ind['titulo']) ?>
                            </span>

                            <span class="indicador__descricao">
                                <?= htmlspecialchars($ind['descricao']) ?>
                            </span>

                        </div>

                    </article>

                <?php endforeach; ?>

            </section>

        </div>
    </div>
    <script src="assets/js/dashboard.js"></script>

</body>