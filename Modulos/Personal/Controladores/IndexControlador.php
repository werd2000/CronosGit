<?php
require_once MODS_PATH . 'Personal' . DS . 'Modelos' . DS . 'IndexModelo.php';
require_once BASE_PATH . 'LibQ' . DS . 'BarraHerramientas.php';
require_once 'BarraHerramientasPersonal.php';
require_once 'BotonesPersonal.php';
require_once BASE_PATH . 'LibQ' . DS . 'ValidarFormulario.php';

/**
 * Clase Personal Controlador 
 */
class Personal_Controladores_indexControlador extends Controladores_PersonalControlador
{

    private $_personal;
    private $_datosLaborales;
    private $_datosContacto;
    private $_datosTerapias;
    private $_bhp;
    private $_listaSexos;
    

    public function __construct()
    {
        parent::__construct();
        $this->_personal = new Personal_Modelos_IndexModelo();
        $this->_datosLaborales = new Personal_Modelos_laboralModelo(); //$this->cargarModelo('laboral');
        $this->_datosContacto = $this->cargarModelo('contacto');
        $this->_bhp = new BarraHerramientasPersonal();
        $this->_listaSexos = array('VARON', 'MUJER');
    }

    public function index()
    {
        $this->isAutenticado();
        $this->_vista->_barraHerramientas = $this->_bhp->getBarraHerramientasIndex();
        $datos = $this->_personal->getTodoPersonal();
        $this->_vista->datos = $datos;
        $this->_vista->titulo = 'Personal';
        $this->_vista->setVistaJs(array('lista_personal'));
        $this->_vista->renderizar('index', 'personal');
    }    

    public function nuevo()
    {
        $this->isAutenticado();
        parent::getLibreria('Fechas');
        $this->_vista->_barraHerramientas = $this->_bhp->getBarraHerramientasNuevo();
        $this->_vista->titulo = 'Nuevo Personal';        
        $this->_vista->setVistaJs(array('validarNuevo','util'));
        $this->_vista->setJs(array('bootstrapValidator.min'));
        $this->_vista->listaSexos = $this->_listaSexos;
        $this->_vista->datos = $_POST;
        if ($this->getIntPost('guardar') == 1) {
            $r = $this->_guardar();
            if ($r){
                parent::redireccionar('option=Personal&sub=index&cont=editar&id='.$r);
            }
        }
        $this->_vista->renderizar('nuevo', 'personal');
    }
    
    private function _guardar()
    {
        $rtdo = '';
        $datos = $this->_limpiarDatos();
        $errores = $this->_validarPost($datos);
        $aGuardarPersonal = $this->_prepararDatosPersonal($datos);
        if ($errores->getRetEval()) {
            $this->_vista->_msj_error = $errores->getErrString();
        } else {
            if (isset($datos['editar'])) {
                $rtdo = $this->_personal->editarPersonal($aGuardarPersonal, 'id=' . $aGuardarPersonal['id']);
            } else {
                unset($aGuardarPersonal['id']);
                $rtdo = $this->_personal->insertarPersonal($aGuardarPersonal);
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
        $datos['calle'] = filter_input(INPUT_POST, 'calle', FILTER_SANITIZE_STRING);
        $datos['casa_nro'] = filter_input(INPUT_POST, 'casa_nro', FILTER_SANITIZE_STRING);
        $datos['barrio'] = filter_input(INPUT_POST, 'barrio', FILTER_SANITIZE_STRING);
        $datos['id_domicilio'] = filter_input(INPUT_POST, 'id_domicilio', FILTER_SANITIZE_NUMBER_INT);
        return $datos;
    }
    
    private function _prepararDatosPersonal()
    {
        $datosPaciente = array(
            'id' => parent::getPostParam('id'),
            'apellidos' => parent::getPostParam('apellidos'),
            'nombres' => parent::getPostParam('nombres'),
            'nacionalidad' => parent::getPostParam('nacionalidad'),
            'tipo_doc' => parent::getIntPost('tipo_doc'),
            'nro_doc' => parent::getPostParam('nro_doc'),
            'sexo' => parent::getPostParam('sexo'),
            'fecha_nac' => parent::getPostParam('fechaNac')
        );
        return $datosPaciente;
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

    public function editar($id)
    {
        $this->isAutenticado();
        $this->_vista->_barraHerramientas = $this->_bhp->getBarraHerramientasEditar();
        $idPer = $this->_controlId($id);
        /** Cargo los archivos js */
        $this->_vista->setJs(array('bootstrapValidator.min'));
        $this->_vista->setVistaJs(array('validarNuevo', 'util'));
        if ($this->getIntPost('editar') == 1) {
            $this->_guardar();
            $this->_guardarDomicilio();
        }
        $personal = $this->_personal->getPersonal("id = " . $idPer);
        $this->_vista->datos = $personal;
        $this->_vista->titulo = 'Editar Personal - ' . $personal->getApellidos() . ', ' . $personal->getNombres();
        $this->_vista->domicilio = $personal->getDomicilio();
        /** Envío los datos de Contacto */
        $this->_vista->datosContacto = $personal->getContactos($this->filtrarInt($id));
        /** Envío los datos de Familia */
//        $this->_vista->datosFamilia = $personal->getFamilia();
        $this->_vista->listaSexos = $this->_listaSexos;
        $this->_vista->renderizar('editar', 'Personal');
    }
    
    private function _controlId($id)
    {
        /** Si no viene id en el POST envío al Index */
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Personal');
        }
        /** Si no encuentro el paciente envío al Index */
        $idPer = $this->filtrarInt($id);
        if (!$this->_personal->existePersonal("id = " . $idPer)) {
            $this->redireccionar('option=Personal');
        }
        return $idPer;
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
        $this->_acl->acceso('eliminar_post');
        
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Personal');
        }

        if (!$this->_personal->getPersonal($this->filtrarInt($id))) {
            $this->redireccionar('option=Personal');
        }
        
        if ($this->_personal->eliminarPersonal('id = ' . $this->filtrarInt($id))>0){
            $this->_msj_error = 'Datos Eliminados';
        }else{
            $this->_msj_error = 'No se pudo eliminar el registro';
        }
        $this->redireccionar('option=Personal');
    }
    
    /**
     * Lista de personal para usarlo en ajax
     */
    public function listarAjax()
    {
        $this->_acl->acceso('eliminar_post');
        $filtro = 'nomina = Terapeutas';
        if (!$this->_personal->getAlgunosPersonal(0, 0, 'apellidos', $filtro, $campos = array('*'))) {
            $this->redireccionar('option=Personal');
        }
    }
    
    

}