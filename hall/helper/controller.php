<?php
namespace Hall\Helper;

use Slim\Slim;

class Controller {
    public function __construct() {
        $this->slim = Slim::getInstance();
    }
}
