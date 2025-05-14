// application/views/estoque/create.php
?>
<h2>Controle de Estoque</h2>
<?= form_open('estoque/store') ?>
  <div class="mb-3">
    <label>Produto</label>
    <select name="produto_id" class="form-control">
      <?php foreach ($produtos as $p): ?>
        <option value="<?= $p->id ?>"><?= $p->nome ?></option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="mb-3">
    <label>Variação</label>
    <input name="variacao" class="form-control">
  </div>
  <div class="mb-3">
    <label>Saldo</label>
    <input name="saldo" type="number" class="form-control">
  </div>
  <button type="submit" class="btn btn-success">Salvar</button>
</form>
<?php
