<?php

/**
 * Description of BotonesPersonal
 *
 * @author WERD
 */
class BotonesPersonal
{
    private $_paramBotonNuevo = array(
        'href' => '?option=Personal&sub=index&cont=nuevo',
        'classIcono' => 'icono-nuevo32',
        'titulo' => 'Nuevo',
//        'icono' => 'Vistas/Layout/Default/Img/iconos/nuevo16.png',
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
//        'icono' => 'glyphicon glyphicon-trash'
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
        'icono' => '',
        'class' => 'btn btn-primary'
    );

    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    private $_paramBotonLista = array(
        'href' => 'index.php?option=Personal&sub=index',
        'classIcono' => 'icono-lista32',
        'titulo' => 'Lista de Personal',
        'class' => 'btn btn-primary'
    );

    /**
     * Propiedad usada para configurar el botón LISTA
     * @var type Array
     */
    protected $_paramBotonDirTelefonico = array(
        'href' => 'index.php?option=Personal&sub=index&met=dirTelefonico',
        'titulo' => 'Lista de Telef.',
        'classIcono' => 'icono-dirTelefonico32',
        'class' => 'btn btn-primary'
    );
    
    /**
     * Propiedad usada para configurar el botón LISTA por O. Social
     * @var type Array
     */
    private $_paramBotonDirOSociales = array(
        'href' => 'index.php?option=Personal&sub=index&met=listaOSocial',
        'titulo' => 'Lista por O.Social',
        'classIcono' => 'icono-dirOSocial32',
        'class' => 'btn btn-primary'
    );
    
    public function __construct()
    {
    }

    public function getParamBotonNuevo()
    {
        return $this->_paramBotonNuevo;
    }
    
    public function getParamBotonEliminar()
    {
        return $this->_paramBotonEliminar;
    }
    
    public function getParamBotonVolver()
    {
        return $this->_paramBotonVolver;
    }
    
    public function getParamBotonGuardar()
    {
        return $this->_paramBotonGuardar;
    }
    
    public function getParamBotonInicio()
    {
        return $this->_paramBotonInicio;
    }

    public function getParamBotonLista()
    {
        return $this->_paramBotonLista;
    }
    
    public function getParamBotonDirTelefonico()
    {
        return $this->_paramBotonDirTelefonico;
    }
    
    public function getParamBotonDirOSociales()
    {
        return $this->_paramBotonDirOSociales;
    }

}
