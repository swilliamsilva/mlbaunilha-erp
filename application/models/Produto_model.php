// application/models/Produto_model.php (com método auxiliar)
class Produto_model extends CI_Model {
    public function get_all() {
        return $this->db->get('produtos')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('produtos', ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert('produtos', $data);
    }
}
