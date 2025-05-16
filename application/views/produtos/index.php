<div class="card shadow">
    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-box-seam"></i> Gestão de Produtos</h4>
        <div>
            <a href="<?= site_url('produtos/create') ?>" class="btn btn-light">
                <i class="bi bi-plus-circle"></i> Novo Produto
            </a>
            <a href="<?= site_url('estoque') ?>" class="btn btn-outline-light ms-2">
                <i class="bi bi-clipboard-data"></i> Controle de Estoque
            </a>
        </div>
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
                            <th>Produto</th>
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
                        <tr class="<?= $prod->saldo_estoque < 5 ? 'table-danger' : '' ?>">
                            <td><?= html_escape($prod->codigo_produto) ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if($prod->imagem): ?>
                                    <img src="<?= base_url('uploads/produtos/'.$prod->imagem) ?>" 
                                         class="img-thumbnail me-3" 
                                         style="width: 60px; height: 60px; object-fit: cover"
                                         alt="<?= html_escape($prod->nome) ?>">
                                    <?php endif; ?>
                                    <div>
                                        <div class="fw-bold"><?= html_escape($prod->nome) ?></div>
                                        <small class="text-muted"><?= html_escape($prod->categoria) ?></small>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end">
                                R$ <?= number_format($prod->preco_compra, 2, ',', '.') ?>
                            </td>
                            <td class="text-end">
                                <span class="fw-bold text-success">
                                    R$ <?= number_format($prod->preco_venda, 2, ',', '.') ?>
                                </span>
                            </td>
                            <td class="text-end">
                                <?php 
                                $margem = (($prod->preco_venda - $prod->preco_compra)/$prod->preco_compra)*100;
                                $cor = $margem >= 50 ? 'text-success' : ($margem >= 30 ? 'text-warning' : 'text-danger');
                                ?>
                                <span class="<?= $cor ?>">
                                    <?= number_format($margem, 1, ',', '.') ?>%
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="d-flex align-items-center justify-content-end">
                                    <span class="badge bg-<?= $prod->saldo_estoque > 0 ? 'success' : 'danger' ?> me-2">
                                        <?= number_format($prod->saldo_estoque, 0, ',', '.') ?>
                                    </span>
                                    <?php if($prod->saldo_estoque < 10): ?>
                                    <i class="bi bi-exclamation-triangle text-danger"></i>
                                    <?php endif; ?>
                                </div>
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
                                    <a href="<?= site_url("produtos/edit/".$prod->id) ?>" 
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
                <?= $this->pagination->create_links() ?>
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
                <a href="#" class="btn btn-danger" id="confirmDelete">Confirmar Exclusão</a>
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
