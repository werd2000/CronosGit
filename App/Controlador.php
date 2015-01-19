<?php

//require_once BASE_PATH . 'LibQ' . DS . 'IniParser.php';

/**
 * Clase Abstracta Controlador 
 * de está clase se extienden todos los controladores
 */
abstract class App_Controlador {

    /**
     * La vista a que usará el controlador
     * @var Vista 
     */
    protected $_vista;
    protected $_acl;
    protected $_request;
    protected $_cfg;

    /**
     * El constuctor inicializa la vista con el objeto Request 
     */
    public function __construct() {
        $this->_request = new App_Request();
//        $this->_acl = new App_Acl();
        $this->_cargarVista();
//        $this->_cfg = new iniParser("config.ini"); 
//        $this->_controlarParametrosIni();
    }

    private function _controlarParametrosIni() {
//        echo $this->_cfg->get("Empresa", "nombre");
        if ($this->_cfg->get("Empresa", "nombre")) {
            $this->redireccionar('option=Paciente&sub=admision');
        }
    }

    private function _cargarVista() {
        $this->_vista = new App_Vista($this->_request, $this->_acl);
    }

    /**
     * Método abstracto que debe ser escrito en cada controlador 
     */
    abstract public function index();

    /**
     * Método protegido para cargar el modelo que usará el controlador
     * 
     * Carga un archivo de modelo buscandoloo en el nombre del modulo indicado.
     * Si no hay nombre de modulo lo busca en la carpeta Modelos
     * @param string $modelo
     * @param string $modulo
     * @return \modelo
     * @throws Exception 
     */
    protected function cargarModelo($modelo, $modulo = false) {
        $modelo = ucfirst($modelo) . 'Modelo';
        $rutaModelo = BASE_PATH . 'Modelos' . DS . $modelo . '.php';
        if (!$modulo) {
            $modulo = $this->_request->getModulo();
        }

        if ($modulo) {
            if ($modulo != 'default') {
                $rutaModelo = BASE_PATH . 'Modulos' . DS . ucfirst($modulo) . DS . 'Modelos' . DS . $modelo . '.php';
            }
        }
//        echo $rutaModelo;
        if (is_readable($rutaModelo)) {
//            echo 'es leible ' . $rutaModelo . '<br>';
            require_once $rutaModelo;
//            echo $rutaModelo . '<br>';
            $clase = $modulo . '_Modelos_' . $modelo;
            $modelo = new $clase;
            return $modelo;
        } else {
            throw new Exception('Error de modelo: ' . $modelo);
        }
    }

    /**
     * Método protegido para cargar el Plugin que usará el controlador
     * 
     * Carga un archivo de Plugin buscandoloo en el nombre del modulo indicado.
     * Si no hay nombre de modulo lo busca en la carpeta Plugin
     * @param string $plugin
     * @param string $modulo
     * @return \plugin
     * @throws Exception 
     */
    protected function cargarPlugin($plugin, $modulo = false) {
        $plugin = ucfirst($plugin) . 'Plugin';
        $rutaPlugin = BASE_PATH . 'Plugins' . DS . $plugin . '.php';

        if (!$modulo) {
            $modulo = $this->_request->getModulo();
        }

        if ($modulo) {
            if ($modulo != 'default') {
                $rutaPlugin = BASE_PATH . 'Modulos' . DS . ucfirst($modulo) . DS . 'Plugins' . DS . $plugin . '.php';
            }
        }
//        echo $rutaPlugin;
        if (is_readable($rutaPlugin)) {
//            echo 'es leible ' . $rutaPlugin . '<br>';
            require_once $rutaPlugin;
//            echo $rutaPlugin . '<br>';
            $plugin = new $plugin;
            return $plugin;
        } else {
            throw new Exception('Error de Plugin: ' . $plugin);
        }
    }

    /**
     * Método para iniciar una librería
     * El método crea la ruta y verifica que exista y pueda ser accedida
     * Si todo está ok la requiere
     * @param string $libreria
     * @throws Exception 
     */
    protected function getLibreria($libreria) {
        $rutaLibreria = BASE_PATH . 'LibQ' . DS . $libreria . '.php';
//        echo $rutaLibreria;
        if (is_readable($rutaLibreria)) {
            require_once $rutaLibreria;
        } else {
            throw new Exception('Error de libreria');
        }
    }

    /**
     * Método para iniciar un formulario
     * El método crea la ruta y verifica que exista y pueda ser accedida
     * Si todo está ok la requiere
     * @param string $formulario
     * @throws Exception 
     */
    protected function getFormulario($formulario) {
        $request = new Request();
        $rutaFormulario = BASE_PATH . 'Formularios' . DS . $request->getControlador() . DS . $formulario . '.php';

        if (is_readable($rutaFormulario)) {
            require_once $rutaFormulario;
        } else {
            throw new Exception('Error de Formulario');
        }
    }

    /**
     * Método protegido para redireccionar una página
     * @param string $ruta 
     */
    protected function redireccionar($ruta = false) {
        if ($ruta) {
            header('location:' . BASE_URL . '?' . $ruta);
            exit;
        } else {
            header('location:' . BASE_URL);
            exit;
        }
    }

    /**
     * Obtiene el texto que viene en una clave del $_POST
     * @param string $clave
     * @return string 
     */
    protected function getTextoPost($clave) {
        $retorno = filter_input(INPUT_POST, $clave, FILTER_SANITIZE_STRING);
        return $retorno;
    }

    /**
     * Obtiene el entero que viene en una clave del $_POST
     * @param string $clave
     * @return int 
     */
    protected function getIntPost($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = filter_input(INPUT_POST, $clave, FILTER_VALIDATE_INT);
            return $_POST[$clave];
        }

        return 0;
    }

    /**
     * Convierte los números en enteros
     * @param mixed $int
     * @return int 
     */
    protected function filtrarInt($int) {
        $int = (int) $int;
        if (is_int($int)) {
            return $int;
        } else {
            return 0;
        }
    }

    /**
     * Obtiene un parametro del Post sin filtrar
     * @param string $clave
     * @return mixed 
     */
    protected function getPostParam($clave) {
        if (isset($_POST[$clave])) {
            return $_POST[$clave];
        }
    }

    /**
     * filtra y sanitiza la clave pasada. Util para password
     * @param string $clave
     * @return string 
     */
    protected function getSql($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = strip_tags($_POST[$clave]);
            return trim($_POST[$clave]);
        }
    }

    /**
     * Filtra y sanitiza la clave obteniendo solo numeros y letras
     * @param string $clave
     * @return string 
     */
    protected function getAlphaNum($clave) {
        if (isset($_POST[$clave]) && !empty($_POST[$clave])) {
            $_POST[$clave] = (string) preg_replace('/[^A-Z0-9_]/i', '', $_POST[$clave]);
            return trim($_POST[$clave]);
        }
    }

    /**
     * Comprueba que la dircción de email sea válida
     * @param string $email
     * @return boolean 
     */
    public function validarEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }
    
    public function gestorMensajes()
    {
        $mensaje = 'DATOS_NO_GUARDADOS';
        $array = $this->_request->getArgumentos();
        if (array_key_exists('resultado', $array)) {
            $this->_vista->_mensaje = $array['resultado'];
        }
        if (array_key_exists('error', $array)) {
            $this->_vista->_msj_error = $array['error'];
        }
    }
    
    public function isAutenticado()
    {
        if (!App_Session::get('autenticado')){
            $this->redireccionar('mod=Usuarios&cont=Login&met=index');
        }
    }


}
