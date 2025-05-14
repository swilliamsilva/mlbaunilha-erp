// application/models/Estoque_model.php
class Estoque_model extends CI_Model {
    public function get_all() {
        $this->db->select('estoque.*, produtos.nome');
        $this->db->join('produtos', 'produtos.id = estoque.produto_id');
        return $this->db->get('estoque')->result();
    }

    public function insert($data) {
        return $this->db->insert('estoque', $data);
    }
}
