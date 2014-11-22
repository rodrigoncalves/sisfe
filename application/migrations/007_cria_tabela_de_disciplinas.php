<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Cria_tabela_de_disciplinas extends CI_Migration {

	public function up() {
		$this->dbforge->add_field(array(
			'id' => array('type' => 'INT','auto_increment' => true),
			'nome' => array('type' => 'varchar(255)'),
			'departamento_id' => array('type' => 'INT')
		));
		$this->dbforge->add_key('id', true);
		$this->dbforge->add_key('departamento_id', true);
		$this->dbforge->create_table('disciplinas');
	}

	public function down() {
		$this->dbforge->drop_table('disciplinas');
	}

}

/* End of file 007.cria_tabela_de_disciplinas.php */
/* Location: ./application/migrations/007.cria_tabela_de_disciplinas.php */
