<?php
require_once MODS_PATH . 'Paciente' . DS . 'Modelos' . DS . 'IndexModelo.php';

/**
 * Clase Personal Controlador 
 */
class Paciente_Controladores_domicilioControlador extends pacienteControlador
{

    private $_paciente;

    public function __construct()
    {
        parent::__construct();
        $this->_paciente = $this->cargarModelo('index');
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
        $respuesta = $this->_paciente->insertarDomicilioPaciente(
            array(
                'id_paciente'=>parent::getPostParam('id_paciente'),
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
        $respuesta = $this->_paciente->modificarDomicilioPaciente(
            array(
                'id_paciente'=>parent::getPostParam('id_paciente'),
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

    
    
    /**
     * Elimina los datos de un paciente
     * @param type $id 
     */
    public function eliminar($id)
    {
        /**
         * Establezco el nivel de acceso 
         */
//        Session::acceso('admin');
//        $this->_acl->acceso('eliminar_post');
        
        if (!$this->filtrarInt($id)) {
//            $this->redireccionar('option=Personal');
        }

        if (!$this->_paciente->getFamiliar($this->filtrarInt($id))) {
//            $this->redireccionar('option=Personal');
        }
        
        $resultado = $this->_paciente->eliminarFamiliarPaciente('id = ' . $this->filtrarInt($id));
        
        if ($resultado > 0){
            echo $resultado;
            exit;
        }else{
            echo 'No se pudo eliminar el registro';
            exit;
        }
//        $this->redireccionar('option=Personal');
    }
    
    

}