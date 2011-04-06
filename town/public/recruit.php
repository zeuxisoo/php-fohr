<?php
require_once realpath("../../kernel/init.php");

Valid_Helper::need_user_logged();

$price = array(
	1 => 2000,
	2 => 2000,
	3 => 2500,
	4 => 4000,
);

if (Request::is_post() === true) {

	$job = Request::post("job");
	$name = Request::post("name");
	$gender = Request::post("gender");
	
	if (empty($job) === true) {
		Session::set("error", "請選擇職業");
	}elseif (empty($name) === true) {
		Session::set("error", "請輸入名稱");
	}elseif (empty($gender) === true) {
		Session::set("error", "請選擇角色性別");
	}elseif (in_array($job, array(1, 2, 3, 4)) === false) {
		Session::set("error", "非法請求職業");
	}elseif (in_array($gender, array(1, 2)) === false) {
		Session::set("error", "非法請求性別");
	}elseif (Table::count("team_member", array("name" => $name)) > 0) {
		Session::set("error", "此名稱已存在");
	}elseif ($user['money'] < $price[$job]) {
		Session::set("error", $config['money_name']."不足");
	}else{
	
		User_Model::reduce_money($user, intval($price[$job]));
	
		$table = new Table("team_member");
		$table->user_id = $user['id'];
		$table->character_id = $job;
		$table->name = $name;
		$table->gender = $gender;
		$table->insert();
		
		Session::set("success", "聘請完成");
	
	}
	
	Util::redirect(PHP_SELF);

}else{
	include_once View::display("town/public/recruit.html");
}
?>