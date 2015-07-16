<?php

class Controladores_errorControlador extends App_Controlador
{
    public function __construct() {
        parent::__construct();
    }
    
    public function index()
    {
        $this->_vista->titulo = 'Error';
        $this->_vista->mensaje = $this->_getError();
        $this->_vista->renderizar('index');
    }
    
    public function acceso($codigo)
    {
        $this->_vista->titulo = 'Error ' . $codigo;
        $this->_vista->mensaje = $this->_getError($codigo);
        $this->_vista->renderizar('acceso','default');
    }
    
    private function _getError($codigo = false)
    {
        if($codigo){
            $codigo = $this->filtrarInt($codigo);
            if(is_int($codigo))
                $codigo = $codigo;
        }else{
            $codigo = 'default';
        }
        $error['default'] = 'Ha ocurrido un error y la página no puede mostrarse';
        $error['5050'] = 'Acceso restringido!';
        $error['8080'] = 'Tiempo de sesión agotado!';
        
        if(array_key_exists($codigo, $error)){
            return $error[$codigo];
        }else{
            return $error['default'];
        }
    }
}