<?php

use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;

class Boards extends Apps
{

    /**
     * @var integer
     *
     */
    public $user_id;

    /**
     * @var string
     *
     */
    public $content;

    public $img_pass;

    public function initialize()
    {
        $this->belongsTo("user_id", "Users", "id");
    }

    public function validation()
    {
        $this->validate(new Uniqueness(array(
            'field' => 'img_pass'
        )));
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }
}
