// application/models/Produto_model.php
class Produto_model extends CI_Model {
    public function get_all() {
        return $this->db->get('produtos')->result();
    }

    public function insert($data) {
        return $this->db->insert('produtos', $data);
    }
}
