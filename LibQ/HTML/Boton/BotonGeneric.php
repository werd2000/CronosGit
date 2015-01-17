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
class LibQ_Html_Boton_BotonGeneric extends LibQ_Html_Boton_BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('');
        }

    }


}


