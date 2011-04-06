<?php
error_reporting(E_ALL);

if (version_compare(PHP_VERSION, '6.0.0', '<') === true) {
	@set_magic_quotes_runtime(0);
}

define('IN_APPS', true);
define('KERNEL_ROOT', str_replace('\\', '/', dirname(__FILE__)));
define('WWW_ROOT', dirname(KERNEL_ROOT));
define('CACHE_ROOT', WWW_ROOT.'/cache');
define('TEMPLATE_ROOT', WWW_ROOT.'/template');
define('LANGUAGE_ROOT', WWW_ROOT.'/language');

require_once KERNEL_ROOT."/config.php";
require_once KERNEL_ROOT."/common.php";

$cpg = array('_COOKIE', '_POST', '_GET');
foreach($cpg as $_request) {
    foreach($$_request as $_key => $_value) {
        $_key{0} != '_' && $$_key = Util::auto_quote($_value);
    }
}
unset($_request, $_key, $_value, $_request);

// Filter on _COOKIE/_POST/_GET/_FILES/_REQUEST
$cpg += array('_FILES', '_REQUEST');
foreach($cpg as $_request) {
	$$_request = Util::auto_quote($$_request);
}
unset($cpg);

define('SITE_URL', $config['site_url']);
define('PHP_SELF', Util::get_php_self());
define('STATIC_URL', SITE_URL.'/static');
define('START_TIME', Benchmark::start());

if (function_exists("date_default_timezone_set")) {
	date_default_timezone_set($config['timezone']);
}

if ($config['no_cache_header'] === true) {
	header("Cache-Control: no-cache, must-revalidate, max-age=0");
	header("Expires: 0");
	header("Pragma:	no-cache");
	header("Content-Type: text/html; charset=utf-8");
}

if ($config['show_php_error'] === true) {
	error_reporting(E_ALL);
}else{
	error_reporting(E_ALL & ~E_NOTICE);
}

Session::init();

Cache::set_cacher(new File_System_Cacher(array(
	"cache_root" => CACHE_ROOT
)));

Language::set_settings(array(
	'language_root' => LANGUAGE_ROOT,
	'language_name' => $config['site_language']
));

View::set_settings(array(
	"debug" => $config['show_view_error'],
	"view_folder" => TEMPLATE_ROOT,
	"view_cache_folder" => CACHE_ROOT."/template",
	"theme" => "default",
));

$db = Database::create($config['db']);

Table::init($db);

ob_get_clean(); ob_start('ob_gzhandler');

$action = Request::get("action");
$action = empty($action) === true ? Request::post("action") : $action;

$flow_message['error'] = Session::get("error", true);
$flow_message['success'] = Session::get("success", true);

$user = array();
if (Valid_Helper::user_logged() === true) {
	$user = Table::fetch_one_by_column("user", Valid_Helper::get_user('id'));
	User_Model::update_activity_time($user);
}
?>