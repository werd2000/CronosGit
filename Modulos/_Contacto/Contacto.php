<?php

/**
 * Clase abstracta Contacto
 * @author WERD
 */
abstract class ContactoAbstract{

    /**
     * Atributo identificador en la bd
     * @access private
     * @var Integer
     */
    private $id = null;

    /**
     * Tipo de contacto: Tel - Cel - Email - etc
     * @access private
     * @var Integer
     */
    private $tipo = null;

    /**
     * Valor del contacto. Un num de tel, cel o un email
     * @access private
     * @var String
     */
    private $valor = null;

    /**
     * Observaciones del contacto
     * @access private
     * @var String
     */
    private $observaciones = null;


}


