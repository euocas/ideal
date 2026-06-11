<?php
$nomeUsuario = $_SESSION['usuario']['nome'] ?? 'Usuário';
?>

<aside class="sidebar">

    <!-- LOGO -->
    <div class="logo">
        <a href="/ideal/public/index.php?url=dashboard">
            <img src="/ideal/public/assets/img/logo.png" alt="Logo Ideal">
        </a>
    </div>

    <!-- MENU -->
    <ul class="menu">

        <li>
            <a href="index.php?url=dashboard" class="active">
                <i class="fa-solid fa-house"></i>
                Home
            </a>
        </li>

        <li>
            <a href="index.php?url=clientes">
                <i class="fa-solid fa-users"></i>
                Clientes
            </a>
        </li>

        <li>
            <a href="index.php?url=obras">
                <i class="fa-solid fa-building"></i>
                Obras
            </a>
        </li>

        <li>
            <a href="index.php?url=veiculos">
                <i class="fa-solid fa-car"></i>
                Veículos
            </a>
        </li>

        <li>
            <a href="index.php?url=funcionarios">
                <i class="fa-solid fa-user-tie"></i>
                Funcionários
            </a>
        </li>

        <li>
            <a href="index.php?url=financeiros">
                <i class="fa-solid fa-wallet"></i>
                Financeiro
            </a>
        </li>

        <li>
            <a href="index.php?url=relatorios">
                <i class="fa-solid fa-chart-line"></i>
                Relatórios
            </a>
        </li>

        <!-- CONFIGURAÇÕES -->
        <div class="menu-title">
            CONFIGURAÇÕES
        </div>

        <li>
            <a href="index.php?url=credenciais">
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

</aside>