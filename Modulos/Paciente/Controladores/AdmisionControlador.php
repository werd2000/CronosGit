<?php
/**
 * Clase Paciente Controlador 
 */
class AdmisionControlador extends pacienteControlador
{

    private $_paciente;
    private $_datosTerapia;
    private $_datosContacto;
    private $_datosFamilia;
    private $_datosOSocial;
    private $_menuIndex = array(
            array(
                'onclick'=> '',
                'href'   => "?option=Paciente&sub=admision&cont=nuevo",
                'title' => 'Nuevo',
                'class'  => 'icono-nuevo32'
            ),
            array(
                'onclick'=> '',
                'href'   => "javascript:history.back(1)",
                'title' => 'Volver',
                'class'  => 'icono-volver32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Index",
                'title' => 'Inicio',
                'class'  => 'icono-inicio32'
            )
        );
    private $_menuNuevo = array(
            array(
                'onclick'=> '',
                'href'   => "?option=Paciente&sub=admision&cont=nuevo",
                'title' => 'Nuevo',
                'class'  => 'icono-nuevo32',
                'children' => ''
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Paciente&sub=admision",
                'title' => 'Lista',
                'class'  => 'icono-lista32'
            ),
            array(
                'onclick'=> '',
                'href'   => "javascript:history.back(1)",
                'title' => 'Volver',
                'class'  => 'icono-volver32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Index",
                'title' => 'Inicio',
                'class'  => 'icono-inicio32'
            )
        );
    private $_menuEditar = array(
            array(
                'onclick'=> '',
                'href'   => "?option=Paciente&sub=admision&cont=nuevo",
                'title' => 'Nuevo',
                'class'  => 'icono-nuevo32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Paciente&sub=admision",
                'title' => 'Lista',
                'class'  => 'icono-lista32'
            ),
            array(
                'onclick'=> '',
                'href'   => "javascript:history.back(1)",
                'title' => 'Volver',
                'class'  => 'icono-volver32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Index",
                'title' => 'Inicio',
                'class'  => 'icono-inicio32'
            )
        );

    public function __construct()
    {
        parent::__construct();
        $this->_paciente = $this->cargarModelo('admision');
        $this->_datosContacto = $this->cargarModelo('contactoPaciente');
        $this->_datosTerapia = $this->cargarModelo('terapia');
        $this->_datosFamilia = $this->cargarModelo('familia');
        $this->_datosOSocial = $this->cargarModelo('osocial');
    }

    public function index($pagina = false)
    {
        parent::getLibreria('paginador');
        parent::getLibreria('Fechas');        
        $this->_vista->_barraHerramientas = $this->_menuIndex;
        
        $paginador = new Paginador();
        $datos = $this->_paciente->getPacientes();
        $todos = array();
        foreach ($datos as $paciente) {
            $todos[]=$paciente;
        }
        $this->_vista->datos = $paginador->paginar($todos,$pagina);
        $this->_vista->paginacion = $paginador->getView('prueba','?option=Paciente&sub=admision');
        $this->_vista->titulo = 'Lista de Espera de Pacientes';
        if($pagina == 0){
            $this->_vista->i = 1;
        }else{
            $this->_vista->i = (($pagina - 1) * LIMITE_REGISTROS) + 1;
        }
        $this->_vista->renderizar('index', 'admision');
    }

    public function nuevo()
    {
        parent::getLibreria('Fechas');
        
        $this->_vista->_barraHerramientas = $this->_menuNuevo;
        
        $this->_vista->titulo = 'Nueva Admisión';
        $this->_vista->setJs(array('validarNuevo','jquery-ui','util'));
        $this->_vista->setJs(array('jquery.validate.min'));

        $this->_vista->datos = $_POST;

        if (parent::getInt('guardar') == 1) {
            if (!parent::getTexto('interesado')) {
                $this->_vista->_msj_error = 'Debe ingresar el nombre del interesado';
                $this->_vista->renderizar('nuevo', 'admision');
                exit;
            }

            if (!parent::getTexto('paciente')) {
                $this->_vista->_msj_error = 'Debe ingresar el nombre del paciente';
                $this->_vista->renderizar('nuevo', 'admision');
                exit;
            }
            $fecha = parent::getPostParam('fecha');
            if ($this->_paciente->insertarPaciente(array(
                    'fecha'=>fecha::getFechaBd($fecha),
                    'interesado'=>parent::getPostParam('interesado'),
                    'paciente'=>parent::getPostParam('paciente'),
                    'domicilio'=>parent::getPostParam('domicilio'),
                    'tel'=>parent::getPostParam('tel'),
                    'cel'=>parent::getPostParam('cel'),
                    'edad'=>parent::getInt('edad'),
                    'sexo'=>parent::getPostParam('sexo'),
                    'diagnostico'=>parent::getPostParam('diagnostico'),
                    'obra_social'=>parent::getPostParam('obra_social'),
                    'carnet_discapacidad'=>parent::getPostParam('carnet_discapacidad'),
                    'escolarizado'=>parent::getPostParam('escolarizado'),
                    'escolarizado_en'=>parent::getPostParam('escolarizado_en'),
                    'recibe_terapia'=>parent::getPostParam('recibe_terapia'),
                    'terapia_que_recibe'=>parent::getPostParam('terapia_que_recibe'),
                    'derivado'=>parent::getPostParam('derivado'),
                    'derivado_por'=>parent::getPostParam('derivado_por'),
                    'turno_preferente'=>parent::getPostParam('turno_preferente'),
                    'Observaciones'=>parent::getPostParam('Observaciones'),
             ))){
                $this->_msj_error = 'Datos Guardados';
            }else{
                $this->_msj_error = 'No se guardo';
            }

            parent::redireccionar('option=Paciente&sub=admision');
        }
        $this->_vista->listaOSociales = $this->_datosOSocial->getOSociales();
        $this->_vista->renderizar('nuevo', 'admision');
    }

