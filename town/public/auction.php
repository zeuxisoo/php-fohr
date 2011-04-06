<?php
require_once realpath("../../kernel/init.php");

Valid_Helper::need_user_logged();

if ($action == "apply") {

	if ($user['money'] < $config['public']['auction']['apply_cost']) {
		Session::set("error", "沒有足夠的金錢");
	}elseif (User_Item_Model::has_item($user, $config['public']['auction']['card_id']) == true) {
		Session::set("error", "你已是會員");
	}else{
		User_Item_Model::add_item($user, $config['public']['auction']['card_id']);
		
		User_Model::reduce_money($user, $config['public']['auction']['apply_cost']);
		
		Session::set("success", "入會成功");
	}
	
	Util::redirect(PHP_SELF);

}elseif ($action == "auction") {

	$id = Request::post("id");
	$price = Request::post("price");
	$hour = Request::post("hour");
	$amount = Request::post("amount");
	$comment = Request::post("comment");
	
	if (empty($id) === true) {
		Session::set("error", "請選擇物品");
	}elseif (empty($price) === true) {
		Session::set("error", "請輸入價錢");
	}elseif (is_numeric($price) === false) {
		Session::set("error", "價錢只能為整數數字");
	}elseif ($price < 0) {
		Session::set("error", "價錢不能小於 0");
	}elseif (in_array($hour, $config['public']['auction']['hold_hours']) === false) {
		Session::set("error", "時間不被接受");
	}elseif (empty($amount) === true) {
		Session::set("error", "請輸入個數");
	}elseif (is_numeric($amount) === false) {
		Session::set("error", "個數只能為整數數字");
	}elseif ($amount < 0) {
		Session::set("error", "個數不能小於 0");
	}elseif (Util::utf8_string_length($comment) > 25) {
		Session::set("error", "寄語不能多於 25 個字");
	}else{
		
		// Select item
		$user_item = Table::fetch_all("user_item", array(
			"one" => true,
			"select" => "item_id, amount, refine_count",
			"where" => array(
				"user_id" => $user['id'],
				"id" => $id,
			)
		));

		//
		if (empty($user_item) === true) {
			Session::set("error", "找不到此物品");
		}elseif ($user_item['amount'] < $amount) {
			Session::set("error", "沒有足夠的數量");
		}else{
	
			// Calcuate end date
			$end_date = strtotime("+".intval($hour)." hours");

			// Remove/Update user item/amount
			$gain_amount = $user_item['amount'] - $amount;

			if ($gain_amount <= 0) {
				User_Item_Model::remove_item($user, $id);
			}else{
				$table = new Table("user_item", $id);
				$table->amount = $gain_amount;
				$table->renew();
			}

			// Add auction record
			$table = new Table("auction");
			$table->user_id = $user['id'];
			$table->item_id = $user_item['item_id'];
			$table->refine_count = $user_item['refine_count'];
			$table->price = $price;
			$table->amount = $amount;
			$table->comment = $comment;
			$table->end_date = $end_date;
			$table->create_date = time();
			$table->insert();
			
			// Add auction history
			$table = new Table("auction_log");
			$table->status = "add";
			$table->user_id = $user['id'];
			$table->item_id = $user_item['item_id'];
			$table->refine_count = $user_item['refine_count'];
			$table->amount = $amount;
			$table->create_date = time();
			$table->insert();
			
			Session::set("success", "已成功加入拍賣場");
			
		}
	}

	Util::redirect(PHP_SELF);

}else{
	
	// Check is or not auction member
	$is_member = User_Item_Model::has_item($user, $config['public']['auction']['card_id']);

	// Get user item list
	$items = User_Item_Model::fetch_all_item_list($user, User_Item_Model::FILTER_PROPS);
	
	// Get auction list
	$auction_lists = Auction_Model::fetch_all_auction_list();
	
	// Get auction log
	$auction_logs = Auction_Log_Model::fetch_all_log_list();

	include_once View::display("town/public/auction.html");
}
?>