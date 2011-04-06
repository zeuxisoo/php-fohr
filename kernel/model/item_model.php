<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Item_Model {

	public static function fetch_all_can_by_item_list($details) {
		global $db;
	
		$items = array();
		$query = $db->query("
			SELECT i.id, i.name, i.price, i.image, it.type
			FROM ".Table::table("item")." i
			LEFT JOIN ".Table::table("item_type")." it ON i.item_type_id = it.id
			WHERE i.can_buy = 1
		");
		while($row = $db->fetch_array($query)) {
			$items[$row['id']] = array(
				'name' => $row['name'],
				'price' => $row['price'],
				'image' => $row['image'],
				'type' => Item_Type_Helper::to_type_name($row['type']),
				'detail' => $details[$row['id']],
			);
		}
		$db->free_result($query);
		
		return $items;
	}

}
?>