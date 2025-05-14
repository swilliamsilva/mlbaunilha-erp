// application/models/Cupom_model.php
class Cupom_model extends CI_Model {
    public function get_all() {
        $this->db->select('cupons.*, produtos.nome AS produto');
        $this->db->join('produtos', 'produtos.id = cupons.produto_id');
        return $this->db->get('cupons')->result();
    }

    public function insert($data) {
        return $this->db->insert('cupons', $data);
    }
}
