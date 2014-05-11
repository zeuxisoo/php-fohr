<?php
namespace Hall\Controller;

use Hall\Helper\Controller;

class Forging extends Controller {

    public function refine() {
        $this->slim->render('forging/refine.html');
    }

    public function create() {
        $this->slim->render('forging/create.html');
    }

}
