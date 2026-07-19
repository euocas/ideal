<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

$titulo = "Financeiro";
$favicon = "/ideal/public/assets/icon/financeiro3.png";
require_once __DIR__ . "/../includes/header.php";
$aba = $aba ?? ($_GET["aba"] ?? "funcionario");
$abas = ["funcionario", "obra", "automovel"];
if (!in_array($aba, $abas)) {
    $aba = "funcionario";
}
?>
<link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/base.css">
<link rel="stylesheet" href="/ideal/public/assets/css/component.css">
<link rel="stylesheet" href="/ideal/public/assets/css/forms.css">
<link rel="stylesheet" href="/ideal/public/assets/css/alerts.css">
<link rel="stylesheet" href="/ideal/public/assets/css/tables.css">
<link rel="stylesheet" href="/ideal/public/assets/css/dashboard.css">
<link rel="stylesheet" href="/ideal/public/assets/css/financeiro.css?v=<?= time() ?>">
</head>

<body>
    <div class="dashboard-container">
        <?php include __DIR__ . "/../includes/sidebar.php"; ?>

        <main class="main-content">

            <div class="abas-container">
                <a href="?url=financeiros&aba=funcionario" class="aba <?= $aba ===
                    "funcionario"
                    ? "ativa"
                    : "" ?>"><i class="fa-solid fa-user-tie"></i> Funcionário</a>
                <a href="?url=financeiros&aba=obra" class="aba <?= $aba ===
                    "obra"
                    ? "ativa"
                    : "" ?>"><i class="fa-solid fa-hard-hat"></i> Obra</a>
                <a href="?url=financeiros&aba=automovel" class="aba <?= $aba ===
                    "automovel"
                    ? "ativa"
                    : "" ?>"><i class="fa-solid fa-car"></i> Automóvel</a>
            </div>

            <?php if (isset($_SESSION["mensagem_sucesso"])): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($_SESSION["mensagem_sucesso"]) ?>
                </div>
                <?php unset($_SESSION["mensagem_sucesso"]); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION["mensagem_erro"])): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($_SESSION["mensagem_erro"]) ?>
                </div>
                <?php unset($_SESSION["mensagem_erro"]); ?>
            <?php endif; ?>

            <?php if ($aba === "funcionario"): ?>

                <?php require __DIR__ . "/funcionario.php"; ?>

            <?php elseif ($aba === "obra"): ?>

                <?php require __DIR__ . "/obra.php"; ?>

            <?php elseif ($aba === "automovel"): ?>

                <?php require __DIR__ . "/automovel.php"; ?>

            <?php endif; ?>

        </main>
    </div>

</body>

</html>