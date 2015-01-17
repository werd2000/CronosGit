<?php

/**
 * La clase domicilio reune los datos de localización una persona en un objeto
 *
 * @author WERD
 */
class LibQ_Sclases_Domicilio_DomicilioDepto extends LibQ_Sclases_Domicilio_Domicilio
{

    /**
     * Nro del piso
     * @var int
     */
    protected $_piso;

    /**
     * Identificación de la casa o depto
     * @var string
     */
    protected $_depto;

    /**
     * Inicialización de los datos
     * @param array $datos
     */
    public function __construct($datos = array())
    {
        parent::__construct();
        $this->_depto = (isset($datos['depto'])) ? $datos['depto'] : '';
        $this->_piso = (isset($datos['piso'])) ? $datos['piso'] : '';
    }

    /**
     * Obtiene el nro del piso
     * @return int
     */
    public function getPiso()
    {
        return $this->_piso;
    }

    /**
     * Obtiene la identificacion de la casa o depto
     * @return string
     */
    public function getDepto()
    {
        return $this->_depto;
    }
}
