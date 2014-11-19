<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Disciplinas_model extends CI_Model {

	public function busca($atributo, $disciplina) {
		$res = $this->db->get_where("disciplinas", array($atributo => $disciplina[$atributo]))->row_array();
		return $res;
	}

	public function buscaTodos() {
		return $this->db->get("disciplinas")->result_array();
	}

	public function salva($disciplina) {
		$this->db->insert("disciplinas", $disciplina);
	}

	public function altera($disciplina) {
		$this->db->where('id', $disciplina["id"]);
		$dado = array(
			'nome' => $disciplina['nome'],
			'departamento_id' => $disciplina['departamento_id']
		);
		$res = $this->db->update("disciplinas", $dado);
		return $res;
	}

	public function remove($id) {
		$res = $this->db->delete("disciplinas", array('id' => $id));
		var_dump($this->db->last_query());
		return $res;
	}

}

/* End of file disciplinas_model.php */
/* Location: ./application/models/disciplinas_model.php */ ?>