// models adicionados:
class Cliente_model extends CI_Model {
    public function get_all() {
        return $this->db->get('clientes')->result();
    }

    public function get_by_id($id) {
        return $this->db->get_where('clientes', ['id' => $id])->row();
    }

    public function insert($data) {
        return $this->db->insert('clientes', $data);
    }
}
