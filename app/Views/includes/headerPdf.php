<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <title><?= $titulo ?? 'Relatório'; ?></title>

    <style>
        <?= file_get_contents(__DIR__ . '/../../../public/assets/css/pdf.css'); ?>
        <?= file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/ideal/public/assets/css/variables.css'); ?>
    </style>

</head>

<body>