<?php

use Phalcon\Mvc\Model\Validator\Uniqueness as Uniqueness;
use Phalcon\Validation\Validator\PresenceOf as PresenceOf;

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

        $this->validate(new Uniqueness(
            array(
                "field"   => "img_pass",
                "message" => "ロボットの名前が重複してはいけません"
            )
        ));

        return $this->validationHasFailed() != true;
    }
}
