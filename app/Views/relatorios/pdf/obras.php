<?php
/** @var array{tipo:string,dados:array,total:int} $relatorio */

$titulo = 'Relatório de Obras';

require __DIR__ . '/../../includes/headerPdf.php';

$baseAssets = is_dir(__DIR__ . '/../../../../public/assets')
    ? __DIR__ . '/../../../../public/assets'
    : __DIR__ . '/../../../../assets';

$logo = $baseAssets . '/img/logopdf.png';

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
$obras = $relatorio['dados'];

?>

<div class="cabecalho">

    <?php if ($logoBase64 !== ''): ?>

        <img src="<?= $logoBase64 ?>" class="logo" alt="Logo IDEAL">

    <?php endif; ?>

    <div class="titulo">
        <h2>Relatório de Obras</h2>

        <p>Sistema IDE<span class="colorA">A</span>L • Soluções Elétricas</p>

    </div>

</div>

<p>Total de registros: <?= $relatorio['total'] ?></p>

<table>

    <thead>
        <tr>
            <th>Contrato</th>
            <th>Cliente</th>
            <th>Cidade</th>
            <th>Início</th>
            <th>Fim</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($obras as $obra): ?>

            <?php
            $status = match ($obra['status']) {
                'Em andamento' => 'Em andamento',
                'Concluida', 'Concluída' => 'Concluída',
                'Cancelada' => 'Cancelada',
                default => $obra['status']
            };
            ?>

            <tr>

                <td><?= htmlspecialchars($obra['contrato']) ?></td>

                <td><?= htmlspecialchars($obra['nomeCliente']) ?></td>

                <td><?= htmlspecialchars($obra['cidade']) ?></td>

                <td>
                    <?= !empty($obra['dataInicio'])
                        ? date('d/m/Y', strtotime($obra['dataInicio']))
                        : '' ?>
                </td>

                <td>
                    <?= !empty($obra['dataFim'])
                        ? date('d/m/Y', strtotime($obra['dataFim']))
                        : '' ?>
                </td>

                <td class="status <?= strtolower(str_replace(' ', '-', $obra['status'])) ?>">
                    <?= htmlspecialchars($status) ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

</body>

</html>