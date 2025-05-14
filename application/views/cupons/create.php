// application/views/cupons/create.php
?>
<h2>Cadastro de Cupom</h2>
<?= form_open('cupons/store') ?>
  <div class="mb-3">
    <label>Produto</label>
    <select name="produto_id" class="form-control">
      <?php foreach ($produtos as $p): ?>
        <option value="<?= $p->id ?>"><?= $p->nome ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Pedido (opcional)</label>
    <select name="pedido_id" class="form-control">
      <option value="">-- Nenhum --</option>
      <?php foreach ($pedidos as $pd): ?>
        <option value="<?= $pd->id ?>">Pedido #<?= $pd->id ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Valor do Cupom</label>
    <input name="valor_cupom" type="number" step="0.01" class="form-control">
  </div>
  <div class="mb-3">
    <label>Data de Validade</label>
    <input name="data_validade" type="date" class="form-control">
  </div>
  <div class="mb-3">
    <label>Condição (até 100 caracteres)</label>
    <input name="condicao" maxlength="100" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Salvar</button>
</form>
<?php
