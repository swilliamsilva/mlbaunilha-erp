<?php
namespace MlbaunilhaErp\Models;
use CI_Model;
class Estoque_model extends CI_Model {

    public function listar_todos() {
        return $this->db->select('estoque.*, produtos.nome, produtos.codigo_produto')
                        ->join('produtos', 'produtos.id = estoque.produto_id')
                        ->get('estoque')
                        ->result();
    }

    public function atualizar_registro($dados) {
        // Verifica se já existe registro para o produto
        $existente = $this->db->get_where('estoque', ['produto_id' => $dados['produto_id']])->row();
        
        if ($existente) {
            return $this->db->update('estoque', $dados, ['id' => $existente->id]);
        }
        return $this->db->insert('estoque', $dados);
    }
}
