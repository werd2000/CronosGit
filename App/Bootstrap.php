<?php

/**
 * Comprueba que tenemos un directorio del controlador predeterminado. Si no, una
 * Excepción es lanzada.
 */
class App_Bootstrap
{

    protected $_request;
    protected $_controller;
    protected $rutaControlador;

    /**
     * Inicializacion del bootstrap
     * @param App_Request $request
     */
    public function __construct(App_Request $request)
    {
        $this->_request = $request;
    }

    /**
     * Ejecuta la App
     * @throws Exception 
     */
    public function run()
    {
        /* Inicio Session */
        $session = App_Session::init();
        /* Obtengo el módulo con el que voy a trabajar */
        $modulo = $this->_request->getModulo();
        /* Obtengo el nombre del archivo controlador */
        $this->_controller = $this->_request->getControlador() . 'Controlador';
        /* Obtengo el nombre del método que quiero ejecutar */
        $metodo = $this->_request->getMetodo();
        /* Obtengo los argumentos para la función que quiero ejecutar */
        $args = $this->_request->getArgumentos();
        /* Control y carga del módulo */
        
        $this->_controlModulo($modulo);
        $this->_controlControlador($modulo, $metodo, $args);
        $this->_controlMetodo($metodo, $args);
    }

    /**
     * Verifica que el módulo exista y establece la ruta del controlador
     * @param string $modulo
     * @throws Exception
     */
    private function _controlModulo($modulo)
    {
        if ($modulo) {
            $rutaBaseModulo = BASE_PATH . 'Controladores' . DS . ucfirst($modulo) . 'Controlador.php';
            if (is_readable($rutaBaseModulo)) {
                require_once $rutaBaseModulo;
                $this->rutaControlador = BASE_PATH . 'Modulos' . DS . ucfirst($modulo) . DS . 'Controladores' . DS . ucfirst($this->_controller) . '.php';
            } else {
                throw new Exception('Error de base de modulo');
            }
        } else {
            $this->rutaControlador = BASE_PATH . 'Controladores' . DS . ucfirst($this->_controller) . '.php';
        }
    }

    /**
     * Verifica que exista y crea el objeto controlador
     * @param string $modulo
     * @throws Exception
     */
    private function _controlControlador($modulo)
    {
        if (is_readable($this->rutaControlador)) {
            require_once $this->rutaControlador;
            if ($modulo) {
                $controlador = $modulo . '_Controladores_' . $this->_controller;
            } else {
                $controlador = 'Controladores_' . $this->_controller;
            }
            $this->_controller = new $controlador;
        } else {
            throw new Exception($this->_controller . ' No encontrado');
        }
    }

    /**
     * Verifica y llama al método del controlador
     * @param string $metodo
     * @param array $args
     */
    private function _controlMetodo($metodo, $args)
    {
        if (is_callable(array($this->_controller, $metodo))) {
            $metodo = $this->_request->getMetodo();
        } else {
            $metodo = 'Index';
        }
        if (isset($this->_controller)) {
            call_user_func_array(array($this->_controller, $metodo), $args);
        } else {
            call_user_func($this->_controller, $metodo);
        }
    }

}
