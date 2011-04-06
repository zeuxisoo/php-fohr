<?php
require_once dirname(__FILE__).'/kernel/init.php';

Valid_Helper::need_user_logged();

if ($action == "normal") {

	$save_history = Request::post("save_history");
	$say_color = Request::post("say_color");
	
	if (strlen($say_color) > 7) {
		Session::set("error", "顏色格式錯誤");
	}else{
	
		$table = new Table("user", $user['id']);
		$table->save_history = intval($save_history);
		$table->say_color = $say_color;
		$table->renew();
		
		Session::set("success", "更改基本設置完成");
		
	}
	
	Util::redirect(PHP_SELF);

}elseif ($action === "team") {

	$team_name = Request::post("team_name");
	
	if (empty($team_name) === true) {
		Session::set("error", "請輸入隊伍名稱");
	}elseif (Util::utf8_string_length($team_name) > 50) {
		Session::set("error", "隊伍名稱只能在 30 字元以內");
	}elseif (Table::count("user", array("team_name" => $team_name)) > 0) {
		Session::set("error", "此隊名已經存在");
	}elseif ($user['money'] < $config['rename_team_name_price']) {
		Session::set("error", $config['money_name']."不足");
	}else{
	
		// Reduce user money
		User_Model::reduce_money($user, $config['rename_team_name_price']);
		
		$table = new Table("user", $user['id']);
		$table->team_name = $team_name;
		$table->renew();
		
		Session::set("success", "更改隊伍名稱完成");
	
	}
	
	Util::redirect(PHP_SELF);

}elseif ($action === "password") {

	$old_password = Request::post("old_password");
	$new_password = Request::post("new_password");
	$confirm_password = Request::post("confirm_password");
	
	if (empty($old_password) === true) {
		Session::set("error", "請輸入舊的密碼");
	}elseif (empty($new_password) === true) {
		Session::set("error", "請輸入新的密碼");
	}elseif (md5($old_password) != $user['password']) {
		Session::set("error", "舊的密碼不正確");
	}elseif (strlen($new_password) < 6) {
		Session::set("error", "新的密碼長度需多於 6 個字元");
	}elseif ($new_password != $confirm_password) {
		Session::set("error", "再次輸入的密碼與新密碼不相符");
	}elseif ($new_password == $old_password) {
		Session::set("error", "新的密碼不能和舊的密碼相同");
	}else{
	
		$table = new Table("user", $user['id']);
		$table->password = md5($new_password);
		$table->renew();
	
		Util::remove_cookie($config['cookie_auth_name']);
		Session::set("success", "密碼修改完成");
		
		exit(Util::redirect(SITE_URL."/index.php"));
	
	}
	
	Util::redirect(PHP_SELF);

}elseif ($action === "kill") {

	$password = Request::post("password");
	
	if (empty($password) === true) {
		Session::set("error", "請輸入密碼");
	}elseif ($user['password'] != md5($password)) {
		Session::set("error", "輸入的密碼不正確");
	}else{
	
		$table = new Table("user", $user['id']);
		$table->status = "killed";
		$table->renew();
		
		Util::remove_cookie($config['cookie_auth_name']);
		Session::set("success", "此輩子已完");
		
		exit(Util::redirect(SITE_URL."/index.php"));
	
	}

	Util::redirect(PHP_SELF);

}else{
	include_once View::display("setting.html");
}
?>