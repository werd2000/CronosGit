<?php

require_once BASE_PATH . 'LibQ' . DS . 'Bd' . DS . 'Sql.php';
require_once BASE_PATH . 'LibQ' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php';
require_once BASE_PATH . 'LibQ' . DS . 'jpgraph' . DS . 'src' . DS . 'jpgraph_bar.php';
require_once BASE_PATH . 'LibQ' . DS . 'ValidarFormulario.php';
require_once MODS_PATH . 'Paciente' . DS . 'Modelos' . DS . 'IndexModelo.php';
require_once 'BarraHerramientasPacientes.php';
require_once 'BotonesPacientes.php';

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
    private $_estadoPaciente = array('EVALUACION', 'ACTIVO');
    private $_arrayEscuelas = Array(array('id' => '0476','denominacion' => 'Pequeño Hogar' ));
    private $_bt;
    private $_bhp;

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
        $this->_bt = new BotonesPacientes();
        $this->_bhp = new BarraHerramientasPacientes();
    }

    /**
     * Método por defecto del módulo Paciente
     * Muestra una lista con los pacientes
     */
    public function index()
    {
        $this->isAutenticado();
        /** Barra de herramientas */
//        $bh = new BarraHerramientasPacientes();
        $this->_vista->_barraHerramientas = $this->_bhp->getBarraHerramientasIndex();
        $datos = $this->_paciente->getPacientes();
        $this->_vista->estadisticas = $this->_datosEstadistica($datos);
        $this->_vista->datos = $datos;
        /** Establezco el titulo */
        $this->_vista->titulo = 'Pacientes';
        $this->_vista->setVistaJs(array('lista_pacientes'));
        $this->_vista->renderizar('index', 'paciente');
    }

    /**
     * Método para listar los contactos de los pacientes
     */
    public function dirTelefonico()
    {
        $this->isAutenticado();
        /** Barra de herramientas */
        $bh = new BarraHerramientasPacientes();
        $this->_vista->_barraHerramientas = $bh->getBarraHerramientasDirTelefonico();
        $datos = $this->_paciente->getPacientes();
        $this->_vista->datos = $datos;
        /** Establezco el titulo */
        $this->_vista->titulo = 'Directorio Telefónico de Pacientes';
        $this->_vista->setVistaJs(array('lista_telefonos'));
        $this->_vista->renderizar('dirTelefonico', 'paciente');
    }
    
    /**
     * Método para listar los pacientes por obra social
     */
    public function listaOSocial()
    {
        $this->isAutenticado();
        /** Barra de herramientas */
        $bh = new BarraHerramientasPacientes();
        $this->_vista->_barraHerramientas = $bh->getBarraHerramientasListaOSocial();
        $datos = $this->_paciente->getPacientes();
        foreach ($datos as $pac) {
            $os = $pac->getOSocial();
            $idOs = intval($os['idOSocial']);
            $nos = $this->_datosOSocial->obtenerOSocial("id = $idOs");
            $vista[$pac->getId()]=array(
                'ayn' => $pac->getAyN(),
                'os' => $nos['denominacion'],
//                'observaciones' = $pac->getPacos_observaciones()
            );
        }
        $this->_vista->listaOSociales = $this->_datosOSocial->getOSociales();
        $this->_vista->datos = $vista;
        $this->_vista->titulo = 'Directorio Pacientes por Obra Social';
        $this->_vista->setVistaJs(array('lista_pacienteOSocial'));
        $this->_vista->renderizar('pacienteOSocial', 'paciente');
    }

    public function nuevo()
    {
        $this->isAutenticado();
        parent::getLibreria('Fechas');
//        $bh = new BarraHerramientasPacientes();
        $this->_vista->_barraHerramientas = $this->_bhp->getBarraHerramientasNuevo();
        $this->_vista->estadosPaciente = $this->_estadoPaciente;
        $this->_vista->titulo = 'Nuevo Paciente';
        $this->_vista->setVistaJs(array('validarNuevo', 'util'));
        $this->_vista->setJs(array('bootstrapValidator.min'));
        $this->_vista->listaSexos = $this->_listaSexos;
        $this->_vista->datos = $_POST;        
        if ($this->getIntPost('guardar') == 1) {
            $this->_guardar();
        }
        $this->_vista->renderizar('nuevo', 'Paciente');
    }

    
    public function editar($id)
    {
        $this->isAutenticado();
        $this->_vista->_barraHerramientas = $this->_bhp->getBarraHerramientasEditar($id);
        $idPac = $this->_controlId($id);
        $this->_vista->titulo = 'Editar Paciente';
        $this->_vista->estadosPaciente = $this->_estadoPaciente;
        /** Cargo los archivos js */
        $this->_vista->setJs(array('bootstrapValidator.min'), TRUE);
        $this->_vista->setVistaJs(array('validarNuevo', 'util', 'tag-it'));
        $this->_vista->setVistaJs(array('tinymce/tinymce.min', 'iniciarTinyMce'));
        /** Si el Post viene con guardar = 1 */
        if ($this->getIntPost('editar') == 1) {
            $this->_guardar();
            $this->_guardarDiagnostico();
            $this->_guardarDomicilio();
        }
        /** Si no es para guardar lleno el form con datos de la bd */
        $paciente = $this->_paciente->getPaciente("id = " . $idPac);
        /** Envío los datos a la vista */
        $this->_vista->datos = $paciente;
        $this->_vista->domicilio = $paciente->getDomicilio();
        /** Envío los datos de Terapia */
        $this->_vista->listaTerapias = $this->_paciente->getTerpias();
        $this->_vista->datosTerapia = $paciente->getObjTerapias();
        /** Envío los datos de Contacto */
        $this->_vista->datosContacto = $paciente->getContactos($this->filtrarInt($id));
        /** Envío los datos de Familia */
        $this->_vista->datosFamilia = $paciente->getFamilia();
        /** Envío los datos de la Obra Social */
        $this->_vista->datosOSocial = $paciente->getOSocial();
        $this->_vista->listaOSociales = $this->_datosOSocial->getOSociales();
        $this->_vista->listaSexos = $this->_listaSexos;
        $this->_vista->listaProfesionales = $this->_personal->getAlgunosPersonal(0, 0, 'apellidos', "personal.nomina='TERAPEUTAS' OR personal.nomina='DOCENTES'");
        $this->_vista->datosEducacion = $paciente->getObjEducacion();
        $this->_vista->listaEscuelas = $this->_arrayEscuelas;
        $this->_vista->renderizar('editar', 'Paciente');
    }
    
    private function _controlId($id)
    {
        /** Si no viene id en el POST envío al Index */
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Paciente');
        }
        /** Si no encuentro el paciente envío al Index */
        $idPac = $this->filtrarInt($id);
        if (!$this->_paciente->existePaciente("id = " . $idPac)) {
            $this->redireccionar('option=Paciente');
        }
        return $idPac;
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
                unset($aGuardarPaciente['id']);
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

    private function _guardarDomicilio()
    {
        $rtdo = '';
        $datos = $this->_limpiarDatos();
        $errores = $this->_validarPost($datos);
        $aGuardarDomicilio = $this->_prepararDatosDomicilio($datos);
        if ($errores->getRetEval()) {
            $this->_vista->_msj_error = $errores->getErrString();
        } else {
            if (null != $datos['id_domicilio']) {
                $idPac = $aGuardarDomicilio['id_paciente'];
                unset($aGuardarDomicilio['id_paciente']);
                $rtdo = $this->_paciente->editarDomicilioPaciente($aGuardarDomicilio, 'id_paciente=' . $idPac);
            } else {
                $rtdo = $this->_paciente->insertarDomicilioPaciente($aGuardarDomicilio);
            }
            if ($rtdo) {
                $this->_vista->_mensaje = 'DATOS_GUARDADOS';
            } else {
                $this->_vista->_mensaje = 'DATOS_DOMICILIO_NO_GUARDADOS';
            }
        }
        return $rtdo;
    }
    
    private function _guardarDiagnostico()
    {
        $rtdo = '';
        $aGuardarDiagnostico = $this->_prepararDatosDiagnostico();
            if (null != $aGuardarDiagnostico['id']) {
                $idPac = $aGuardarDiagnostico['id_paciente'];
                $rtdo = $this->_paciente->editarDiagnosticoPaciente($aGuardarDiagnostico, 'id_paciente=' . $idPac);
            } else {
                $rtdo = $this->_paciente->insertarDiagnosticoPaciente($aGuardarDiagnostico);
            }
            if ($rtdo) {
                $this->_vista->_mensaje = 'DATOS_GUARDADOS';
            } else {
                $this->_vista->_mensaje = 'DATOS_DIAGNOSTICOS_NO_GUARDADOS';
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
        $datos['calle'] = filter_input(INPUT_POST, 'calle', FILTER_SANITIZE_STRING);
        $datos['casa_nro'] = filter_input(INPUT_POST, 'casa_nro', FILTER_SANITIZE_STRING);
        $datos['barrio'] = filter_input(INPUT_POST, 'barrio', FILTER_SANITIZE_STRING);
        $datos['id_domicilio'] = filter_input(INPUT_POST, 'id_domicilio', FILTER_SANITIZE_NUMBER_INT);
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
//        $validar->ValidField($datos['calle'], 'text', 'La Calle no es válida');
        return $validar;
    }

    private function _prepararDatosPaciente()
    {
        $datosPaciente = array(
            'id' => parent::getPostParam('id'),
            'estado' => parent::getPostParam('estado'),
            'apellidos' => parent::getPostParam('apellidos'),
            'nombres' => parent::getPostParam('nombres'),
            'nacionalidad' => parent::getPostParam('nacionalidad'),
            'tipo_doc' => parent::getIntPost('tipo_doc'),
            'nro_doc' => parent::getPostParam('nro_doc'),
            'sexo' => parent::getPostParam('sexo'),
            'fecha_nac' => parent::getPostParam('fechaNac'),
            'diagnostico' => parent::getPostParam('diagnostico')
        );
        return $datosPaciente;
    }

    private function _prepararDatosDomicilio($datos)
    {
        $datosPaciente = array(
            'id_paciente' => parent::getPostParam('id'),
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
        );
        return $datosPaciente;
    }
    
    private function _prepararDatosDiagnostico()
    {
        $datosDiagnostico = array(
            'id_paciente' => parent::getPostParam('id'),
            'id' => parent::getPostParam('id_diagnostico'),
            'diagnostico' => parent::getPostParam('diagnostico')
        );
        return $datosDiagnostico;
    }

    /**
     * Elimina los datos de un paciente
     * @param int $id 
     */
    public function eliminar($id)
    {
        /**
         * Establezco el nivel de acceso 
         */
//        $this->_acl->acceso('eliminar_post');

        $idPac = $this->_controlId($id);
        if ($this->_paciente->eliminarPaciente('id = ' . $this->filtrarInt($idPac)) > 0) {
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
