<?php

class AuthBase extends ControllerBase
{
    public function initialize()
    {
        $auth = $this->session->get('auth');
        if (empty($auth)) {
            $this->flashSession->error('Please Login!!');
            return $this->response->redirect("");
        }
        parent::initialize();
    }

    public function afterExecuteRoute($dispatcher)
    {
        $user = $this->session->get('auth');
        $this->view->setVar("my_user", $user);
    }
}