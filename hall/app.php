<?php
namespace Hall;

use ORM;
use Model;
use Slim\Slim;
use Slim\Extras;
use Slim\Views;
use Hall\Helper;
use Hall\Middleware\Route;

class App {

    protected $slim   = null;
    protected $config = array();

    public function __construct($config) {
        if (is_file($config) === true && file_exists($config) === true) {
            $this->config = require($config);
        }else{
            throw new \Exception('Can not load the default config file');
        }
    }

    public function registerAutoload() {
        spl_autoload_register(function($_class) {
            $file_path = str_replace('\\', DIRECTORY_SEPARATOR, $_class);
            $path_info = pathinfo($file_path);
            $directory = strtolower($path_info['dirname']);

            $filename_underscore = preg_replace('/\B([A-Z])/', '_$1', $path_info['filename']);

            $class_file_normal     = $directory.DIRECTORY_SEPARATOR.$path_info['filename'].'.php';
            $class_file_underscore = $directory.DIRECTORY_SEPARATOR.$filename_underscore.'.php';

            if (is_file($class_file_normal) === true) {
                require_once $class_file_normal;
            }else if (is_file($class_file_underscore) === true) {
                require_once $class_file_underscore;
            }
        });
    }

    public function registerDatabase() {
        if (strtolower($this->config['database']['driver']) !== "sqlite") {
            ORM::configure(sprintf(
                '%s:host=%s;dbname=%s',
                $this->config['database']['driver'], $this->config['database']['host'], $this->config['database']['dbname']
            ));
            ORM::configure('username', $this->config['database']['username']);
            ORM::configure('password', $this->config['database']['password']);
        }else{
            ORM::configure('sqlite:'.$this->config['database']['host']);
        }

        if (strtolower($this->config['database']['driver']) === "mysql") {
            ORM::configure('driver_options', array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
            ));
        }

        Model::$auto_prefix_models = '\\Hall\\Model\\';
    }

    public function registerSlim() {
        $this->slim = new Slim(array(
            'debug' => $this->config['default']['debug'],
            'view'  => new Views\Twig(),
        ));
    }

    public function registerSlimMiddleware() {
        $this->slim->add(new Extras\Middleware\CsrfGuard());
    }

    public function registerSlimView() {
        $view = $this->slim->view();
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
        $view->getEnvironment()->addGlobal("view", new Helper\View());
    }

    public function registerSlimRoutes() {
        $this->slim->get('/', '\Hall\Controller\Index:index')->name('index.index');
        $this->slim->map('/signup', '\Hall\Controller\Index:signup')->name('index.signup')->via('GET', 'POST');
        $this->slim->post('/signin', '\Hall\Controller\Index:signin')->name('index.signin');
        $this->slim->get('/signout', '\Hall\Controller\Index:signout')->name('index.signout');

        $this->slim->group('/home', Route::requireLogin(), function() {
            $this->slim->get('/index','\Hall\Controller\Home:index')->name('home.index');
            $this->slim->post('/first', '\Hall\Controller\Home:first')->name('home.first');
        });

        $this->slim->group('/auction', Route::requireLogin(), function() {
            $this->slim->get('/index', '\Hall\Controller\Auction:index')->name('auction.index');
        });

        $this->slim->group('/competition', Route::requireLogin(), function() {
            $this->slim->get('/index', '\Hall\Controller\Competition:index')->name('competition.index');
        });

        $this->slim->group('/forging', Route::requireLogin(), function() {
            $this->slim->get('/refine', 'Hall\Controller\Forging:refine')->name('forging.refine');
            $this->slim->get('/create', 'Hall\Controller\Forging:create')->name('forging.create');
        });

        $this->slim->group('/grocery', Route::requireLogin(), function() {
            $this->slim->get('/buy', 'Hall\Controller\Grocery:buy')->name('grocery.buy');
            $this->slim->get('/sell', 'Hall\Controller\Grocery:sell')->name('grocery.sell');
            $this->slim->get('/work', 'Hall\Controller\Grocery:work')->name('grocery.work');
        });

        $this->slim->group('/town', Route::requireLogin(), function() {
            $this->slim->get('/town/index', 'Hall\Controller\Town:index')->name('town.index');
        });

        $this->slim->group('/recruit', Route::requireLogin(), function() {
            $this->slim->map('/index', 'Hall\Controller\Recruit:index')->name('recruit.index')->via('GET', 'POST');
        });
    }

    public function registerSlimViewVariable() {
        $this->slim->view()->setData('config', $config);
    }

    public function registerSlimConfig() {
        $request   = $this->slim->request();
        $site_url  = $request->getUrl().$request->getRootUri();

        $this->slim->config('app.config',   $config);
        $this->slim->config('app.site_url', $site_url);
    }

    public function run() {
        $this->slim->run();
    }

}
