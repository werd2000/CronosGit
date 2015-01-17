<?php
/**
 * La clase contacto reune los datos de contacto
 *
 * @author WERD
 */
class LibQ_Sclases_Contacto
{
    /**
     * Tipo de contacto
     * @var string tipo
     */
    protected $_tipo;
    /**
     * Numero de tel, cel, direcc de mail, web, etc.
     * @var mixed valor
     */
    protected $_valor;
    /**
     * Observaciones del contacto
     * @var string observaciones
     */
    protected $_observaciones;

    /**
     * Constructor inicializador de datos
     * @param array $datos
     */
    public function __construct($datos)
    {
        $this->_tipo = $datos['tipo'];
        $this->_valor = $datos['valor'];
        $this->_observaciones = $datos['observaciones'];
    }
    
    /**
     * Retorna el tipo de Contacto
     * @return string tipo
     */
    public function getTipo()
    {
        return $this->_tipo;
    }
    
    /**
     * Retorna el valor del contacto
     * @return mixed valor
     */
    public function getValor()
    {
        return $this->_valor;
    }
    
    /**
     * Retorna las observaciones del contacto
     * @return string observaciones
     */
    public function getObservaciones()
    {
        return $this->_observaciones;
    }
    
    /**
     * Retorna tipo y valor del contacto
     * @return strinh
     */
    public function __toString()
    {
        return $this->_tipo . ': ' . $this->_valor;
    }
}

