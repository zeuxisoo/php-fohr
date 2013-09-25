<?php
namespace App\Model;

class Base {
	public static function create($row) {
		if (array_key_exists('created_at', $row) === false) {
			$row['created_at'] = time();
		}

		if (array_key_exists('updated_at', $row) === false) {
			$row['updated_at'] = time();
		}

		\ORM::for_table(static::$_table)->create($row)->save();
	}

	public static function get($id) {
		return \ORM::for_table(static::$_table)->find_one($id);
	}
}
