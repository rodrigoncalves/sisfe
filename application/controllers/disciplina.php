<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Disciplina extends CI_Controller {

	public function formulario() {
		$this->load->model("disciplinas_model");
		$this->load->model("departamentos_model");
		$disciplinas = $this->disciplinas_model->buscaTodos();
		$departamentos = $this->departamentos_model->buscaTodos();
		$opcoes = array();
		foreach ($departamentos as $departamento) {
			array_push($opcoes, $departamento["nome"]);
		}

		$dados = array(
			"disciplinas" => $disciplinas,
			"opcoes" => $opcoes
		);
		$this->load->template("disciplinas/formulario", $dados);
	}

	public function formulario_altera($id) {
		autoriza();
		$this->load->model("disciplinas_model");
		$this->load->model("departamentos_model");
		$disciplina = array('id' => $id);
		$disciplina = $this->disciplinas_model->busca("id", $disciplina);
		$departamentos = $this->departamentos_model->buscaTodos();
		$opcoes = array();
		foreach ($departamentos as $departamento) {
			array_push($opcoes, $departamento["nome"]);
		}
		$departamento["id"] = $disciplina["departamento_id"];
		$selecionado = $this->departamentos_model->busca("id", $departamento);

		$dados = array(
			'disciplina' => $disciplina,
			'departamentos' => $opcoes,
			'selecionado' => $selecionado["id"]-1
		);
		$this->load->template("disciplinas/formulario_altera", $dados);
	}

	public function novo() {
		$usuarioLogado = autoriza();
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome da disciplina", "required");
		$this->form_validation->set_error_delimiters("<p class='alert alert-danger'>", "</p>");
		$sucesso = $this->form_validation->run();


		if ($sucesso) {
			$usuarioLogado = autoriza();
			$nome = $this->input->post("nome");
			$disciplina = array('nome' => $nome);

			$this->load->model("disciplinas_model");
			$departamentoExiste = $this->disciplinas_model->busca("nome", $disciplina);

			if ($departamentoExiste) {
				$this->session->set_flashdata("danger", "Esta disciplina já está cadastrada");
			} else if ($this->disciplinas_model->salva($disciplina)) {
				$this->session->set_flashdata("success", "Disciplina \"$nome\" salvo com sucesso");
			}
		}

		redirect("disciplinas");
	}

	public function altera() {
		autoriza();
		$id = $this->input->post("disciplina_id");
		$nome = $this->input->post("nome");
		$departamento_id = $this->input->post("departamentos");
		$this->load->model("disciplinas_model");

		$disciplina = array(
			'id' => $id,
			'nome' => $nome,
			'departamento_id' => $departamento_id+1
		);

		if ($this->disciplinas_model->altera($disciplina)) {
			$this->session->set_flashdata("success", "Disciplina alterada para \"$nome\".");
			redirect("disciplinas");
		} else {
			$this->session->set_flashdata("danger", "Esta disciplina não pôde ser alterada.");
			redirect("disciplinas/{$id}");
		}
	}

	public function remove() {
		autoriza();
		$id = $this->input->post("disciplina_id");
		$this->load->model("disciplinas_model");
		$disciplina = array("id" => $id);
		$disciplina = $this->disciplinas_model->busca("id", $disciplina);

		if ($this->disciplinas_model->remove($id)) {
			$this->session->set_flashdata("success", "Disciplina \"{$disciplina['nome']}\" foi removida");
		}

		redirect("disciplinas");
	}
}
