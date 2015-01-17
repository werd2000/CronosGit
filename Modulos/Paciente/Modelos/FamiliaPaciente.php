<?php
/**
 * Clase FamiliaPaciente
 *
 * @author WERD
 */
class FamiliaPaciente
{
    protected $_id;
    protected $_idPaciente;
    protected $_parentesco;
    protected $_nombre;
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
    
    public function getParentesco()
    {
        return $this->_parentesco;
    }
    
    public function getNombre()
    {
        return $this->_nombre;
    }
    
    public function getObservaciones()
    {
        return $this->_observaciones;
    }
    
    public function getEliminador()
    {
        return $this->_eliminado;
    }
    
    public function __construct($datos)
    {
        $this->_id = $datos['id'];
        $this->_idPaciente = $datos['idPaciente'];
        $this->_nombre = $datos['nombre'];
        $this->_observaciones = $datos['observaciones'];
        $this->_parentesco = $datos['parentesco'];
        $this->_eliminado = $datos['eliminado'];
    }
    
    public static function getFamiliares($lista = array())
    {
        $resultado = array();
//        print_r($lista);
        if (count($lista)>1){
            foreach ($lista as $datos) {
                $resultado[] = new FamiliaPaciente($datos);
            }
        }
        return $resultado;
    }
    
    public function __toString()
    {
        return $this->_nombre . '('. $this->_parentesco .')';
    }
}

