<?php
/**
 * Clase ContactoPaciente
 *
 * @author WERD
 */
class DomicilioPaciente extends LibQ_Sclases_Persona_Domicilio
{
    protected $_id;
    protected $_idPaciente;
    protected $_eliminado;
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getIdPaciente()
    {
        return $this->_idPaciente;
    }
    
    public function __construct($datos)
    {
        parent::__construct($datos);
        $this->_id = $datos['id'];
        $this->_idPaciente = $datos['id_paciente'];
        $this->_eliminado = $datos['eliminado'];
    }
    
}
