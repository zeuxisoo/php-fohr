<?php
$config['site_url'] 	 = "http://localhost/git/hof";
$config['site_title'] 	 = "Hall oF Wall";
$config['site_language'] = "zh_HK";

$config['money_name']			 = "金幣";
$config['money_prefix'] 		 = "$";
$config['max_activity_time'] 	 = 5000;
$config['pre_day_activity_time'] = 10000;
$config['rename_team_name_price']= 3000000;

$config['grocery']['work_speed_time'] = 100;
$config['grocery']['work_gain_money'] = 500;
$config['grocery']['sell_gain_money'] = 0.5;	// Discount in each sell item

$config['forging']['refine']['max_value'] = 10;
$config['forging']['refine']['colors'] = array(
	1 => "#FFFFFF", 2 => "#9E7BFF", 3 => "#306EFF", 4 => "#F778A1", 5 => "#387C44",
	6 => "#FFFF00", 7 => "#FF8040", 8 => "#43C6DB", 9 => "#C9BE62", 10 => "#EAC117",
);

$config['public']['auction']['apply_cost'] = 55000;
$config['public']['auction']['card_id'] = 4;
$config['public']['auction']['hold_hours'] = array(1, 2, 3, 5, 7, 9, 12, 16, 18, 24, 32, 36, 40, 48);

$config['timezone'] 	   = "Asia/Hong_Kong";
$config['no_cache_header'] = true;
$config['show_php_error']  = true;
$config['show_view_error'] = true;

$config['cookie_auth_name']  = "hof_auth";
$config['cookie_secure_key'] = "--_@_1?-(-3-#m-Rt&__";
$config['cookie_store_time'] = 3600*24*14;

$config['db']['driver']   = "mysql";
$config['db']['host']  	  = "localhost"; 
$config['db']['username'] = "root";
$config['db']['password'] = "root";
$config['db']['database'] = "project_hof";
$config['db']['charset']  = 'utf-8';
$config['db']['port']     = "3306";
$config['db']['prefix']   = "ph_";
$config['db']['debug']    = true;

$config['debug']['show_sql']   = true;
$config['debug']['format_sql'] = true;

$config['debug']['show_run_time'] = true;
$config['debug']['run_time_mode'] = 1;		// 1: show in content, 2: hide in content
?>