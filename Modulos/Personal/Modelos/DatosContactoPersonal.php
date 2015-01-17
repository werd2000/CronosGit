<?php
/**
 * Description of Datos Contacto Personal
 *
 * @author WERD
 */
class datosContactoPersonal
{
    protected $_id;
    protected $_idProfesional;
    protected $_tipo;
    protected $_valor;
    protected $_observaciones;
    protected $_eliminado;

    public function __construct($datos)
    {
        $this->_id = $datos['id'];
        $this->_idProfesional = $datos['idProfesional'];
        $this->_tipo = $datos['tipo'];
        $this->_valor = $datos['valor'];
        $this->_observaciones = $datos['observaciones'];
        $this->_eliminado = $datos['eliminado'];
    }
    
    public function getId()
    {
        return $this->_id;
    }

    public function getIdProfesional()
    {
        return $this->_idProfesional;
    }
    
    public function getTipo()
    {
        return $this->_tipo;
    }
    
    public function getValor()
    {
        return $this->_valor;
    }
    
    public function getObservaciones()
    {
        return $this->_observaciones;
    }
    
    
    public function __toString()
    {
        return $this->_tipo . ' ' . $this->_valor;
    }
}

?>
