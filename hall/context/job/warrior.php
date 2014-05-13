<?php
namespace Hall\Context\Job;

use Hall\Base\Job;

class Warrior extends Job {

    public function __construct() {
        parent::__construct('warrior', '戰士', 'warrior_boy.gif', 'warrior_girl.gif');
    }

}
