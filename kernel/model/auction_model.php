<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Auction_Model {

	public static function fetch_all_auction_list() {
		global $db;
		
		$items = Cache::get("items");
		
		$auctions = array();
		$query = $db->query("
			SELECT a.*, u.team_name
			FROM ".Table::table("auction")." a
			LEFT JOIN ".Table::table("user")." u ON a.user_id = u.id
			ORDER BY create_date DESC
		");
		while($row = $db->fetch_array($query)) {
			$item = $items[$row['item_id']];
			$item['refine_count'] = $row['refine_count'];
		
			$auctions[$row['id']] = array(
				'name' => $item['name'],
				'price' => $row['price'],
				'image' => $item['image'],
				'end_date' => $row['end_date'],
				'owner_team_name' => $row['team_name'],
				'comment' => $row['comment'],
				'type' => Item_Type_Helper::to_type_name($item['type']),
				'detail' => Item_Detail_Helper::to_detail_string($item),
			);
		}
		
		return $auctions;
	}

}
?>