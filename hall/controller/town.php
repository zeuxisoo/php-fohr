<?php
namespace Hall\Controller;

use Hall\Helper\Controller;

class Town extends Controller {

    public function index() {
        $this->slim->render('town/index.html');
    }

}
