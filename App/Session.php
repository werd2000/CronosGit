<?php

/**
 * Manejo de sesión
 * @package App
 */
class App_Session
{

    /**
     * Inicio de sesión
     */
    public static function init()
    {
        session_start();
    }

    /**
     * Destruye una clave de sesión o un conjunto de claves si viene en array
     * Si no hay clave destruye toda la sesión
     * @param mixed $clave
     */
    public static function destroy($clave = false)
    {
        if ($clave) {
            if (is_array($clave)) {
                $this->_destroyArray($clave);
            } else {
                $this->_destroyClave($clave);
            }
        } else {
            session_destroy();
        }
    }

    /**
     * Verifica que existe y destruye una clave de sesión
     * @param string $clave
     */
    private function _destroyClave($clave)
    {
        if (isset($_SESSION[$clave])) {
            unset($_SESSION[$clave]);
        }
    }

    /**
     * Destruye las sessiones de un array
     * @param array $claves
     */
    private function _destroyArray($claves)
    {
        for ($i = 0; $i < count($claves); $i++) {
            if (isset($_SESSION[$claves[$i]])) {
                unset($_SESSION[$claves[$i]]);
            }
        }
    }

    /**
     * Establece un valor de clave de sesión
     * @param string $clave
     * @param mixed $valor
     */
    public static function set($clave, $valor)
    {
        if (!empty($clave)){
            $_SESSION[$clave] = $valor;
        }
    }

    /**
     * Obtiene un valor de clave de sesión
     * @param string $clave
     * @return mixed
     */
    public static function get($clave)
    {
        if (isset($_SESSION[$clave])){
            return $_SESSION[$clave];
        }
    }

    /**
     * Controlamos el nivel de acceso
     * @param int $level 
     */
    public static function acceso($level)
    {
        if (!Session::get('autenticado')) {
            header('location:' . BASE_URL . '?option=error&sub=acceso&id=5050');
            exit;
        }

        Session::tiempo();

        if (Session::getLevel($level) > Session::getLevel(Session::get('level'))) {
            header('location:' . BASE_URL . '?option=error&sub=acceso&id=5050');
            exit;
        }
    }

    /**
     * Restringimos el acceso a la vista
     * @param int $level
     * @return boolean 
     */
    public static function accesoView($level)
    {
        if (!Session::get('autenticado')) {
            return false;
        }

        if (Session::getLevel($level) > Session::getLevel(Session::get('level'))) {
            return false;
        }

        return true;
    }

    /**
     * Obtiene el nivel de acceso del usuario
     * @param type $level
     * @return int
     * @throws Exception 
     */
    public static function getLevel($level)
    {
        $role['admin'] = 3;
        $role['especial'] = 2;
        $role['usuario'] = 1;

        if (!array_key_exists($level, $role)) {
            throw new Exception('Error de acceso');
        } else {
            return $role[$level];
        }
    }

    /**
     * Hace un control estricto de un grupo de usuario
     * $level tiene a los usuarios permitidos
     * @param array $level
     * @param bolean $noAdmin
     * @return boolean 
     */
    public static function accesoEstricto(array $level, $noAdmin = false)
    {
        if (!App_Session::get('autenticado')) {
            header('location:' . BASE_URL . '?option=error&sub=acceso&id=5050');
            exit;
        }

        App_Session::tiempo();

        if ($noAdmin == false) {
            if (App_Session::get('level') == 'admin') {
                return;
            }
        }
        //Me fijo si existe el nivel en el array
        if (count($level)) {
            if (in_array(App_Session::get('level'), $level)) {
                return;
            }
        }
        //Si no está en el array genero un error 5050
        header('location:' . BASE_URL . '?option=error&sub=acceso&id=5050');
    }

    /**
     * Hace un control estricto de la vista de un grupo de usuario
     * @param array $level
     * @param bolean $noAdmin
     * @return boolean 
     */
    public static function accesoViewEstricto(array $level, $noAdmin = false)
    {
        if (!App_Session::get('autenticado')) {
            return false;
        }

        if ($noAdmin == false) {
            if (App_Session::get('level') == 'admin') {
                return true;
            }
        }

        if (count($level)) {
            if (in_array(App_Session::get('level'), $level)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Controla el tiempo de session
     * @return type
     * @throws Exception 
     */
    public static function tiempo()
    {
        /** Si no está definida la variable tiempo en la session o
          no está definido el tiempo de session genero error */
        if (!App_Session::get('tiempo') || !defined('SESSION_TIME')) {
            throw new Exception('No se ha definido el tiempo de sesion');
        }

        //si la session es de tiempo indefinido
        if (SESSION_TIME == 0) {
            return;
        }

        if (time() - App_Session::get('tiempo') > (SESSION_TIME * 60)) {
            App_Session::destroy();
            header('location:' . BASE_URL . '?option=error&sub=acceso&id=8080');
        } else {
            App_Session::set('tiempo', time());
        }
    }

}
