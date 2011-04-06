<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Valid_Helper {

	public static function make_auth($string, $operation = 'ENCODE') {
		$string = $operation == 'DECODE' ? base64_decode($string) : base64_encode($string);
		return $string;
	}

	public static function user_logged() {
		global $config;
		
		$user_auth = Request::cookie($config['cookie_auth_name']);

		if (isset($user_auth) === true && empty($user_auth) === false) {
			list($user_id, $user_email, $user_password, $user_auth_key) = explode("\t", self::make_auth($user_auth, "DECODE"));
			
			return sha1($user_id.$user_email.$user_password.$config['cookie_secure_key']) === $user_auth_key;
		}
		return false;
	}
	
	public static function get_user($type) {
		global $config;
		$user = explode("\t", self::make_auth(Request::cookie($config['cookie_auth_name']), "DECODE"));
		$table= array('id' => $user[0], 'email' => $user[1]);
		return $table[$type];
	}
	
	public static function need_user_logged() {
		global $config;
		
		if (self::user_logged() === false) {
			Session::set("error", "Please login first");
			Util::redirect($config['site_url']."/index.php");
		}
	}

}
?>