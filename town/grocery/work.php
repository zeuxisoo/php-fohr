<?php
require_once realpath('../../kernel/init.php');

Valid_Helper::need_user_logged();

if (Request::is_post() === true) {

	$work_time = intval(Request::post("work_time"));
	$need_work_time = $work_time * $config['grocery']['work_speed_time'];
			
	if ($work_time <= 0) {
		Session::set("error", "工作回合不正確");
	}elseif ($user['activity_time'] < $need_work_time) {
		Session::set("error", "你的時間不足以支付工作 ".$work_time." 回合所需的 ".$need_work_time." 時間");
	}else{
	
		$gain_money = $config['grocery']['work_gain_money'] * $work_time;

		$table = new Table("user", $user['id']);
		$table->money = $user['money'] + $gain_money;
		$table->activity_time = $user['activity_time'] - $need_work_time;
		$table->renew();
		
		Session::set("success", "恭喜你!工作順利完成!支付 ".$gain_money." $config[money_name]");

	}

	Util::redirect(PHP_SELF);

}else{

	include_once View::display("town/grocery/work.html");

}
?>