// application/views/carrinho/index.php
?>
<h2>Carrinho</h2>
<table class="table">
  <thead>
    <tr><th>Produto</th><th>Qtd</th><th>Preço</th><th>Subtotal</th></tr>
  </thead>
  <tbody>
    <?php foreach ($itens as $i): ?>
    <tr>
      <td><?= $i['nome'] ?></td>
      <td><?= $i['quantidade'] ?></td>
      <td>R$ <?= number_format($i['preco'], 2, ',', '.') ?></td>
      <td>R$ <?= number_format($i['quantidade'] * $i['preco'], 2, ',', '.') ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<p>Subtotal: <strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong></p>
<p>Frete: <strong>R$ <?= number_format($frete, 2, ',', '.') ?></strong></p>
<p>Total: <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong></p>
<a href="/carrinho/limpar" class="btn btn-danger">Limpar Carrinho</a>
