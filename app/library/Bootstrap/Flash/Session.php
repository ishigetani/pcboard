<?php

namespace Bootstrap\Flash;

use Phalcon\Flash\Session as PhFlash;

class Session extends PhFlash
{
    public function __construct($cssClasses = null)
    {
        if ($cssClasses === null) {
            $cssClasses = array(
                'success' => 'alert alert-success',
                'notice'  => 'alert alert-info',
                'warning' => 'alert alert-warning',
                'error'   => 'alert alert-danger',
            );
        }
        parent::__construct($cssClasses);
    }
}