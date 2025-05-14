// application/models/Pedido_model.php (extendendo)
class Pedido_model extends CI_Model {
    public function get_all() {
        $this->db->select('pedidos.*, clientes.nome AS cliente, produtos.nome AS produto');
        $this->db->join('clientes', 'clientes.id = pedidos.cliente_id');
        $this->db->join('produtos', 'produtos.id = pedidos.produto_id');
        return $this->db->get('pedidos')->result();
    }

    public function insert($data) {
        return $this->db->insert('pedidos', $data);
    }

	public function get_by_id($id) {
    return $this->db->get_where('pedidos', ['id' => $id])->row();
}


    public function update_status($id, $status) {
        return $this->db->update('pedidos', ['status' => $status], ['id' => $id]);
    }

    public function delete($id) {
        return $this->db->delete('pedidos', ['id' => $id]);
    }
}
