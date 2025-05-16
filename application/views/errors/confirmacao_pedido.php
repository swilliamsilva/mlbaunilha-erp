<!DOCTYPE html>
<html>
<head>
    <title>Confirmação de Pedido</title>
</head>
<body>
    <h1>Olá <?= $cliente->nome ?>,</h1>
    <p>Seu pedido <strong>#<?= $pedido->id ?></strong> foi confirmado!</p>
    <p>Data prevista para entrega: <?= date('d/m/Y', strtotime($pedido->data_entrega_prevista)) ?></p>
    <p>Acompanhe seu pedido: <?= anchor(site_url('pedidos/'.$pedido->id), 'Clique aqui') ?></p>
</body>
</html>
