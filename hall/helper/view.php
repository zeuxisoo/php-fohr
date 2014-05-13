<?php
namespace Hall\Helper;

use Hall\Base\Job;

class View {
    public function jobImage($team_member) {
        $job = Job::factory($team_member->job_name);

        return ($team_member->character_gender == 'boy') ? $job->image_boy : $job->image_girl;
    }

    public function formatMoney($price) {
        return '$'.number_format($price, 2, '.', ',');
    }
}
