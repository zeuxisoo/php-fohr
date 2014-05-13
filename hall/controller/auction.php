<?php
namespace Hall\Controller;

use Hall\Base\Controller;

class Auction extends Controller {

    public function index() {
        $this->slim->render('auction/index.html');
    }

}
