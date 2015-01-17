<?php
namespace LibQ\View;


require_once BASE_PATH . 'LibQ' . DS . 'View' . DS . 'TemplateInterface.php' ;
require_once BASE_PATH . 'LibQ' . DS . 'View' . DS . 'ContainerInterface.php' ;
require_once BASE_PATH . 'LibQ' . DS . 'View' . DS . 'ViewInterface.php' ;

class View implements TemplateInterface, ContainerInterface, ViewInterface 
{
    public function __set($field, $value)
    {
        
    }
    
    public function __get($field)
    {
        
    }
    
    public function __isset($field)
    {
        
    }
    
    public function __unset($field)
    {
        
    }
    
    public function setTemplate($template)
    {
        
    }
    
    public function getTemplate()
    {
        
    }
    
    public function render()
    {
        
    }
}