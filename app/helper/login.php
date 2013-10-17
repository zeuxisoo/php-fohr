<?php
namespace App\Helper;

class Login {
	public static function createKey($user_id, $signin_token, $cookie_secret_key) {
		$user_id      = dechex($user_id);
		$signin_token = hash('sha256', $signin_token.$cookie_secret_key);
		$auth_key     = hash('sha256', $user_id.$signin_token.$cookie_secret_key);
		$auth_string  = static::makeAuth("$user_id:$signin_token:$auth_key");
		return $auth_string;
	}

	public static function makeAuth($string, $operation = 'ENCODE') {
		$string = $operation == 'DECODE' ? base64_decode($string) : base64_encode($string);
		return $string;
	}
}
