<?php
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
    /**
     * Controla el acceso a la informaciÃ³n
     * @var type 
     */
    protected $_acl;
    /**
     * Solicitud requerida en la url
     * @var String
     */
    protected $_request;

    /**
     * El constuctor inicializa la vista con el objeto Request 
     */
    public function __construct() {
        $this->_request = new App_Request();
//        $this->_acl = new App_Acl();
        $this->_cargarVista();
    }

    /**
     * Cargga la vista de acuerdo al objeto request y el controlador de accesos.
     */
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
        if (is_readable($rutaModelo)) {
            require_once $rutaModelo;
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
        if (is_readable($rutaPlugin)) {
            require_once $rutaPlugin;
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
     * returns <b>FALSE</b> if the variable is not set and <b>NULL</b> if the filter fails.
     * @return int 
     */
    protected function getIntPost($clave) {
        $retorno = filter_input(INPUT_POST, $clave, FILTER_SANITIZE_NUMBER_INT);
        return $retorno;
    }

    /**
     * Convierte los números en enteros
     * @param mixed $int
     * @return int 
     */
    protected function filtrarInt($intParam) {
        $int = (int) $intParam;
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
        return filter_input(INPUT_POST, $clave);
    }

    /**
     * filtra y sanitiza la clave pasada. Util para password
     * @param string $clave
     * @return string 
     */
    protected function getSql($clave) {
        $retorno = $this->getPostParam($clave);
        if (isset($retorno) && !empty($retorno)) {
            $retorno = strip_tags($this->getPostParam($clave));
            return trim($retorno);
        }
    }

    /**
     * Filtra y sanitiza la clave obteniendo solo numeros y letras
     * @param string $clave
     * @return string 
     */
    protected function getAlphaNum($clave) {
        $retorno = filter_input(INPUT_POST, $clave); 
        if (isset($retorno) && !empty($retorno)) {
            $retorno = (string) preg_replace('/[^A-Z0-9_]/i', '', filter_input(INPUT_POST, $clave));
        }
        return trim($retorno);
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
        if (!App_Session::get('autenticado') || App_Session::tiempo()){
            $this->redireccionar(URL_LOGIN);
        }
    }


}
