<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Item_Type_Helper {

	public static function to_type_name($type) {
		$item_name = array(
			"sword" => "劍", "dsword" => "雙手劍", "knife" => "匕首", "mstaff" => "魔杖", "staff" => "杖", 
			"bow" => "弓", "whip" => "鞭", "daxe" => "斧", "gun" => "槍", "crossbow" => "弩", "stick" => "棍",
			"shield" => "盾", "book" => "書",
			"armour" => "甲", "clothes" => "衣服", "robe" => "長袍",
			"props" => "道具",
		);
		return $item_name[$type];
	}

}
?>