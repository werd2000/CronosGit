<?php

/**
 * Botón Filtrar
 * @author Walter Ruiz Diaz
 * @see BotonAbstract.php
 */
require_once 'BotonAbstract.php';

/**
 * Clase para crear el boton Nuevo
 */
class LibQ_Html_Boton_BotonDropDown extends LibQ_Html_Boton_BotonAbstract
{
    private $_childrens = array();

    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('');
        }
        if (!isset($parametros['children'])) {
            $this->_childrens = $parametros['children'];
        }
        if (isset($parametros['children'])) {
            $this->_childrens = $parametros['children'];
        }
    }

    /**
     * Muestra el botón
     */
    public function render()
    {
        $retorno = $this->_mostrarBotonPrincipal();
        $retorno .= $this->_crearSubMenu($this->_childrens);
        return $retorno;
    }

    private function _mostrarBotonPrincipal()
    {
        $this->_botonHtml = '<button class="' . $this->_class . '" type="button"'
                . ' data-toggle="dropdown">';
        $this->_botonHtml .= $this->_titulo;
        if (isset($this->_icono) AND $this->_icono != '') {
            $this->_botonHtml .= '<img src="' . $this->_icono . '" alt="' . $this->_titulo .
                    '" class="' . $this->_class . '">';
            $this->_botonHtml .= '<span class="' . $this->_classIcono . '" title="' .
                    $this->_titulo . '"> </span>';
        } else {
            $this->_botonHtml .= '<span class="' . $this->_spanClass . '"> </span>';
        }
        $this->_botonHtml .= '</button>';
        return $this->_botonHtml;
    }

    private function _crearSubMenu($childrens)
    {
        $retorno = '<ul class="dropdown-menu" role="menu">';
        foreach ($childrens as $children) {
            $retorno.='<li role="presentation">'
                    . '<a href="'. $children['href'].'" role="menuitem">'.
                    $children['titulo'].'</a>'.
                    '</li>';
        }
        $retorno .= '</ul>';
        return $retorno;
    }

}
