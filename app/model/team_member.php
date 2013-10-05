<?php
namespace App\Model;

use App\Model\Job;

class TeamMember extends Base {
	public static $_table = 'team_member';

	public static function existsCharacterName($character_name) {
		return \ORM::for_table(self::$_table)->where_equal('character_name', $character_name)->count() >= 1;
	}

	public static function findByUserId($user_id) {
		return \ORM::for_table(self::$_table)
				->join(Job::$_table, 'team_member.job_id = job.id')
				->where_equal('user_id', $user_id)
				->findMany();
	}
}
