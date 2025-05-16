<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="bi bi-tag"></i> Cadastrar Novo Cupom</h4>
    </div>
    <div class="card-body">
        <?= form_open('cupons/store', ['class' => 'needs-validation', 'novalidate' => '']) ?>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Produto Associado <span class="text-danger">*</span></label>
                    <select name="produto_id" class="form-select <?= form_error('produto_id') ? 'is-invalid' : '' ?>" required>
                        <option value="">Selecione um produto...</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= $p->id ?>" <?= set_select('produto_id', $p->id) ?>>
                                <?= html_escape($p->nome) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">
                        <?= form_error('produto_id') ?? 'Selecione um produto válido' ?>
                    </div>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Vincular a Pedido (Opcional)</label>
                    <select name="pedido_id" class="form-select">
                        <option value="">Nenhum pedido específico</option>
                        <?php foreach ($pedidos as $pd): ?>
                            <option value="<?= $pd->id ?>" <?= set_select('pedido_id', $pd->id) ?>>
                                Pedido #<?= $pd->id ?> - <?= html_escape($pd->cliente) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Valor do Cupom <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text">R$</span>
                        <input type="number" name="valor_cupom" 
                            class="form-control <?= form_error('valor_cupom') ? 'is-invalid' : '' ?>"
                            value="<?= set_value('valor_cupom') ?>"
                            min="0.01" step="0.01" required>
                        <div class="invalid-feedback">
                            <?= form_error('valor_cupom') ?? 'Valor inválido (mínimo R$ 0,01)' ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Data de Validade <span class="text-danger">*</span></label>
                    <input type="date" name="data_validade" 
                        class="form-control <?= form_error('data_validade') ? 'is-invalid' : '' ?>"
                        value="<?= set_value('data_validade') ?>"
                        min="<?= date('Y-m-d') ?>" required>
                    <div class="invalid-feedback">
                        <?= form_error('data_validade') ?? 'Data inválida (deve ser futura)' ?>
                    </div>
                </div>

                <div class="col-md-4 mb-3">
                    <label class="form-label">Tipo de Desconto</label>
                    <select name="tipo" class="form-select">
                        <option value="percentual" <?= set_select('tipo', 'percentual') ?>>Percentual</option>
                        <option value="valor_fixo" <?= set_select('tipo', 'valor_fixo') ?>>Valor Fixo</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Condições de Uso <small class="text-muted">(Máx. 100 caracteres)</small></label>
                <textarea name="condicao" class="form-control <?= form_error('condicao') ? 'is-invalid' : '' ?>"
                    rows="2" maxlength="100"><?= set_value('condicao') ?></textarea>
                <div class="invalid-feedback">
                    <?= form_error('condicao') ?? 'Limite de caracteres excedido' ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Salvar Cupom
                </button>
                <a href="<?= site_url('cupons') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>

        <?= form_close() ?>
    </div>
</div>
