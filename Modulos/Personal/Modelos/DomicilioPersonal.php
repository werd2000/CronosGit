<?php
/**
 * Clase ContactoPersonal
 *
 * @author WERD
 */
class DomicilioPersonal extends LibQ_Sclases_Persona_Domicilio
{
    protected $_id;
    protected $_idPersonal;
    protected $_eliminado;
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getIdPersonal()
    {
        return $this->_idPersonal;
    }
    
    public function __construct($datos)
    {
        parent::__construct($datos);
        $this->_id = $datos['id'];
        $this->_idPersonal = $datos['id_personal'];
        $this->_eliminado = $datos['eliminado'];
    }
    
}
