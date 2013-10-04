<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Create_Team_Member_Migration extends Migration {
	public function up() {
		$this->create_table('team_member', array(
			'id'               => array('type' => 'mediumint', 'unsigned' => true, 'auto_increment' => true),
			'user_id'          => array('type' => 'mediumint', 'unsigned' => true),
			'job_id'           => array('type' => 'tinyint', 'unsigned' => true, 'limit' => 2),
			'character_name'   => array('type' => 'varchar', 'limit' => 30),
			'character_gender' => array('type' => 'tinyint', 'unsigned' => true, 'limit' => 1),
			'created_at'       => array('type' => 'int', 'unsigned' => true),
			'updated_at'       => array('type' => 'int', 'unsigned' => true),
		), array(
			'primary_keys' => array('id'),
			'charset' => 'utf8'
		));
	}

	public function down() {
		$this->drop_table('team_member');
	}
}
