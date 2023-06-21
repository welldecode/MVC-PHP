<?php

namespace app\library;

class Controller
{
    public $view = null;
    protected $action = null;
    protected $controller = null;
    protected $params = null;

    public function init(string $action, array $params)
    {
        $this->view = new View();

        $this->action = $action;
        $this->$action($params);

        $controller = get_class($this); 
        $controller_paste = strtolower(str_replace('Controller', '', pathinfo($controller, PATHINFO_FILENAME)));

        $this->view->render( $controller_paste, $this->renderView($action));
    }

    protected function renderView(string $action)
    {
        $controller = get_class($this);

        $controller_paste = strtolower(str_replace('Controller', '', pathinfo($controller, PATHINFO_FILENAME)));
     
        return $controller_paste . '\\' . $action . '.php';
    }
}
