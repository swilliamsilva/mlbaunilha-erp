<?php
class Produto_model extends CI_Model {

    // Lista de campos permitidos para inserção/atualização (whitelist)
    private $allowed_fields = [
        'nome',
        'descricao',
        'preco',
        // Adicione outros campos permitidos da tabela aqui
    ];

    public function __construct() {
        parent::__construct();
    }

    // Lista todos os produtos
    public function listar_todos() {
        return $this->db->get('produtos')->result();
    }

    // Cadastra um novo produto com filtragem de campos
    public function cadastrar($dados) {
        // Filtra apenas os campos permitidos
        $dados_filtrados = $this->filtrar_campos($dados);
        
        // Validação básica (opcional, mas recomendado)
        if (empty($dados_filtrados['nome'])) {
            return false;
        }

        $this->db->insert('produtos', $dados_filtrados);
        return $this->db->affected_rows() > 0;
    }

    // Obtém um produto pelo código
    public function obter_por_codigo($codigo) {
        return $this->db->get_where('produtos', ['codigo_produto' => $codigo])->row();
    }

    // Atualiza um produto (método adicional para completar o CRUD)
    public function atualizar($codigo, $dados) {
        $dados_filtrados = $this->filtrar_campos($dados);
        $this->db->where('codigo_produto', $codigo);
        $this->db->update('produtos', $dados_filtrados);
        return $this->db->affected_rows() > 0;
    }

    // Deleta um produto (método adicional)
    public function deletar($codigo) {
        $this->db->where('codigo_produto', $codigo);
        $this->db->delete('produtos');
        return $this->db->affected_rows() > 0;
    }
	public function listar_ativos() {
		return $this->db->get_where('produtos', ['ativo' => 1])->result();
	}

	public function obter_por_id($id) {
		return $this->db->get_where('produtos', ['id' => $id])->row();
	}
	public function edit($id) {
		// Lógica para edição
	}
	
	public function delete($id) {
		// Lógica para exclusão
	}

    // --- Métodos privados para segurança ---
    
    // Filtra campos não permitidos
    private function filtrar_campos($dados) {
        return array_intersect_key($dados, array_flip($this->allowed_fields));
    }

    // Validação de dados (personalize conforme sua regra de negócio)
    private function validar_dados($dados) {
        // Exemplo: verifica se o nome está preenchido
        if (empty($dados['nome'])) {
            return false;
        }
        return true;
    }
}
