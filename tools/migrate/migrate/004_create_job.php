<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Create_Job_Migration extends Migration {
	public function up() {
		$this->create_table('job', array(
			'id'         => array('type' => 'mediumint', 'unsigned' => true, 'auto_increment' => true),
			'name'       => array('type' => 'varchar', 'limit' => 30),
			'image_boy'  => array('type' => 'varchar', 'limit' => 30),
			'image_girl' => array('type' => 'varchar', 'limit' => 30),
			'created_at' => array('type' => 'int', 'unsigned' => true),
			'updated_at' => array('type' => 'int', 'unsigned' => true),
		), array(
			'primary_keys' => array('id'),
			'charset' => 'utf8'
		));

		$jobs = array(
			array('name' => '戰士', 'image_boy' => 'warrior_boy.gif', 'image_girl' => 'warrior_girl.gif'),
			array('name' => '法師', 'image_boy' => 'socerer_boy.gif', 'image_girl' => 'socerer_girl.gif'),
			array('name' => '牧師', 'image_boy' => 'pastor_boy.gif',  'image_girl' => 'pastor_girl.gif'),
			array('name' => '獵人', 'image_boy' => 'hunter_boy.gif',  'image_girl' => 'hunter_girl.gif'),
		);

		foreach($jobs as $job) {
			Database::instance()->update("
				INSERT INTO ".DataBase::table_prefix("job")."
					(name, image_boy, image_girl, created_at, updated_at)
				VALUES
					('".$job['name']."', '".$job['image_boy']."', '".$job['image_girl']."', ".time().", ".time().")
			");
		}
	}

	public function down() {
		$this->drop_table('job');
	}
}
