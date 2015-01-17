<?php

require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'Sql.php';
require_once BASE_PATH . 'LibQ' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php';
require_once BASE_PATH . 'LibQ' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph_bar.php';
require_once BASE_PATH . 'LibQ' . DS . 'BarraHerramientas.php';
require_once BASE_PATH . 'LibQ' . DS . 'ValidarFormulario.php';
require_once MODS_PATH . 'Paciente' . DS . 'Modelos' . DS . 'IndexModelo.php';

/**
 * Clase Paciente Controlador 
 */
class Paciente_Controladores_IndexControlador extends pacienteControlador
{

    private $_paciente;
    private $_datosTerapia;
    private $_datosContacto;
    private $_datosFamilia;
    private $_datosOSocial;
    private $_listaSexos;
    private $_personal;
    private $_listaProfesionales;
    private $_estadoPaciente = array('EVALUACION', 'ACTIVO');
    private $_paramBotonNuevo = array(
        'href' => '?option=Paciente&sub=index&cont=nuevo',
        'classIcono' => 'icono-nuevo32',
        'titulo' => 'Nuevo',
        'class' => 'btn btn-primary'
    );

    /**
     * Propiedad usada para configurar el boton ELIMINAR
     * @var type Array
     */
    private $_paramBotonEliminar = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Eliminar')\"",
        'class' => 'btn btn-primary'
    );

    /**
     * Propiedad usada para configurar el botón VOLVER
     * @var type Array
     */
    private $_paramBotonVolver = array(
        'href' => "javascript:history.back(1)",
        'classIcono' => 'icono-volver32',
        'titulo' => 'Volver',
        'class' => 'btn btn-primary'
    );

    /**
     * Propiedad usa para configurar el botón GUARDAR ALUMNO
     * @var type Array
     */
    private $_paramBotonGuardar = array(
        'href' => "\"javascript:void(0);\"",
        'evento' => "onclick=\"javascript: submitbutton('Guardar')\"",
        'class' => 'btn btn-primary'
    );
    private $_paramBotonInicio = array(
        'href' => "?option=Index",
        'classIcono' => 'icono-inicio32',
        'titulo' => 'Inicio',
        'class' => 'btn btn-primary'
    );

    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonLista = array(
        'href' => 'index.php?option=Paciente&sub=index',
        'classIcono' => 'icono-lista32',
        'titulo' => 'Lista',
        'class' => 'btn btn-primary'
    );

    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonDirTelefonico = array(
        'href' => 'index.php?option=Paciente&sub=index&met=dirTelefonico',
        'titulo' => 'Telef.',
        'classIcono' => 'icono-dirTelefonico32',
        'class' => 'btn btn-primary'
    );

    /**
     * Constructor de la clase Index
     * Inicializa los modelos 
     */
    public function __construct()
    {
        parent::__construct();
        $this->_paciente = new Paciente_Modelos_indexModelo();
        $this->_datosContacto = $this->cargarModelo('contactoPaciente');
        $this->_datosTerapia = $this->cargarModelo('terapia');
        $this->_datosFamilia = $this->cargarModelo('familia');
        $this->_datosOSocial = $this->cargarModelo('osocial');
        $this->_personal = $this->cargarModelo('personalPaciente');
        $this->_listaSexos = array('VARON', 'MUJER');
    }

    /**
     * Método por defecto del módulo Paciente
     * Muestra una lista con los pacientes
     */
    public function index()
    {
        $this->isAutenticado();
//        parent::getLibreria('Fechas');
        /** Barra de herramientas */
        $bh = new LibQ_BarraHerramientas();
        $bh->addBoton('DropDown', $this->_crearBotonImprimir());
        $bh->addBoton('Telef', $this->_paramBotonDirTelefonico);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $bh->addBoton('Inicio', $this->_paramBotonInicio);
        $this->_vista->_barraHerramientas = $bh->render();
        $datos = $this->_paciente->getPacientes();
        $this->_vista->estadisticas = $this->_datosEstadistica($datos);
        $this->_vista->datos = $datos;
        /** Establezco el titulo */
        $this->_vista->titulo = 'Pacientes';
        $this->_vista->setVistaJs(array('lista_pacientes'));
        /** Muestro la vista */
        $this->_vista->renderizar('index', 'paciente');
    }

    /**
     * Método para listar los contactos de los pacientes
     */
    public function dirTelefonico()
    {
        $this->isAutenticado();
        /** Barra de herramientas */
        $bh = new LibQ_BarraHerramientas();
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $bh->addBoton('Inicio', $this->_paramBotonInicio);
        $this->_vista->_barraHerramientas = $bh->render();
        $datos = $this->_paciente->getPacientes();
        $this->_vista->datos = $datos;
        /** Establezco el titulo */
        $this->_vista->titulo = 'Directorio Telefónico de Pacientes';
        $this->_vista->setVistaJs(array('lista_telefonos'));
        /** Muestro la vista */
        $this->_vista->renderizar('dirTelefonico', 'paciente');
    }

    public function nuevo()
    {
        $this->isAutenticado();
        parent::getLibreria('Fechas');
//        Session::accesoEstricto(array('usuario'),true);
        /** Barra de herramientas */
        $bh = new LibQ_BarraHerramientas('ul');
        $bh->addBoton('Telef', $this->_paramBotonLista);
        $bh->addBoton('Telef', $this->_paramBotonDirTelefonico);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $bh->addBoton('Inicio', $this->_paramBotonInicio);
        $this->_vista->_barraHerramientas = $bh->render();
        /** Estado del paciente * */
        $this->_vista->estadosPaciente = $this->_estadoPaciente;
        $this->_vista->titulo = 'Nuevo Paciente';
        $this->_vista->setVistaJs(array('validarNuevo', 'util'));
        $this->_vista->setJs(array('bootstrapValidator.min'));

        $this->_vista->datos = $_POST;

        if (parent::getIntPost('guardar') == 1) {
            if (!parent::getTextoPost('apellidos')) {
                $this->_vista->_msj_error = 'Debe ingresar el apellido';
                $this->_vista->renderizar('nuevo', 'paciente');
                exit;
            }

            if (!parent::getTextoPost('nombres')) {
                $this->_vista->_msj_error = 'Debe ingresar el nombre';
                $this->_vista->renderizar('nuevo', 'paciente');
                exit;
            }

            if (!parent::getTextoPost('nro_doc')) {
                $this->_vista->_msj_error = 'Debe ingresar el número de documento';
                $this->_vista->renderizar('nuevo', 'paciente');
                exit;
            }

            $fecha_nac = parent::getPostParam('fecha_nac');

            $newIdPaciente = $this->_paciente->insertarPaciente(array(
                'estado' => parent::getPostParam('estado'),
                'apellidos' => parent::getPostParam('apellidos'),
                'nombres' => parent::getPostParam('nombres'),
                'nacionalidad' => parent::getPostParam('nacionalidad'),
                'tipo_doc' => parent::getIntPost('tipo_doc'),
                'nro_doc' => parent::getPostParam('nro_doc'),
                'sexo' => parent::getPostParam('sexo'),
                'fecha_nac' => fecha::getFechaBd($fecha_nac),
                'diagnostico' => parent::getPostParam('diagnostico')
            ));
            if ($newIdPaciente) {
                $this->_msj_error = 'Datos Guardados';
            } else {
                $this->_msj_error = 'No se guardo';
            }

            if ($this->_paciente->insertarDomicilioPaciente(
                            array(
                                'id_paciente' => $newIdPaciente,
                                'tipo_domicilio' => 'Real',
                    ))) {
                $this->_msj_error = 'Datos Guardados';
            } else {
                $this->_msj_error = 'No se guardo';
            }

            parent::redireccionar('option=Paciente');
        }
        $this->_vista->renderizar('nuevo', 'Paciente');
    }

    private function _crearBotonImprimir($id = null)
    {
        $fecha = new LibQ_Fecha();
        if (intval($fecha->time_details->m) >= 12) {
            $fechaImpresion = '01/' . intval($fecha->getAnio() + 1);
        } else {
            $fechaImpresion = $fecha->getMes() . '/' . $fecha->getAnio();
        }
        $_paramBotonImprimir = array(
            'class' => 'btn dropdown-toggle btn-primary',
            'titulo' => 'Imprimir',
            'classIcono' => 'icono-imprimir32 dropdown',
            'children' => array(
                0 => array(
                    'titulo' => 'NOTA PEDIDO IPS',
                    'href' => "index.php?option=pdf&sub=pedidoIps&id=$id&getV=$fechaImpresion",
                    'children' => Array(),
                ),
                1 => array(
                    'titulo' => 'CONSTANCIA DE REHABILITACION',
                    'href' => "index.php?option=pdfphsrl&sub=constanciaAsistenciaRegular&id=$id",
                    'children' => Array(),
                )
            )
        );
        return $_paramBotonImprimir;
    }

    public function editar($id)
    {
        $this->isAutenticado();
        parent::getLibreria('AjaxFileUploader.inc');
        /** Barra de herramientas */
        $bh = new LibQ_BarraHerramientas();
        $bh->addBoton('DropDown', $this->_crearBotonImprimir($id));
        $bh->addBoton('Telef', $this->_paramBotonLista);
        $bh->addBoton('Telef', $this->_paramBotonDirTelefonico);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $bh->addBoton('Inicio', $this->_paramBotonInicio);
        $this->_vista->_barraHerramientas = $bh->render();

        /** Si no viene id en el POST envío al Index */
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Paciente');
        }

        /** Si no encuentro el paciente envío al Index */
        $idPac = $this->filtrarInt($id);
        if (!$this->_paciente->getPaciente("id = " . $idPac)) {
            $this->redireccionar('option=Paciente');
        }
        /** Establezco el título */
        $this->_vista->titulo = 'Editar Paciente';
        /** Estado del paciente * */
        $this->_vista->estadosPaciente = $this->_estadoPaciente;
        /** Cargo los archivos js */
        $this->_vista->setJs(array('bootstrapValidator.min'));
        $this->_vista->setVistaJs(array('validarNuevo', 'util', 'tag-it'));
        $this->_vista->setVistaJs(array('tinymce/tinymce.min', 'iniciarTinyMce'));

        /** Si el Post viene con guardar = 1 */
        if ($this->getIntPost('editar') == 1) {
            $this->_guardar();
            $this->redireccionar();
        }
        /** Si no es para guardar lleno el form con datos de la bd */
        $paciente = $this->_paciente->getPaciente("id = " . $idPac);
        /** Envío los datos a la vista */
        $this->_vista->datos = $paciente;
        var_dump($paciente);
        $this->_vista->domicilio = $paciente->getDomicilio();

        if ($paciente->getDomicilio()->getId() != null) {
             if ($this->_paciente->editarDomicilioPaciente(
                            array(
                        'tipo_domicilio' => 'Real',
                        'calle' => parent::getPostParam('calle'),
                        'casa_nro' => parent::getPostParam('casa_nro'),
                        'barrio' => parent::getPostParam('barrio'),
                        'localidad' => parent::getPostParam('localidad'),
                        'cp' => parent::getPostParam('cp'),
                        'piso' => parent::getPostParam('piso'),
                        'depto' => parent::getPostParam('depto'),
                        'provincia' => parent::getPostParam('provincia'),
                        'pais' => parent::getPostParam('pais')
                            ), 'id_paciente = ' . $idPac
                    ) > 0) {
                $this->_msj_error = 'Datos Modificados';
            } else {
                $this->_msj_error = 'No se modificó';
            }
        } else {
            if ($this->_paciente->insertarDomicilioPaciente(
                            array(
                                'id_paciente' => $idPac,
                                'tipo_domicilio' => 'Real',
                                'calle' => parent::getPostParam('calle'),
                                'casa_nro' => parent::getPostParam('casa_nro'),
                                'barrio' => parent::getPostParam('barrio'),
                                'localidad' => parent::getPostParam('localidad'),
                                'cp' => parent::getPostParam('cp'),
                                'piso' => parent::getPostParam('piso'),
                                'depto' => parent::getPostParam('depto'),
                                'provincia' => parent::getPostParam('provincia'),
                                'pais' => parent::getPostParam('pais')
                            )
                    ) > 0) {
                $this->_msj_error = 'Datos Modificados';
            } else {
                $this->_msj_error = 'No se modificó';
            }
        }
        /** Envío los datos de Terapia */
        $this->_vista->listaTerapias = $this->_paciente->getTerpias();
        $this->_vista->datosTerapia = $paciente->getTerapias();
        //$this->_vista->up = $ajaxFileUploader->showFileUploader('if1');
        /** Envío los datos de Contacto */
        $this->_vista->datosContacto = $paciente->getContactos($this->filtrarInt($id));
        /** Envío los datos de Familia */
        $this->_vista->datosFamilia = $paciente->getFamilia();
        /** Envío los datos de la Obra Social */
        $this->_vista->datosOSocial = $paciente->getOSocial();
        $this->_vista->listaOSociales = $this->_datosOSocial->getOSociales();
        $this->_vista->listaSexos = $this->_listaSexos;
        $this->_vista->listaProfesionales = $this->_personal->getAlgunosPersonal(0, 0, 'apellidos', "personal.nomina='TERAPEUTAS' OR personal.nomina='DOCENTES'");
