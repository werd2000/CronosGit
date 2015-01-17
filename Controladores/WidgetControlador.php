<?php

class WidgetControlador extends Controlador
{
    
    public function __construct()
    {
        parent::__construct();
        parent::getLibreria('Widget/Div');
    }
    
    
    public function index()
    {
        $div = new Div();
        $this->_vista->titulo = APP_NAME;
        $this->_vista->renderizar('Index');
    }
    
}