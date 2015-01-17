<?php

require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'EtiquetaInterface.php';

require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'FilaDiv.php';

require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'TablaAbstract.php';

/**
 * Crea un tabla HTML con etiquetas DIV
 *
 * @author WERD
 */
class TablaDiv extends TablaAbstract implements EtiquetaInterface
{

    /**
     * Contenido parcial de la tabla
     * @var string 
     */
    private $_html_parcial;

    /**
     * Contenido parcial de la fila de la tabla
     * @var string
     */
    private $_fila_parcial;

    /**
     * Crea la tabla
     * @param array $datos el contenido de la tabla
     * @param array $options con los parámetros de configuración
     */
    public function __construct($datos = null, $options = null)
    {
        $optionsFila = array();
        if (is_array($options) && isset($options['fila'])) {
            $optionsFila = $options['fila'];
        }
        $optionTabla = $this->_getOptions($options);
        $this->_html_parcial = '<div ';
        $this->_html_parcial .= parent::_configurar($optionTabla) . ' >';
        if ($datos instanceof FilaDiv) {
            $this->_fila_parcial[] = $datos->render();
        } elseif (!is_null($datos)) {
            $this->_armarDatos($datos, $optionsFila);
        } else {
            $this->_fila_parcial[] = 'No se encontró contenido para mostrar';
        }
    }

    /**
     * Obtiene los parametros de configuracion de la tabla
     * @param array $options
     * @return array 
     */
    private function _getOptions($options = null)
    {
        $retorno = array();
        if (is_array($options)) {
            foreach ($options as $key => $value) {
                if ($key == 'fila') {
                    break;
                }
                $retorno[$key] = $value;
            }
        }
        return $retorno;
    }

    /**
     * Crea las filas con el contenido de $options
     * @param array $datos
     * @param array $options 
     */
    private function _armarDatos($datos, $options)
    {
        foreach ($datos as $key => $fila) {
            if (!is_array($fila)) {
                $fila = array($fila);
            }
            $fila = new FilaDiv($fila, $options);
            $this->_fila_parcial[] = $fila->render();
        }
    }

    /**
     * Regresa el html a mostrar
     * @return string 
     */
    public function render()
    {
        foreach ($this->_fila_parcial as $fila) {
            $this->_html_parcial = $this->_html_parcial . $fila;
        }
        $html = $this->_html_parcial . '</div>';
        return $html;
    }

}
