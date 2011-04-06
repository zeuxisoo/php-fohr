<?php
require_once dirname(__FILE__)."/kernel/init.php";

if ($action == "login") {

	$email = Request::post("email");
	$password = Request::post("password");
	
	if (empty($email) === true) {
		Session::set("error", "請輸入電郵");	
	}elseif (empty($password) === true) {
		Session::set("error", "請輸入密碼");
	}else{
	
		$user = Table::fetch_one_by_column("user", $email, "email");
	
		if (empty($user['email']) === true) {
			Session::set("error", "找不到此帳號");
		}elseif ($user['password'] != md5($password)){
			Session::set("error", "密碼錯誤");
		}elseif ($user['status'] === "locked") {
			Session::set("error", "此帳號已被鎖定");
		}elseif ($user['status'] === "killed") {
			Session::set("error", "此人已圓寂了");
		}else{
		
			$password = md5($password);
			$auth_key = sha1($user['id'].$email.$password.$config['cookie_secure_key']);
			$auth_string = Valid_Helper::make_auth("$user[id]\t$email\t$password\t$auth_key");

			Util::add_cookie($config['cookie_auth_name'], $auth_string, time()+$config['cookie_store_time']);
		
		}
	
	}
	
	Util::redirect(PHP_SELF);

}elseif ($action == "logout") {

	Util::remove_cookie($config['cookie_auth_name']);
	Util::redirect(PHP_SELF);

}elseif ($action == "init_team") {

	Valid_Helper::need_user_logged();

	$team_name = Request::post("team_name");
	$character_name = Request::post("character_name");
	$character_job = Request::post("character_job");
	
	if (empty($team_name) === true) {
		Session::set("error", "請輸入隊伍名稱");
	}elseif (empty($character_name) === true) {
		Session::set("error", "請輸入角色名稱");
	}elseif (empty($character_job) === true) {
		Session::set("error", "請選擇角色職業");
	}elseif (Util::utf8_string_length($team_name) > 50) {
		Session::set("error", "隊伍名稱只能在 30 字元以內");
	}elseif (Util::utf8_string_length($character_name) > 30) {
		Session::set("error", "角色名稱只能在 30 字元以內");
	}elseif (preg_match("/^\d_\d$/", $character_job) == false) {
		Session::set("error", "非法的角色職業");
	}elseif (Table::count("user", array("team_name" => $team_name)) > 0) {
		Session::set("error", "此隊名已經存在");
	}elseif (Table::count("team_member", array("name" => $character_name)) > 0) {
		Session::set("error", "此角色名稱已經存在");
	}else{
		
		list($character_id, $character_gender) = explode("_", $character_job);
		
		if (in_array($character_id, array(1, 2)) === false) {
			Session::set("error", "非法請求職業");
		}elseif (in_array($character_gender, array(1, 2)) === false) {
			Session::set("error", "非法的角色性別");
		}else{

			$table = new Table("user", $user['email'], "email");
			$table->team_name = $team_name;
			$table->renew();

			$table = new Table("team_member");
			$table->user_id = $user['id'];
			$table->character_id = $character_id;
			$table->name = $character_name;
			$table->gender = $character_gender;
			$table->insert();

		}
		
	}
	
	Util::redirect(PHP_SELF);

}else{

	if (Valid_Helper::user_logged() === true) {
		if (empty($user['team_name']) === true) {
			exit(include_once View::display("first_login.html"));
		}else{
			$characters = Team_Member_Model::fetch_team_list($user);
			
			exit(include_once View::display("panel.html"));
		}
	}

	include_once View::display("index.html");
}
?>