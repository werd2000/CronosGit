<?php
/**
 * La clase vista construye la vista a mostrar
 */
use LibQ\View\View,
    LibQ\View\CompositeView;

require_once BASE_PATH . 'LibQ' . DS . 'View' . DS . 'ViewInterface.php' ;
require_once BASE_PATH . 'LibQ' . DS . 'View' . DS . 'View.php' ;
require_once BASE_PATH . 'LibQ' . DS . 'View' . DS . 'CompositeView.php' ;

class App_Vista
{
    /**
     * El objeto request donde distingo los parametros para la vista.
     * @var App_Request
     */
    private $_request;
    /**
     * Un array con los archivos js a cargar en la vista.
     * @var Array
     */
    private $_js;
    /**
     * El objeto ACL que controla los permisos del usuario para ver o no la vista.
     * @var ACL
     */
    private $_acl;
    /**
     * Un array con las rutas a utilizar en la vista.
     * @var Array
     */
    private $_rutas;
    /**
     * Un array con los archivos css a incluir en la vista.
     * @var Array
     */
    private $_css;
    /**
     * Un array con las rutas CDN a incluir en la vista
     * @var Array
     */
    private $_cdn;
    /**
     * Nombre de un archivo css para usar en la vista actual.
     * @var String
     */
    private $_style;
    /**
     * Script para ser incluido en el tag <body>.
     * @var String
     */
    private $_bodyOnLoad;

    public function __construct(App_Request $peticion, App_Acl $_acl=null)
    {
        $this->_request = $peticion;
        $this->_js = array();
        $this->_acl = $_acl;
        $this->_rutas = array();
        $modulo = $this->_request->getModulo();
        $controlador = $this->_request->getControlador();
        if ($modulo) {
            $this->_rutas['vista'] = BASE_PATH . 'Modulos' . DS . ucfirst($modulo) . DS . 'Vistas' . DS . ucfirst($controlador) . DS;
            $this->_rutas['js'] = BASE_URL . 'Modulos/' . ucfirst($modulo) . '/Vistas/' . ucfirst($controlador) . '/Js/';
            $this->_rutas['css'] = BASE_URL . 'Modulos/' . ucfirst($modulo) . '/Vistas/' . ucfirst($controlador) . '/Css/';            
        } else {
            $this->_rutas['vista'] = BASE_PATH . 'Vistas' . DS . ucfirst($controlador) . DS;
            $this->_rutas['js'] = BASE_URL . 'Vistas/' . ucfirst($controlador) . '/Js/';
            $this->_rutas['css'] = BASE_URL . 'Vistas/' . ucfirst($controlador) . '/Css/';
        }
    }

    /**
     * Muestra una vista
     * @param type $vista
     * @param type $item
     * @throws Exception
     */
    public function renderizar($vista, $item = false)
    {
        $css   = $this->_controlCss();
        $js    = $this->_controlJs();
        $style = $this->_controlStyle();
        $bodyOnLoad = $this->_controlBodyOnLoad();
//        $_acl = $this->_acl;
        $rutaVista = $this->_controlRutaVista($vista);
        $header = $this->_incluirHeader($js, $css, $style, $bodyOnLoad);
        $encabezado = $this->_incluirEncabezado();
        $body = $this->_incluirBody($rutaVista);
        $footer = $this->_incluirFooter();
        $this->_crearPagina($header,$encabezado,$body,$footer);
    }
    
    /**
     * Controla que la variable css sea un Array.
     * @return Array
     */
    private function _controlCss()
    {
        $css = array();
        if (count($this->_css)) {
            $css = $this->_css;
        }
        return $css;
    }
    
    /**
     * Controla que la variable js sea un Array.
     * @return Array
     */
    private function _controlJs()
    {
        $js = array();
        if (count($this->_js)) {
            $js = $this->_js;
        }
        return $js;
    }
    
     /**
     * Controla que la variable style sea un Array.
     * @return Array
     */
    private function _controlStyle()
    {
        $style = array();
        if (count($this->_style)) {
            $style = $this->_style;
        }
        return $style;
    }
    
    /**
     * Controla que la variable bodyOnLoad sea un string.
     * @return String
     */
    private function _controlBodyOnLoad()
    {
        $bodyOnLoad = '';
        if (is_string($this->_bodyOnLoad)) {
            $bodyOnLoad = $this->_bodyOnLoad;
        }
        return $bodyOnLoad;
    }
    
    /**
     * Controla que la ruta de la vista exista y sea leible.
     * Si no lanza una exception.
     * @param String $vista
     * @return string
     * @throws Exception
     */
    private function _controlRutaVista($vista)
    {
        $rutaVista = $this->_rutas['vista'] . ucfirst($vista) . '.phtml';
        if (!is_readable($rutaVista)) {
            throw new Exception('Error de vista');
        }
        return $rutaVista;
    }
    
    /**
     * Incluye el header de la vista
     * @return View
     */
    private function _incluirHeader($js, $css, $style, $bodyOnLoad)
    {
        $_layoutParams = array(
            'ruta_css' => BASE_URL . 'Vistas/Layout/' . DEFAULT_LAYOUT . '/Css/',
            'ruta_bootstrap' => BASE_URL . 'Vistas/Layout/' . DEFAULT_LAYOUT . '/bootstrap/',
            'ruta_Img' => BASE_URL . 'Vistas/Layout/' . DEFAULT_LAYOUT . '/Img/',
            'ruta_js' => BASE_URL . 'Vistas/Layout/' . DEFAULT_LAYOUT . '/Js/',
            'js' => $js,
            'root' => BASE_URL,
            'css' => $css,
            'style' => $style,
            'bodyOnLoad' => $bodyOnLoad
        );
        $header = new View("header");
        $header->content = include_once BASE_PATH . 'Vistas' . DS . 'Layout' . DS . DEFAULT_LAYOUT . DS . 'Header.php';
        return $header;
    }
    
