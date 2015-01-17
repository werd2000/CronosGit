<?php
use LibQ\View\View,
    LibQ\View\CompositeView;

require_once BASE_PATH . 'LibQ' . DS . 'View' . DS . 'ViewInterface.php' ;
require_once BASE_PATH . 'LibQ' . DS . 'View' . DS . 'View.php' ;
require_once BASE_PATH . 'LibQ' . DS . 'View' . DS . 'CompositeView.php' ;

class App_Vista
{

    private $_request;
    private $_js;
    private $_acl;
    private $_rutas;
    private $_css;
    private $_cdn;
    private $_style;
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
        $css = array();

        if (count($this->_css)) {
            $css = $this->_css;
        }

        $js = array();

        if (count($this->_js)) {
            $js = $this->_js;
        }
        
        $style = array();
        
        if (count($this->_style)) {
            $style = $this->_style;
        }
        
        $bodyOnLoad = '';
        
        if (is_string($this->_bodyOnLoad)) {
            $bodyOnLoad = $this->_bodyOnLoad;
        }


        $_layoutParams = array(
            'ruta_css' => BASE_URL . 'Vistas/Layout/' . DEFAULT_LAYOUT . '/Css/',
            'ruta_Img' => BASE_URL . 'Vistas/Layout/' . DEFAULT_LAYOUT . '/Img/',
            'ruta_js' => BASE_URL . 'Vistas/Layout/' . DEFAULT_LAYOUT . '/Js/',
            'js' => $js,
            'root' => BASE_URL,
            'css' => $css,
            'style'=> $style,
            'bodyOnLoad'=>$bodyOnLoad
        );

        $_acl = $this->_acl;

        $rutaVista = $this->_rutas['vista'] . ucfirst($vista) . '.phtml';
        if (!is_readable($rutaVista)){
            throw new Exception('Error de vista');
        }
        
        
        $header = new \LibQ\View\LibQ_View_CompositeView("header");
        $header->content = include_once BASE_PATH . 'Vistas' . DS . 'Layout' . DS . DEFAULT_LAYOUT . DS . 'Header.php';
                
        $encabezado = new \LibQ\View\LibQ_View_CompositeView("encabezado");
        $encabezado->content = include_once BASE_PATH . 'Vistas' . DS . 'Layout' . DS . DEFAULT_LAYOUT . DS . 'Encabezado.php';

        $body = new \LibQ\View\LibQ_View_CompositeView("body");
        $body->content = include_once $rutaVista;

//        $lateral_izq = new \LibQ\View\LibQ_View_CompositeView("lateral_izq");
//        $lateral_izq->content = include_once BASE_PATH . 'Vistas' . DS . 'Layout' . DS . DEFAULT_LAYOUT . DS . 'Lateral_izq.php';
        
        $footer = new \LibQ\View\LibQ_View_CompositeView("footer");
        $footer->content = include_once BASE_PATH . 'Vistas' . DS . 'Layout' . DS . DEFAULT_LAYOUT . DS . 'Footer.php';

        $compositeView = new \LibQ\View\LibQ_View_CompositeView;

        echo $compositeView->attachView($header)
                ->attachView($encabezado)
                ->attachView($body)
//                ->attachView($lateral_izq)
                ->attachView($footer)
                ->render();


//        $rutaVista = $this->_rutas['vista'] . ucfirst($vista) . '.phtml';
////        echo $rutaVista;
//        if (is_readable($rutaVista)){
//            include_once BASE_PATH . 'Vistas' . DS . 'Layout' . DS . DEFAULT_LAYOUT . DS . 'Header.php';
//            include_once BASE_PATH . 'Vistas' . DS . 'Layout' . DS . DEFAULT_LAYOUT . DS . 'Encabezado.php';
//            include_once $rutaVista;
//            include_once BASE_PATH . 'Vistas' . DS . 'Layout' . DS . DEFAULT_LAYOUT . DS . 'Footer.php';
//        }  else {
//            throw new Exception('Error de vista');
//        }
    }

    /**
     * Incluye un archivo Js propio de esa vista
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
        if (is_array($js) && count($js)) {
            foreach ($js as $archivoJs) {
                $this->_js[] = BASE_URL . 'Vistas/' . $this->_request->getControlador() . '/js/' . $archivoJs . '.js';
            }
        } else {
            throw new Exception('Error de Js');
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
                if($absoluto){
                    $this->_js[] = $this->_rutas['js'] . $archivoJs . '.js';
                }  else {
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