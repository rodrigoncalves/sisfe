<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Usuario extends CI_Controller {
	
	public function formulario() {
		$this->load->model('usuarios_model');
		$usuarios = $this->usuarios_model->buscaTodos();

		if ($usuarios && !$this->session->userdata('usuario_logado')) {
			$this->session->set_flashdata("danger", "Você deve ter permissão do administrador. Faça o login.");
			redirect('login');
		} else {
			$this->load->template("usuario/formulario");
		}
	}
	
	public function formulario_entrada() {
	
		$this->load->template("usuario/formulario_entrada");
		
	}
	

	public function conta() {
		$usuarioLogado = autoriza();
		$dados = array("usuario" => $usuarioLogado);
		$this->load->template("usuario/conta", $dados);
	}

	function alpha_dash_space($str){
	    return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? FALSE : TRUE;
	}
	 
	public function novo() {
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome", "trim|xss_clean|callback__alpha_dash_space");
		$this->form_validation->set_rules("cpf", "CPF", "required|valid_cpf");
		$this->form_validation->set_rules("email", "E-mail", "required|valid_email");
		$this->form_validation->set_rules("login", "Login", "required|alpha_dash");
		$this->form_validation->set_rules("senha", "Senha", "required");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		if ($success) {
			$nome  = $this->input->post("nome");
			$cpf   = $this->input->post("cpf");
			$email = $this->input->post("email");
			$tipo  = $this->input->post("userType");
			$login = $this->input->post("login");
			$senha = md5($this->input->post("senha"));
			
			$usuario = array(
				'name'     => $nome,
				'cpf'      => $cpf,
				'email'    => $email,
				'login'    => $login,
				'password' => $senha
			);

			$this->load->model("usuarios_model");
			$usuarioExiste = $this->usuarios_model->buscaPorLoginESenha($login);

			if ($usuarioExiste) {
				$this->session->set_flashdata("danger", "Usuário já existe no sistema");
				redirect("usuario/formulario");
			} else {
				$this->usuarios_model->salva($usuario);
				$this->usuarios_model->saveType($usuario, $tipo);
				$this->session->set_flashdata("success", "Usuário \"{$usuario['login']}\" cadastrado com sucesso");
				redirect("/");
			}
		} else {
			$this->load->template("usuario/formulario");
		}
	}

	public function altera() {
		$usuarioLogado = autoriza();
		$this->load->library("form_validation");
		$this->form_validation->set_rules("nome", "Nome", "alpha");
		$this->form_validation->set_rules("email", "E-mail", "valid_email");
		$this->form_validation->set_error_delimiters("<p class='alert-danger'>", "</p>");
		$success = $this->form_validation->run();

		if ($success) {
			$nome = $this->input->post("nome");
			$email = $this->input->post("email");
			$login = $usuarioLogado['login'];
			$senha = md5($this->input->post("senha"));
			$nova_senha = md5($this->input->post("nova_senha"));

			$senha_em_branco = 'd41d8cd98f00b204e9800998ecf8427e';

			if ($nova_senha != $senha_em_branco && $senha != $usuarioLogado['senha']) {
				$this->session->set_flashdata("danger", "Senha atual incorreta");
				redirect("usuario/conta");
			} else if ($nova_senha == $senha_em_branco) {
				$nova_senha = $usuarioLogado['senha'];
			}

			if ($nome == "") {
				$nome = $usuarioLogado['nome'];
			}

			if ($email == "") {
				$email = $usuarioLogado['email'];
			}

			$usuario = array(
				'nome' => $nome,
				'email' => $email,
				'login' => $login,
				'senha' => $nova_senha
			);

			$this->load->model("usuarios_model");
			$alterado = $this->usuarios_model->altera($usuario);
			if ($alterado && $usuarioLogado != $usuario) {
				$this->session->set_userdata('usuario_logado', $usuario);
				$this->session->set_flashdata("success", "Os dados foram alterados");
			} else if (!$alterado){
				$this->session->set_flashdata("danger", "Os dados não foram alterados");
			}

			redirect("usuario/conta");
		} else {
			$this->load->template("usuario/conta");
		}
	}

	public function remove() {
		$usuarioLogado = autoriza();
		$this->load->model("usuarios_model");
		if ($this->usuarios_model->remove($usuarioLogado)) {
			$this->session->unset_userdata('usuario_logado');
			$this->session->set_flashdata("success", "Usuário \"{$usuarioLogado['login']}\" removido");
			redirect("login");
		} else {
			redirect("usuario/conta");
		}
		
	}


	public function getUserTypes(){
		
		// $usuarioLogado = autoriza();
		$this->load->model("usuarios_model");
		$user_types = $this->usuarios_model->getUserTypes();
		
		$user_types_to_array = $this->turnUserTypesToArray($user_types);

		return $user_types_to_array;
	}
	
	/**
	  * Join the id's and names of user types into an array as key => value.
	  * Used to the user type form
	  * @param $user_types - The array that contains the tuples of user_type
	  * @return An array with the id's and user types names as key => value
	  */
	private function turnUserTypesToArray($user_types){
		// Quantity of user types registered
		$quantity_of_user_types = sizeof($user_types);

		for($cont = 0; $cont < $quantity_of_user_types; $cont++){
			$keys[$cont] = $user_types[$cont]['id_type'];
			$values[$cont] = $user_types[$cont]['type_name'];
		}

		$form_user_types = array_combine($keys, $values);

		return $form_user_types;
	}
	
}