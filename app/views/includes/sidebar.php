<aside class="sidebar">

    <!-- LOGO -->
    <div class="logo">
        <img src="assets/img/logo.png" alt="Logo Ideal">
    </div>

    <!-- MENU -->
    <ul class="menu">

        <li>
            <a href="index.php?url=dashboard" class="active">
                Home
            </a>
        </li>

        <li>
            <a href="index.php?url=clientes">
                Clientes
            </a>
        </li>

        <li>
            <a href="index.php?url=obras">
                Obras
            </a>
        </li>

        <li>
            <a href="index.php?url=veiculos">
                Veículos
            </a>
        </li>

        <li>
            <a href="index.php?url=funcionarios">
                Funcionários
            </a>
        </li>

        <li>
            <a href="index.php?url=financeiro">
                Financeiro
            </a>
        </li>

        <!-- CONFIGURAÇÕES -->
        <div class="menu-title">
            CONFIGURAÇÕES
        </div>

        <li>
            <a href="index.php?url=usuarios">
                Usuários
            </a>
        </li>

        <li>
            <a href="index.php?url=empresas">
                Empresas
            </a>
        </li>

        <li>
            <a href="index.php?url=logs">
                Logs
            </a>
        </li>

        <li>
           <a href="index.php?url=logout">
                Sair
            </a>
        </li>

    </ul>

    <!-- USUÁRIO -->

    <div class="user">

    <div class="avatar">
       <?= strtoupper(substr($_SESSION['usuario']['nome'], 0, 1)); ?>
    </div>

    <div class="user-info">

        <h3>Administrador</h3>

        <span>
         <?= $_SESSION['usuario']['nome']; ?>
        </span>

    </div>

</div>

</aside>