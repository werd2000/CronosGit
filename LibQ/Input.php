<?php
class Input
{
    private static $instance = NULL;
    // get Singleton instance of Input class
    public static function getInstance ()
    {
        if (self::$instance === NULL) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Retornona una variable GET escapada
     * @param type $var
     * @return type $var
     */
    public static function get ($var = NULL)
    {
        if (! isset($_GET[$var])) {
            return $var;
        }
        return mysql_escape_string(trim($_GET[$var]));
    }
    /**
     * Retorno una variable POST escapada
     * @param type $var
     * @return type 
     */
    public static function post ($var = NULL)
    {
        if (! isset($_POST[$var])) {
            return $var;
        }
        return mysql_escape_string(trim($_POST[$var]));
        echo $var;
    }
        /**
     * Obtiene el texto que viene en una clave del $_POST
     * @param string $clave
     * @return string 
     */
    public static function obtenerTexto($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = htmlspecialchars($_POST[$clave], ENT_QUOTES);
            return $_POST[$clave];
        }
        
        return '';
    }
    
    /**
     * Obtiene el entero que viene en una clave del $_POST
     * @param string $clave
     * @return int 
     */
    public static function obtenerInt($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
            return $_POST[$clave];
        }
        
        return 0;
    }

     /**
     * Obtiene un parametro del Post sin filtrar
     * @param string $clave
     * @return mixed 
     */
    public static function obtenerPostParam($clave)
    {
        if(isset($_POST[$clave])){
            return $_POST[$clave];
        }
    }
    
    /**
     * filtra y sanitiza la clave pasada. Util para password
     * @param string $clave
     * @return string 
     */
    public static function obtenerSql($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = strip_tags($_POST[$clave]);
            
//            if(!get_magic_quotes_gpc()){
//                $_POST[$clave] = mysql_real_escape_string($_POST[$clave]);
//                print_r($_POST[$clave]);
//            }
            
            return trim($_POST[$clave]);
        }
    }
    
    /**
     * Filtra y sanitiza la clave obteniendo solo numeros y letras
     * @param string $clave
     * @return string 
     */
    public static function obtenerAlphaNum($clave)
    {
        if(isset($_POST[$clave]) && !empty($_POST[$clave])){
            $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
        }
        
    }

}