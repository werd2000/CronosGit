<?php
require_once LIB_PATH . 'Fechas.php';
require_once 'Contacto.php';

/**
 * Clase abstracta de la clase persona
 * @package Persona
 * @author WERD
 */
abstract class LibQ_Sclases_Persona_Persona {
    /**
     * Apellidos de la persona
     * @var string 
     */
    protected $_apellidos;
    /**
     * Nombres de la persona
     * @var string
     */
    protected $_nombres;
    /**
     * Devuelve el apellido y nombre de la persona
     * @var string
     */
    protected $_AyN;
    /**
     * El objeto domicilio de la persona
     * @var Domicilio
     */
    protected $_domicilio;
    /**
     * Tipo de documento
     * @var int
     */
    protected $_tipo_doc;
    /**
     * Número de documento
     * @var string
     */
    protected $_nro_doc;
    /**
     * Nacionalidad
     * @var string
     */
    protected $_nacionalidad;
    /**
     * Fecha de nacimiento
     * @var datetime
     */
    protected $_fecha_nac;
    /**
     * Sexo
     * @var string
     */
    protected $_sexo;
    
    /**
     * Contactos
     * @var array 
     */
    protected $_contactos;

    public function __construct($persona = array()) {
        $this->_apellidos = $persona['apellidos'];
        $this->_fecha_nac = $persona['fecha_nac'];
        $this->_nacionalidad = $persona['nacionalidad'];
        $this->_nombres = $persona['nombres'];
        $this->_nro_doc = $persona['nro_doc'];
        $this->_sexo = $persona['sexo'];
        $this->_tipo_doc = $persona['tipo_doc'];
    }
    
    /**
     * Estabece el array de contactos de la persona
     * @param Array $contactos
     */
    public function setContactos(Array $contactos)
    {
        $this->_contactos = $contactos;
    }
    
    
    /**
     * Estabece el array de contactos de la persona
     * @param Array $domicilios
     */
    public function setDomicilio($domicilios)
    {
        $this->_domicilio = $domicilios;
    }

    public function getApellidos() {
        return $this->_apellidos;    
    }
    
    public function getNombres() {
        return $this->_nombres;
    }
    
    public function getAyN() {
        if (isset($this->_apellidos) AND $this->_apellidos != '') {
            $ayn = $this->_apellidos . ', ' . $this->_nombres;
        } else {
            $ayn = $this->_nombres;
        }
        return $ayn;
    }
    
    public function getFecha_nac() {
        $fecha = new LibQ_Fecha($this->_fecha_nac);
        return $fecha->getDate();
    }
    
    public function getNacionalidad() {
        return $this->_nacionalidad;
    }
    
    public function getNro_doc() {
        return $this->_nro_doc;
    }

    public function getSexo() {
        return $this->_sexo;
    }
    
    public function getTipo_doc() {
        return $this->_tipo_doc;
    }
    
    public function getContactos()
    {
        return $this->_contactos;
    }
    
    public function getDomicilio()
    {
        return $this->_domicilio;
    }

    public function __toString() {
        return $this->getAyN();
    }
}