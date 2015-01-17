<?php
/**
 * Clase Terapia Controlador 
 */
class Paciente_Controladores_TerapiaControlador extends pacienteControlador
{

    private $_terapia;

    public function __construct()
    {
        parent::__construct();
        $this->_terapia = $this->cargarModelo('terapia');
    }


    public function nuevo()
    {
        if (parent::getIntPost('guardar') == 1) {
            if (!parent::getTextoPost('terapia')) {
                echo 'Debe seleccionar el tipo de terapia';
                exit;
            }

            if (!parent::getTextoPost('cantidad')) {
                echo 'Debe ingresar la cantidad de terapia';
                exit;
            }
            
            $respuesta = $this->_terapia->insertarTerapiaPaciente(array(
                    'idPaciente'=>parent::getPostParam('idPaciente'),
                    'idTerapia'=>parent::getPostParam('terapia'),
                    'idProfesional'=>parent::getPostParam('idProfesional'),
                    'sesiones'=>parent::getPostParam('cantidad'),
                    'observaciones'=>parent::getPostParam('observaciones')
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
     * Elimina los datos de un personal
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

        if (!$this->_terapia->getTerapia($this->filtrarInt($id))) {
//            $this->redireccionar('option=Personal');
        }
        
        $resultado = $this->_terapia->eliminarTerapiaPaciente('id = ' . $this->filtrarInt($id));
        
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