h2>Clientes</h2>
<a class="btn btn-primary mb-3" href="/clientes/create">Novo Cliente</a>
<table class="table table-bordered">
  <thead>
    <tr><th>CPF</th><th>Nome</th><th>CEP</th><th>Telefone</th><th>Email</th></tr>
  </thead>
  <tbody>
    <?php foreach ($clientes as $cli): ?>
    <tr>
      <td><?= $cli->cpf ?></td>
      <td><?= $cli->nome ?></td>
      <td><?= $cli->cep ?></td>
      <td><?= $cli->telefone ?></td>
      <td><?= $cli->email ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<?php
