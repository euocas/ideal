<?php
/** @var \App\Models\Funcionario $funcionario */
/** @var float $valor */
/** @var string|null $dataReferencia */


$nomeFuncionario = $funcionario->getNome() ?? '';
$cpfFuncionario = $funcionario->getCpf() ?? '';
$valorFormatado = number_format(
    (float) $valor,
    2,
    ',',
    '.'
);
$dataFormatada = $dataReferencia ? date('d/m/Y', strtotime($dataReferencia)) : '';

$cpfFormatado = preg_replace(
    '/(\d{3})(\d{3})(\d{3})(\d{2})/',
    '$1.$2.$3-$4',
    preg_replace('/\D/', '', $cpfFuncionario)
);
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo - <?= htmlspecialchars($nomeFuncionario) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/recibo.css?v=<?= time() ?>">

</head>
<main class="recibo">

    <header class="cabecalho">
        <img src="<?= BASE_URL ?>/assets/img/logopdf.png" alt="IDEAL - Soluções Elétricas" class="logo">
        <div class="titulo">
            <h1>RECIBO</h1>
            <p>Comprovante de pagamento</p>
        </div>
    </header>

    <section class="identificacao">
        <h2>Identificação do Funcionário</h2>
        <div class="dados-funcionario">
            <div class="campo">
                <span class="campo-label"> Nome</span>

                <span class="campo-valor"><?= htmlspecialchars($nomeFuncionario) ?> </span>
            </div>

            <div class="campo">
                <span class="campo-label"> CPF</span>
                <span class="campo-valor"> <?= htmlspecialchars($cpfFormatado) ?></span>
            </div>
        </div>

    </section>

    <section class="lancamento">
        <h2>Dados do Lançamento</h2>
        <div class="dados-lancamento">

            <div class="linha">
                <div class="linha-label"> Categoria </div>
                <div class="linha-valor"> <?= htmlspecialchars($categoria ?? '') ?></div>
            </div>

            <div class="linha">
                <div class="linha-label"> Descrição </div>

                <div class="linha-valor"> <?= htmlspecialchars($descricao ?? '') ?> </div>

            </div>

            <div class="linha">
                <div class="linha-label"> Data do pagamento </div>

                <div class="linha-valor"><?= htmlspecialchars($dataFormatada) ?></div>

            </div>

            <div class="linha">
                <div class="linha-label"> Forma de pagamento</div>
                <div class="linha-valor"> <?= htmlspecialchars($formaPagamento ?? '') ?> </div>

            </div>

            <?php if (!empty($contaPagamento)): ?>
                <div class="linha">

                    <div class="linha-label">
                        Conta de pagamento
                    </div>

                    <div class="linha-valor">
                        <?= htmlspecialchars($contaPagamento) ?>
                    </div>
                </div>

            <?php endif; ?>

            <div class="linha">
                <div class="linha-label">
                    Valor
                </div>

                <div class="linha-valor valor-destaque">
                  R$ <?= $valorFormatado ?>
                </div>
            </div>
        </div>
    </section>

    <?php if (!empty($observacao)): ?>
        <section class="observacao">
            <strong>Observação</strong>
            <p>
                <?= nl2br(htmlspecialchars($observacao)) ?>
            </p>
        </section>

    <?php endif; ?>

    <p class="declaracao">
        Declaro, para os devidos fins, que recebi da
        <strong>IDEAL – Soluções Elétricas</strong>
        o valor acima especificado, referente ao lançamento financeiro
        descrito neste recibo.
    </p>

    <div class="assinatura">

        <div class="linha-assinatura">
            <?= htmlspecialchars($nomeFuncionario) ?><br>
            Funcionário
        </div>
    </div>

    <footer class="rodape">
        Documento gerado pelo sistema IDEAL – Soluções Elétricas.
    </footer>
</main>

<div class="acoes">
    <button type="button" class="btn-imprimir" onclick="window.print()">
        Imprimir / Salvar PDF
    </button>
</div>

</body>

</html>