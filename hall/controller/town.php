<?php
namespace Hall\Controller;

use Hall\Base\Controller;

class Town extends Controller {

    public function index() {
        $this->slim->render('town/index.html');
    }

}
