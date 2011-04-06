<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class User_Model {

	public static function update_activity_time($user) {
		global $config;
	
		// Get gained activity time
		$now  = time();
		$gain = ($now - $user["last_activity_time"]) / (24*60*60) * $config['pre_day_activity_time'];
		
		// Set activity time to max limit if activity time bigger than max activity time
		$original_activity_time = $user["activity_time"];
		$user["activity_time"] += floor($gain);
		if($user["activity_time"] > $config['max_activity_time']) {
			$user["activity_time"]	= $config['max_activity_time'];
		}
		
		// Update to database
		if ($gain > 0 && $user["activity_time"] != $original_activity_time) {
			$table = new Table("user", $user['id']);
			$table->activity_time = $user["activity_time"];
			$table->last_activity_time = $now;
			$table->renew();
		}
	}
	
	public static function change_money($user, $to_money, $to_action) {
		if ($to_action == "-") {
			$gain = $user['money'] - $to_money;
		}else{
			$gain = $user['money'] + $to_money;
		}
		
		if ($gain < 0) {
			$gain = 0;
		}
	
		$table = new Table("user", $user['id']);
		$table->money = $gain;
		$table->renew();
	}
	
	public static function reduce_money($user, $spend_moeny) {
		self::change_money($user, $spend_moeny, "-");
	}
	
	public static function add_money($user, $plus_moeny) {
		self::change_money($user, $plus_moeny, "+");
	}
	
}