<?php

/**
 * Botón Nuevo
 * @author Walter Ruiz Diaz
 */

require_once 'BotonAbstract.php';

/**
 * Clase para crear el boton Nuevo
 */
class LibQ_Html_Boton_BotonGuardar extends LibQ_Html_Boton_BotonAbstract
{
    function __construct($parametros)
    {
        parent::__construct($parametros);
        if (!isset($parametros['titulo'])) {
            $this->setTitle('Guardar');
        }
        if (!isset($parametros['classIcono'])) {
            $this->setClassIcono('icono-guardar32');
        }
    }


}


