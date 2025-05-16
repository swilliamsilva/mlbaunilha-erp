<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Página Não Encontrada</title>
    <link href="<?= base_url('assets/css/errors.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="error-container">
        <h1>403</h1>
        <p>A página que você procura não existe.</p>
        <a href="<?= site_url() ?>" class="btn">Voltar para a página inicial</a>
    </div>
</body>
</html>
