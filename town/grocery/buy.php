<?php
require_once realpath("../../kernel/init.php");

Valid_Helper::need_user_logged();

if (Request::is_post() === true) {

	$item_id = Request::post("item_id");
	$qty = Request::post("qty");
	
	if (empty($item_id) === true) {
		Session::set("error", "請選擇物品");
	}elseif (empty($qty) === true) {
		Session::set("error", "請輸入數量");
	}elseif (intval($qty) <= 0) {
		Session::set("error", "物品數量不能小於 0");
	}elseif (is_numeric($item_id) === false) {
		Session::set("error", "非法請求物品資料");
	}elseif (is_numeric($qty) === false) {
		Session::set("error", "非法請求物品數量");
	}else{
	
		$item = Table::fetch_one_by_column("item", $item_id);
		
		if (empty($item['name']) === true) {
			Session::set("error", "找不到此物品");
		}elseif ($item['can_buy'] != 1) {
			Session::set("error", "此商品不能直接購買");
		}else{
	
			// Calc total cost on selected item
			$total_cost = intval($item['price'] * $qty);
		
			if ($user['money'] < $total_cost) {
				Session::set("error", "自身的 $config[money_name] 不足");
			}else{

				// Search is or not had item (by refine count, item_id)
				$total = Table::count("user_item", array(
					"user_id" => $user['id'],
					"refine_count" => 0,
					"item_id" => $item_id
				));
				
				if ($total <= 0) {
				
					// Add new row for item
					$table = new Table("user_item");
					$table->user_id = $user['id'];
					$table->item_id = intval($item_id);
					$table->amount = $qty;
					$table->insert();
					
				}else{
				
					$db->update("
						UPDATE ".Table::table("user_item")."
						SET amount = (amount + ".$qty.")
						WHERE user_id = ".$user['id']."
						AND item_id = ".$item_id."
					");
				
				}
				
				// Reduce total cost in user money
				User_Model::reduce_money($user, $total_cost);
				
				Session::set("success", "購買數量 ".$qty." 的 ".$item['name']." 完成!花費 ".Util::money_it($total_cost));
				
			}
		
		}
	
	}

	Util::redirect(PHP_SELF);

}else{

	// Get item_detail list
	$details = Item_Detail_Model::fetch_all_item_detail();

	// Get item list
	$items = Item_Model::fetch_all_can_by_item_list($details);

	include_once View::display("town/grocery/buy.html");
}
?>