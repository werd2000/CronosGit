<?php

/**
 * Clase ObraSocialPaciente
 *
 * @author WERD
 */
class ObraSocialPaciente
{
    protected $_id;
    protected $_idPaciente;
    protected $_idOSocial;
    protected $_denominacionOS;
    protected $_nro_afiliado;
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
    
    public function getIdOSocial()
    {
        return $this->_idOSocial;
    }
    
    public function getDenominacionOS()
    {
        return $this->_denominacionOS;
    }


    public function getNro_afiliado()
    {
        return $this->_nro_afiliado;
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
        $this->_idOSocial = $datos['idOSocial'];
        $this->_denominacionOS = '';
        $this->_idPaciente = $datos['idPaciente'];
        $this->_nro_afiliado = $datos['nro_afiliado'];
        $this->_observaciones = $datos['observaciones'];
    }
    
//    public static function getOSocialPaciente($datos=array())
//    {
//        $resultado = array();
//        if (count($datos)>1){
//            foreach ($datos as $oSocial) {
//                $resultado[] = new ObraSocialPaciente($oSocial);
//            }
//        }
//        return $resultado;
//    }
}

?>
