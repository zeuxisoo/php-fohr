<?php
session_start();
date_default_timezone_set("Asia/Hong_Kong");

define('IN_APPS',     true);
define('WWW_ROOT',    dirname(__FILE__));
define('APP_ROOT',    WWW_ROOT.'/app');
define('CACHE_ROOT',  WWW_ROOT.'/cache');
define('CONFIG_ROOT', WWW_ROOT.'/config');
define('DATA_ROOT',   WWW_ROOT.'/data');
define('STATIC_ROOT', WWW_ROOT.'/static');
define('VENDOR_ROOT', WWW_ROOT.'/vendor');

require VENDOR_ROOT.'/autoload.php';
require CONFIG_ROOT.'/default.php';

use Slim\Slim;
use Slim\Extras;
use Slim\Views;

spl_autoload_register(function($_class) {
	$file_path = str_replace('\\', DIRECTORY_SEPARATOR, $_class);
	$path_info = pathinfo($file_path);
	$directory = strtolower($path_info['dirname']);

	$class_file = $directory.DIRECTORY_SEPARATOR.$path_info['filename'].'.php';

	if (is_file($class_file) === true) {
		require_once $class_file;
	}else{
		foreach(array(APP_ROOT.'/model') as $directory) {
			$file_path = $directory.'/'.strtolower($_class).'.php';
			if (is_file($file_path) === true) {
				require $file_path;
			}
		}
	}
});

// Connect to database
if (strtolower($config['database']['driver']) !== "sqlite") {
	ORM::configure(sprintf(
		'%s:host=%s;dbname=%s',
		$config['database']['driver'], $config['database']['host'], $config['database']['dbname']
	));
	ORM::configure('username', $config['database']['username']);
	ORM::configure('password', $config['database']['password']);
}else{
	ORM::configure('sqlite:'.$config['database']['host']);
}

if (strtolower($config['database']['driver']) === "mysql") {
	ORM::configure('driver_options', array(
		PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
	));
}

// Slim
$app = new Slim(array(
	'debug' => $config['default']['debug'],
	'view'  => new Views\Twig(),
));

// Slim middleware
$app->add(new Extras\Middleware\CsrfGuard());

// Slim view
$view = $app->view();
$view->twigTemplateDirs = array(APP_ROOT.'/view');
$view->parserOptions = array(
	'charset'          => 'utf-8',
	'cache'            => realpath(CACHE_ROOT.'/view'),
	'auto_reload'      => true,
	'strict_variables' => false,
	'autoescape'       => true
);
$view->parserExtensions = array(
	new Views\TwigExtension(),
);

// Slim view global variable
$view->getEnvironment()->addGlobal("session", $_SESSION);

// Load build in route
foreach(array('index', 'home') as $route) {
	require_once APP_ROOT.'/route/'.$route.'.php';
}

// Bind app variable
$app->config('app.config',   $config);
$app->config('app.site_url', $site_url);

// Bind view variable
$protocol = isset($_SERVER['HTTPS']) === true ? 'https' : 'http';
$headers  = $app->request()->headers();
$root_uri = $app->request()->getRootUri();
$site_url = $protocol.'://'.$headers['HOST'].$root_uri;

$app->view()->setData('config',   $config);
$app->view()->setData('site_url', $site_url);

// Start
$app->run();
