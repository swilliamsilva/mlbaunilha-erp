<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-list-check"></i> Gestão de Pedidos</h4>
        <a href="<?= site_url('pedidos/create') ?>" class="btn btn-light">
            <i class="bi bi-plus-circle"></i> Novo Pedido
        </a>
    </div>

    <div class="card-body">
        <?php if(empty($pedidos)): ?>
            <div class="alert alert-info">Nenhum pedido registrado</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Produto</th>
                            <th class="text-end">Quantidade</th>
                            <th class="text-end">Valor Total</th>
                            <th>Status</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pedidos as $p): ?>
                        <tr class="<?= $p->status === 'cancelado' ? 'table-danger' : '' ?>">
                            <td>#<?= $p->id ?></td>
                            <td><?= html_escape($p->cliente) ?></td>
                            <td><?= html_escape($p->produto) ?></td>
                            <td class="text-end"><?= number_format($p->quantidade_solicitada, 0, ',', '.') ?></td>
                            <td class="text-end">
                                R$ <?= number_format($p->preco * $p->quantidade_solicitada + $p->frete, 2, ',', '.') ?>
                            </td>
                            <td>
                                <?php 
                                $statusClass = [
                                    'pendente' => 'warning',
                                    'processando' => 'primary',
                                    'enviado' => 'info',
                                    'entregue' => 'success',
                                    'cancelado' => 'danger'
                                ];
                                ?>
                                <span class="badge bg-<?= $statusClass[strtolower($p->status)] ?>">
                                    <?= ucfirst($p->status) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="<?= site_url("pedidos/view/{$p->id}") ?>" 
                                       class="btn btn-sm btn-outline-primary"
                                       title="Detalhes">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <?php if($p->status !== 'cancelado'): ?>
                                    <a href="<?= site_url("pedidos/edit/{$p->id}") ?>" 
                                       class="btn btn-sm btn-outline-secondary"
                                       title="Editar">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#cancelModal"
                                            title="Cancelar"
                                            data-id="<?= $p->id ?>">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                    <?php endif; ?>
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

<!-- Modal de Cancelamento -->
<div class="modal fade" id="cancelModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Cancelamento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja cancelar este pedido? Esta ação não pode ser desfeita.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <a href="#" class="btn btn-danger" id="confirmCancel">Confirmar Cancelamento</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cancelModal = document.getElementById('cancelModal')
    cancelModal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget
        const id = button.getAttribute('data-id')
        document.getElementById('confirmCancel').href = `<?= site_url('pedidos/cancelar/') ?>${id}`
    })
})
</script>
