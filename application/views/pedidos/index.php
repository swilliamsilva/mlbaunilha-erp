// application/views/pedidos/index.php
?>
<h2>Pedidos</h2>
<a class="btn btn-primary mb-3" href="/pedidos/create">Novo Pedido</a>
<table class="table table-bordered">
  <thead><tr>
    <th>Cliente</th><th>Produto</th><th>Quantidade</th><th>Preço</th><th>Frete</th><th>Status</th>
  </tr></thead>
  <tbody>
    <?php foreach ($pedidos as $p): ?>
    <tr>
      <td><?= $p->cliente ?></td>
      <td><?= $p->produto ?></td>
      <td><?= $p->quantidade_solicitada ?></td>
      <td>R$ <?= number_format($p->preco, 2, ',', '.') ?></td>
      <td>R$ <?= number_format($p->frete, 2, ',', '.') ?></td>
      <td><?= $p->status ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
