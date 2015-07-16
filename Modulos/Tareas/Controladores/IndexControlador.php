<?php
require_once BASE_PATH . 'LibQ' . DS . 'BarraHerramientas.php';
require_once MODS_PATH . 'Tareas' . DS . 'Modelos' . DS . 'IndexModelo.php';

/**
 * Clase Tareas Controlador 
 */
class Tareas_Controladores_indexControlador extends Controladores_tareasControlador
{

    private $_tareas;
    protected $_listaTipoTareas = array('TAREA','EVENTO','NOTA','PAGO');
    protected $_listaEstados = array('SIN ASIGNAR','MANTENER','RESUELTO','RECHAZADO');
    protected $_listaRepetir = array('NUNCA','DIARIAMENTE','SEMANALMENTE','MENSUALMENTE','ANUALMENTE');
    protected $_mes31 = array(1,3,5,7,8,10,12);
    private $_paramBotonNuevo = array(
        'href' => '?option=Tareas&sub=index&cont=nuevo',
        'classIcono' => 'icono-nuevo32',
        'titulo' => 'Nuevo',
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
        'href' => 'index.php?option=Tareas&sub=index',
        'classIcono' => 'icono-lista32',
        'titulo' => 'Lista',
        'class' => 'btn btn-primary'
    );
    private $_paramBotonExportar = array(
        'href' => 'index.php?option=exportExcel&sub=tareas',
        'classIcono' => 'icono-lista32',
        'titulo' => 'Lista',
        'class' => 'btn btn-primary'
    );

    public function __construct()
    {
        parent::__construct();
        $this->_tareas = new Tareas_Modelos_indexModelo();
    }

    public function index($pagina = false)
    {
        $this->isAutenticado();
        parent::getLibreria('Fechas');
        /** Barra de herramientas */
        $bh = new LibQ_BarraHerramientas();
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $bh->addBoton('Inicio', $this->_paramBotonInicio);
        $this->_vista->_barraHerramientas = $bh->render();
        parent::getLibreria('Fechas');
//        print_r($this->_tareas->getTareas());
        $this->_vista->datos = $this->_tareas->getTareas();
        $this->_vista->titulo = 'Tareas';
        $this->_vista->setVistaJs(array('lista_tareas'));
        $this->_vista->setVistaCss(array('tareas'));        
        $this->_vista->renderizar('index', 'tarea');
    }

    public function nuevo()
    {
        $this->isAutenticado();
        /** Barra de herramientas */
        $bh = new LibQ_BarraHerramientas();
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $bh->addBoton('Inicio', $this->_paramBotonInicio);
        $this->_vista->_barraHerramientas = $bh->render();
        
        $this->_vista->titulo = 'Nueva Tarea';
        $this->_vista->setJs(array('jquery.validate.min','util'));
        $this->_vista->setJs(array('tinymce/jscripts/tiny_mce/tiny_mce','iniciarTinyMce'));
        $this->_vista->setCss(array('tiny_mce/themes/simple/skins/default/ui'));
        $this->_vista->setJs(array('validarNuevo'));
        $this->_vista->listaTipoTareas = $this->_listaTipoTareas;
        $this->_vista->listaTareas = $this->_tareas->getTareas();
        $this->_vista->listaRepetir = $this->_listaRepetir;
        $this->_vista->listaEstados = $this->_listaEstados;
        $this->_vista->datos = $_POST;

        if (parent::getIntPost('guardar') == 1) {
            if (!parent::getTextoPost('fechaInicio')) {
                $this->_vista->_msj_error = 'Debe ingresar la fecha de inicio';
                $this->_vista->renderizar('nuevo', 'tarea');
                exit;
            }
            switch (parent::getPostParam('repetir')) {
                case 'NUNCA':
                    $veces = 1;
                    $multiplicador = 1;
                    break;
                case 'SEMANALMENTE':
                    $veces = 104;
                    $multiplicador = 7;
                    break;
                case 'MENSUALMENTE':
                    $veces = 6;
                    $multiplicador = 30;
                    break;
                case 'ANUALMENTE':
                    $veces = 4;
                    $multiplicador = 365;
                    break;
                default:
                    $veces = 1;
                    break;
            }
            $fechaInicio = new LibQ_Fecha(parent::getPostParam('fechaInicio'));
            $fechaFin    = new LibQ_Fecha(parent::getPostParam('fechaFin'));
            for ($index = 0; $index < $veces; $index++) {
                if ($index > 0){
                    if (in_array($fechaInicio->getMes(),  $this->_mes31)){
                        $multiplic = $multiplicador + 1;
                        $fechaInicio->add(new DateInterval('P'. $multiplic . 'D'));
                        $fechaFin->add(new DateInterval('P'. $multiplic . 'D'));
                    }else{
                        $multiplic = $multiplicador;
                        $fechaInicio->add(new DateInterval('P'. $multiplic . 'D'));
                        $fechaFin->add(new DateInterval('P'. $multiplic . 'D'));
                    }
                }
                if ($this->_tareas->insertarTareas(array(
                        'id'=>'',
                        'id_creador'=> App_Session::get('id_usuario'),
                        'depende_de'=>parent::getPostParam('depende_de'),
                        'tipo_tarea'=>parent::getPostParam('tipo_tarea'),
                        'fechaInicio'=>$fechaInicio->format('Y-m-d'),
                        'fechaFin'=>$fechaFin->format('Y-m-d'),
                        'descripcion'=>parent::getPostParam('descripcion'),
                        'estado'=>parent::getPostParam('estado'),
                        'observaciones'=>parent::getPostParam('observaciones')
                ))){
                    $this->_msj_error = 'Datos Guardados';
                }else{
                    $this->_msj_error = 'No se guardo';
                }
            }
            parent::redireccionar('option=Tareas');
        }
        $this->_vista->renderizar('nuevo', 'tarea');
    }

