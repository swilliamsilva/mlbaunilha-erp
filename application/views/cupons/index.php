// application/views/cupons/index.php
?>
<h2>Cupons</h2>
<a class="btn btn-primary mb-3" href="/cupons/create">Novo Cupom</a>
<table class="table table-bordered">
  <thead><tr><th>Produto</th><th>Valor</th><th>Validade</th><th>Condição</th></tr></thead>
  <tbody>
    <?php foreach ($cupons as $c): ?>
    <tr>
      <td><?= $c->produto ?></td>
      <td>R$ <?= number_format($c->valor_cupom, 2, ',', '.') ?></td>
      <td><?= $c->data_validade ?></td>
      <td><?= $c->condicao ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
