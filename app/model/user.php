<?php
namespace App\Model;

class User extends Base {
	public static $_table = 'user';

	public static function existsEmail($email) {
		return \ORM::for_table(self::$_table)->where_equal('email', $email)->count() >= 1;
	}

	public static function existsTeamName($team_name) {
		return \ORM::for_table(self::$_table)->where_equal('team_name', $team_name)->count() >= 1;
	}

	public static function findByEmail($email) {
		return \ORM::for_table(self::$_table)->where_equal('email', $email)->findOne();
	}
}
