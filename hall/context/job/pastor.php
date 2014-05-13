<?php
namespace Hall\Context\Job;

use Hall\Base\Job;

class Pastor extends Job {

    public function __construct() {
        parent::__construct('pastor', '牧師', 'pastor_boy.gif', 'pastor_girl.gif');
    }

}
