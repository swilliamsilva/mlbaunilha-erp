// application/views/estoque/index.php
?>
<h2>Estoque</h2>
<a class="btn btn-primary mb-3" href="/estoque/create">Adicionar Variação</a>
<table class="table table-bordered">
  <thead><tr><th>Produto</th><th>Variação</th><th>Saldo</th></tr></thead>
  <tbody>
    <?php foreach ($estoques as $e): ?>
    <tr>
      <td><?= $e->nome ?></td>
      <td><?= $e->variacao ?></td>
      <td><?= $e->saldo ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
