<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Item_Detail_Helper {

	public static function format_detail($detail) {
		unset($detail['item_id']);
		
		// Get Refine number
		if (isset($detail['refine_count']) === false) {
			$detail['refine_count'] =  0;
		}
		$detail['refine_string'] = User_Item_Helper::to_refine_count($detail['refine_count']);
		
		$detail = self::calcuate_refined_value($detail);
		
		// Mixin attack, defense, magic_defense
		$detail['attack'] = $detail['attack_normal']."+".$detail['attack_percentage'];
		unset($detail['attack_normal'], $detail['attack_percentage']);
		
		$detail['defense'] = $detail['defense_normal']."+".$detail['defense_percentage'];
		unset($detail['defense_normal'], $detail['defense_percentage']);
		
		$detail['magic_defense'] = $detail['magic_defense_normal']."+".$detail['magic_defense_percentage'];
		unset($detail['magic_defense_normal'], $detail['magic_defense_percentage']);
		
		// Move handle value to end of array
		$handle = $detail['handle']; unset($detail['handle']);
		$detail['handle'] = $handle;
		
		// Display filter
		switch($detail['type']) {
			case "sword":
			case "dsword":
			case "knife":
			case "mstaff":
			case "staff":
			case "bow":
			case "whip":
			case "daxe":
			case "gun":
			case "crossbow":
			case "stick":
				unset($detail['defense']);
				unset($detail['magic_defense']);
				break;
			case "shield":
			case "armour":
			case "book":
			case "clothes":
			case "robe":
				unset($detail['attack']);
				break;
			case "props":
				unset($detail['attack']);
				unset($detail['defense']);
				unset($detail['magic_defense']);
				break;
		}
		
		return $detail;	
	}
	
	public static function calcuate_refined_value($detail) {
		if(isset($detail["attack_normal"])) {
			$detail["attack_normal"] *= ( 1 + ($detail['refine_count'] * $detail['refine_count'])/100 );
			$detail["attack_normal"]  = ceil($detail["attack_normal"]);
		}
		
		if(isset($detail["attack_percentage"])) {
			$detail["attack_percentage"] *= ( 1 + ($detail['refine_count'] * $detail['refine_count'])/100 );
			$detail["attack_percentage"]  = ceil($detail["attack_percentage"]);
		}
		
		$refine_rate	= 1 + 0.3 * ($detail['refine_count']/10);
		
		if(isset($detail["defense_normal"])) {
			$detail["defense_normal"] = ceil($detail['defense_normal'] * $refine_rate);
		}

		if(isset($detail["defense_percentage"])) {
			$detail["defense_percentage"] = ceil($detail["defense_percentage"] * $refine_rate);
		}

		if(isset($detail["magic_defense_normal"])) {
			$detail["magic_defense_normal"]	= ceil($detail["magic_defense_normal"] * $refine_rate);
		}

		if(isset($detail["magic_defense_percentage"])) {
			$detail["magic_defense_percentage"]	= ceil($detail["magic_defense_percentage"] * $refine_rate);
		}

		return $detail;
	}

	public static function to_detail_string($detail) {
		$detail = self::format_detail($detail);
		
		$type_word = array(
			'attack' => '攻擊',
			'defense' => '防禦',
			'magic_defense' => '魔防',
			'handle' => '重',
		);
		
		$string = array();
		foreach($detail as $type => $value) {
			if (array_key_exists($type, $type_word) === true) {
				$string[] = sprintf("<span class='%s'>%s:%s</span>", $type, $type_word[$type], $value);
			}
		}
		
		$amount = isset($detail['amount']) ? "x".$detail['amount'] : "";

		return sprintf(
			"%s%s %s <span class='item_type'>(%s)</span> / %s",
			User_Item_Helper::to_refine_color($detail['refine_string'], $detail['refine_count']),
			User_Item_Helper::to_refine_color($detail['name'], $detail['refine_count']),
			$amount,
			Item_Type_Helper::to_type_name($detail['type']),
			implode(" / ", $string)
		);
	}

}
?>