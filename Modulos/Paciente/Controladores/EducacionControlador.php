<?php
require_once MODS_PATH . 'Paciente' . DS . 'Modelos' . DS . 'EducacionPacienteModelo.php';

/**
 * Clase Personal Controlador 
 */
class Paciente_Controladores_educacionControlador extends pacienteControlador
{

    private $_paciente;

    public function __construct()
    {
        parent::__construct();
        $this->_paciente = new Paciente_Modelos_educacionPacienteModelo();
    }

    public function guardar()
    {
        if (parent::getIntPost('guardar') == 1) {
            if (!parent::getIntPost('idEscuela')) {
                echo 'Debe seleccionar la Escuela';
                exit;
            }

            if (!parent::getTextoPost('curso')) {
                echo 'Debe ingresar el curso';
                exit;
            }

//            if ($this->_paciente->getOSocial(parent::getInt('id'))) {
            if (parent::getIntPost('id')) {
                $respuesta = 'modificar';
                $respuesta = $this->_paciente->editarEducacionPaciente(array(
                    'id_paciente' => parent::getPostParam('idPaciente'),
                    'id_escuela' => parent::getPostParam('idEscuela'),
                    'curso' => parent::getPostParam('curso'),
                    'observaciones' => parent::getPostParam('observaciones')
                        ), "id=" . parent::getPostParam('id'));
            } else {
                $respuesta = 'nuevo';
                $respuesta = $this->_paciente->insertarEducacionPaciente(array(
                    'id_paciente' => parent::getPostParam('idPaciente'),
                    'id_escuela' => parent::getPostParam('idEscuela'),
                    'curso' => parent::getPostParam('curso'),
                    'observaciones' => parent::getPostParam('observaciones')
                        ));
            }

            if (isset($respuesta)) {
                echo 'DATOS DE EDUCACION GUARDADOS';
                exit;
            } else {
                echo 'NO SE GUARDARON LOS DATOS DE EDUCACION';
                exit;
            }
        }
        echo 'error';
    }

}