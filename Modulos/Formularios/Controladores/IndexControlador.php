<?php
/**
 * Clase Formularios Controlador 
 */
class indexControlador extends formulariosControlador
{

    protected $_listaFormularios = array(
        'Solicitud_de_entrevista_de_admision.pdf',
        'Ficha_de_diagnostico_individual.pdf',
        'Historia_Clinica_Unica_Para_Alumnos_de_Escuela_Saludable.pdf',
        'Entrevista_Inicial.pdf'
        );

    public function __construct()
    {
        parent::__construct();
//        $this->_formularios = $this->cargarModelo('index');
    }

    public function index()
    {
        
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=formularios",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Formularios&sub=index&cont=nuevo",
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
        
        $this->_vista->datos = $this->_listaFormularios;
        $this->_vista->titulo = 'Formularios';
        $this->_vista->renderizar('index','formularios');
    }

    
    
    

}