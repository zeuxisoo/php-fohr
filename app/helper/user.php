<?php
namespace App\Helper;

class User {
	public static function take_money($user, $money) {
		$user->money -= $money;
		$user->save();

		static::update_session_money($user->money);
	}

	public static function init_session($user) {
		$_SESSION['user'] = array(
			'id' => $user->id,
			'email' => $user->email,
			'team_name' => $user->team_name,
			'money' => $user->money,
			'time' => $user->time,
		);
	}

	public static function update_session_money($value) {
		$_SESSION['user']['money'] = $value;
	}
}
