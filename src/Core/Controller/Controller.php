<?php

namespace Core\Controller;

use Core\Http\Request;
use Core\Views\Views;

class Controller
{
    protected Request $request;
    protected Views $view;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->view = new Views();
    }
    protected function layout(string $name)
    {
        $this->view->layout($name);
    }

    protected function view(string $name, array $data = [])
    {
        $this->view->render($name, $data);
    }

    protected function render(string $layout, string $view, array $data = [])
    {
        $this->view->layout($layout);
        return $this->view->render($view, $data);
    }
}
