<div class="card shadow-lg">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="bi bi-cart-plus"></i> Novo Pedido</h4>
    </div>
    
    <div class="card-body">
        <?= form_open('pedidos/store', ['id' => 'pedidoForm', 'class' => 'needs-validation', 'novalidate' => '']) ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Cliente <span class="text-danger">*</span></label>
                    <select name="cliente_id" class="form-select <?= form_error('cliente_id') ? 'is-invalid' : '' ?>" required>
                        <option value="">Selecione um cliente...</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c->id ?>" <?= set_select('cliente_id', $c->id) ?>>
                                <?= html_escape($c->nome) ?> (<?= $c->cpf ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= form_error('cliente_id') ?? 'Selecione um cliente válido' ?>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Produto <span class="text-danger">*</span></label>
                    <select name="produto_id" id="produtoSelect" class="form-select <?= form_error('produto_id') ? 'is-invalid' : '' ?>" required>
                        <option value="">Selecione um produto...</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= $p->id ?>" 
                                data-preco="<?= $p->preco_venda ?>"
                                data-estoque="<?= $p->saldo_estoque ?>"
                                <?= set_select('produto_id', $p->id) ?>>
                                <?= html_escape($p->nome) ?> (Estoque: <?= $p->saldo_estoque ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= form_error('produto_id') ?? 'Selecione um produto válido' ?>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Data Entrega <span class="text-danger">*</span></label>
                    <input type="date" name="data_entrega_prevista" 
                        class="form-control <?= form_error('data_entrega_prevista') ? 'is-invalid' : '' ?>"
                        min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                        value="<?= set_value('data_entrega_prevista') ?>"
                        required>
                    <div class="invalid-feedback">
                        <?= form_error('data_entrega_prevista') ?? 'Data inválida' ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Quantidade <span class="text-danger">*</span></label>
                    <input type="number" name="quantidade_solicitada" id="quantidade"
                        class="form-control <?= form_error('quantidade_solicitada') ? 'is-invalid' : '' ?>"
                        min="1" value="<?= set_value('quantidade_solicitada', 1) ?>"
                        required>
                    <small class="form-text text-muted estoque-info"></small>
                    <div class="invalid-feedback">
                        <?= form_error('quantidade_solicitada') ?? 'Quantidade inválida' ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Preço Unitário <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="number" name="preco" id="preco"
                            class="form-control <?= form_error('preco') ? 'is-invalid' : '' ?>"
                            step="0.01" value="<?= set_value('preco') ?>"
                            required>
                        <div class="invalid-feedback">
                            <?= form_error('preco') ?? 'Preço inválido' ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Frete</label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="number" name="frete" 
                            class="form-control <?= form_error('frete') ? 'is-invalid' : '' ?>"
                            step="0.01" value="<?= set_value('frete', 0) ?>">
                        <div class="invalid-feedback">
                            <?= form_error('frete') ?? 'Valor de frete inválido' ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Cupom de Desconto</label>
                    <input type="text" name="cupom" 
                        class="form-control <?= form_error('cupom') ? 'is-invalid' : '' ?>"
                        placeholder="Código do cupom"
                        value="<?= set_value('cupom') ?>">
                    <div class="invalid-feedback">
                        <?= form_error('cupom') ?? 'Cupom inválido' ?>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle"></i> Registrar Pedido
                </button>
                <a href="<?= site_url('pedidos') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>

        <?= form_close() ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const produtoSelect = document.getElementById('produtoSelect');
    const precoInput = document.getElementById('preco');
    const quantidadeInput = document.getElementById('quantidade');
    const estoqueInfo = document.querySelector('.estoque-info');

    function atualizarPreco() {
        const selectedOption = produtoSelect.options[produtoSelect.selectedIndex];
        if(selectedOption && selectedOption.value) {
            precoInput.value = selectedOption.dataset.preco;
            estoqueInfo.textContent = `Estoque disponível: ${selectedOption.dataset.estoque}`;
            quantidadeInput.setAttribute('max', selectedOption.dataset.estoque);
        }
    }

    produtoSelect.addEventListener('change', atualizarPreco);
    quantidadeInput.addEventListener('input', function() {
        const max = parseInt(quantidadeInput.getAttribute('max'));
        if(this.value > max) {
            this.setCustomValidity(`Quantidade máxima: ${max}`);
        } else {
            this.setCustomValidity('');
        }
    });

    // Inicialização
    atualizarPreco();
});
</script>
