// application/views/produtos/index.php
?>
<h2>Produtos</h2>
<a class="btn btn-primary mb-3" href="/produtos/create">Novo Produto</a>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>Código</th><th>Nome</th><th>Preço Compra</th><th>Preço Venda</th><th>Estoque</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($produtos as $prod): ?>
    <tr>
      <td><?= $prod->codigo_produto ?></td>
      <td><?= $prod->nome ?></td>
      <td>R$ <?= number_format($prod->preco_compra, 2, ',', '.') ?></td>
      <td>R$ <?= number_format($prod->preco_venda, 2, ',', '.') ?></td>
      <td><?= $prod->saldo_estoque ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
