<?php
namespace App\Helper;

class User {
	public static function takeMoney($user, $money) {
		$user->money -= $money;
		$user->save();

		static::updateMoneyInSession($user->money);
	}

	public static function initSession($user) {
		$_SESSION['user'] = array(
			'id' => $user->id,
			'email' => $user->email,
			'team_name' => $user->team_name,
			'money' => $user->money,
			'time' => $user->time,
		);
	}

	public static function updateMoneyInSession($value) {
		$_SESSION['user']['money'] = $value;
	}
}
