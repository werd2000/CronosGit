<?php

namespace LibQ\View;

abstract class AbstractViewDecorator implements ViewInterface
{
    const DEFAULT_TEMPLATE = "default.php";
    protected $template = self::DEFAULT_TEMPLATE;
    protected $view;

    public function __construct(ViewInterface $view) {
        $this->view = $view;
    }
    
    public function render() {
        return $this->view->render();
    }
    
    protected function renderTemplate(array $data = array()) {
        extract($data);
        ob_start();
        include $this->template;
        return ob_get_clean();
    }
}
