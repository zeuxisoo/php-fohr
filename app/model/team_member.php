<?php
namespace App\Model;

class TeamMember extends Base {
	public static $_table = 'team_member';

	public static function exists_character_name($character_name) {
		return \ORM::for_table(self::$_table)->where_equal('character_name', $character_name)->count() >= 1;
	}
}
