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
use Slim\Views;

$app = new Slim(array(
	'debug'          => $config['debug'],
	'view'           => new Views\Twig(),
));

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
$view->getEnvironment()->addGlobal("session", $_SESSION);

foreach(array('index') as $route) {
	require_once APP_ROOT.'/route/'.$route.'.php';
}

$app->run();
