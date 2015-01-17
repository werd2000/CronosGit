<?php

require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'EtiquetaInterface.php';

require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'CeldaDiv.php';

require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'TablaAbstract.php';

/**
 * Crea una fila con etiquetas Div
 *
 * @author WERD
 */
class FilaDiv extends TablaAbstract implements EtiquetaInterface {

    /**
     * Contenido parcial de la fila
     * @var string 
     */
    private $_fila_parcial;

    /**
     * Clase constructora
     * crea la fila que va a mostrarse en la tabla con el contenido de $options
     * @param array $options 
     */
    public function __construct($datos = null, $options = null) {
        $optionsCelda = array();
        $optionsFila = $this->_getOptions($options);
        if (is_array($options) && isset($options['celda'])) {
            $optionsCelda = $options['celda'];
        }
        $this->_fila_parcial = '<div ';
        $this->_fila_parcial .= parent::_configurar($optionsFila) . ' >';
        if (!is_null($datos)) {
//            print_r($datos);
            $this->_crearCelda($datos, $optionsCelda);
        } else {
            $this->_fila_parcial[] = 'No se encontró contenido para mostrar';
        }
    }

    /**
     * Obtiene los parametros de configuracion de la fila
     * @param array $options
     * @return array 
     */
    private function _getOptions($options = null) {
        $retorno = array();
        if (is_array($options)) {
            foreach ($options as $key => $value) {
                if ($key == 'celda') {
                    break;
                }
                $retorno[$key] = $value;
            }
        }
        return $retorno;
    }

    /**
     * Crea las celdas con el contenido de $datos
     * @param string $datos 
     */
    private function _crearCelda($datos = null, $options = null) {
        foreach ($datos as $key => $datoCelda) {
            if (is_array($datoCelda)) {
                $datoCelda = implode(',', $datoCelda);
            }
            $celda = new CeldaDiv($datoCelda, $options);
            $this->_celda_parcial[] = $celda->render();
        }
    }

    /**
     * Regresa el html a mostrar
     * @return string 
     */
    public function render() {
        foreach ($this->_celda_parcial as $celda) {
            $this->_fila_parcial = $this->_fila_parcial . $celda;
        }
        $html = $this->_fila_parcial . '</div>';
        return $html;
    }

}
