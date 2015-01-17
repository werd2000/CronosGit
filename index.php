<?php
ob_start();
/* Reportar E_NOTICE puede ser bueno tambien (para reportar variables
  no inicializadas o capturar equivocaciones en nombres de variables ...) */
//error_reporting(E_ALL | E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
//ini_set("display_errors", 1);
abstract class Index
{
    public static function ejecutar()
    {
        if (0 > version_compare(PHP_VERSION, '5')) {
            die('Este sitio fue generado para PHP 5');
        }
         /* Path del sitio */
        define('BASE_PATH', realpath(dirname(__FILE__)) . DIRECTORY_SEPARATOR);
        /* Import the definition file paths. */
        if (!include 'App/Defines.php') {
            die("Error loading defines.php");
        }
        /* incluir requires básicos */
        if (!include 'App/Requires.php') {
            die("Error loading requires.php");
        }
        try {
            new \App_App();
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
Index::ejecutar();
ob_end_flush();

