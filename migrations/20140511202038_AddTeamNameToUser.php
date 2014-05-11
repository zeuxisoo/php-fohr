<?php

use Phpmig\Migration\Migration;

class AddTeamNameToUser extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $container = $this->getContainer();

        // Rename table
        $container['db']->query('ALTER TABLE user RENAME TO _user_old;');

        // Create new table (include team_name)
        $container['db']->query('
            CREATE TABLE user (
                 "id" INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
                 "email" TEXT(80,0),
                 "password" TEXT(64,0),
                 "money" INTEGER DEFAULT 0,
                 "time" INTEGER,
                 "team_name" TEXT(30,0),
                 "signin_token" TEXT(32,0),
                 "create_at" INTEGER,
                 "update_at" INTEGER
            );
        ');

        // Update index
        $container['db']->query('INSERT INTO sqlite_sequence (name, seq) VALUES ("user", "1");');

        // Copy data from renamed table into new table
        $container['db']->query('
            INSERT INTO user ("id", "email", "password", "money", "time", "signin_token", "create_at", "update_at")
            SELECT "id", "email", "password", "money", "time", "signin_token", "create_at", "update_at" FROM _user_old;
        ');

        // Drop renamed table
        $container['db']->query('DROP TABLE IF EXISTS _user_old;');
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $container = $this->getContainer();

        // Rename table
        $container['db']->query('ALTER TABLE user RENAME TO _user_old;');

        // Create new table (remove team_name)
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

        // Update index
        $container['db']->query('INSERT INTO sqlite_sequence (name, seq) VALUES ("user", "1");');

        // Copy data from renamed table into new table
        $container['db']->query('
            INSERT INTO user ("id", "email", "password", "money", "time", "signin_token", "create_at", "update_at")
            SELECT "id", "email", "password", "money", "time", "signin_token", "create_at", "update_at" FROM _user_old;
        ');

        // Drop renamed table
        $container['db']->query('DROP TABLE IF EXISTS _user_old;');
    }
}
