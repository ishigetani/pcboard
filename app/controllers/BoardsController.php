<?php

use \Phalcon\Tag as Tag;

class BoardsController extends AuthBase
    {

    function indexAction()
    {
        $this->dispatcher->forward(array('controller' => 'boards', 'action' => 'search'));
    }

    public function searchAction()
{
        $parameters["order"] = "id";
        $numberPage = $this->request->getQuery("page", "int");
        if ($numberPage <= 0) {
            $numberPage = 1;
        }

        $boards = Boards::find($parameters);
        if (count($boards) == 0) {
            $this->flash->notice("The search did not find any boards");
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "index"));
        }

        $paginator = new \Phalcon\Paginator\Adapter\Model(array(
            "data" => $boards,
            "limit"=> 10,
            "page" => $numberPage
        ));
        $page = $paginator->getPaginate();

        $this->view->setVar("page", $page);
    }

    public function viewAction($id)
    {
        $id = $this->filter->sanitize($id, array("int"));

        $boards = Boards::findFirst('id="'.$id.'"');
        if (!$boards) {
            $this->flash->error("boards was not found");
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "search"));
        }
        $this->view->setVar("username", $boards->users->name);
        $this->view->setVar("content", $boards->content);
        $this->view->setVar("img_pass", $boards->img_pass);
    }

    public function newAction()
    {

    }

    public function editAction($id)
    {
        $request = $this->request;
        if (!$request->isPost()) {

            $id = $this->filter->sanitize($id, array("int"));

            $boards = Boards::findFirst('id="'.$id.'"');
            if (!$boards) {
                $this->flash->error("boards was not found");
                return $this->dispatcher->forward(array("controller" => "boards", "action" => "search"));
            }
            $user = $this->session->get('auth');
            if ($boards->user_id != $user['id']) {
                $this->flash->error('This contents is not mine');
                return $this->dispatcher->forward(array('controller' => 'boards', 'action' => 'search'));
            }
            $this->view->setVar("id", $boards->id);

            Tag::displayTo("user_id", $boards->user_id);
            Tag::displayTo("content", $boards->content);
            Tag::displayTo("created", $boards->created);
            Tag::displayTo("modified", $boards->modified);
        }
        Tag::displayTo("id", $id);
    }

    public function createAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "index"));
        }

        $boards = new Boards();
        $user = $this->session->get('auth');
        $boards->user_id = $user['id'];
        $boards->content = $this->request->getPost("content");
        if ($this->request->hasFiles()) {
            foreach ($this->request->getUploadedFiles() as $file) {
                $boards->img_pass = $file->getName();
            }
        }
        if (!$boards->save()) {
            foreach ($boards->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "new"));
        } else {
            $file->moveTo(BASE_DIR. '/public/img/' . $file->getName());
            $this->flash->success("boards was created successfully");
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "index"));
        }
    }

    public function saveAction()
    {

        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "index"));
        }

        $id = $this->request->getPost("id", "int");
        $boards = Boards::findFirst("id='$id'");
        if (!$boards) {
            $this->flash->error("boards does not exist ".$id);
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "index"));
        }
        $boards->id = $id;
        $boards->content = $this->request->getPost("content");
        if (!$boards->save()) {
            foreach ($boards->getMessages() as $message) {
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "edit", "params" => array($boards->id)));
        } else {
            $this->flash->success("boards was updated successfully");
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "index"));
        }

    }

    public function deleteAction($id){

        $id = $this->filter->sanitize($id, array("int"));

        $boards = Boards::findFirst('id="'.$id.'"');
        if (!$boards) {
            $this->flash->error("boards was not found");
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "index"));
        }

        if (!$boards->delete()) {
            foreach ($boards->getMessages() as $message){
                $this->flash->error((string) $message);
            }
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "search"));
        } else {
            $this->flash->success("boards was deleted");
            return $this->dispatcher->forward(array("controller" => "boards", "action" => "index"));
        }
    }
}
