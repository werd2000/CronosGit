<?php

require_once BASE_PATH . 'LibQ' . DS . 'Url.php';

Class App_Request
{

    private $_modulo;
    private $_controlador;
    private $_metodo;
    private $_argumentos;
    /* modulos de la app */
    private $_modulos = array(
        'Compras',
        'Honorarios',
        'Ventas',
        'Usuarios',
        'Proveedores',
        'Paciente',
        'Personal',
        'Cuentas',
        'Tareas',
        'Sueldos',
        'Turnos',
        'Formularios',
        'Impuestos',
        'PagoImpuestos',
        'Registro',
        'Configuracion'
    );

    public function __construct()
    {
        /* obtengo la URL desde la libreria */
        $url = LibQ_Url::obtenerURL();
        /* verifico la validez de la url. Si no es válida va a index.php */
        if (LibQ_Url::validarQueryString($url) === FALSE) {
            header('Location: index.php');
        }
        if (isset($url)) {
            /* Convierto la url en array */
            $urlArray = $this->_urlToArray($url);
            /* Establezco el módulo de acuerdo a la url */
            $this->_setModulo(array_shift($urlArray));
            /* si no existe el modulo establezco a false */
            if (!$this->_modulo) {
                $this->_modulo = false;
            } else {
                /* si existe 1º me fijo que el array modulos tenga datos */
                $urlArray = $this->_controlModulos($urlArray);
            }
            $this->_setMetodo(array_shift($urlArray));
            $this->_setArgumentos($urlArray);
        }


        if (!$this->_controlador) {
            $this->_controlador = DEFAULT_CONTROLADOR;
        }

        if (!$this->_metodo) {
            $this->_metodo = 'index';
        }

        if (!isset($this->_argumentos)) {
            $this->_argumentos = array();
        }
    }

    private function _controlModulos($urlArray)
    {
        if (count($this->_modulos)) {
            /* 2º me fijo que no exista en el array */
            if (!in_array($this->_modulo, $this->_modulos)) {
                /* si no existe entonces el controlador es el modulo */
                $this->_controlador = $this->_modulo;
                $this->_modulo = false;
            } else {
                //si existe en el array entonces seteo el controlador
//                        $this->_controlador = $this->_modulo;
                $this->_setControlador(array_shift($urlArray));

                if (!$this->_controlador) {
                    $this->_controlador = 'index';
                }
            }
        } else { //Si el array no tiene datos entonces el controlador es el modulo
            $this->_controlador = $this->_modulo;
            $this->_modulo = false;
        }
        return $urlArray;
    }

    private function _urlToArray()
    {
        $urlToArray = explode('&', trim($_SERVER['QUERY_STRING'], '?'));
        $urlArray = array_filter($urlToArray);
        return $urlArray;
    }

    private function _setModulo($url)
    {
        $url = explode('=', $url);
        if (isset($url[1])) {
            $this->_modulo = ($url[1]);
        }
    }

    private function _setControlador($url)
    {
        $url = explode('=', $url);
        if (isset($url[1])) {
            $this->_controlador = strtolower($url[1]);
        }
    }

    private function _setMetodo($url)
    {
        $url = explode('=', $url);
        if (isset($url[1])) {
            $this->_metodo = strtolower($url[1]);
        }
    }

    private function _setArgumentos($url)
    {
//        var_dump($url);
        foreach ($url as $argumento) {
            $arg = explode('=', $argumento);
            if (isset($arg[1])) {
                $this->_argumentos[$arg[0]] = $arg[1];
            }
        }
    }

    public function getModulo()
    {
        return $this->_modulo;
    }

    public function getControlador()
    {
        return $this->_controlador;
    }

    public function getMetodo()
    {
        return $this->_metodo;
    }

    public function getArgumentos()
    {
        return $this->_argumentos;
    }

}
