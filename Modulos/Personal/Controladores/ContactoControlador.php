<?php
/**
 * Clase Personal Controlador 
 */
class Personal_Controladores_contactoControlador extends Controladores_PersonalControlador
{

    private $_personal;

    public function __construct()
    {
        parent::__construct();
        $this->_personal = $this->cargarModelo('contacto');
    }

    public function index($pagina = false)
    {
        parent::getLibreria('paginador');
        parent::getLibreria('Fechas');
        
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=contactos_personal",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
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
        
        $paginador = new Paginador();
        $datos = $this->_personal->getListadoContactos();
        $this->_vista->datos = $paginador->paginar($datos,$pagina);
        $this->_vista->paginacion = $paginador->getView('prueba','?option=Personal&sub=contacto');
        $this->_vista->titulo = 'Personal';
        if($pagina == 0){
            $this->_vista->i = 1;
        }else{
            $this->_vista->i = (($pagina - 1) * LIMITE_REGISTROS) + 1;
        }
        $this->_vista->renderizar('index', 'personal');
    }

    public function nuevo()
    {
        if (parent::getIntPost('guardar') == 1) {
            if (!parent::getTextoPost('tipo')) {
                echo 'Debe ingresar el tipo de contacto';
                exit;
            }

            if (!parent::getTextoPost('contacto')) {
                echo 'Debe ingresar un dato de contacto';
                exit;
            }
            
            $respuesta = $this->_personal->insertarContactoProfesional(array(
                    'idProfesional'=>parent::getPostParam('idProfesional'),
                    'tipo'=>parent::getPostParam('tipo'),
                    'valor'=>parent::getPostParam('contacto'),
                    'observaciones'=>parent::getPostParam('observaciones')
             ));

            if ($respuesta){
                echo $respuesta;
                exit;
            }else{
                echo 'No se guardó';
                exit;
            }

        }
        echo 'error';
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
//            $this->redireccionar('option=Personal');
        }

        if (!$this->_personal->getContacto($this->filtrarInt($id))) {
//            $this->redireccionar('option=Personal');
        }
        
        $resultado = $this->_personal->eliminarContactoProfesional('id = ' . $this->filtrarInt($id));
        
        if ($resultado > 0){
            echo $resultado;
            exit;
        }else{
            echo 'No se pudo eliminar el registro';
            exit;
        }
//        $this->redireccionar('option=Personal');
    }
    
    

}