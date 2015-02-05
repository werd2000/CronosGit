<?php
require_once MODS_PATH . 'Personal' . DS . 'Modelos' . DS . 'IndexModelo.php';
require_once BASE_PATH . 'LibQ' . DS . 'BarraHerramientas.php';
/**
 * Clase Personal Controlador 
 */
class Personal_Controladores_indexControlador extends Controladores_PersonalControlador
{

    private $_personal;
    private $_datosLaborales;
    private $_datosContacto;
    private $_datosTerapias;
    private $_paramBotonNuevo = array(
        'href' => '?option=Personal&sub=index&cont=nuevo',
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
        'class' => 'btn btn-primary',
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
     * Propiedad usa para configurar el botón GUARDAR
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
        'href' => 'index.php?option=Personal&sub=index',
        'classIcono' => 'icono-lista32',
        'titulo' => 'Lista',
        'class' => 'btn btn-primary'
    );
    
    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonDirTelefonico = array(
        'href' => 'index.php?option=&sub=index&met=dirTelefonico',
        'titulo' => 'Telef.',
        'classIcono' => 'icono-dirTelefonico32',
        'class' => 'btn btn-primary'
    );

    public function __construct()
    {
        parent::__construct();
        $this->_personal = new Personal_Modelos_IndexModelo();
        $this->_datosLaborales = new Personal_Modelos_laboralModelo(); //$this->cargarModelo('laboral');
        $this->_datosContacto = $this->cargarModelo('contacto');
    }

