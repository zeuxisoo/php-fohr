<?php
namespace Hall\Base;

class Job {

    public $id;
    public $name;
    public $image_boy;
    public $image_girl;

    public function __construct($id, $name, $image_boy, $image_girl) {
        $this->id         = $id;
        $this->name       = $name;
        $this->image_boy  = $image_boy;
        $this->image_girl = $image_girl;
    }

    public function factory($job_name) {
        $job_class = "\\Hall\\Context\\Job\\".ucfirst($job_name);

        return new $job_class();
    }

}
