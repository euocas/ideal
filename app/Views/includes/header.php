<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- FAVICON DINÂMICO -->
    <link rel="icon" type="image/png" href="<?= $favicon ?? '/ideal/public/assets/icons/padrao.png'; ?>">

    <title><?= $titulo ?? 'Sistema'; ?></title>

    <!-- CSS -->

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/variables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/base.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/component.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/forms.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/alerts.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/tables.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/dashboard.css?v=<?= time() ?>">


    <script>
        window.addEventListener("pageshow", function (event) {
            if (event.persisted || performance.navigation.type === 2) {
                window.location.reload();
            }
        });
    </script>
</head>

<body>

    <button class="menu-toggle" id="menuToggle">

        <i class="fa-solid fa-bars"></i>

    </button>