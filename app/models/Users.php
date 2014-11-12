<?php


class Users extends Apps
{

    /**
     * @var string
     *
     */
    public $login_name;

    /**
     * @var string
     *
     */
    public $password;

    /**
     * @var string
     *
     */
    public $name;

    public function initialize()
    {
        $this->hasMany('id', 'Boards', 'user_id');
    }

    public function beforeSave()
    {
        if (!empty($this->password)) {
            $this->password = sha1($this->password);
        }
    }
}
