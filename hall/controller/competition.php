<?php
namespace Hall\Controller;

use Hall\Base\Controller;

class Competition extends Controller {

    public function index() {
        $this->slim->render('competition/index.html');
    }

}
