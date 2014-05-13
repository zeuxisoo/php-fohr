<?php

use Phpmig\Migration\Migration;

class CreateTeamMember extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $container['db']->query('
            CREATE TABLE team_member (
                 "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                 "user_id" INTEGER,
                 "job_name" TEXT(30,0),
                 "character_name" TEXT(30,0),
                 "character_gender" TEXT(5,0),
                 "create_at" INTEGER,
                 "update_at" INTEGER
            );
        ');
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();
        $container['db']->query('DROP TABLE IF EXISTS team_member;');
    }
}
