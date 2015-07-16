<?php
require_once MODS_PATH . 'Personal' . DS . 'Modelos' . DS . 'IndexModelo.php';

/**
 * Clase Personal Controlador 
 */
class Personal_Controladores_domicilioControlador extends Controladores_PersonalControlador
{

    private $_personal;

    public function __construct()
    {
        parent::__construct();
        $this->_personal = $this->cargarModelo('index');
    }


    public function guardar()
    {
        $respuesta = FALSE;
        if (parent::getIntPost('id_domicilio') == false) {
            $respuesta = $this->_nuevoDomicilio();
        }else{
            $respuesta = $this->_modificarDomicilio();
        }
        if ($respuesta){
                echo 'DOMICILIO GUARDADO';
                exit;
            }else{
                echo 'NO SE GUARDO EL DOMICILIO';
                exit;
        }
    }
    
    private function _nuevoDomicilio()
    {
        $respuesta = $this->_personal->insertarDomicilioPersonal(
            array(
                'id_personal'=>parent::getPostParam('id_personal'),
                'calle'=>parent::getPostParam('calle'),
                'casa_nro'=>parent::getPostParam('casa_nro'),
                'piso'=>parent::getPostParam('piso'),
                'depto'=>parent::getPostParam('depto'),
                'barrio'=>parent::getPostParam('barrio'),
                'cp'=>parent::getPostParam('cp'),
                'localidad'=>parent::getPostParam('localidad'),
                'provincia'=>parent::getPostParam('provincia'),
                'pais'=>parent::getPostParam('pais')
                )
            );
        return $respuesta;
    }
    
    private function _modificarDomicilio()
    {
        $respuesta = $this->_personal->modificarDomicilioPersonal(
            array(
                'id_personal'=>parent::getPostParam('id_personal'),
                'calle'=>parent::getPostParam('calle'),
                'casa_nro'=>parent::getPostParam('casa_nro'),
                'piso'=>parent::getPostParam('piso'),
                'depto'=>parent::getPostParam('depto'),
                'barrio'=>parent::getPostParam('barrio'),
                'cp'=>parent::getPostParam('cp'),
                'localidad'=>parent::getPostParam('localidad'),
                'provincia'=>parent::getPostParam('provincia'),
                'pais'=>parent::getPostParam('pais')
                ),'id='.parent::getPostParam('id_domicilio')
            );
        return $respuesta;
    }
    
}