<?php

/**
 * Separador de directorios 
 */
define('DS', DIRECTORY_SEPARATOR);
/**
 * Path del sitio 
 */
defined('BASE_PATH') or define('BASE_PATH', realpath(dirname(__FILE__)) . DS);
/**
 * Path de la aplicación: BASE_PATH . 'App' . DS
 */
define('APP_PATH', BASE_PATH . 'App' . DS);
/**
 * Path de las LibQ = BASE_PATH . 'LibQ' . DS
 */
define('LIB_PATH', BASE_PATH . 'LibQ' . DS);
/**
 * Path de los módulos = BASE_PATH - 'Modulos' . DS
 */
define('MODS_PATH', BASE_PATH . 'Modulos' . DS);
/**
 * Path de la carpeta donde está el diccionario de lenguaje
 */
define('LANG_PATH', BASE_PATH . 'App' . DS . 'Languages' . DS);
