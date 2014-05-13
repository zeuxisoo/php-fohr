<?php
namespace Hall\Controller;

use Hall\Base\Controller;

class Grocery extends Controller {

    public function buy() {
        $this->slim->render('grocery/buy.html');
    }

    public function sell() {
        $this->slim->render('grocery/sell.html');
    }

    public function work() {
        $this->slim->render('grocery/work.html');
    }

}
