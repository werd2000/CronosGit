<?php

namespace LibQ\View;

class FooterViewDecorator extends AbstractViewDecorator
{
    const DEFAULT_TEMPLATE = "footer.php";
    
    public function render() {
        return $this->view->render() . $this->renderTemplate();
    }
}