<?php
class Departamentos_model extends CI_Model {

	public function busca($atributo, $departamento) {
		$res = $this->db->get_where("departamentos", array($atributo => $departamento[$atributo]))->row_array();
		return $res;
	}

	public function buscaTodos() {
		return $this->db->get("departamentos")->result_array();
	}

	public function salva($departamento) {
		$this->db->insert("departamentos", $departamento);
	}

	public function altera($id, $nome) {
		$this->db->where('id', $id);
		$res = $this->db->update("departamentos", array('nome' => $nome));
		return $res;
	}

	public function remove($id) {
		$res = $this->db->delete("departamentos", array('id' => $id));
		return $res;
	}
}
