<?php
/**
 * La clase domicilio reune los datos de localización una persona en un objeto
 *
 * @author WERD
 */
class LibQ_Sclases_Domicilio_Domicilio
{

    /**
     * Tipo de domicilio: real - fiscal
     * @var string
     */
    protected $_tipo_domicilio;

    /**
     * Nombre o numero de la calle
     * @var string
     */
    protected $_calle;

    /**
     * Nro de la casa
     * @var int
     */
    protected $_casa_nro;

    /**
     * Nombre del barrio
     * @var string
     */
    protected $_barrio;

    /**
     * Nro del piso
     * @var int
     */
    protected $_localidad;

    /**
     * Código postal
     * @var string
     */
    protected $_cp;

    /**
     * Provincia
     * @var string
     */
    protected $_provincia;

    /**
     * País
     * @var string
     */
    protected $_pais;

    /**
     * Inicialización de los datos
     * @param array $datos
     */
    public function __construct($datos = array())
    {
        $this->_tipo_domicilio = (isset($datos['tipo_domicilio'])) ? $datos['tipo_domicilio'] : '';
        $this->_calle = (isset($datos['calle'])) ? $datos['calle'] : 's/d';
        $this->_casa_nro = (isset($datos['casa_nro'])) ? $datos['casa_nro'] : '';
        $this->_barrio = (isset($datos['barrio'])) ? $datos['barrio'] : '';
        $this->_cp = (isset($datos['cp'])) ? $datos['cp'] : '';
        $this->_localidad = (isset($datos['localidad'])) ? $datos['localidad'] : '';
        $this->_pais = (isset($datos['pais'])) ? $datos['pais'] : '';
        $this->_provincia = (isset($datos['provincia'])) ? $datos['provincia'] : '';
    }
    
    public function getTipo_domicilio()
    {
        return $this->_tipo_domicilio;
    }

    /**
     * Obtiene el nombre o nro de la calle
     * @return string
     */
    public function getCalle()
    {
        return $this->_calle;
    }

    /**
     * Obtiene el nro de la casa
     * @return int
     */
    public function getCasa_nro()
    {
        return $this->_casa_nro;
    }

    /**
     * Obtiene el nombre del barrio
     * @return string
     */
    public function getBarrio()
    {
        return $this->_barrio;
    }

    /**
     * Obtiene la localidad
     * @return string
     */
    public function getLocalidad()
    {
        return $this->_localidad;
    }

    /**
     * Obtiene el código postal
     * @return string
     */
    public function getCp()
    {
        return $this->_cp;
    }

    /**
     * Obtiene la provincia
     * @return string
     */
    public function getProvincia()
    {
        return $this->_provincia;
    }

    /**
     * Obtiene el país
     * @return string
     */
    public function getPais()
    {
        return $this->_pais;
    }

    /**
     * Obtiene la calle y nro de casa
     * @return string
     */
    public function __toString()
    {
        if (isset($this->_casa_nro) && $this->_casa_nro != '') {
            $retorno = $this->_calle . ' ' . $this->_casa_nro;
        } else {
            $retorno = $this->_calle;
        }
        return $retorno;
    }

}
