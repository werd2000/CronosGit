<?php

/**
 * Clase EducacionPaciente
 *
 * @author WERD
 */
class EducacionPaciente
{
    protected $_id;
    protected $_idPaciente;
    protected $_idEscuela;
    protected $_denominacionEscuela;
    protected $_curso;
    protected $_nivel;
    protected $_observaciones;
    protected $_eliminado;
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getIdPaciente()
    {
        return $this->_idPaciente;
    }
    
    public function getIdEscuela()
    {
        return $this->_idEscuela;
    }
    
    public function getDenominacionEscuela()
    {
        return $this->_denominacionEscuela;
    }

    public function getNivel()
    {
        return $this->_nivel;
    }
    
    public function getCurso()
    {
        return $this->_curso;
    }
    
    public function getObservaciones()
    {
        return $this->_observaciones;
    }
    
    public function getEliminado()
    {
        return $this->_eliminado;
    }
    
    public function __construct($datos)
    {
        $this->_id = $datos['id'];
        $this->_idEscuela = $datos['id_escuela'];
        $this->_denominacionEscuela = '';
        $this->_idPaciente = $datos['id_paciente'];
        $this->_curso = $datos['curso'];
        $this->_nivel = $datos['nivel'];
        $this->_observaciones = $datos['observaciones'];
    }
    
}