    /**
     * Incluye un encabezado en la vista.
     * @return View
     */
    private function _incluirEncabezado()
    {
        $encabezado = new View("encabezado");
        $encabezado->content = include_once BASE_PATH . 'Vistas' . DS . 'Layout' . DS . DEFAULT_LAYOUT . DS . 'Encabezado.php';
        return $encabezado;
    }
    
    /**
     * Incluye el body en la vista.
     * @param Strng $rutaVista La ruta del body a incluir.
     * @return View
     */
    private function _incluirBody($rutaVista)
    {
        $body = new View("body");
        $body->content = include_once $rutaVista;
        return $body;
    }
    
    /**
     * Incluye el footer en la vista.
     * @return View
     */
    private function _incluirFooter()
    {
        $footer = new View("footer");
        $footer->content = include_once BASE_PATH . 'Vistas' . DS . 'Layout' . DS . DEFAULT_LAYOUT . DS . 'Footer.php';
        return $footer;
    }
    
    private function _crearPagina($header, $encabezado, $body, $footer)
    {
        $compositeView = new LibQ\View\LibQ_View_CompositeView();
        echo $compositeView->attachView($header)
                ->attachView($encabezado)
                ->attachView($body)
                ->attachView($footer)
                ->render();
    }

    /**
     * Incluye una ruta CDN propio de esa vista
     * @param array $js
     * @throws Exception 
     */
    public function setVistaCdn(array $js)
    {
        if (is_array($js) && count($js)) {
            foreach ($js as $archivoJs) {
                $this->_cdn[] = $archivoJs;
            }
        } else {
            throw new Exception('Error de Js');
        }
    }
    
    /**
     * Incluye un archivo Js propio de esa vista
     * @param array $js
     * @throws Exception 
     */
    public function setVistaJs(array $js)
    {
        $ruta = $this->_request->getModulo();
        $ruta .= '/Vistas/' . ucfirst($this->_request->getControlador());
        
        if (is_array($js) && count($js)) {
            foreach ($js as $archivoJs) {
                if($ruta=='login'){
                    $ruta = 'Usuarios/Vistas/Login/';
                }
                $this->_js[] = BASE_URL . 'Modulos/' . 
                        $ruta . '/Js/' . $archivoJs . '.js';
            }
        } else {
            throw new Exception('Error de Js');
        }
    }

    
    public function setVistaCss(array $css)
    {
        $ruta = $this->_request->getModulo();
        $ruta .= '/Vistas/' . ucfirst($this->_request->getControlador());
        if (is_array($css) && count($css)) {
            foreach ($css as $archivoCss) {
                if($ruta=='login'){
                    $ruta = 'Usuarios/Vistas/Login/';
                }
                $this->_css[] = BASE_URL . 'Modulos/' . 
                        $ruta . '/css/'. $archivoCss . '.css';
            }
        } else {
            throw new Exception('Error de CSS');
        }
    }
        
    /**
     * Incluye un archivo Js propio de ese layout
     * @param array $js
     * @throws Exception 
     */
    public function setLayoutJs(array $js)
    {
        if (is_array($js) && count($js)) {
            foreach ($js as $archivoJs) {
                $this->_js[] = BASE_URL . 'Vistas/Layout/Default/js/' . $archivoJs . '.js';
            }
        } else {
            throw new Exception('Error de Js');
        }
    }

    /**
     * Incluye un archivo Js
     * @param array $js
     * @throws Exception 
     */
    public function setJs(array $js, $absoluto = TRUE)
    {
        if (is_array($js) && count($js)) {
            foreach ($js as $archivoJs) {
                if ($absoluto) {
                    $this->_js[] = BASE_URL . 'Vistas/Layout/' . DEFAULT_LAYOUT . '/Js/' . $archivoJs . '.js';
                } else {
                    $this->_js[] = $archivoJs;
                }
            }
        } else {
            throw new Exception('Error de Js');
        }
    }
        

    /**
     * Incluye un archivo Js propio de esa vista
     * @param array $js
     * @throws Exception 
     */
    public function setCss(array $css)
    {
        if (is_array($css) && count($css)) {
            foreach ($css as $archivoCss) {
                $this->_css[] = $this->_rutas['css'] . $archivoCss . '.css';
            }
        } else {
            throw new Exception('Error de Css');
        }
    }
    
    
    
    /**
     * Incluye un estilo propio de esa vista
     * @param string style
     * @throws Exception 
     */
    public function setStyle($style)
    {
        if (is_string($style)){
            $this->_style[] = $style;
        }else {
            throw new Exception('Error de Script');
        }
    }
    
    /**
     * Incluye parametros en la etiqueta body
     * @param string bodyOnLoad
     * @throws Exception 
     */
    public function setBodyOnLoad($bodyOnLoad)
    {
        if (is_string($bodyOnLoad)){
            $this->_bodyOnLoad[] = $bodyOnLoad;
        }else {
            throw new Exception('Error de Script Body');
        }
    }

}