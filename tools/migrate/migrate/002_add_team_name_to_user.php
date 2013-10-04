<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Add_Team_Name_To_User_Migration extends Migration {
	public function up() {
		$this->add_columns('user', array(
			'team_name' => array('type' => 'varchar', 'limit' => 30, 'AFTER' => 'time'),
		));
	}

	public function down() {
		$this->drop_columns('user', 'team_name');
	}
}
