<?php

/**
 * Clase usada para administrar archivos y carpetas
 * @author Walter Ruiz Diaz
 * @category archivosYcarpetas
 * @package LibQ
 */
class LibQ_ArchivosYcarpetas
{

    function __construct()
    {
        
    }

    /**
     * Retorna un array con las carpetas encontradas en la $ruta
     * @param String $ruta
     * @return Array
     * @throws Exception
     */
    public static function listar_directorios_ruta($ruta)
    {
        $retorno = array();
        // abrir un directorio y listarlo recursivo 
        if (is_dir($ruta)) {
            if ($gestor = opendir($ruta)) {
                /* Esta es la forma correcta de iterar sobre el directorio. */
                while (false !== ($entrada = readdir($gestor))) {
                    if ($entrada != "." && $entrada != "..") {
                        $retorno[]=$entrada;
                    }
                }
                closedir($gestor);
            }
        } else {
            throw new Exception('No es ruta valida: ' . $ruta);
        }
        return $retorno;
    }
    
    public function ifExisteFile ($file)
    {
        if (! file_exists($file)) {
            die('No existe el archivo');
        }
        return true;
    }

}