<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Redefinir Senha</title>

    <link rel="stylesheet"
          href="/ideal/public/assets/css/redefinirSenha.css">

</head>

<body>

<div class="container">

    <!-- IMAGEM -->
    <div class="left-side">

        <div class="overlay">

            <span class="tag">
                • CONSTRUINDO DESDE 2000
            </span>

            <h1>
                OBRAS QUE <br>
                RESISTEM AO <br>
                <span>TEMPO.</span>
            </h1>

            <p>
                Materiais de alta performance para projetos
                que exigem precisão, durabilidade e confiança.
            </p>

        </div>

    </div>

    <!-- FORM -->
    <div class="right-side">

        <!-- SUCESSO -->
        <?php if (!empty($_GET['sucesso'])): ?>

            <div class="mensagem-sucesso">

                <h2>
                    SENHA <span>ALTERADA!</span>
                </h2>

                <p class="descricao">
                    Sua senha foi redefinida com sucesso.
                </p>

                <a href="/ideal/public/index.php?url=login"
                   class="btn-voltar-login">

                    VOLTAR PARA LOGIN

                </a>

            </div>

        <?php endif; ?>


        <!-- FORMULÁRIO -->
        <?php if (empty($_GET['sucesso'])): ?>

            <form action="/ideal/public/index.php?url=redefinir-senha"
                  method="POST">

                <h2>
                    REDEFINIR <span>SENHA</span>
                </h2>

                <p class="descricao">
                    Informe seu e-mail e defina uma nova senha.
                </p>

                <label>E-mail</label>

                <input type="email"
                       name="email"
                       placeholder="seu@email.com.br"
                       required>

                <label>Nova senha</label>

                <input type="password"
                       name="nova_senha"
                       required>

                <label>Confirmar nova senha</label>

                <input type="password"
                       name="confirmar_senha"
                       required>

                <button type="submit">
                    SALVAR NOVA SENHA
                </button>

                <a href="/ideal/public/index.php?url=login">

                    ← Voltar ao login

                </a>

            </form>

        <?php endif; ?>

    </div>

</div>

</body>

</html>