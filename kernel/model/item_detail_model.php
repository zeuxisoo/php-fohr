<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Item_Detail_Model {

	public static function fetch_all_item_detail($condition = array(), $plus_detail = array()) {
		global $db;
		
		$condition_code = isset($condition['item_id']) ? " AND id.item_id IN ('".implode("','", $condition['item_id'])."')" : "";

		$details = array();
		$query = $db->query("
			SELECT id.*, it.type, i.name
			FROM ".Table::table("item_detail")." id
			LEFT JOIN ".Table::table("item")." i ON id.item_id = i.id
			LEFT JOIN ".Table::table("item_type")." it ON i.item_type_id = it.id
			WHERE i.can_buy = 1 $condition_code
		");
		while($row = $db->fetch_array($query)) {
			$details[$row['item_id']] = Item_Detail_Helper::to_detail_string($row);
		}
		$db->free_result($query);
		
		return $details;
	}

}
?>