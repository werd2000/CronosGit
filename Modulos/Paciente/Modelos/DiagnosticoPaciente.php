<?php
//require_once BASE_PATH . 'Modulos' . DS . 'Personal' . DS . 'Modelos' . DS .  'Personal.php';
//require_once BASE_PATH . 'Modulos' . DS . 'Terapias' . DS . 'Modelos' . DS .  'Terapia.php';
//require_once 'PersonalPacienteModelo.php';
//require_once 'TerapiaModelo.php';

/**
 * Clase TerapiaPaciente
 *
 * @author WERD
 */
class DiagnosticoPaciente
{
    protected $_id;
    protected $_diagnostico;

        /** Métodos GET */
    public function getId()
    {
        return $this->_id;
    }
    
    public function getDiagnostico()
    {
        return $this->_diagnostico;
    }
    
    public function __construct($datos)
    {
        $this->_id = $datos['id'];
        $this->_diagnostico = $datos['diagnostico'];
    }
    
    public function __toString()
    {
        return ''.$this->_diagnostico;
    }
    
}