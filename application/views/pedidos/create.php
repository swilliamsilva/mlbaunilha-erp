// application/views/pedidos/create.php
?>
<h2>Novo Pedido</h2>
<?= form_open('pedidos/store') ?>
  <div class="mb-3">
    <label>Cliente</label>
    <select name="cliente_id" class="form-control">
      <?php foreach ($clientes as $c): ?>
        <option value="<?= $c->id ?>"><?= $c->nome ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Produto</label>
    <select name="produto_id" class="form-control">
      <?php foreach ($produtos as $p): ?>
        <option value="<?= $p->id ?>" data-preco="<?= $p->preco_venda ?>"><?= $p->nome ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Data Prevista de Entrega</label>
    <input name="data_entrega_prevista" type="date" class="form-control">
  </div>
  <div class="mb-3">
    <label>Quantidade Solicitada</label>
    <input name="quantidade_solicitada" type="number" class="form-control">
  </div>
  <div class="mb-3">
    <label>Preço Unitário</label>
    <input name="preco" type="number" step="0.01" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Salvar Pedido</button>
</form>
<?php
