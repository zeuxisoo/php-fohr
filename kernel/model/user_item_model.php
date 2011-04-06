<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class User_Item_Model {

	const FILTER_PROPS = 1;

	public static function fetch_all_item_list($user, $filter_options = "") {
		global $db, $config;
	
		$where_code  = 1;
		$where_code .= ($filter_options == self::FILTER_PROPS) ? " AND i.item_type_id != 17 " : "";
	
		$items = array();
		$query = $db->query("
				SELECT ui.id, ui.amount, ui.refine_count, ui.item_id, id.*, it.type, i.name, i.price, i.image
				FROM ".Table::table("user_item")." ui
				LEFT JOIN ".Table::table("item")." i ON ui.item_id = i.id
				LEFT JOIN ".Table::table("item_detail")." id ON id.item_id = i.id
				LEFT JOIN ".Table::table("item_type")." it ON i.item_type_id = it.id
				WHERE $where_code AND ui.user_id = ".$user['id']."
				ORDER BY it.type DESC
			");
		while($row = $db->fetch_array($query)) {
			$items[$row['id']] = array(
				'item_id' => $row['item_id'],
				'name' => $row['name'],
				'price' => intval($row['price'] * $config['grocery']['sell_gain_money']),
				'image' => $row['image'],
				'type' => Item_Type_Helper::to_type_name($row['type']),
				'detail' => Item_Detail_Helper::to_detail_string($row),
			);
		}
		$db->free_result($query);
		
		return $items;
	}
	
	public static function remove_item($user, $item_id) {
		global $db;
		
		if (is_array($item_id) === false) {
			$item_ids = array($item_id);
		}else{
			$item_ids = &$item_id;
		}

		if (empty($item_ids) === false) {
			$db->update("
				DELETE FROM ".Table::table("user_item")."
				WHERE id IN ('".implode("','", $item_ids)."')
				AND user_id = ".$user['id']."
			");	
		}
	}

	public static function add_item($user, $item_id, $amount = 1) {
		$table = new Table("user_item");
		$table->user_id = $user['id'];
		$table->item_id = $item_id;
		$table->amount = $amount;
		$table->insert();
	}

	public static function has_item($user, $item_id) {
		return Table::count("user_item", array(
			"item_id" => $item_id,
			"user_id" => $user['id'],
		)) > 0;
	}

}
?>