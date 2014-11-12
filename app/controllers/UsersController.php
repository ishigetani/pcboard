<?php

use \Phalcon\Tag as Tag;

class UsersController extends AuthBase
    {

    function indexAction()
    {
        $this->dispatcher->forward(array('controller' => 'users', 'action' => 'search'));
    }

    public function searchAction()
{
        $numberPage = $this->request->getQuery("page", "int");
        if ($numberPage <= 0) {
            $numberPage = 1;
        }

        $parameters = array();
        $parameters["order"] = "id";

        $users = Users::find($parameters);
        if (count($users) == 0) {
            $this->flash->notice("The search did not find any users");
            return;
        }

        $paginator = new \Phalcon\Paginator\Adapter\Model(array(
            "data" => $users,
            "limit"=> 10,
            "page" => $numberPage
        ));
        $page = $paginator->getPaginate();

        $this->view->setVar("page", $page);
    }

    public function newAction()
    {

    }

    public function editAction($id)
    {
        $request = $this->request;
        if (!$request->isPost()) {

            $id = $this->filter->sanitize($id, array("int"));

            $user = $this->session->get('auth');
            if ($id != $user['id']) {
                $this->flash->error('This contents is not mine');
                return $this->dispatcher->forward(array('controller' => 'users', 'action' => 'search'));
            }

            $users = Users::findFirst('id="'.$id.'"');
            if (!$users) {
                $this->flash->error("users was not found");
                return $this->dispatcher->forward(array("controller" => "users", "action" => "search"));
            }
            $this->view->setVar("id", $users->id);

            Tag::displayTo("login_name", $users->login_name);
            Tag::displayTo("name", $users->name);
        }
        Tag::displayTo("id", $id);
    }

    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array("controller" => "users", "action" => "index"));
        }

        $users = new Users();
        $users->id = $this->request->getPost("id");
        $users->login_name = $this->request->getPost("login_name");
        $users->password = $this->request->getPost("password");
        $users->name = $this->request->getPost("name");
        $users->created = $this->request->getPost("created");
        $users->modified = $this->request->getPost("modified");
        if (!$users->save()) {
            foreach ($users->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array("controller" => "users", "action" => "new"));
        } else {
            $this->flash->success("users was created successfully");
            return $this->dispatcher->forward(array("controller" => "users", "action" => "search"));
        }

    }

    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array("controller" => "users", "action" => "index"));
        }

        $id = $this->request->getPost("id", "int");
        $users = Users::findFirst("id='$id'");
        if (!$users) {
            $this->flash->error("users does not exist ".$id);
            return $this->dispatcher->forward(array("controller" => "users", "action" => "index"));
        }
        $users->id = $this->request->getPost("id");
        $users->login_name = $this->request->getPost("login_name");
        $users->password = $this->request->getPost("password");
        $users->name = $this->request->getPost("name");
        if (!$users->save()) {
            foreach ($users->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array("controller" => "users", "action" => "edit", "params" => array($users->id)));
        } else {
            $this->flash->success("users was updated successfully");
            return $this->dispatcher->forward(array("controller" => "users", "action" => "search"));
        }
    }

    public function deleteAction($id){

        $id = $this->filter->sanitize($id, array("int"));

        $user = $this->session->get('auth');
        if ($id != $user['id']) {
            $this->flash->error('This contents is not mine');
            return $this->dispatcher->forward(array('controller' => 'users', 'action' => 'index'));
        }

        $users = Users::findFirst('id="'.$id.'"');
        if (!$users) {
            $this->flash->error("users was not found");
            return $this->dispatcher->forward(array("controller" => "users", "action" => "index"));
        }

        if (!$users->delete()) {
            foreach ($users->getMessages() as $message){
                $this->flash->error((string) $message);
            }
        } else {
            $this->flash->success("users was deleted");
        }
        return $this->dispatcher->forward(array("controller" => "users", "action" => "search"));
    }

}
