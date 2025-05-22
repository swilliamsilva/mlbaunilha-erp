<?php
namespace MlbaunilhaErp\Models;
use CI_Model;
use Exception;

class Pedido_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_all() {
        $this->db->select('pedidos.*, 
            clientes.nome AS cliente, 
            produtos.nome AS produto,
            produtos.preco_venda AS preco_unitario');
        $this->db->join('clientes', 'clientes.id = pedidos.cliente_id', 'left');
        $this->db->join('produtos', 'produtos.id = pedidos.produto_id', 'left');
        $this->db->where('pedidos.deleted_at', null); // Soft delete
        return $this->db->get('pedidos')->result();
    }

    public function insert($data) {
        $this->db->insert('pedidos', $data);
        return $this->db->insert_id(); // Retorna o ID inserido
    }

    public function get_by_id($id) {
        $this->db->where('id', $id);
        $this->db->where('deleted_at', null); // Soft delete
        return $this->db->get('pedidos')->row();
    }

    public function update_status($id, $status) {
		// Implementação original em inglês
		$status_validos = ['pendente', 'processando', 'enviado', 'entregue', 'cancelado'];
		
		if(!in_array(strtolower($status), $status_validos)) {
			throw new Exception('Status inválido');
		}
	
		return $this->db->update('pedidos', 
			['status' => $status, 'updated_at' => date('Y-m-d H:i:s')],
			['id' => $id]
		);
	}
    public function delete($id) {
        // Soft delete ao invés de apagar fisicamente
        return $this->db->update('pedidos', 
            ['deleted_at' => date('Y-m-d H:i:s')],
            ['id' => $id]
        );
    }

    public function cancelar($id) {
        $this->db->trans_start();
        
        // Atualiza status
        $this->update_status($id, 'cancelado');
        
        // Atualiza estoque
        $pedido = $this->get_by_id($id);
        $this->db->query('UPDATE produtos 
                          SET estoque = estoque + ? 
                          WHERE id = ?', 
                          [$pedido->quantidade_solicitada, $pedido->produto_id]);
        
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

	public function obter_por_id($id) {
		return $this->get_by_id($id); // Reaproveita a função existente
	}

	public function listar_recentes($limite = 10) {
		$this->db->select('id, codigo_pedido, data_pedido, cliente_id, status')
				 ->where('deleted_at', null)
				 ->order_by('data_pedido', 'DESC')
				 ->limit($limite);
		
		return $this->db->get('pedidos')->result();
	}

	public function atualizar_status($pedido_id, $status) {
		// Método alternativo para compatibilidade com código em português
		return $this->update_status($pedido_id, $status);
	}

	
}
