<?php

namespace Bootstrap\Flash;
use Library\Bootstrap\Flash\Session;

class Closable extends Library\Bootstrap\Flash\Session
{
    public function __construct($cssClasses = null)
    {
        if (is_array($cssClasses)) {
            $cssClasses = array_map(function ($cssClass) {
                return $cssClass.' fade in';
            }, $cssClasses);
        }
        parent::__construct($cssClasses);
    }

    public function message($type, $message)
    {
        $button = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>';
        parent::message($type, $button.$message);
    }
}