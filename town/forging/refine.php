<?php
require_once realpath("../../kernel/init.php");

Valid_Helper::need_user_logged();

if (Request::is_post() === true) {

	$id = Request::post("id");
	$times = Request::post("times");
	
	if (empty($id) === true) {
		Session::set("error", "請先選擇物品");
	}elseif (empty($times) === true) {
		Session::set("error", "請輸入精練次數");
	}elseif (is_numeric($id) === false) {
		Session::set("error", "無法識別物品");
	}elseif (is_numeric($times) === false) {
		Session::set("error", "精練次數必須為數字");
	}elseif ($times < 1) {
		Session::set("error", "精練次數最少為 1 次");	
	}elseif ($times > $config['forging']['refine']['max_value']) {
		Session::set("error", "精練最大值為 ".$config['forging']['refine']['max_value']);
	}else{

		$item = $db->fetch_one("
			SELECT i.id, i.name, i.price, i.can_refine, ui.refine_count, ui.amount
			FROM ".Table::table("user_item")." ui
			LEFT JOIN ".Table::table("item")." i ON ui.item_id = i.id
			WHERE ui.user_id = ".$user['id']."
			AND ui.id = ".$id."
		");
		
		if (empty($item['name']) === true) {
			Session::set("error", "找不到此物品");
		}elseif ($item['can_refine'] != 1) {
			Session::set("error", "此物品不能精練");
		}else{
		
			// Calc remain value for refine
			$remain_count = intval($config['forging']['refine']['max_value'] - $item['refine_count']);
			
			// Check user input make sure refine number not bigger than max value
			$remain_count = $times > $remain_count ? $remain_count : $times;
			
			if ($remain_count <= 0) {
				Session::set("error", "已是神兵利器,無法再精練");
			}else{
			
				$cost = round($item['price']/2);		// Calc cost
				$total_cost = $will_refine_count = 0;
				$message = array();
				
				for($i=0; $i<$remain_count; $i++) {
					if ($user['money'] > $cost) {
						$will_refine_count++;	// Store refined value
						$total_cost += $cost;	// Store total cost
						$user['money'] -= $cost;// For looping checking
						
						$message[] = sprintf("+%d 成功", $i+1);
					}
				}
				
				// Check is or not exists same item (by user_id, refine_count)
				$exists_same_item = Table::fetch_all("user_item", array(
					"one" => true,
					"select" => "id, amount",
					"where" => array(
						"user_id" => $user['id'],
						"refine_count" => $item['refine_count'] + $will_refine_count
					)
				));
				
				// If exists same item, plus one, else create new one
				if (empty($exists_same_item['id']) === false) {
					$table = new Table("user_item", $exists_same_item['id']);
					$table->amount = $exists_same_item['amount'] + 1;
					$table->renew();
				}else{
					$table = new Table("user_item");
					$table->item_id = $item['id'];
					$table->user_id = $user['id'];
					$table->refine_count = $item['refine_count'] + $will_refine_count;
					$table->amount = 1;
					$table->insert();					
				}
				
				// Reduce original item amount once
				if ($item['amount'] - 1 <= 0) {
					User_Item_Model::remove_item($user, $id);
				}else{
					$table = new Table("user_item", $id);
					$table->amount = $item['amount'] - 1;
					$table->renew();
				}
				
				// Reduce cost
				User_Model::reduce_money($user, $cost);
				
				Session::set("success", implode("<br />", $message));
			}
		
		}
				
	}
	
	Util::redirect(PHP_SELF);

}else{

	// Get user item list
	$items = User_Item_Model::fetch_all_item_list($user);

	include_once View::display("town/forging/refine.html");
}
?>