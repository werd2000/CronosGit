<?php

/**
 * Clase Personal Controlador 
 */
class Paciente_Controladores_OsocialControlador extends pacienteControlador
{

    private $_paciente;

    public function __construct()
    {
        parent::__construct();
        $this->_paciente = $this->cargarModelo('Osocial');
    }

    public function guardar()
    {
        if (parent::getIntPost('guardar') == 1) {
            if (!parent::getIntPost('idObraSocial')) {
                echo 'Debe seleccionar la Obra Social';
                exit;
            }

            if (!parent::getTextoPost('nroAfiliado')) {
                echo 'Debe ingresar el nro de afiliado';
                exit;
            }

//            if ($this->_paciente->getOSocial(parent::getInt('id'))) {
            if (parent::getIntPost('id')) {
                $respuesta = 'modificar';
                $respuesta = $this->_paciente->editarOSocialPaciente(array(
                    'idPaciente' => parent::getPostParam('idPaciente'),
                    'idOSocial' => parent::getPostParam('idObraSocial'),
                    'nro_afiliado' => parent::getPostParam('nroAfiliado'),
                    'observaciones' => parent::getPostParam('observaciones')
                        ), "id=" . parent::getPostParam('id'));
            } else {
                $respuesta = 'nuevo';
                $respuesta = $this->_paciente->insertarOSocialPaciente(array(
                    'idPaciente' => parent::getPostParam('idPaciente'),
                    'idOSocial' => parent::getPostParam('idObraSocial'),
                    'nro_afiliado' => parent::getPostParam('nroAfiliado'),
                    'observaciones' => parent::getPostParam('observaciones')
                        ));
            }

            if (isset($respuesta)) {
                echo $respuesta;
                exit;
            } else {
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

        if (!$this->_paciente->getContacto($this->filtrarInt($id))) {
//            $this->redireccionar('option=Personal');
        }

        $resultado = $this->_paciente->eliminarOSocialPaciente('id = ' . $this->filtrarInt($id));

        if ($resultado > 0) {
            echo $resultado;
            exit;
        } else {
            echo 'No se pudo eliminar el registro';
            exit;
        }
//        $this->redireccionar('option=Personal');
    }

}