<?php
/**
 * Clase Tareas Controlador 
 */
class indexControlador extends tareasControlador
{

    private $_tareas;
    protected $_listaTipoTareas = array('TAREA','EVENTO','NOTA','PAGO');
    protected $_listaEstados = array('SIN ASIGNAR','MANTENER','RESUELTO','RECHAZADO');
    protected $_listaRepetir = array('NUNCA','DIARIAMENTE','SEMANALMENTE','MENSUALMENTE','ANUALMENTE');

    public function __construct()
    {
        parent::__construct();
        $this->_tareas = $this->cargarModelo('index');
    }

    public function index($pagina = false)
    {
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=tareas",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Tareas&sub=index&cont=nuevo",
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
        
        parent::getLibreria('paginador');
        parent::getLibreria('Fechas');
        $paginador = new Paginador();
//        print_r($this->_tareas->getTareas());
        $datos = $this->_tareas->getTareas();
        $this->_vista->datos = $paginador->paginar($datos,$pagina);
        $this->_vista->paginacion = $paginador->getView('prueba','?option=Tareas&sub=index');
        $this->_vista->titulo = 'Tareas';
        if($pagina == 0){
            $this->_vista->i = 1;
        }else{
            $this->_vista->i = (($pagina - 1) * LIMITE_REGISTROS) + 1;
        }
        $this->_vista->renderizar('index', 'tarea');
    }

    public function nuevo()
    {
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=tareas",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Tareas&sub=index&cont=nuevo",
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
        
//        Session::accesoEstricto(array('usuario'),true);
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
//        $this->_vista->datos['depende_de'] = $_SESSION['id_usuario'];

        if (parent::getInt('guardar') == 1) {
            if (!parent::getTexto('fechaInicio')) {
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
                    $veces = 24;
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
            $fechaInicio = new DateTime(fechas::getFechaBd(parent::getPostParam('fechaInicio')));
            $fechaFin = new DateTime(fechas::getFechaBd(parent::getPostParam('fechaFin')));
            for ($index = 0; $index < $veces; $index++) {
                if ($index > 0){
                    $fechaInicio->add(new DateInterval('P'. $multiplicador . 'D'));
                    $fechaFin->add(new DateInterval('P'. $multiplicador . 'D'));
                }
                if ($this->_tareas->insertarTareas(array(
                        'id'=>'',
                        'id_creador'=>  Session::get('id_usuario'),
                        'depende_de'=>parent::getPostParam('depende_de'),
                        'tipo_tarea'=>parent::getPostParam('tipo_tarea'),
                        'fechaInicio'=>date_format($fechaInicio, 'Y-m-d'),
                        'fechaFin'=>  date_format($fechaFin, 'Y-m-d'),
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
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=tareas",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick' => '',
                'href' => "?option=Tareas",
                'title' => 'Lista',
                'class' => 'icono-lista32'
            ),
            array(
                'onclick' => '',
                'href' => "?option=Tareas&sub=index&cont=eliminar&id=$id",
                'title' => 'Eliminar',
                'class' => 'icono-eliminar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Tareas&sub=index&cont=nuevo",
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
        $this->_vista->setJs(array('jquery.validate.min','tinymce/jscripts/tiny_mce/tiny_mce','validarNuevo','iniciarTinyMce','util'));
        $this->_vista->setCss(array('tiny_mce/themes/simple/skins/default/ui'));
        
        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;
            if ($this->_tareas->editarTareas(array(
                'id_creador'=>parent::getPostParam('id_creador'),
                'depende_de'=>parent::getPostParam('depende_de'),
                'tipo_tarea'=>parent::getPostParam('tipo_tarea'),
                'fechaInicio'=>fecha::getFechaBd(parent::getPostParam('fechaInicio')),
                'fechaFin'=>fecha::getFechaBd(parent::getPostParam('fechaFin')),
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
//        print_r ($this->_tareas->getTarea($this->filtrarInt($id)));
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