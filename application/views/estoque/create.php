<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="bi bi-box-seam"></i> Cadastrar Variação de Estoque</h4>
    </div>
    <div class="card-body">
        <?= form_open('estoque/store', ['class' => 'needs-validation', 'novalidate' => '']) ?>
            
            <div class="mb-3">
                <label class="form-label">Selecione o Produto</label>
                <select name="produto_id" class="form-select <?= form_error('produto_id') ? 'is-invalid' : '' ?>" required>
                    <option value="">Selecione...</option>
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

            <div class="mb-3">
                <label class="form-label">Variação/Descrição</label>
                <?= form_input([
                    'name' => 'variacao',
                    'class' => 'form-control ' . (form_error('variacao') ? 'is-invalid' : ''),
                    'value' => set_value('variacao'),
                    'placeholder' => 'Ex: Tamanho M, Cor Vermelha...',
                    'maxlength' => 50
                ]) ?>
                <div class="invalid-feedback">
                    <?= form_error('variacao') ?? 'Informe uma descrição válida (máx. 50 caracteres)' ?>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Saldo Inicial</label>
                <?= form_input([
                    'type' => 'number',
                    'name' => 'saldo',
                    'class' => 'form-control ' . (form_error('saldo') ? 'is-invalid' : ''),
                    'value' => set_value('saldo', 0),
                    'min' => 0,
                    'step' => 1,
                    'required' => ''
                ]) ?>
                <div class="invalid-feedback">
                    <?= form_error('saldo') ?? 'Informe um valor numérico válido' ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> Salvar Registro
                </button>
                <a href="<?= site_url('estoque') ?>" class="btn btn-secondary">
                    <i class="bi bi-x-circle"></i> Cancelar
                </a>
            </div>
            
        <?= form_close() ?>
    </div>
</div>
