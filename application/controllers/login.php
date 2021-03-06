<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

	public function index() {
		$this->load->template('login/index');
	}

	public function autenticar() {
		$this->load->model("usuarios_model");
		$login = $this->input->post("login");
		$senha = $this->input->post("senha");
		$usuario = $this->usuarios_model->buscaPorLoginESenha($login, $senha);
		$user_type = $this->usuarios_model->getUserType($usuario['id']);
		
		// Load the Module model
		$this->load->model("module_model");
		$registered_permissions = $this->module_model->getUserPermissionNames($usuario['id']);

		$userData = array('user' => $usuario,'user_type' => $user_type, 'user_permissions' => $registered_permissions);

		if ($usuario) {
			$this->session->set_userdata("usuario_logado", $userData);
		} else {
			$this->session->set_flashdata("danger", "Usuário ou senha inválida");
		}

		redirect('/');
	}

	public function logout() {
		$this->session->unset_userdata("usuario_logado", $usuario);
		$this->session->set_flashdata("success", "Usuário deslogado");
		redirect('/');
	}
}