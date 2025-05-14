// application/views/clientes/create.php
?>
<h2>Cadastro de Cliente</h2>
<?= validation_errors('<div class="alert alert-danger">', '</div>'); ?>
<?= form_open('clientes/store') ?>
  <div class="mb-3">
    <label>CPF</label>
    <input name="cpf" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Nome</label>
    <input name="nome" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>CEP</label>
    <input name="cep" id="cep" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Rua</label>
    <input name="rua" id="rua" class="form-control">
  </div>
  <div class="mb-3">
    <label>Bairro</label>
    <input name="bairro" id="bairro" class="form-control">
  </div>
  <div class="mb-3">
    <label>Cidade</label>
    <input name="cidade" id="cidade" class="form-control">
  </div>
  <div class="mb-3">
    <label>Estado</label>
    <input name="estado" id="estado" class="form-control">
  </div>
  <div class="mb-3">
    <label>Telefone</label>
    <input name="telefone" class="form-control">
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input name="email" class="form-control">
  </div>
  <div class="mb-3">
    <label>Observação</label>
    <textarea name="observacao" class="form-control"></textarea>
  </div>
  <button type="submit" class="btn btn-success">Salvar</button>
</form>

<script>
document.getElementById('cep').addEventListener('blur', function() {
  const cep = this.value.replace(/\D/g, '');
  if (cep.length === 8) {
    fetch(`https://viacep.com.br/ws/${cep}/json/`)
      .then(response => response.json())
      .then(data => {
        if (!('erro' in data)) {
          document.getElementById('rua').value = data.logradouro;
          document.getElementById('bairro').value = data.bairro;
          document.getElementById('cidade').value = data.localidade;
          document.getElementById('estado').value = data.uf;
        }
      });
  }
});
</script>
