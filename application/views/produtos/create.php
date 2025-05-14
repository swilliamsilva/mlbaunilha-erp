// application/views/produtos/create.php
?>
<h2>Cadastro de Produto</h2>
<?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<?= form_open('produtos/store') ?>
  <div class="mb-3">
    <label>Código</label>
    <input name="codigo_produto" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Nome</label>
    <input name="nome" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Descrição</label>
    <textarea name="descricao" class="form-control"></textarea>
  </div>
  <div class="mb-3">
    <label>Link do Mercado Livre</label>
    <input name="link_ml" class="form-control">
  </div>
  <div class="mb-3">
    <label>Preço de Compra</label>
    <input name="preco_compra" type="number" step="0.01" class="form-control">
  </div>
  <div class="mb-3">
    <label>Cupom de Desconto</label>
    <input name="cupom_desconto" type="number" step="0.01" class="form-control">
  </div>
  <div class="mb-3">
    <label>Preço de Venda</label>
    <input name="preco_venda" type="number" step="0.01" class="form-control">
  </div>
  <div class="mb-3">
    <label>Valor do Frete</label>
    <input name="valor_frete" type="number" step="0.01" class="form-control">
  </div>
  <div class="mb-3">
    <label>Estoque Inicial</label>
    <input name="saldo_estoque" type="number" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Salvar</button>
</form>
<?php
