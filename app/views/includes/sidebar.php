<aside class="sidebar">

    <!-- LOGO -->
    <div class="logo">
        <img src="assets/img/logo.png" alt="Logo Ideal">
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
                 <i class="fa-solid fa-users"></i>
                Funcionários
            </a>
        </li>

        <li>
            <a href="index.php?url=financeiro">
                 <i class="fa-solid fa-wallet"></i>
                Financeiro
            </a>
        </li>

        <!-- CONFIGURAÇÕES -->
        <div class="menu-title">
            CONFIGURAÇÕES
        </div>

        <li>
            <a href="index.php?url=usuarios">
                  <i class="fa-regular fa-circle-user"></i>
                Usuários
            </a>
        </li>

        <li>
            <a href="index.php?url=empresas">
                    <i class="fa-regular fa-building"></i>
                Empresas
            </a>
        </li>

        <li>
            <a href="index.php?url=logs">
                     <i class="fa-solid fa-list-check"></i>
                Logs
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
       <?= strtoupper(substr($_SESSION['usuario']['nome'], 0, 1)); ?>
    </div>

    <div class="user-info">

        <h3>Administrador</h3>

        <!-- Comando abaixo pega as informações  no banco: o usuário e nome  -->
        <span>
         <?= $_SESSION['usuario']['nome']; ?>
        </span>

    </div>

</div>

</aside>