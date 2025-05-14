// application/models/Cliente_model.php
class Cliente_model extends CI_Model {
    public function get_all() {
        return $this->db->get('clientes')->result();
    }

    public function insert($data) {
        return $this->db->insert('clientes', $data);
    }
}
