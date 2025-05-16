<div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="bi bi-cart3"></i> Seu Carrinho</h4>
    </div>
    
    <div class="card-body">
        <?php if(empty($itens)): ?>
            <div class="alert alert-info">Seu carrinho está vazio</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th class="text-center">Quantidade</th>
                            <th class="text-end">Preço Unitário</th>
                            <th class="text-end">Subtotal</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($itens as $i): ?>
                        <tr>
                            <td><?= html_escape($i['nome']) ?></td>
                            <td class="text-center">
                                <div class="input-group" style="max-width: 120px">
                                    <?= form_open('carrinho/atualizar/'.html_escape($i['id']), ['class' => 'update-form']) ?>
                                        <input type="number" name="quantidade" 
                                               value="<?= html_escape($i['quantidade']) ?>" 
                                               min="1" max="99" 
                                               class="form-control text-center"
                                               onchange="this.form.submit()">
                                    <?= form_close() ?>
                                </div>
                            </td>
                            <td class="text-end">R$ <?= number_format($i['preco'], 2, ',', '.') ?></td>
                            <td class="text-end">R$ <?= number_format($i['quantidade'] * $i['preco'], 2, ',', '.') ?></td>
                            <td class="text-center">
                                <?= form_open('carrinho/remover/'.html_escape($i['id'])) ?>
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                <?= form_close() ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <div class="card-footer">
        <div class="row">
            <div class="col-md-6 mb-3">
                <?php if(!empty($itens)): ?>
                <a href="<?= site_url('produtos') ?>" class="btn btn-outline-primary">
                    <i class="bi bi-arrow-left"></i> Continuar Comprando
                </a>
                <?php endif; ?>
            </div>
            
            <div class="col-md-6">
                <div class="cart-summary">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <strong>R$ <?= number_format($subtotal, 2, ',', '.') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Frete:
                            <i class="bi bi-info-circle" 
                               data-bs-toggle="tooltip" 
                               title="Grátis para compras acima de R$ 200"></i>
                        </span>
                        <strong>R$ <?= number_format($frete, 2, ',', '.') ?></strong>
                    </div>
                    <div class="d-flex justify-content-between fs-5 text-success">
                        <span>Total:</span>
                        <strong>R$ <?= number_format($total, 2, ',', '.') ?></strong>
                    </div>
                    
                    <div class="d-grid gap-2 mt-4">
                        <?php if(!empty($itens)): ?>
                        <a href="<?= site_url('checkout') ?>" class="btn btn-success btn-lg">
                            <i class="bi bi-credit-card"></i> Finalizar Compra
                        </a>
                        <button type="button" 
                                class="btn btn-outline-danger" 
                                data-bs-toggle="modal" 
                                data-bs-target="#clearCartModal">
                            <i class="bi bi-trash"></i> Limpar Carrinho
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div class="modal fade" id="clearCartModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Limpeza</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Tem certeza que deseja remover todos os itens do carrinho?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="<?= site_url('carrinho/limpar') ?>" class="btn btn-danger">Confirmar</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ativar tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
})
</script>
