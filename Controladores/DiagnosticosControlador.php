<?php

class DiagnosticosControlador extends Controlador
{    
    protected $_diagnosticos;
    
    public function __construct() 
    {
        parent::__construct();
        $this->_diagnosticos = $this->cargarModelo('diagnosticos');
    }
    
    public function index()
    {
    }
    
    public function getListaDiagnosticos()
    {
        $lista = array();
        $datos = $this->_diagnosticos->getDiagnosticos();
//        print_r($datos);
        foreach ($datos as $diag) {
            $lista[]="'".$diag['diagnostico']."'";
        }
        echo implode(',', $lista);
        return implode(',', $lista);
    }
}