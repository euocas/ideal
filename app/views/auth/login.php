<?php
 //app/views/auth/login.php
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">

    <title>Login</title>

    <!-- Favicon -->
    <link
        rel="shortcut icon"
        href="assets/icons/empreiteira.png"
        type="image/x-icon"
    >

    <!-- CSS -->
    <link
        rel="stylesheet"
        href="assets/css/login.css"
    >
</head>

<body>

<div class="container">

    <!-- LEFT -->
    <div class="left">

        <div class="conteudo">

            <span class="tag">
                • CONSTRUINDO DESDE 2000 •
            </span>

            <h1>
                OBRAS QUE
                <br>
                RESISTEM AO
                <br>
                <span>TEMPO.</span>
            </h1>

            <p>
                Materiais de alta performance para projetos
                que exigem precisão, durabilidade e confiança.
            </p>

        </div>

    </div>

    <!-- RIGHT -->
    <div class="right">

        <div class="login-box">

            <!-- LOGO -->
            <div class="logo">

                <img
                    src="assets/img/logo.png"
                    alt="Logo"
                >

            </div>

            <!-- TITLE -->
            <h2>
                ACESSE SUA
                <br>

                <span>
                    ÁREA EXCLUSIVA
                </span>
            </h2>

            <p class="p_login">
                Acompanhe obras, contratos e documentações.
            </p>

            <!-- ALERTA -->
            <?php if (!empty($_GET['erro'])): ?>

                <div class="alerta-erro">

                    <?php

                    $erros = [

                        'senha' =>
                            'Senha incorreta. Tente novamente.',

                        'usuario' =>
                            'Usuário não encontrado.',

                        'campos' =>
                            'Preencha todos os campos.'
                    ];

                    echo htmlspecialchars(
                        $erros[$_GET['erro']]
                        ?? 'Erro ao fazer login.'
                    );

                    ?>

                </div>

            <?php endif; ?>

            <!-- FORM -->
            <form
                action="index.php?url=logar"
                method="POST"
            >

                <!-- EMAIL -->
                <label>E-MAIL</label>

                <input
                    type="email"
                    name="email"
                    placeholder="seu@email.com.br"

                    value="<?= htmlspecialchars($_GET['email'] ?? '') ?>"

                    required
                >

                <!-- SENHA -->
                <label>SENHA</label>

                <input
                    type="password"
                    name="senha"
                    required
                >

                <!-- OPCOES -->
                <div class="opcoes-login">

                    <label class="manter-conectado">

                        <input
                            type="checkbox"
                            name="manter_conectado"
                            value="1"
                        >

                        Manter conectado

                    </label>

                    <a
                        href="#"
                        class="link-esqueceu"
                    >
                        Esqueceu a senha?
                    </a>

                </div>

                <!-- BUTTON -->
                <button type="submit">
                    ENTRAR NO PORTAL
                </button>

            </form>

        </div>

    </div>

</div>

</body>
</html>