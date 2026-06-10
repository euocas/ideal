<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!-- FONT AWESOME -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- FAVICON DINÂMICO -->
    <link rel="icon" type="image/png"
          href="<?= $favicon ?? '/ideal/public/assets/icons/padrao.png'; ?>">



    <title><?= $titulo ?? 'Sistema'; ?></title>

    <script>
        window.addEventListener("unload", function () {
            // força o navegador a invalidar BFCache
        });

        window.addEventListener("pageshow", function (event) {

            if (
                event.persisted ||
                performance.navigation.type === 2
            ) {
                window.location.reload();
            }

        });
    </script>