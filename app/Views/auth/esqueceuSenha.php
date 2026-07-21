<?php
$etapa = $etapa ?? 'email';
$erro = $_GET['erro'] ?? '';
$emailAtual = $email ?? ($_SESSION['recuperacao_email'] ?? '');

$mensagensErro = [
    'campos'     => 'Preencha todos os campos corretamente.',
    'codigo'     => 'Código inválido ou expirado. Tente novamente.',
    'diferentes' => 'As senhas não coincidem.',
    'tamanho'    => 'A senha precisa ter pelo menos 6 caracteres.',
    'aguarde'    => 'Aguarde um minuto antes de pedir um novo código.',
];
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redefinir Senha</title>
    <link rel="stylesheet" href="/ideal/public/assets/css/variables.css">
    <link rel="stylesheet" href="/ideal/public/assets/css/redefinirSenha.css">
</head>

<body>

<div class="container">

    <div class="left-side">
        <div class="overlay">
            <span class="tag">• CONSTRUINDO DESDE 2000</span>
            <h1>OBRAS QUE <br>RESISTEM AO <br><span>TEMPO.</span></h1>
            <p>Materiais de alta performance para projetos que exigem precisão, durabilidade e confiança.</p>
        </div>
    </div>

    <div class="right-side">

        <?php if (!empty($_GET['sucesso'])): ?>

            <div class="mensagem-sucesso">
                <h2>SENHA <span>ALTERADA!</span></h2>
                <p class="descricao">Sua senha foi redefinida com sucesso.</p>
                <a href="/ideal/public/index.php?url=login" class="btn-voltar-login">VOLTAR PARA LOGIN</a>
            </div>

        <?php elseif ($etapa === 'email'): ?>

            <form action="/ideal/public/index.php?url=esqueci-senha/enviar" method="POST">
                <h2>ESQUECI <span>MINHA SENHA</span></h2>
                <p class="descricao">
                    Informe seu e-mail cadastrado. Vamos enviar um código
                    de verificação para você redefinir sua senha.
                </p>

                <?php if ($erro && isset($mensagensErro[$erro])): ?>
                    <p class="mensagem-erro"><?= htmlspecialchars($mensagensErro[$erro]) ?></p>
                <?php endif; ?>

                <label>E-mail</label>
                <input type="email" name="email" placeholder="seu@email.com.br"
                       value="<?= htmlspecialchars($emailAtual) ?>" required>

                <button type="submit">ENVIAR CÓDIGO</button>
                <a href="/ideal/public/index.php?url=login">← Voltar ao login</a>
            </form>

        <?php elseif ($etapa === 'codigo'): ?>

            <form action="/ideal/public/index.php?url=esqueci-senha/validar" method="POST">
                <h2>VERIFIQUE <span>SEU E-MAIL</span></h2>
                <p class="descricao">
                    Enviamos um código de 6 dígitos para
                    <strong><?= htmlspecialchars($emailAtual) ?></strong>.
                    O código expira em 10 minutos.
                </p>

                <?php if ($erro && isset($mensagensErro[$erro])): ?>
                    <p class="mensagem-erro"><?= htmlspecialchars($mensagensErro[$erro]) ?></p>
                <?php endif; ?>

                <label>Código de verificação</label>
                <input type="text" name="codigo" class="input-codigo" inputmode="numeric"
                       maxlength="6" placeholder="000000" autocomplete="one-time-code" required>

                <button type="submit">VALIDAR CÓDIGO</button>
            </form>

            <form action="/ideal/public/index.php?url=esqueci-senha/reenviar" method="POST">
                <button type="submit" class="btn-secundario">Reenviar código</button>
            </form>

            <a href="/ideal/public/index.php?url=esqueci-senha">← Usar outro e-mail</a>

        <?php elseif ($etapa === 'nova-senha'): ?>

            <form action="/ideal/public/index.php?url=redefinir-senha" method="POST">
                <h2>REDEFINIR <span>SENHA</span></h2>
                <p class="descricao">Código verificado! Agora defina sua nova senha.</p>

                <?php if ($erro && isset($mensagensErro[$erro])): ?>
                    <p class="mensagem-erro"><?= htmlspecialchars($mensagensErro[$erro]) ?></p>
                <?php endif; ?>

                <label>Nova senha</label>
                <input type="password" name="nova_senha" minlength="6" required>

                <label>Confirmar nova senha</label>
                <input type="password" name="confirmar_senha" minlength="6" required>

                <button type="submit">SALVAR NOVA SENHA</button>
                <a href="/ideal/public/index.php?url=login">← Voltar ao login</a>
            </form>

        <?php endif; ?>

    </div>
</div>

</body>
</html>