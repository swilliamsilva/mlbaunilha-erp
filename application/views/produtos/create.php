<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-box-seam"></i> Catálogo de Produtos</h4>
        <a href="<?= site_url('produtos/create') ?>" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Novo Produto
        </a>
    </div>

    <div class="card-body">
        <?php if(empty($produtos)): ?>
            <div class="alert alert-info">Nenhum produto cadastrado</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Nome</th>
                            <th class="text-end">Custo</th>
                            <th class="text-end">Venda</th>
                            <th class="text-end">Margem</th>
                            <th class="text-end">Estoque</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($produtos as $prod): ?>
                        <tr class="<?= $prod->saldo_estoque < 1 ? 'table-danger' : '' ?>">
                            <td><?= html_escape($prod->codigo_produto) ?></td>
                            <td>
                                <?= html_escape($prod->nome) ?>
                                <?php if($prod->variacoes > 0): ?>
                                    <span class="badge bg-info"><?= $prod->variacoes ?> variações</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                R$ <?= number_format($prod->preco_compra, 2, ',', '.') ?>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold">R$ <?= number_format($prod->preco_venda, 2, ',', '.') ?></span>
                            </td>
                            <td class="text-end">
                                <?php 
                                $margem = (($prod->preco_venda - $prod->preco_compra) / $prod->preco_compra) * 100;
                                $cor_margem = $margem >= 50 ? 'text-success' : ($margem >= 30 ? 'text-warning' : 'text-danger');
                                ?>
                                <span class="<?= $cor_margem ?>">
                                    <?= number_format($margem, 2, ',', '.') ?>%
                                </span>
                            </td>
                            <td class="text-end">
                                <span class="badge bg-<?= $prod->saldo_estoque < 10 ? 'danger' : ($prod->saldo_estoque < 20 ? 'warning' : 'success') ?>">
                                    <?= number_format($prod->saldo_estoque, 0, ',', '.') ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if($prod->ativo): ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="<?= site_url("produtos/edit/{$prod->id}") ?>" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            title="Excluir"
                                            data-id="<?= $prod->id ?>">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Paginação -->
            <div class="mt-4">
                <?= $pagination ?? '' ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja excluir este produto permanentemente?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="#" class="btn btn-danger" id="confirmDelete">Excluir</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteModal = document.getElementById('deleteModal')
    deleteModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const id = button.getAttribute('data-id')
        document.getElementById('confirmDelete').href = `<?= site_url('produtos/delete/') ?>${id}`
    })
})
</script>
