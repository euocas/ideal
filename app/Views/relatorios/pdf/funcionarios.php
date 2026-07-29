<?php
/** @var array{tipo:string,dados:array,total:int} $relatorio */

$titulo = 'Relatório de Funcionários';

require __DIR__ . '/../../includes/headerPdf.php';

/*
|--------------------------------------------------------------------------
| Logo da empresa (PNG em Base64)
|--------------------------------------------------------------------------
|
| Em vez de usar $_SERVER['DOCUMENT_ROOT'] (que depende da configuração
| do servidor e pode variar dependendo de como o script é chamado),
| montamos o caminho a partir de __DIR__, que é sempre relativo a este
| arquivo. Ajuste o número de "/.." conforme a posição real deste
| arquivo dentro do projeto.
|
*/

// Este arquivo está em: app/Views/relatorios/pdf/funcionarios.php
// então subimos 4 níveis para chegar na raiz do projeto e então
// descemos até public/assets/img/.
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

usort($relatorio['dados'], function ($a, $b) {
    return strcasecmp($a['nome'], $b['nome']);
});
?>

<div class="cabecalho">

    <?php if ($logoBase64 !== ''): ?>
        <!-- <img src="<?= $logoBase64 ?>" class="logo" alt="Logo IDEAL"> -->

   <img src="<?= $logoBase64 ?>" class="logo" alt="Logo IDEAL">

    <?php else: ?>
        <!-- Logo não encontrado - verifique o caminho em $logo -->
    <?php endif; ?>

    <div class="titulo">
        <h2>Relatório de Funcionários</h2>

        <p>Sistema IDE<span class="colorA">A</span>L • Soluções Elétricas</p>

    </div>

</div>

<p>Total de registros: <?= $relatorio['total'] ?></p>

<table>

    <thead>
        <tr>
            <th>Nome</th>
            <th>CPF</th>
            <th>Cargo</th>
            <th>Status</th>
        </tr>
    </thead>

    <tbody>

        <?php foreach ($relatorio['dados'] as $funcionario): ?>

            <tr>

                <td><?= htmlspecialchars($funcionario['nome']) ?></td>

                <td><?= preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $funcionario['cpf']) ?></td>

                <td><?= htmlspecialchars($funcionario['cargoFuncao']) ?></td>

                <td class="status <?= strtolower($funcionario['status']) ?>">
                    <?= ucfirst(htmlspecialchars($funcionario['status'])) ?>
                </td>

            </tr>

        <?php endforeach; ?>

    </tbody>

</table>

</body>

</html>
