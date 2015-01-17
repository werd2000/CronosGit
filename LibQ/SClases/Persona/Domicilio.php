<?php

/**
 * La clase domicilio reune los datos de localización una persona en un objeto
 *
 * @author WERD
 */
abstract class LibQ_Sclases_Persona_Domicilio {
    
    protected $_tipo_domicilio;
    protected $_calle;
    protected $_casa_nro;
    protected $_barrio;
    protected $_piso;
    protected $_depto;
    protected $_localidad;
    protected $_cp;
    protected $_provincia;
    protected $_pais;
    
    public function __construct($datos = array()) {
     $this->_tipo_domicilio = (isset($datos['tipo_domicilio'])) ? $datos['tipo_domicilio']:'';
        $this->_calle = (isset($datos['calle'])) ? $datos['calle']:'';
        $this->_casa_nro = (isset($datos['casa_nro'])) ? $datos['casa_nro']:'';
        $this->_barrio = (isset($datos['barrio'])) ? $datos['barrio']:'';
        $this->_barrio = (isset($datos['barrio'])) ? $datos['barrio']:'';
        $this->_cp = (isset($datos['cp'])) ? $datos['cp']:'';        
        $this->_depto = (isset($datos['depto'])) ? $datos['depto']:'';        
        $this->_localidad = (isset($datos['localidad'])) ? $datos['localidad']:'';        
        $this->_pais = (isset($datos['pais'])) ? $datos['pais']:'';        
        $this->_piso = (isset($datos['piso'])) ? $datos['piso']:'';        
        $this->_provincia = (isset($datos['provincia'])) ? $datos['provincia']:'';                
    }
    
    public function getCalle(){
        return $this->_calle;
    }
    
    public function getCasa_nro(){
        return $this->_casa_nro;
    }
    
    public function getBarrio(){
        return $this->_barrio;
    }

        public function getPiso(){
        return $this->_piso;
    }
    
    public function getDepto(){
        return $this->_depto;
    }
    
    public function getLocalidad(){
        return $this->_localidad;
    }
    
    public function getCp(){
        return $this->_cp;
    }
    
    public function getProvincia(){
        return $this->_provincia;
    }
    
    public function getPais(){
        return $this->_pais;
    }
    
    public function __toString() {
        return $this->_calle . ' ' . $this->_casa_nro;
    }
   
}
