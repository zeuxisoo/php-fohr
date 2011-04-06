<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Util {

	public static function auto_quote($string, $force = 0) {
		if(get_magic_quotes_gpc() == false || $force) {
			if(is_array($string)) {
				foreach($string as $key => $val) {
					$string[$key] = self::auto_quote($val, $force);
				}
			} else {
				$string = addslashes($string);
			}
		}
		return $string;
	}
	
	public static function get_php_self() {
		$php_self =	isset($_SERVER['PHP_SELF'])	? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
		if (substr($php_self, -1) == '/') {
			$php_self .= 'index.php';
		}
		return $php_self;
	}
	
	public static function to_date_time($time_stamp, $format = 'Y-m-d', $time_zone = 8) {
		return gmdate($format, $time_stamp + $time_zone * 3600);
	}
	
	public static function is_email($email) {
		$pattern = "/^([a-z0-9+_]|\\-|\\.)+@(([a-z0-9_]|\\-)+\\.)+[a-z]{2,6}\$/i";
		if (strpos($email, '@') !== false && strpos($email, '.') !== false) {
			return preg_match($pattern, $email);
		}
		return false;
	}
	
	public static function redirect($url, $query_string = array(), $time = 0, $message = '') {
		$url = str_replace(array("\n", "\r"), '', $url);
		
		if (empty($query_string) === false) {
			$url .= "?".http_build_query($query_string);
		}
		
		if(empty($message) === true) {
			$message = "{$time}s will auto redirect to {$url}?I";
		}
		
		if (headers_sent() === false) {
			header("Content-Type:text/html; charset=utf-8");
			
			if($time === 0) {
				header("Location: ".$url);
			}else{
				header("refresh:{$time};url={$url}");
			}
			
			exit();
		}else{
			$string = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
			
			if($time != 0) {
				$string .= $message;
			}
			
			exit($string);
		}
	}

	public static function add_cookie($name, $value, $time_out = 3600, $path = '/', $domain = '') {
		setcookie($name, $value, $time_out, $path, $domain, ($_SERVER['SERVER_PORT'] == 443 ? 1 : 0));
	}
	
	public static function remove_cookie($name) {
		self::add_cookie($name, '', -84600);
		
		if (isset($_COOKIE[$name])) {
			unset($_COOKIE[$name]);
		}
	}
	
	public static function utf8_string_length($string) {
		if (function_exists("mb_strlen")) {
			return mb_strlen($string, "UTF-8");
		}else if (function_exists("preg_match_all")) {
			preg_match_all("/./us", $string, $match);
			return count($match[0]);
		}else{
			$byte_length = strlen($string);
			$count = 0;
			
			for ($i = 0; $i < $byte_length; $i++){
				if ((ord($str[$i]) & 192) == 128) {
					continue;
				}
				$count++;
			}
			return $count;
		}
	}
	
	public static function money_it($money) {
		global $config;
		return sprintf("%s%s", $config['money_prefix'], number_format($money));
	}

}
?>