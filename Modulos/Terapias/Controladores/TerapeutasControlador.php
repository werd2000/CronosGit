<?php
/**
 * Clase Terapias Controlador 
 */
class terapeutasControlador extends terapiasControlador
{

    private $_terapias;

    public function __construct()
    {
        parent::__construct();
        $this->_terapias = $this->cargarModelo('index');
    }

    
    public function getTerapiaTerapeuta($id)
    {        
        $datos=$this->_terapias->getTerapiaTerapeuta($this->filtrarInt($id));
        echo $datos[0]['puesto'];
    }
    
    

}