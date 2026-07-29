<?php
/** @var array{tipo:string,dados:array,total:int} $relatorio */

$titulo = 'Relatório Financeiro';

require __DIR__ . '/../../includes/headerPdf.php';

$logo = __DIR__ . '/../../../../public/assets/img/logopdf.png';

$logoBase64 = '';

if (file_exists($logo)) {
    $conteudo = file_get_contents($logo);

    if ($conteudo !== false) {
        $logoBase64 = 'data:image/png;base64,' . base64_encode($conteudo);
    } else {
        error_log('PdfService: falha ao ler o arquivo de logo em: ' . $logo);
    }
} else {
    error_log('PdfService: logo não encontrado no caminho: ' . $logo);
}

/*
|--------------------------------------------------------------------------
| Ordenação alfabética
|--------------------------------------------------------------------------
*/
$financeiros = $relatorio['dados'];
$totalEntradas = 0;

$totalSaidas = 0;

?>

<div class="cabecalho">

    <?php if ($logoBase64 !== ''): ?>

        <img src="<?= $logoBase64 ?>" class="logo" alt="Logo IDEAL">

    <?php endif; ?>

    <div class="titulo">
        <h2>Relatório do Financeiro da Empresa</h2>

        <p>Sistema IDE<span class="colorA">A</span>L • Soluções Elétricas</p>

    </div>

</div>

<p>Total de registros: <?= $relatorio['total'] ?></p>

<table>

    <thead>
        <tr>
            <th>Origem</th>
            <th>Categoria</th>
            <th>Descrição</th>
            <th>Tipo</th>
            <th>Valor</th>
            <th>Data</th>
        </tr>
    </thead>

<tbody>

    <?php foreach ($financeiros as $financeiro): ?>

        <?php
        if (strtoupper($financeiro['tipo']) === 'ENTRADA') {
            $totalEntradas += $financeiro['valor'];
        } else {
            $totalSaidas += $financeiro['valor'];
        }

        $tipo = match (strtoupper($financeiro['tipo'])) {
            'ENTRADA' => 'Entrada',
            'SAIDA', 'SAÍDA' => 'Saída',
            default => $financeiro['tipo']
        };
        ?>

        <tr>

            <td><?= htmlspecialchars($financeiro['origem']) ?></td>

            <td><?= htmlspecialchars($financeiro['categoria']) ?></td>

            <td><?= htmlspecialchars($financeiro['descricao']) ?></td>

            <td class="tipo <?= strtolower($financeiro['tipo']) ?>">
                <?= htmlspecialchars($tipo) ?>
            </td>

            <td>
                R$ <?= number_format($financeiro['valor'], 2, ',', '.') ?>
            </td>

            <td>
                <?= date('d/m/Y', strtotime($financeiro['data'])) ?>
            </td>

        </tr>

    <?php endforeach; ?>

</tbody>

</table>

<?php
$saldo = $totalEntradas - $totalSaidas;
?>

<table class="resumo-financeiro">
    <tr>
        <td>
            <strong>Entradas:</strong><br>
            R$ <?= number_format($totalEntradas, 2, ',', '.') ?>
        </td>

        <td>
            <strong>Saídas:</strong><br>
            R$ <?= number_format($totalSaidas, 2, ',', '.') ?>
        </td>

        <td>
            <strong>Saldo:</strong><br>
            <?= $saldo < 0 ? '-R$ ' : 'R$ ' ?>
            <?= number_format(abs($saldo), 2, ',', '.') ?>
        </td>
    </tr>
</table>

</body>

</html>