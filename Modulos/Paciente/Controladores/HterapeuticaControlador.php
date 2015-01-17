<?php

/**
 * Clase Personal Controlador 
 */
class hterapeuticaControlador extends pacienteControlador
{


    public function __construct()
    {
        parent::__construct();
        $this->_datosHTerapeutica = $this->cargarModelo('hTerapeutica');
        parent::getLibreria('Fechas');
    }

    public function nuevo($id)
    {
        /** Si el Post viene con guardar = 1 */
        if ($this->getInt('guardar') == 1) {
            /** envío los datos del post a la vista */
            if ($this->_datosHTerapeutica->insertarHTerapeutica(array(
                'idPaciente' => parent::getPostParam('idPaciente'),
                'idProfesional' => parent::getPostParam('profesional'),
                'tipo' => parent::getPostParam('tipo_informe'),
                'fechaObservacion' => Fechas::getFechaBd(parent::getPostParam('fechaObservacion')),
                'observacion' => parent::getPostParam('informe')
                ))) {
                $this->_msj_error = 'Datos Guardados';
                $this->redireccionar('option=Paciente&sub=index&cont=editar&id='.$id);
            } else {
                $this->_msj_error = 'No se pudo guardar los datos';
            }
        }
    }
    
    public function editar($id)
    {
        /** Si no viene id en el POST envío al Index */
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Paciente');
        }

        /** Si no encuentro el paciente envío al Index */
        if (!$this->_paciente->getPaciente($this->filtrarInt($id))) {
//        if (!$this->_paciente->getPaciente($this->filtrarInt($id))) {
            $this->redireccionar('option=Paciente');
        }
        /** Establezco el título */
        $this->_vista->titulo = 'Editar Paciente';
        /** Cargo los archivos js */
        $this->_vista->setJs(array('jquery.validate.min', 'uploader'));
        $this->_vista->setJs(array('validarNuevo', 'util', 'tag-it'));
//        $this->_vista->setCss(array('tagit.ui-zendesk', 'jquery.tagit'));
        $this->_vista->setJs(array('tinymce/tinymce.min','iniciarTinyMce'));
//        $this->_vista->setCss(array('tiny_mce/themes/simple/skins/default/ui'));

        /** Si el Post viene con guardar = 1 */
        if ($this->getInt('guardar') == 1) {
            /** envío los datos del post a la vista */
            $this->_vista->datos = $_POST;

            /** evaluo los datos del POST */
            if (!parent::getTexto('apellidos')) {
                $this->_vista->_msj_error = 'Debe ingresar el apellido';
                $this->_vista->renderizar('editar', 'paciente');
                exit;
            }

            if (!parent::getTexto('nombres')) {
                $this->_vista->_msj_error = 'Debe ingresar el nombre';
                $this->_vista->renderizar('editar', 'paciente');
                exit;
            }
            $fecha_nac = parent::getPostParam('fechaNac');
            /** Guardo los datos editados */
            if ($this->_paciente->editarPaciente(array(
                        'apellidos' => parent::getPostParam('apellidos'),
                        'nombres' => parent::getPostParam('nombres'),
                        'domicilio' => parent::getPostParam('domicilio'),
                        'localidad' => parent::getPostParam('localidad'),
                        'nacionalidad' => parent::getPostParam('nacionalidad'),
                        'tipo_doc' => parent::getInt('tipo_doc'),
                        'nro_doc' => parent::getPostParam('nro_doc'),
                        'sexo' => parent::getPostParam('sexo'),
                        'fecha_nac' => fechas::getFechaBd($fecha_nac),
                        'diagnostico' => parent::getPostParam('diagnostico')
                            ), 'id = ' . $this->filtrarInt($id)
                    ) > 0) {
                $this->_msj_error = 'Datos Modificados';
            } else {
                $this->_msj_error = 'No se modificó';
            }
//
//            $this->redireccionar('option=Paciente');
        }
        /** Si no es para guardar lleno el form con datos de la bd */
        $paciente = $this->_paciente->getPaciente($this->filtrarInt($id));
        /** Envío los datos a la vista */
        $this->_vista->datos = $paciente;
        /** Envío los datos de Terapia */
//        echo '<pre>'; print_r($paciente->getTerapias());
        $this->_vista->datosTerapia = $paciente->getTerapias();
        $this->_vista->up = $ajaxFileUploader->showFileUploader('if1');
        /** Envío los datos de Contacto */
//        echo '<pre>'; print_r($paciente->getContactos($this->filtrarInt($id)));
        $this->_vista->datosContacto = $paciente->getContactos($this->filtrarInt($id));
        /** Envío los datos de Familia */
        $this->_vista->datosFamilia = $paciente->getFamilia();
        /** Envío los datos de la Obra Social */
        $this->_vista->datosOSocial = $paciente->getOSocial();
        $this->_vista->listaOSociales = $this->_datosOSocial->getOSociales();
        $this->_vista->listaSexos = $this->_listaSexos;
        $this->_vista->listaProfesionales = $this->_personal->getAlgunosPersonal(0, 0, 'apellidos', "personal.nomina='TERAPEUTAS' OR personal.nomina='DOCENTES'");
        $this->_vista->hTerapeutica = $paciente->getHTerapeuticas();
//        echo '<pre>';        var_dump($paciente->getHTerapeuticas());
        /** Diagnóstico */
//        $diagnosticos = $this->cargarModelo('diagnosticos', true);
//        $listaDiagnosticos = $diagnosticos->getDiagnosticos();
//        foreach ($listaDiagnosticos as $diag) {
//            $lista[] = $diag['diagnostico'];
//        }
        /** Envío datos de diagnóstico */
//        $this->_vista->listaDiagnostico = utf8_encode(implode(',', $lista));
        /** Muestro la vista */
        $this->_vista->renderizar('editar', 'Paciente');
    }

    

}