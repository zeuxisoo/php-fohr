<?php
date_default_timezone_set('Asia/Hong_Kong');

define('WWW_ROOT',    dirname(__FILE__));
define('APP_ROOT',    WWW_ROOT.'/hall');
define('CONFIG_ROOT', WWW_ROOT.'/config');
define('VENDOR_ROOT', WWW_ROOT.'/vendor');

require VENDOR_ROOT.'/autoload.php';
require APP_ROOT.'/app.php';

use Phpmig\Adapter;
use Pimple;
use Hall;

$app = new \Hall\App(CONFIG_ROOT.'/default.php');
$app->registerDatabase();

$container = new Pimple();
$container['db'] = function() {
    return ORM::get_db();
};
$container['phpmig.adapter'] = function() use($container) {
    return new Adapter\PDO\Sql($container['db'],'migrations');
};
$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;
