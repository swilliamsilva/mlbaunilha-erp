<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-tags"></i> Gerenciamento de Cupons</h4>
        <a href="<?= site_url('cupons/create') ?>" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Novo Cupom
        </a>
    </div>

    <div class="card-body">
        <?php if(empty($cupons)): ?>
            <div class="alert alert-info">Nenhum cupom cadastrado</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Produto</th>
                            <th>Valor</th>
                            <th>Validade</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cupons as $c): ?>
                        <tr class="<?= (strtotime($c->data_validade) < time()) ? 'table-danger' : '' ?>">
                            <td><?= html_escape($c->codigo) ?></td>
                            <td><?= html_escape($c->produto) ?></td>
                            <td>
                                <?= ($c->tipo === 'percentual') 
                                    ? $c->valor_cupom.'%' 
                                    : 'R$ '.number_format($c->valor_cupom, 2, ',', '.') ?>
                            </td>
                            <td>
                                <?= date('d/m/Y', strtotime($c->data_validade)) ?>
                                <small class="text-muted d-block">
                                    <?= $this->cupom_model->dias_restantes($c->data_validade) ?>
                                </small>
                            </td>
                            <td>
                                <?php if(strtotime($c->data_validade) < time()): ?>
                                    <span class="badge bg-danger">Expirado</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="<?= site_url("cupons/edit/{$c->id}") ?>" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            title="Excluir"
                                            data-id="<?= $c->id ?>">
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
                Tem certeza que deseja excluir este cupom permanentemente?
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
        document.getElementById('confirmDelete').href = `<?= site_url('cupons/delete/') ?>${id}`
    })
})
</script>
