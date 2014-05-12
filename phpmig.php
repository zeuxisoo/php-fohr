<?php
date_default_timezone_set('Asia/Hong_Kong');

use \Phpmig\Adapter;
use \Pimple;

define('WWW_ROOT',    dirname(__FILE__));
define('CONFIG_ROOT', WWW_ROOT.'/config');

$container = new Pimple();

$container['db'] = function() {
    require CONFIG_ROOT.'/default.php';

    ORM::configure('sqlite:'.$config['database']['host']);

    Model::$auto_prefix_models = '\\Hall\\Model\\';

    return ORM::get_db();
};

$container['phpmig.adapter'] = function() use($container) {
    return new Adapter\PDO\Sql($container['db'],'migrations');
};

$container['phpmig.migrations_path'] = __DIR__ . DIRECTORY_SEPARATOR . 'migrations';

return $container;
