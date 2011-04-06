<?php
if (defined("IN_APPS") === false) exit("Access Dead");

class Auction_Log_Model {

	public static function fetch_all_log_list() {
		global $db;
		
		$logs = $db->fetch_all("
			SELECT al.*, u.team_name
			FROM ".Table::table("auction_log")." al
			LEFT JOIN ".Table::table("user")." u ON al.user_id = u.id
			ORDER BY create_date DESC
		");
		
		return self::format_all_logs($logs);
	}
	
	public static function format_all_logs($logs) {
		$items = Cache::get("items");
	
		$temp = array();
		foreach($logs as $log) {
			$refine_number = User_Item_Helper::to_refine_count($log['refine_count']);
			$item_name = User_Item_Helper::to_refine_color($refine_number.$items[$log['item_id']]['name'], $log['refine_count']);
			$item_name = sprintf("<img src='%s/image/item/%s' width=16 height=16 /> %s", STATIC_URL, $items[$log['item_id']]['image'], $item_name);
		
			switch($log['status']) {
				case "add":
					$message = "%s %s 個 <span class='auction_add'>加入拍賣</span>";
					$message = sprintf($message, $item_name, $log['amount']);
					break;
				case "bit":
					$message = "%s 對 %s 以 %s <span class='auction_bit'>出價</span>";
					$message = sprintf($message, $log['team_name'], $item_name, $log['price']);
					break;
				case "win":
					$message = "數量 %s 個的 %s, %s <span class='auction_win'>中標</span>";
					$message = sprintf($message, $log['amount'], $item_name, $log['team_name']);
					break;
				case "lose":
					$message = "%s %s 個 <span class='auction_lose'>流標</span>";
					$message = sprintf($message, $item_name, $log['amount']);
					break;
			}
			
			$temp[] = sprintf("[%s] %s", Util::to_date_time($log['create_date'], "m-d H:i:s"), $message);
		}
		
		return $temp;
	}

}
?>