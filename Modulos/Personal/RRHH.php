<?php

require_once LibQ . 'Persona/Persona.php';
require_once DIRMODULOS . 'ContactosRRHH/ContactosRRHH.php';
require_once DIRMODULOS . 'DatosLaboralesRRHH/DatosLaboralesRRHH.php';
require_once DIRMODULOS . 'RRHH/Modelo/RRHHModelo.php';

class RRHH extends Persona {

    private $_contactos;
    private $_modelo;
    private $_terapias;
    private $_observacionesOs;
    private $_datosLaborales;

    function __construct($datos) 
    {
        parent::__construct($datos);
        $this->_modelo = new RRHHModelo(); 
        $this->_contactos = new ContactosRRHH();
        $this->_datosLaborales = new DatosLaboralesRRHH();
    }

    public function getContactos() {
        return $this->_contactos->getContactos($this->getId());
    }
       
    public function getEdad(){
        $edad = date(Y) - parent::getFechaNac();
        return intval($edad);
    }
        
    public function getTerapias()
    {
        return $this->_terapias;
    }
        
    public function getObservaciones()
    {
        return $this->_observacionesOs;
    }
    
    public function getDatosLaborales()
    {
        return $this->_datosLaborales;
    }
    
    

}