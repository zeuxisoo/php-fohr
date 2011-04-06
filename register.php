<?php
require_once dirname(__FILE__).'/kernel/init.php';

if (Request::is_post() === true) {

	$email = Request::post("email");
	$password = Request::post("password");
	$confirm_password = Request::post("confirm_password");
	
	if (empty($email) === true) {
		Session::set("error", "請輸入電郵");
	}elseif (Util::is_email($email) === false) {
		Session::set("error", "電郵格式不正確");
	}elseif (empty($password) === true) {
		Session::set("error", "請輸入密碼");
	}elseif (strlen($password) < 6) {
		Session::set("error", "密碼需多於 6 個字元");
	}elseif ($password != $confirm_password) {
		Session::set("error", "密碼與確認密碼不相符");
	}else{
	
		$total = Table::count("user", array("email" => $email));
		
		if ($total > 0) {
			Session::set("error", "電郵已存在");
		}else{
			$table = new Table("user");
			$table->email = $email;
			$table->password = md5($password);
			$table->last_activity_time = time();
			$table->create_date = time();
			$table->insert();
			
			Session::set("success", "註冊完成");
		}
	
	}
	
	Util::redirect(PHP_SELF);

}else{

	include_once View::display("register.html");

}
?>