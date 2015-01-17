<?php

/**
 * Botón Eliminar
 * @author Walter Ruiz Diaz
 */

require_once 'BotonAbstract.php';

/**
 * Clase para crear el boton Nuevo
 */
class LibQ_Html_Boton_BotonEliminar extends LibQ_Html_Boton_BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('Eliminar');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('icono-eliminar32');
        }
    }


}


