<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Create_User_Migration extends Migration {
	public function up() {
		$this->create_table('user', array(
			'id'           => array('type' => 'mediumint', 'unsigned' => true, 'auto_increment' => true),
			'email'        => array('type' => 'varchar', 'limit' => 80),
			'password'     => array('type' => 'varchar', 'limit' => 64),
			'money'        => array('type' => 'int', 'unsigned' => true),
			'time'         => array('type' => 'int', 'unsigned' => true),
			'signin_token' => array('type' => 'varchar', 'limit' => 32),
			'created_at'   => array('type' => 'int', 'unsigned' => true),
			'updated_at'   => array('type' => 'int', 'unsigned' => true),
		), array(
			'primary_keys' => array('id'),
			'charset' => 'utf8'
		));
	}

	public function down() {
		$this->drop_table('user');
	}
}
