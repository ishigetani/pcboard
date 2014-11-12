<?php
/**
 * Created by PhpStorm.
 * User: n1220012
 * Date: 14/09/30
 * Time: 13:39
 */
use \Phalcon\Tag as Tag;

class AuthController extends ControllerBase {

    private function _setSession($user)
    {
        $this->session->set('auth', array(
            'id' => $user->id,
            'login_name' => $user->login_name,
            'name' => $user->name
        ));
    }

    public function loginAction()
    {
        if ($this->request->isPost()) {
            $login_name = $this->request->getPost('login_name');
            $password = $this->request->getPost('password');

            $password = sha1($password);

            $user = Users::findFirst(array(
                "login_name = :login_name: AND password = :password:",
                "bind" => array('login_name' => $login_name, 'password' => $password)
            ));

            if (!empty($user)) {
                // ログイン成功
                $this->_setSession($user);
                return $this->response->redirect("boards/");
            }
            // ログイン失敗
            $this->flashSession->error('ID or password is incorrect.');
            return $this->response->redirect("");
        }
    }

    public function logoutAction()
    {
        $this->session->remove('auth');
        $this->response->redirect('');
    }

    public function createAction()
    {

    }
}