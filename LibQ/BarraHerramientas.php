<?php

require_once LIB_PATH . 'BotonBarra.php';
require_once LIB_PATH . 'HTML' . DS . 'Decorator.php';

class LibQ_BarraHerramientas
{

    private $_retorno;
    private $_botones;
    private $_decorador;
    public $buttonDecorators = array(
        'ViewHelper',
        array(
            array('data' => 'HtmlTag'),
            array('tag' => 'td', 'class' => 'button', 'id' => 'toolbar-save')
        ),
    );

    function __construct(ArrayObject $arrayBotones = null, LibQ_HTML_Decorator $decorador = null)
    {
        if (!is_null($arrayBotones)) {
            foreach ($arrayBotones as $boton) {
                $this->agregarBoton($boton);
            }
        }

        if (is_null($decorador)) {
            $this->_decorador = new LibQ_HTML_Decorator();
        }
    }

    public function agregarBoton(BotonInterface $boton)
    {
        $this->_botones[] = $boton;
    }

    public function render()
    {
        $retorno = '<div class="btn-toolbar" role="toolbar" '
                . 'aria-label="Toolbar with button groups">';
        $retorno .= '<div class="btn-group">';
        foreach ($this->_botones as $boton) {
            $retorno .= $boton->render();
        }
        $retorno .= '</div>';
        $retorno .= '</div>';
        return $retorno;
    }

    private function _ifExisteClase($class)
    {
        $file = LIB_PATH . 'HTML/Boton/' . 'Boton' . ucfirst($class) . '.php';
        if (!file_exists($file)) {
            $class = 'generic';
        }
        return 'Boton' . ucfirst($class);
    }

    /**
     * Agrega un botón a la barra de herramientas
     * @param mixed $tipo indica el tipo de boton: agregar, eliminar, nuevo...
     * @param array $arg Argumentos para crear el botóns
     */
    public function addBoton($tipo, $arg)
    {
        $clase = self::_ifExisteClase($tipo);
        $file = LIB_PATH . 'HTML' . DS . 'Boton' . DS . $clase . '.php';
        require_once ($file);
        $class = 'LibQ_Html_Boton_' . $clase;
//        echo $class . '<br>';
        $boton = new $class($arg);
        $this->_botones[] = $boton;
        return $boton;
    }

    /**
     * Agrega un botón a la barra de herramientas
     * @param mixed $tipo indica el tipo de boton: agregar, eliminar, nuevo...
     * @param array $arg Argumentos para crear el botóns
     */
    public function addABoton($tipo, $arg)
    {
        $clase = self::_ifExisteClase($tipo);
        $file = LIB_PATH . 'HTML' . DS . 'Boton' . DS . $clase . '.php';
        require_once ($file);
        $class = 'LibQ_Html_Boton_' . $clase;
        $boton = new $class($arg);
        $this->_botones[] = $boton;
    }

    public function __toString()
    {
        $this->render();
    }

}
