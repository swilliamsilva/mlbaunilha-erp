public function up() {
    // Executar SQL raw para MSSQL
    $this->db->query("
        BEGIN TRANSACTION;
        
        -- Adicionar colunas com constraints
        ALTER TABLE pedidos 
        ADD created_at DATETIME CONSTRAINT DF_pedidos_created_at DEFAULT GETDATE(),
        ADD updated_at DATETIME,
        ADD deleted_at DATETIME NULL;
        
        -- Criar trigger para updated_at
        CREATE TRIGGER tr_pedidos_updated_at
        ON pedidos
        AFTER UPDATE
        AS
        BEGIN
            UPDATE pedidos
            SET updated_at = GETDATE()
            FROM inserted
            WHERE pedidos.id = inserted.id;
        END;
        
        COMMIT TRANSACTION;
    ");
}
