<?php
namespace App\Model;

class User extends Base {
	public static $_table = 'user';

	public static function exists_email($email) {
		return \ORM::for_table(self::$_table)->where_equal('email', $email)->count() >= 1;
	}

	public static function find_by_email($email) {
		return \ORM::for_table(self::$_table)->where_equal('email', $email)->find_one();
	}
}
