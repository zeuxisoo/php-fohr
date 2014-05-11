<?php

use Phpmig\Migration\Migration;

class CreateUserTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();
        $container['db']->query('
            CREATE TABLE user (
                 "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                 "email" TEXT(80,0),
                 "password" TEXT(64,0),
                 "money" INTEGER DEFAULT 0,
                 "time" INTEGER,
                 "signin_token" TEXT(32,0),
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
        $container['db']->query('DROP TABLE IF EXISTS user;');
    }
}
