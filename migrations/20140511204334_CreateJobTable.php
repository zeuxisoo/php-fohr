<?php

use Phpmig\Migration\Migration;

class CreateJobTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $container['db']->query('
            CREATE TABLE job (
                 "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                 "name" TEXT(30,0),
                 "image_boy" TEXT(30,0),
                 "image_girl" TEXT(30,0),
                 "create_at" INTEGER,
                 "update_at" INTEGER
            );
        ');

        $jobs = array(
            array('name' => '戰士', 'image_boy' => 'warrior_boy.gif', 'image_girl' => 'warrior_girl.gif'),
            array('name' => '法師', 'image_boy' => 'socerer_boy.gif', 'image_girl' => 'socerer_girl.gif'),
            array('name' => '牧師', 'image_boy' => 'pastor_boy.gif',  'image_girl' => 'pastor_girl.gif'),
            array('name' => '獵人', 'image_boy' => 'hunter_boy.gif',  'image_girl' => 'hunter_girl.gif'),
        );

        foreach($jobs as $job) {
            $container['db']->query("
                INSERT INTO job
                    (name, image_boy, image_girl, create_at, update_at)
                VALUES
                    ('".$job['name']."', '".$job['image_boy']."', '".$job['image_girl']."', ".time().", ".time().")
            ");
        }
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        $container['db']->query('DROP TABLE IF EXISTS job;');
    }
}
