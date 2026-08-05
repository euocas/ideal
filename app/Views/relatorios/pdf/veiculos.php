<?php
/** @var array{tipo:string,dados:array,total:int} $relatorio */

$titulo = 'Relatório de Veículos';

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
$veiculos = $relatorio['dados'];

usort($veiculos, function ($a, $b) {
    return strcasecmp($a['placa'], $b['placa']);
});
?>

<div class="cabecalho">

    <?php if ($logoBase64 !== ''): ?>

        <img src="<?= $logoBase64 ?>" class="logo" alt="Logo IDEAL">

    <?php endif; ?>

    <div class="titulo">
        <h2>Relatório de Veículos</h2>

        <p>Sistema IDE<span class="colorA">A</span>L • Soluções Elétricas</p>

    </div>

</div>

<p>Total de registros: <?= $relatorio['total'] ?></p>

<table>

    <thead>
        <tr>
            <th>Placa</th>
            <th>Renavam</th>
             <th>Modelo</th>
             <th>Marca</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($veiculos as $veiculo): ?>

            <tr>

                <td><?= htmlspecialchars($veiculo['placa']) ?></td>
                
                
                <td>
                    <?= !empty($veiculo['renavam'])
                        ? preg_replace(
                            '/(\d{4})(\d{6})(\d)/',
                            '$1.$2-$3',
                            $veiculo['renavam']
                            )
                            : '-' ?>
                </td>
                <td><?= htmlspecialchars($veiculo['modelo']) ?></td>
                <td><?= htmlspecialchars($veiculo['marca']) ?></td>

                <?php
                $status = match ($veiculo['statusVeiculo']) {
                    'ATIVO' => 'Ativo',
                    'INATIVO' => 'Inativo',
                    'VENDIDO' => 'Vendido',
                    'EM MANUTENCAO' => 'Em manutenção',
                    default => $veiculo['statusVeiculo']
                };
                ?>

                <td class="status <?= strtolower($veiculo['statusVeiculo']) ?>">
                    <?= htmlspecialchars($status) ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

</body>

</html>