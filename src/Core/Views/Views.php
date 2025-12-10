<?php

namespace Core\Views;

class Views
{
    protected string $layout = 'main';
    protected array $sections = [];
    protected string $currentSection = '';

    public function layout(string $layout)
    {
        $this->layout = $layout;
    }

    public function start(string $section)
    {
        $this->currentSection = $section;
        ob_start();
    }

    public function end()
    {
        $this->sections[$this->currentSection] = ob_get_clean();
        $this->currentSection = '';
    }

    public function render(string $view, array $data=[]) 
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . "/../../../resources/views/$view.php";
        if (!file_exists($viewFile)) {
            throw new \Exception("View file not found: $viewFile");
        }
        ob_start();
        require $viewFile;
        $content = ob_get_clean();
        $layoutFile = __DIR__ . "/../../../resources/layouts/" . $this->layout . ".php";
        if(!file_exists($layoutFile)) {
            throw new \Exception("Layout file not found: $layoutFile");
        }
        require $layoutFile;
    }

    public function section(string $section): string
    {
        return $this->sections[$section] ?? '';
    }
}
