<?php

class ControllerBase extends \Phalcon\Mvc\Controller
{
    public function initialize(){}

    protected function _forward($controller = null, $action = null)
    {
        if (empty($controller) || empty($action)) return;
        $this->dispatcher->forward(array(
            "controller" => $controller,
            "action" => $action
        ));
    }
}