<?php
require_once 'Contacto.php';

/**
 * La clase ContactoComerciante reune los datos de contacto de un comerciante
 * La clase hereda de la clase LibQ_Sclases_Contacto
 * @author WERD
 * @see Contacto
 */
class LibQ_Sclases_ContactoComerciante extends LibQ_Sclases_Contacto
{
    /**
     * Id de la BD
     * @var int id
     */
    protected $_id;
    /**
     * Id del comerciante en la BD.
     * @var int valor
     */
    protected $_idComerciante;
    /**
     * Indica si el reg esta eliminado
     * @var boolean eliminado
     */
    protected $_eliminado;

    /**
     * Constructor inicializador de datos
     * @param array $datos
     */
    public function __construct($datos)
    {
        parent::__construct($datos);
        $this->_id = $datos['id'];
        $this->_idComerciante = $datos['idComerciante'];
        $this->_eliminado = $datos['eliminado'];
    }
    
    /**
     * Retorna el tipo de Contacto
     * @return string tipo
     */
    public function getId()
    {
        return $this->_id;
    }
    
    /**
     * Retorna el valor del contacto
     * @return mixed valor
     */
    public function getIdComerciante()
    {
        return $this->_idComerciante;
    }
    
    /**
     * Retorna las observaciones del contacto
     * @return string observaciones
     */
    public function getEliminado()
    {
        return $this->_eliminado;
    }
    
}

