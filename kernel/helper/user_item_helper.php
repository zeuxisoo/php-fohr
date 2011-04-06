<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class User_Item_Helper {

	public static function to_refine_count($refine_number) {
		if ($refine_number > 0) {
			return "+".$refine_number."&nbsp;";
		}else{
			return "";
		}
	}
	
	public static function to_refine_color($name, $refine_number) {
		global $config;

		if ($refine_number > 0) {
			$colors = $config['forging']['refine']['colors'];
			return sprintf("<span style='color: %s'>%s</span>", $colors[$refine_number], $name);
		}else{
			return $name;
		}
	}

}
?>