    public function editar($id)
    {
        parent::getLibreria('Fechas');
        $agregar = array(
                'onclick'=> '',
                'href'   => "?option=Paciente&sub=admision&cont=eliminar&id=$id",
                'title' => 'Eliminar',
                'class'  => 'icono-eliminar32'
            );
        array_push($this->_menuEditar, $agregar);
        $this->_vista->_barraHerramientas = $this->_menuEditar;
        
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Paciente');
        }

        if (!$this->_paciente->getPaciente($this->filtrarInt($id))) {
            $this->redireccionar('option=Paciente');
        }

        $this->_vista->titulo = 'Editar Paciente de Admisión';
        $this->_vista->setJs(array('jquery.validate.min'));
        $this->_vista->setJs(array('validarNuevo','jquery-ui','util'));

        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;

            if (!parent::getTexto('interesado')) {
                $this->_vista->_msj_error = 'Debe ingresar el nombre del interesado';
                $this->_vista->renderizar('nuevo', 'admision');
                exit;
            }

            if (!parent::getTexto('paciente')) {
                $this->_vista->_msj_error = 'Debe ingresar el nombre del paciente';
                $this->_vista->renderizar('nuevo', 'admision');
                exit;
            }
            $fecha = parent::getPostParam('fecha');
            if ($this->_paciente->editarPaciente(array(
                'fecha'=>fecha::getFechaBd($fecha),
                'interesado'=>parent::getPostParam('interesado'),
                'paciente'=>parent::getPostParam('paciente'),
                'domicilio'=>parent::getPostParam('domicilio'),
                'tel'=>parent::getPostParam('tel'),
                'cel'=>parent::getPostParam('cel'),
                'edad'=>parent::getInt('edad'),
                'sexo'=>parent::getPostParam('sexo'),
                'diagnostico'=>  trim(parent::getPostParam('diagnostico')),
                'obra_social'=>parent::getPostParam('obra_social'),
                'carnet_discapacidad'=>parent::getPostParam('carnet_discapacidad'),
                'escolarizado'=>parent::getPostParam('escolarizado'),
                'escolarizado_en'=>parent::getPostParam('escolarizado_en'),
                'recibe_terapia'=>parent::getPostParam('recibe_terapia'),
                'terapia_que_recibe'=>parent::getPostParam('terapia_que_recibe'),
                'derivado'=>parent::getPostParam('derivado'),
                'derivado_por'=>parent::getPostParam('derivado_por'),
                'turno_preferente'=>parent::getPostParam('turno_preferente'),
                'Observaciones'=>parent::getPostParam('Observaciones'),
             ), 'id = ' . $this->filtrarInt($id)
                    ) > 0){
                $this->_msj_error = 'Datos Modificados';
            }else{
                $this->_msj_error = 'No se modificó';
            }

            $this->redireccionar('option=Paciente&sub=admision');
        }
        //Si no es para guardar lleno el form con datos de la bd
        $this->_vista->datos = $this->_paciente->getPaciente($this->filtrarInt($id));
//        $this->_vista->datosTerapia = $this->_datosTerapia->getTerapias($this->filtrarInt($id));
//        $this->_vista->datosContacto = $this->_datosContacto->getContactos($this->filtrarInt($id));
//        $this->_vista->datosFamilia = $this->_datosFamilia->getFamiliares($this->filtrarInt($id));
//        $this->_vista->datosOSocial = $this->_datosOSocial->getOSocial($this->filtrarInt($id));
//        $this->_vista->listaOSociales = $this->_datosOSocial->getOSociales();
        $this->_vista->renderizar('editar', 'Paciente');
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
        $this->_acl->acceso('eliminar_post');
        
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Paciente&sub=admision');
        }

        if (!$this->_paciente->getPaciente($this->filtrarInt($id))) {
            $this->redireccionar('option=Paciente&sub=admision');
        }
        
        if ($this->_paciente->eliminarPaciente('id = ' . $this->filtrarInt($id))>0){
            $this->_msj_error = 'Datos Eliminados';
        }else{
            $this->_msj_error = 'No se pudo eliminar el registro';
        }
        $this->redireccionar('option=Paciente&sub=admision');
    }
    
    

}