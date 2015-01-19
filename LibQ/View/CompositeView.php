<?php

namespace LibQ\View;

class LibQ_View_CompositeView implements ViewInterface
{
    protected $views = array();
    
    public function attachView(ViewInterface $view) {
        if (!in_array($view, $this->views, true)) {
            $this->views[] = $view;
        }
        return $this;
    }
    
    public function detachView(ViewInterface $view) {
        $this->views = array_filter($this->views, function ($value) use ($view) {
            return $value !== $view;
        });
        return $this;
    }
    
    public function render() {
        $output = "";
        foreach ($this->views as $view) {
            $output .= $view->render();
        }
        return $output;
    }
}