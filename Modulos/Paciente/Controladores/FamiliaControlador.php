<?php
/**
 * Clase Personal Controlador 
 */
class FamiliaControlador extends pacienteControlador
{

    private $_paciente;

    public function __construct()
    {
        parent::__construct();
        $this->_paciente = $this->cargarModelo('familia');
    }


    public function nuevo()
    {
        if (parent::getInt('guardar') == 1) {
            if (!parent::getTexto('parentesco')) {
                echo 'Debe ingresar el tipo de parentesco';
                exit;
            }

            if (!parent::getTexto('nombre')) {
                echo 'Debe ingresar un nombre';
                exit;
            }
            
            $respuesta = $this->_paciente->insertarFamiliarPaciente(array(
                    'idPaciente'=>parent::getPostParam('idPaciente'),
                    'parentesco'=>parent::getPostParam('parentesco'),
                    'nombre'=>parent::getPostParam('nombre'),
                    'observaciones'=>parent::getPostParam('observacionesFamilia')
             ));

            if ($respuesta){
                echo $respuesta;
                exit;
            }else{
                echo 'No se guardó';
                exit;
            }

        }
        echo 'error';
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
        $this->_acl->acceso('eliminar_post');
        
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