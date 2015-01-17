<?php
/**
 * Clase Terapias Controlador 
 */
class indexControlador extends terapiasControlador
{

    private $_terapias;

    public function __construct()
    {
        parent::__construct();
        $this->_terapias = $this->cargarModelo('index');
    }

    public function index($pagina = false)
    {
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=terapias",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Terapias&sub=index&cont=nuevo",
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
        $datos = $this->_terapias->getTerapias();
        $this->_vista->datos = $paginador->paginar($datos,$pagina);
        $this->_vista->paginacion = $paginador->getView('prueba','?option=Terapias&sub=index');
        $this->_vista->titulo = 'Terapias';
        if($pagina == 0){
            $this->_vista->i = 1;
        }else{
            $this->_vista->i = (($pagina - 1) * LIMITE_REGISTROS) + 1;
        }
        $this->_vista->renderizar('index', 'terapias');
    }

    public function nuevo()
    {
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=terapias",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Terapias&sub=index&cont=nuevo",
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
        $this->_vista->titulo = 'Nuevo Terapias';
        $this->_vista->setJs(array('jquery.validate.min'));
        $this->_vista->setVistaJs(array('validarNuevo'));

        $this->_vista->datos = $_POST;

        if (parent::getInt('guardar') == 1) {
            if (!parent::getTexto('terapia')) {
                $this->_vista->_msj_error = 'Debe ingresar la especialidad';
                $this->_vista->renderizar('nuevo', 'terapias');
                exit;
            }

            if ($this->_terapias->insertarTerapias(array(
                    'terapia'=>parent::getPostParam('terapia'),
             ))){
                $this->_msj_error = 'Datos Guardados';
            }else{
                $this->_msj_error = 'No se guardo';
            }

            parent::redireccionar('option=Terapias');
        }
        $this->_vista->renderizar('nuevo', 'terapias');
    }

    public function editar($id)
    {
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=terapias",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Terapias&sub=index&cont=nuevo",
                'title' => 'Nuevo',
                'class'  => 'icono-nuevo32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Terapias&sub=index&cont=eliminar&id=$id",
                'title' => 'Eliminar',
                'class'  => 'icono-eliminar32'
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
        
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Terapias');
        }

        if (!$this->_terapias->getTerapia($this->filtrarInt($id))) {
            $this->redireccionar('option=Terapias');
        }

        $this->_vista->titulo = 'Editar Terapias';
        $this->_vista->setJs(array('jquery.validate.min'));
        $this->_vista->setVistaJs(array('validarNuevo'));

        if ($this->getInt('guardar') == 1) {
            $this->_vista->datos = $_POST;

            if ($this->_terapias->editarTerapias(array(
                    'terapia'=>parent::getPostParam('terapia')
             ), 'id = ' . $this->filtrarInt($id)
                    ) > 0){
                $this->_msj_error = 'Datos Modificados';
            }else{
                $this->_msj_error = 'No se modificó';
            }

            $this->redireccionar('option=Terapias');
        }
        //Si no es para guardar lleno el form con datos de la bd
        $this->_vista->datos = $this->_terapias->getTerapia($this->filtrarInt($id));
        $this->_vista->renderizar('editar', 'Terapias');
    }
    
    /**
     * Elimina los datos de un terapias
     * @param type $id 
     */
    public function eliminar($id)
    {
        /**
         * Establezco el nivel de acceso 
         */
//        Session::acceso('admin');
        
        if (!$this->filtrarInt($id)) {
            $this->redireccionar('option=Terapias');
        }

        if (!$this->_terapias->getTerapia($this->filtrarInt($id))) {
            $this->redireccionar('option=Terapias');
        }
        
        if ($this->_terapias->eliminarTerapias('id = ' . $this->filtrarInt($id))>0){
            $this->_msj_error = 'Datos Eliminados';
        }else{
            $this->_msj_error = 'No se pudo eliminar el registro';
        }
        $this->redireccionar('option=Terapias');
    }
    
    public function listaTerapiasPaciente($id)
    {
        echo $this->_terapias->getTerapia($this->filtrarInt($id));

    }
    
    

}