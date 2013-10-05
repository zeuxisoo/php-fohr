<?php
namespace App\Helper;

class Common {
	public function randomString($length = 8) {
		$characters = "abcdefghijklmnopqrstuxyvwzABCDEFGHIJKLMNOPQRSTUXYVWZ+-*#&@!?";
		$char_length = strlen($characters);

		$result = array();

		for ($i=0; $i<$length; $i++) {
			$index = mt_rand(0, $char_length - 1);
			$result[] = $characters[$index];
		}

		return implode("", $result);
	}
}
