<?php

/**
 * Clase Personal Controlador 
 */
class laboralControlador extends PersonalControlador
{

    private $_personal;

    public function __construct()
    {
        parent::__construct();
        $this->_personal = $this->cargarModelo('laboral');
    }

    public function nuevo()
    {
        $respuesta = '';
    
        if (parent::getInt('guardar') == 1) {
            if (!parent::getTexto('puesto')) {
                echo 'Debe ingresar la ocupación que tiene en la empresa';
                exit;
            }

            if (!parent::getTexto('fechaIngreso')) {
                echo 'Debe ingresar la fecha de ingreso';
                exit;
            }
            $id = $this->getInt('id');
            $fecha = implode('/', array_reverse(explode('/', parent::getPostParam('fechaIngreso'))));
//            $fecha = fechas::getFechaBd(parent::getPostParam('fechaIngreso'));
            if ($id > 0) {
                $respuesta = $this->_personal->editarDatosLaborales(array(
                    'fechaIngreso' => $fecha,
                    'puesto' => parent::getPostParam('puesto'),
                    'observaciones' => parent::getPostParam('observaciones')
                        ), 'idProfesional = ' . $this->getInt('idProfesional')
                );
                
            } else {
                $respuesta = $this->_personal->insertarDatosLaborales(array(
                    'idProfesional' => parent::getPostParam('idProfesional'),
                    'fechaIngreso' => $fecha,
                    'puesto' => parent::getPostParam('puesto'),
                    'observaciones' => parent::getPostParam('observaciones')
                        ));
            }
            if ($respuesta) {
                echo $respuesta;
                exit;
            } else {
                echo 'No se guardó';
                exit;
            }
        }
        echo 'error';
    }
    
    public function getDatosLaborales($id)
    {
        $datos=$this->_personal->getDatosLaborales($id);
        echo $datos['puesto'];
    }

}