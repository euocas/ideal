<?php
/** @var array{tipo:string,dados:array,total:int} $relatorio */

$titulo = 'Relatório de Funcionários';
require __DIR__ . '/../../includes/headerPdf.php';

?>


<div class="cabecalho">


    <div class="titulo">
        <h2>Relatório de Funcionários</h2>

        <p>Sistema IDEAL • Soluções Elétricas</p>

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

        <?php

        usort($relatorio['dados'], function ($a, $b) {

            return strcasecmp($a['nome'], $b['nome']);

        });

        ?>
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