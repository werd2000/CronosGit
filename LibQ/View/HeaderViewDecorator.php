<?php

namespace LibQ\View;

class HeaderViewDecorator extends AbstractViewDecorator
{
    const DEFAULT_TEMPLATE = "header.php";
    
    public function render() {
        return $this->renderTemplate() . $this->view->render();
    }
}