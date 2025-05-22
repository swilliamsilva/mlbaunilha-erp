<?php
namespace MlbaunilhaErp\Models;
use CI_Model;
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
	public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function obter_por_id($id) {
        return $this->db->get_where('clientes', ['id' => $id])->row();
    }
}
