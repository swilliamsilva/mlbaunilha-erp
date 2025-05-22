<?php
namespace MlbaunilhaErp\Models;
use CI_Model;
use DateTime;
class Cupom_model extends CI_Model {

    public function listar_todos() {
        return $this->db->select('cupons.*, produtos.nome as produto_nome')
                        ->join('produtos', 'produtos.id = cupons.produto_id', 'left')
                        ->get('cupons')
                        ->result();
    }

    public function cadastrar($dados) {
        $this->db->insert('cupons', $dados);
        return $this->db->affected_rows() > 0;
    }
	
	public function dias_restantes($data_validade) {
		$hoje = new DateTime();
		$validade = new DateTime($data_validade);
		$intervalo = $hoje->diff($validade);
		
		if($validade < $hoje) {
			return 'Expirado há '.$intervalo->days.' dias';
		}
		return $intervalo->days.' dias restantes';
	}
}
