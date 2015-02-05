<?php
/**
 * Clase Personal Controlador 
 */
class Paciente_Controladores_contactoControlador extends pacienteControlador
{

    private $_paciente;

    public function __construct()
    {
        parent::__construct();
        $this->_paciente = $this->cargarModelo('contactoPaciente');
    }
    
    public function index($pagina = false)
    {
        parent::getLibreria('paginador');
        parent::getLibreria('Fechas');
        
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=contactos_pacientes",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Paciente&sub=index&cont=nuevo",
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
        $datos = $this->_paciente->getListadoContactos();
        $this->_vista->datos = $paginador->paginar($datos,$pagina);
        $this->_vista->paginacion = $paginador->getView('prueba','?option=Paciente&sub=index');
        $this->_vista->titulo = 'Pacientes';
        if($pagina == 0){
            $this->_vista->i = 1;
        }else{
            $this->_vista->i = (($pagina - 1) * LIMITE_REGISTROS) + 1;
        }
        $this->_vista->renderizar('index', 'paciente');
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
            
            $respuesta = $this->_paciente->insertarContactoPaciente(array(
                    'id_paciente'=>parent::getPostParam('id_paciente'),
                    'tipo'=>parent::getPostParam('tipoContacto'),
                    'valor'=>parent::getPostParam('contacto'),
                    'observaciones'=>parent::getPostParam('observaciones')
             ));

            if ($respuesta){
                echo 'DATOS DE CONTACTO GUARDADO';
                exit;
            }else{
                echo 'NO SE GUARDARON LOS DATOS DE CONTACTO';
                exit;
            }

        }
        echo 'error';
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
//        Session::acceso('admin');
//        $this->_acl->acceso('eliminar_post');
        
        if (!$this->filtrarInt($id)) {
//            $this->redireccionar('option=Personal');
        }

        if (!$this->_paciente->getContacto($this->filtrarInt($id))) {
//            $this->redireccionar('option=Personal');
        }
        
        $resultado = $this->_paciente->eliminarContactoPaciente('id = ' . $this->filtrarInt($id));
        
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