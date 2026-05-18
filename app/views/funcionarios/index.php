<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Funcionários</title>
    <!-- Icone na Aba de navegador-->
    <link rel="shortcut icon" href="assets/icons/funcionario.png" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/funcionarios.css">
</head>

<body>
    <div class="container-funcionario">
        <!-- BUSCA -->
        <section class=" card busca-funcionario">

            <div class="busca-esquerda">
                <h2>BUSCAR FUNCIONÁRIO</h2>

                <form action="" method="POST">

                    <div class="campo">
                        <label>CPF</label>

                        <input type="text" name="cpf" placeholder="Digite o CPF do funcionário">
                    </div>

                    <button type="submit" class="btn-buscar">
                        Buscar
                    </button>
                </form>
            </div>

            <div class="dica">

                <h3>DICA</h3>
                <p> Digite o CPF do funcionário e clique em BUSCAR. Se não existir, você poderá cadastrar um novo funcionário.
                </p>

            </div>
        </section>

</body>

</html>