    public function editar($id)
    {
        $this->isAutenticado();
        /** Barra de herramientas */
        $bh = new LibQ_BarraHerramientas();
        $bh->addBoton('Lista', $this->_paramBotonLista);
        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $bh->addBoton('Inicio', $this->_paramBotonInicio);
        $this->_vista->_barraHerramientas = $bh->render();
        parent::getLibreria('Fechas');        
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Tareas');
        }

        if (!$this->_tareas->getTarea($this->filtrarInt($id))) {
            $this->redireccionar('option=Tareas');
        }
        $this->_vista->listaTareas = $this->_tareas->getTareas();        
        $this->_vista->listaTipoTareas = $this->_listaTipoTareas;
        $this->_vista->listaEstados = $this->_listaEstados;
        $this->_vista->titulo = 'Editar Tareas';
        $this->_vista->setVistaJs(array('jquery.validate.min','tinymce/jscripts/tiny_mce/tiny_mce','validarNuevo','iniciarTinyMce','util'));
        $this->_vista->setVistaCss(array('tiny_mce/themes/simple/skins/default/ui'));
        
        if ($this->getIntPost('guardar') == 1) {
            $this->_vista->datos = $_POST;
            $fechaInicio = parent::getPostParam('fechaInicio');
            $fechaFin = parent::getPostParam('fechaFin');
            if ($this->_tareas->editarTareas(array(
                'id_creador'=>parent::getPostParam('id_creador'),
                'depende_de'=>parent::getPostParam('depende_de'),
                'tipo_tarea'=>parent::getPostParam('tipo_tarea'),
                'fechaInicio'=>$fechaInicio,
                'fechaFin'=>$fechaFin,
                'descripcion'=>parent::getPostParam('descripcion'),
                'estado'=>parent::getPostParam('estado'),
                'observaciones'=>parent::getPostParam('historial') . parent::getPostParam('observaciones')
             ), 'id = ' . $this->filtrarInt($id)
                    ) > 0){
                $this->_msj_error = 'Datos Modificados';
            }else{
                $this->_msj_error = 'No se modificó';
            }

            $this->redireccionar('option=Tareas');
        }
        //Si no es para guardar lleno el form con datos de la bd
        $this->_vista->datos = $this->_tareas->getTarea($this->filtrarInt($id));
        $this->_vista->renderizar('editar', 'Tareas');
    }
    
    /**
     * Elimina los datos de un tarea
     * @param type $id 
     */
    public function eliminar($id)
    {
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Tareas');
        }

        if (1 > count($this->_tareas->getTareas($this->filtrarInt($id)))) {
            $this->redireccionar('option=Tareas');
        }
        
        if ($this->_tareas->eliminarTareas('id = ' . $this->filtrarInt($id))>0){
            $this->_msj_error = 'Datos Eliminados';
        }else{
            $this->_msj_error = 'No se pudo eliminar el registro';
        }
        $this->redireccionar('option=Tareas');
    }
    
    

}