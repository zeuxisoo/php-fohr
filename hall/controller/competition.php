<?php
namespace Hall\Controller;

use Hall\Helper\Controller;

class Competition extends Controller {

    public function index() {
        $this->slim->render('competition/index.html');
    }

}
