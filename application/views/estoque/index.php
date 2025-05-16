<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-boxes"></i> Controle de Estoque</h4>
        <a href="<?= site_url('estoque/create') ?>" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Nova Variação
        </a>
    </div>
    
    <div class="card-body">
        <?php if(empty($estoques)): ?>
            <div class="alert alert-info">Nenhum registro encontrado</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Produto</th>
                            <th>Variação</th>
                            <th class="text-end">Estoque</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($estoques as $e): ?>
                            <tr class="<?= $e->saldo < 10 ? 'table-danger' : '' ?>">
                                <td><?= html_escape($e->nome) ?></td>
                                <td><?= html_escape($e->variacao) ?></td>
                                <td class="text-end">
                                    <span class="badge bg-<?= $e->saldo > 20 ? 'success' : 'warning' ?>">
                                        <?= number_format($e->saldo, 0, ',', '.') ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="<?= site_url("estoque/edit/{$e->id}") ?>" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Editar">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal"
                                                title="Excluir"
                                                data-id="<?= $e->id ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
                Tem certeza que deseja excluir este registro de estoque?
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
        document.getElementById('confirmDelete').href = `<?= site_url('estoque/delete/') ?>${id}`
    })
})
</script>
