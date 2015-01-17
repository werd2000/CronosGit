<?php

namespace LibQ\View;

class OuterViewDecorator extends AbstractViewDecorator
{
    const DEFAULT_TEMPLATE = "layout.php";
    
    public function render() {
        $data["innerview"] = $this->view->render();
        return $this->renderTemplate($data);
    }
}