    public function index($pagina = false)
    {
        parent::getLibreria('paginador');
        parent::getLibreria('Fechas');
        
        $menu = array(
            array(
                'onclick' => '',
                'href' => "javascript:void(0)",
                'title' => 'Buscar',
                'class' => 'icono-buscar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=personal",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Personal&sub=contacto",
                'title' => 'Contactos',
                'class'  => 'contacto_personal'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Personal&sub=index&cont=nuevo",
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
        $this->_vista->_barraHerramientas = $menu;
        if ($_POST) {
            $filtro = $this->_crearFiltro();
            Session::set('filtroPersonal', $filtro);
            $datos = $this->_personal->getAlgunosPersonal(0, 0, 'apellidos', $filtro);
        } elseif (App_Session::get('filtroPersonal')) {
//            $_POST = Session::get('post');
            $filtro = App_Session::get('filtroPersonal');
            $datos = $this->_personal->getAlgunosPersonal(0, 0, 'apellidos', $filtro);
        } else {
            App_Session::destroy('filtroPersonal');
            $datos = $this->_personal->getTodoPersonal();
        }
//        echo '<pre>';print_r($datos);
        $paginador = new Paginador();
        $this->_vista->datos = $paginador->paginar($datos,$pagina);
        $this->_vista->paginacion = $paginador->getView('prueba','?option=Personal&sub=index');
        $this->_vista->titulo = 'Personal';
//        $this->_vista->setJs(array('barra_herramientas'));
        $this->_vista->setVistaJs(array('lista_personal'));
        if($pagina == 0){
            $this->_vista->i = 1;
        }else{
            $this->_vista->i = (($pagina - 1) * LIMITE_REGISTROS) + 1;
        }
        $this->_vista->renderizar('index', 'personal');
    }
    
    

    public function nuevo()
    {
//        Session::accesoEstricto(array('usuario'),true);
        parent::getLibreria('Fechas');
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=Personal&sub=index&cont=nuevo",
                'title' => 'Nuevo',
                'class'  => 'icono-nuevo32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Personal",
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
        $this->_vista->_barraHerramientas = $menu;
        
        $this->_vista->titulo = 'Nuevo Personal';
        $this->_vista->setJs(array('validarNuevo','util'));
        $this->_vista->setJs(array('jquery.validate.min'));

        $this->_vista->datos = $_POST;

        if (parent::getIntPost('guardar') == 1) {
            if (!parent::getTexto('apellidos')) {
                $this->_vista->_msj_error = 'Debe ingresar el apellido';
                $this->_vista->renderizar('nuevo', 'personal');
                exit;
            }

            if (!parent::getTexto('nombres')) {
                $this->_vista->_msj_error = 'Debe ingresar el nombre';
                $this->_vista->renderizar('nuevo', 'personal');
                exit;
            }

            if (!parent::getTexto('nro_doc')) {
                $this->_vista->_msj_error = 'Debe ingresar el número de documento';
                $this->_vista->renderizar('nuevo', 'personal');
                exit;
            }
            
            if ($this->_personal->getPersonalByNro_doc($this->getPostParam('nro_doc'))) {
                $this->_vista->_msj_error = 'El número de documento que intenta ingresar ya existe';
                $this->_vista->renderizar('nuevo', 'personal');
                exit;
            }
            
            $fecha_nac = parent::getPostParam('fechaNac');
            
            if ($this->_personal->insertarPersonal(array(
                'apellidos'=>parent::getPostParam('apellidos'),
                'nombres'=>parent::getPostParam('nombres'),
                'domicilio'=>parent::getPostParam('domicilio'),
                'localidad'=>parent::getPostParam('localidad'),
                'nacionalidad'=>parent::getPostParam('nacionalidad'),
                'tipo_doc'=>parent::getIntPost('tipo_doc'),
                'nro_doc'=>parent::getPostParam('nro_doc'),
                'sexo'=>parent::getPostParam('sexo'),
                'fecha_nac'=>Fecha::getFechaBd($fecha_nac)
             ))){
                $this->_msj_error = 'Datos Guardados';
            }else{
                $this->_msj_error = 'No se guardo';
            }

            parent::redireccionar('option=Personal');
        }
        $this->_vista->renderizar('nuevo', 'personal');
    }

    public function editar($id)
    {
        $this->isAutenticado();
        parent::getLibreria('AjaxFileUploader.inc');
        /** Barra de herramientas */
        $bh = new LibQ_BarraHerramientas();
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Telef', $this->_paramBotonDirTelefonico);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $bh->addBoton('Inicio', $this->_paramBotonInicio);
        $this->_vista->_barraHerramientas = $bh->render();
//            array(
//                'onclick'=> '',
//                'href'   => "?option=Personal&sub=index&cont=eliminar&id=$id",
//                'title' => 'Eliminar',
//                'class'  => 'icono-eliminar32'
//            )        
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Personal');
        }
        
        /** Si no encuentro el paciente envío al Index */
        $idPers = $this->filtrarInt($id);
        if (!$this->_personal->getPersonal("id = " . $idPers)) {
            $this->redireccionar('option=Personal');
        }
        
        /** Cargo los archivos js */
        $this->_vista->setJs(array('bootstrapValidator.min'));
        $this->_vista->setVistaJs(array('validarNuevo', 'util'));
//        $this->_vista->setVistaJs(array('tinymce/tinymce.min', 'iniciarTinyMce'));
        
//        $this->_vista->setJs(array('jquery.validate.min','ajaxfileupload'));
//        $this->_vista->setJs(array('validarNuevo','util'));


        if ($this->getIntPost('guardar') == 1) {
            $this->_vista->datos = $_POST;

            if (!parent::getTexto('apellidos')) {
                $this->_vista->_msj_error = 'Debe ingresar el apellido';
                $this->_vista->renderizar('editar', 'personal');
                exit;
            }

            if (!parent::getTexto('nombres')) {
                $this->_vista->_msj_error = 'Debe ingresar el nombre';
                $this->_vista->renderizar('editar', 'personal');
                exit;
            }
            
            $fecha_nac = parent::getPostParam('fechaNac');
            
            if ($this->_personal->editarPersonal(array(
                    'apellidos'=>parent::getPostParam('apellidos'),
                    'nombres'=>parent::getPostParam('nombres'),
                    'domicilio'=>parent::getPostParam('domicilio'),
                    'localidad'=>parent::getPostParam('localidad'),
                    'nacionalidad'=>parent::getPostParam('nacionalidad'),
                    'tipo_doc'=>parent::getIntPost('tipo_doc'),
                    'nro_doc'=>parent::getPostParam('nro_doc'),
                    'sexo'=>parent::getPostParam('sexo'),
                    'fecha_nac'=>fecha::getFechaBd($fecha_nac)
             ), 'id = ' . $this->filtrarInt($id)
                    ) > 0){
                $this->_msj_error = 'Datos Modificados';
            }else{
                $this->_msj_error = 'No se modificó';
            }

            $this->redireccionar('option=Personal');
        }
        //Si no es para guardar lleno el form con datos de la bd
        $datos = $this->_personal->getPersonal($this->filtrarInt($id));
//        setlocale(LC_TIME , 'es_ES');
//        $datos['fecha_nac'] = fechas::getFechaAr($datos['fecha_nac']);
        $this->_vista->titulo = 'Editar Personal - ' . $datos->getApellidos() . ', ' . $datos->getNombres();
        $todos=$datos;
        $this->_vista->datos = $todos;
        $this->_vista->listaSexos = array('VARON','MUJER');
        $this->_vista->listaTipoDoc = array(0=>'DNI',1=>'CI');
        $this->_vista->listaNacionalidades = array('ARGENTINA','PARAGUAY','BRASIL');
        $this->_vista->listaTerapias = $this->_datosLaborales->getTerapias();
        $this->_vista->listaTerapias[] = array('id'=>'9','terapia'=>'ADMINISTRATIVO');
        $this->_vista->datosLaborales = $datos->getDatosLaborales();
        $this->_vista->datosContacto = $datos->getContactosPersonal();
        $this->_vista->renderizar('editar', 'Personal');
//        print_r($this->_datosLaborales->getTerapias());
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