<?php


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

    public function initialize()
    {
        $this->belongsTo("user_id", "Users", "id");
    }
}
