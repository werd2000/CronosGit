<?php

/**
 * Clase abstracta de la etiqueta Tabla
 *
 * @author WERD
 */
abstract class TablaAbstract {

    public function __construct() {
        
    }

    public function _configurar($options) {
        $retorno = '';
        if (is_array($options)) {
            if (isset($options['id'])) {
                $retorno .= ' id = "' . $options['id'] . '"';
            }
            if (isset($options['class'])) {
                $retorno .= ' class = "' . $options['class'] . '"';
            }
            if (isset($options['css_condicional'])) {
                $css = $this->_ponerStyle($datos, $options);
                if ($css != '') {
                    $retorno .= 'STYLE = "' . $css . '"';
                }
            }

            foreach ($options as $key => $value) {
                if (isset($options['mostrar'])) {
                    $retorno .= '';
                    break;
                }
                if (is_array($value)) {
                    $value = implode(' ', $value);
                }
                if (is_array($key)) {
                    $key = implode(' ', $key);
                }
                $retorno .= $key . ' = "' . $value . '"';
            }
        }
        return $retorno;
    }

    private function _ponerStyle($datos, $options) {
        $css = '';
        foreach ($options['css_condicion'] as $key => $value) {
//            echo 'estadoTurno=' . $datos->__get('_estadoTurno') . ' valor= ' . $value . '<br>';
            if ($datos->__get($key) == $value) {
                $css = $options['css_condicional'];
            }
        }
        return $css;
    }

}