//        $this->_vista->hTerapeutica = $paciente->getHTerapeuticas();
        $this->_vista->datosEducacion = $paciente->getEducacion();
        $this->_vista->listaEscuelas = Array(
            array(
                'id' => '0476',
                'denominacion' => 'Pequeño Hogar'
            )
        );
        $this->_vista->renderizar('editar', 'Paciente');
    }

    private function _guardar()
    {
        $rtdo = '';
        $datos = $this->_limpiarDatos();
        $errores = $this->_validarPost($datos);
        $aGuardarPaciente = $this->_prepararDatosPaciente($datos);
        if ($errores->getRetEval()) {
            $this->_vista->_msj_error = $errores->getErrString();
        } else {
            if (isset($datos['editar'])) {
                $rtdo = $this->_paciente->editarPaciente($aGuardarPaciente, 'id=' . $aGuardarPaciente['id']);
            } else {
                $rtdo = $this->_paciente->insertarPaciente($aGuardarPaciente);
            }
            if ($rtdo) {
                $this->_vista->_mensaje = 'DATOS_GUARDADOS';
            } else {
                $this->_vista->_mensaje = 'DATOS_NO_GUARDADOS';
            }
        }
        return $rtdo;
    }

    /**
     * Limpia los datos que vienen del Post
     * @return array $datos
     */
    private function _limpiarDatos()
    {
        $datos['guardar'] = filter_input(INPUT_POST, 'guardar', FILTER_SANITIZE_NUMBER_INT);
        $datos['editar'] = filter_input(INPUT_POST, 'editar', FILTER_SANITIZE_NUMBER_INT);
        $datos['id'] = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        $datos['apellidos'] = filter_input(INPUT_POST, 'apellidos', FILTER_SANITIZE_STRING);
        $datos['nombres'] = filter_input(INPUT_POST, 'nombres', FILTER_SANITIZE_STRING);
        $datos['nro_doc'] = filter_input(INPUT_POST, 'nro_doc', FILTER_SANITIZE_STRING);
        $datos['diagnostico'] = filter_input(INPUT_POST, 'diagnostico', FILTER_SANITIZE_STRING);
        $datos['calle'] = filter_input(INPUT_POST, 'calle', FILTER_SANITIZE_STRING);
        $datos['casa_nro'] = filter_input(INPUT_POST, 'casa_nro', FILTER_SANITIZE_STRING);
        $datos['barrio'] = filter_input(INPUT_POST, 'barrio', FILTER_SANITIZE_STRING);
        var_dump($datos);
        return $datos;
    }

    /**
     * Validar los datos del POST
     * @param array $datos
     * @return \ValidarFormulario
     */
    private function _validarPost($datos)
    {
        $validar = new LibQ_ValidarFormulario();
        $validar->ValidField($datos['apellidos'], 'text', 'El Apellido no es válido');
        $validar->ValidField($datos['nombres'], 'text', 'El Nombre no es válido');
        /* Domicilio */
        $validar->ValidField($datos['calle'], 'text', 'La Calle no es válida');
        return $validar;
    }

    private function _prepararDatosPaciente($datos)
    {
        $datosPaciente = array(
            'id'=>parent::getPostParam('id'),
            'estado' => parent::getPostParam('estado'),
            'apellidos' => parent::getPostParam('apellidos'),
            'nombres' => parent::getPostParam('nombres'),
            'nacionalidad' => parent::getPostParam('nacionalidad'),
            'tipo_doc' => parent::getIntPost('tipo_doc'),
            'nro_doc' => parent::getPostParam('nro_doc'),
            'sexo' => parent::getPostParam('sexo'),
            'fecha_nac' => LibQ_Fecha::getFechaBd(parent::getPostParam('fechaNac')),
            'diagnostico' => parent::getPostParam('diagnostico')
        );
        return $datosPaciente;
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
//        $this->_acl->acceso('eliminar_post');

        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Paciente');
        }

        if (!$this->_paciente->getPaciente($this->filtrarInt($id))) {
            $this->redireccionar('option=Paciente');
        }

        if ($this->_paciente->eliminarPaciente('id = ' . $this->filtrarInt($id)) > 0) {
            $this->_msj_error = 'Datos Eliminados';
        } else {
            $this->_msj_error = 'No se pudo eliminar el registro';
        }
        $this->redireccionar('option=Paciente');
    }

    private function _datosEstadistica($datos)
    {
        $totalPacientes = count($this->_paciente->getPacientes());
        $pacientesVarones = count($this->_paciente->getPacientesBySql(
                        "SELECT * FROM cronos_pacientes WHERE sexo='VARON' AND eliminado=false"));
        $pacientesMujeres = count($this->_paciente->getPacientesBySql(
                        "SELECT * FROM cronos_pacientes WHERE sexo='MUJER' AND eliminado=false"));
        $estadisticas['total'] = $totalPacientes . ' Pacientes';
        $estadisticas['varones'] = $pacientesVarones . ' Varones';
        $estadisticas['mujeres'] = $pacientesMujeres . ' Mujeres';
        $estadisticas['grafica'] = BASE_URL . '?option=Paciente&sub=grafico&met=graficaSexos&t=' .
                $totalPacientes . '&v=' . $pacientesVarones . '&m=' . $pacientesMujeres;
        $listaOSociales = $this->_datosOSocial->getOSociales();
        $datosOSociales = array();
        foreach ($listaOSociales as $oSocial) {
//            print_r(count($this->_paciente->getPacientesByOs($oSocial['id'])));
            array_push($datosOSociales, array($oSocial['denominacion'] => count($this->_paciente->getPacientesByOs($oSocial['id']))));
            $estadisticas['obrasocial'] = $datosOSociales;
        }
        return $estadisticas;
    }

    public function getProfesionalesTerapia()
    {
        $retorno = '';
        $idTerapia = $this->getIntPost('idTerapia');
        $todos = $this->_paciente->getPersonalTerapia("puesto = $idTerapia");
        foreach ($todos as $personal) {
            $retorno .= '<option value="' . $personal->getId() . '" label="' .
                    $personal->getAyN() . '">' . $personal->getAyN() . '</option>';
        }
        echo $retorno;
    }

}
