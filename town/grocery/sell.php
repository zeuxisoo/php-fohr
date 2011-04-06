<?php
require_once realpath("../../kernel/init.php");

Valid_Helper::need_user_logged();

if (Request::is_post() === true) {

	$id = Request::post("id");
	$amount = Request::post("amount");
	
	if (empty($id) === true) {
		Session::set("error", "請選擇物品");
	}elseif (is_numeric($id) === false) {
		Session::set("error", "物品格式不正確");	
	}elseif (empty($amount) === true) {
		Session::set("error", "請輸入數量");
	}elseif (is_numeric($amount) === false) {
		Session::set("error", "請輸入數字");
	}elseif ($amount < 1) {
		Session::set("error", "數量不能小於 1");
	}else{
		
		$user_item = $db->fetch_one("
			SELECT ui.amount, i.price
			FROM ".Table::table("user_item")." ui
			LEFT JOIN ".Table::table("item")." i ON ui.item_id = i.id
			WHERE user_id = ".$user['id']."
			AND ui.id = ".$id."
		");

		if ($user_item['amount'] < $amount) {
			Session::set("error", "沒有足夠的物品");
		}else{
		
			$new_amount = $user_item['amount'] - $amount;
			$gain_price = $user_item['price'] * $amount;

			// Update user item amount
			if ($new_amount <= 0) {
				User_Item_Model::remove_item($user, array($id));
			}else{
				$table = new Table("user_item", $id);
				$table->amount = $new_amount;
				$table->renew();
			}
			
			// Add money to user
			User_Model::add_money($user, $gain_price);
			
			Session::set("success", "賣出 ".$amount." 件,得到 ".Util::money_it($gain_price)." ".$config['money_name']);
		}
	
	}
	
	Util::redirect(PHP_SELF);

}else{
	
	// Get user item list
	$items = User_Item_Model::fetch_all_item_list($user, User_Item_Model::FILTER_PROPS);

	include_once View::display("town/grocery/sell.html");
}
?>