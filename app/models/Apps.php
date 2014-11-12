<?php
/**
 * Created by PhpStorm.
 * User: ishigetani
 * Date: 14/09/10
 * Time: 13:37
 */

class Apps extends \Phalcon\Mvc\Model {

    /**
     * primary key
     * @var integer
     */
    public $id;
    /**
     * create datetime
     * @var datetime
     */
    public $created;
    /**
     * update datetime
     * @var datetime
     */
    public $modified;

    public function beforeValidationOnCreate()
    {
        $this->created = $this->getNow();
        $this->modified = $this->getNow();
    }

    public function beforeValidationOnUpdate()
    {
        $this->modified = $this->getNow();
    }

    public function getNow($format = null)
    {
        if (empty($format)) $format = 'Y-m-d H:i:s';
        $Date = new Datetime();
        return $Date->format($format);
    }
}