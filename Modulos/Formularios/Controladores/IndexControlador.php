<?php
require_once BASE_PATH . 'LibQ' . DS . 'BarraHerramientas.php';

/**
 * Clase Formularios Controlador 
 */
class Formularios_Controladores_indexControlador extends Controladores_formulariosControlador
{

    protected $_listaFormularios = array(
        'Solicitud_de_entrevista_de_admision.pdf',
        'Ficha_de_diagnostico_individual.pdf',
        'Historia_Clinica_Unica_Para_Alumnos_de_Escuela_Saludable.pdf',
        'Entrevista_Inicial.pdf'
        );
    private $_paramBotonNuevo = array(
        'href' => '?option=Formularios&sub=index&cont=nuevo',
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


    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        
        $this->isAutenticado();
        /** Barra de herramientas */
        $bh = new LibQ_BarraHerramientas();
//        $bh->addBoton('Nuevo', $this->_paramBotonNuevo);
        $bh->addBoton('Volver', $this->_paramBotonVolver);
        $bh->addBoton('Inicio', $this->_paramBotonInicio);
        $this->_vista->setVistaCss(array('formularios'));
        $this->_vista->_barraHerramientas = $bh->render();
        
        $this->_vista->datos = $this->_listaFormularios;
        $this->_vista->titulo = 'Formularios';
        $this->_vista->renderizar('index','formularios');
    }

    
    
    

}