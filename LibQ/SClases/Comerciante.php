<?php

require_once LIB_PATH . 'Sclases' . DS . 'Domicilio' . DS . 'Domicilio.php';
require_once 'ContactoComerciante.php';

/**
 * Clase abstracta Comerciante
 * Engloba a los clientes y proveedores
 *
 * @author WERD
 */
abstract class LibQ_Sclases_Comerciante
{

    /**
     * Razon social por la que se conoce al comerciante
     * @var string
     */
    private $_razon_social;

    /**
     * Objeto Domicilio donde se puede ubicar al comerciante
     * @var Objeto Domicilio
     */
    private $_domicilio;

    /**
     * Condición ante el iva
     * @var string
     */
    private $_condicion_iva;

    /**
     * Número de CUIT
     * @var string
     */
    private $_cuit;

    /**
     * Lista de Objetos Contacto donde localizar al comerciante
     * @var array Contacto
     */
    private $_contactos;
    
    private $_notas;

    /**
     * Inicialización de los datos
     * @param array $datos
     */
    public function __construct($datos)
    {
        $this->_razon_social = $datos['razon_social'];
        if (isset($datos['domicilio'])){
            if (is_a($datos['domicilio'], 'LibQ_Sclases_Domicilio_Domicilio')){
                $this->setDomicilio($datos['domicilio']);
            }else{
                $this->_domicilio = new LibQ_Sclases_Domicilio_Domicilio($datos['domicilio']);
            }
        }
        $this->_condicion_iva = $datos['condicion_iva'];
        $this->_cuit = $datos['cuit'];
        if (isset($datos['contacto'])){
            if (is_a($datos['contacto'], 'LibQ_Sclases_Contacto')){
                $this->setContactos($datos['contacto']);
            }else{
                $this->_contactos = new LibQ_Sclases_Contacto($datos['contacto']);
            }
        }
        if(isset($datos['notas'])){
            $this->_notas = $datos['notas'];
        }
    }

    /**
     * Obtiene la razon social del comerciante
     * @return string
     */
    public function getRazon_social()
    {
        return $this->_razon_social;
    }

    /**
     * Obtiene un objeto domicilio
     * @return LibQ_Sclases_Domicilio_Domicilio
     */
    public function getDomicilio()
    {
        return $this->_domicilio;
    }

    /**
     * Obtiene la condición ante el IVA
     * @return string
     */
    public function getCondicion_iva()
    {
        return $this->_condicion_iva;
    }

    /**
     * Obtiene el nro de cuit
     * @return string
     */
    public function getCuit()
    {
        return $this->_cuit;
    }

    /**
     * Obtiene un array de objetos contacto
     * @return Contacto
     */
    public function getContactos()
    {
        return $this->_contactos;
    }
    
    public function getNotas()
    {
        return $this->_notas;
    }

    public function setDomicilio(LibQ_Sclases_Domicilio_Domicilio $domicilio)
    {
        $this->_domicilio = $domicilio;
    }
    
    public function setContacto(LibQ_Sclases_Contacto $contacto)
    {
        $this->_contactos[]=$contacto;
    }
    
    public function setContactos($contactos)
    {
        $this->_contactos = array();
        if(is_array($contactos) && count($contactos) > 0){
            foreach ($contactos as $value) {
                if (is_a($value, 'LibQ_Sclases_Contacto')){
                        $this->setContacto($value);
                    }else{
                        $contacto = new LibQ_Sclases_Contacto($value);
                        $this->setContacto($contacto);
                }                
            }
        }
    }

}
