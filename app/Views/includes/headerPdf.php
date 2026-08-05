<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <title><?= $titulo ?? 'Relatório'; ?></title>

    <?php

    $cssBase = is_dir(__DIR__ . '/../../../public/assets/css')
        ? __DIR__ . '/../../../public/assets/css'
        : __DIR__ . '/../../../assets/css';

    ?>

    <style>
        <?= file_get_contents($cssBase . '/variables.css'); ?>
        <?= file_get_contents($cssBase . '/pdf.css'); ?>
    </style>

</head>

<body>