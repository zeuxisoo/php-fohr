<?php
namespace App\Helper;

use App\Model\User;

class Common {
	public function random_string($length = 8) {
		$characters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?";
		$char_length = strlen($characters);

		$result = array();

		for ($i=0; $i<$length; $i++) {
			$index = mt_rand(0, $char_length - 1);
			$result[] = $characters[$index];
		}

		return implode("", $result);
	}

	public static function create_key($user_id, $password, $cookie_secret_key) {
		$user_id  = dechex($user_id);
		$password = hash('sha256', $user_id.$cookie_secret_key);
		$auth_key = hash('sha256', $user_id.$password.$cookie_secret_key);
		$auth_string = static::make_auth("$user_id:$password:$auth_key");
		return $auth_string;
	}

	public static function make_auth($string, $operation = 'ENCODE') {
		$string = $operation == 'DECODE' ? base64_decode($string) : base64_encode($string);
		return $string;
	}
}
