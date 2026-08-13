<?php
$nomeUsuario = $_SESSION['usuario']['nome'] ?? 'Usuário';
?>

<!-- LOGO -->
<div class="logo">
    <a href="<?= BASE_URL ?>/index.php?url=dashboard">
        <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="Logo Ideal">
    </a>
</div>

<?php
$paginaAtual = $_GET['url'] ?? 'dashboard';
?>

<!-- MENU -->
<ul class="menu">

    <li>
        <a href="index.php?url=dashboard" class="<?= $paginaAtual === 'dashboard' ? 'active' : '' ?>">
            <i class="fa-solid fa-house"></i>
            Home
        </a>
    </li>

    <li>
        <a href="index.php?url=clientes" class="<?= $paginaAtual === 'clientes' ? 'active' : '' ?>">
            <i class="fa-solid fa-users"></i>
            Clientes
        </a>
    </li>

    <li>
        <a href="index.php?url=obras" class="<?= $paginaAtual === 'obras' ? 'active' : '' ?>">
            <i class="fa-solid fa-building"></i>
            Obras
        </a>
    </li>

    <li>
        <a href="index.php?url=veiculos" class="<?= $paginaAtual === 'veiculos' ? 'active' : '' ?>">
            <i class="fa-solid fa-car"></i>
            Veículos
        </a>
    </li>

    <li>
        <a href="index.php?url=funcionarios" class="<?= $paginaAtual === 'funcionarios' ? 'active' : '' ?>">
            <i class="fa-solid fa-user-tie"></i>
            Funcionários
        </a>
    </li>

    <li>
        <a href="index.php?url=financeiros" class="<?= $paginaAtual === 'financeiros' ? 'active' : '' ?>">
            <i class="fa-solid fa-wallet"></i>
            Financeiro
        </a>
    </li>

    <li>
        <a href="index.php?url=relatorios" class="<?= $paginaAtual === 'relatorios' ? 'active' : '' ?>">
            <i class="fa-solid fa-chart-line"></i>
            Relatórios
        </a>
    </li>

    <!-- CONFIGURAÇÕES -->
    <div class="menu-title">
        CONFIGURAÇÕES
    </div>

    <li>
        <a href="index.php?url=credenciais" class="<?= $paginaAtual === 'credenciais' ? 'active' : '' ?>">
            <i class="fa-solid fa-key"></i>
            Trocar Senha
        </a>
    </li>

    <li>
        <a href="index.php?url=logout">
            <i class="fa-solid fa-arrow-right-from-bracket"></i>
            Sair
        </a>
    </li>

    <li>
        <button type="button" id="themeToggle" class="theme-toggle">
            <i class="fa-solid fa-moon"></i>
            <span id="themeToggleLabel">Tema Escuro</span>
        </button>
    </li>

</ul>

<!-- USUÁRIO -->
<div class="user">

    <div class="avatar">
        <?= strtoupper(substr($nomeUsuario, 0, 1)); ?>
    </div>

    <div class="user-info">

        <h3><?= $nomeUsuario; ?></h3>

        <span>
            Usuário do sistema
        </span>

    </div>

</div>