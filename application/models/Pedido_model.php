// application/models/Pedido_model.php
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
}
