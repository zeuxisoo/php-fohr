<?php
namespace Hall\Context\Job;

use Hall\Base\Job;

class Socerer extends Job {

    public function __construct() {
        parent::__construct('socerer', '法師', 'socerer_boy.gif', 'socerer_girl.gif');
    }

}
