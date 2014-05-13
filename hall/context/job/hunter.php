<?php
namespace Hall\Context\Job;

use Hall\Base\Job;

class Hunter extends Job {

    public function __construct() {
        parent::__construct('hunter', '獵人', 'hunter_boy.gif', 'hunter_girl.gif');
    }

}
