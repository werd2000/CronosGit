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
class LibQ_Html_Boton_BotonFiltrar extends LibQ_Html_Boton_BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('Filtrar');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('icono-filtrar32');
        }

    }


}


