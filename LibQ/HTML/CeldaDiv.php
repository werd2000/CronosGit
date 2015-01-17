<?php

require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'EtiquetaInterface.php';

require_once BASE_PATH . 'LibQ' . DS . 'HTML' . DS . 'TablaAbstract.php';

/**
 * Crea una celda con etiquetas Div
 *
 * @author WERD
 */
class CeldaDiv extends TablaAbstract implements EtiquetaInterface {

    /**
     * Identificador de la celda  --  <div id=
     * @var string
     */
    private $_id;

    /**
     * Contenido parcial de la celda
     * @var string
     */
    private $_celda_parcial;

    /**
     * Clase constructora
     * Crea la fila que ha de mostrarse en la tabla
     * @param type $id
     * @param type $options 
     */
    public function __construct($datos = null, $options = null) {
        if (isset($options['mostrar'])) {
            $mostrar = $options['mostrar'];
        }

        $this->_celda_parcial[] = '<div ';
        $this->_celda_parcial[] = $this->_contenidoExtra($datos, $options);
        $this->_celda_parcial[] = parent::_configurar($options) . ' >';
        if (!is_null($datos)) {
            if (is_object($datos)) {
                foreach ($mostrar as $valor) {
                    if ($datos->__get($valor)!= NULL){
                        $this->_celda_parcial[] = $datos->__get($valor);
                    }
                }
                if (isset($options['html'])){
                    $this->_celda_parcial[] = $options['html'];
                }               
            } else {
                $this->_celda_parcial[] = $datos;
            }
        }
    }

    private function _contenidoExtra($datos = null, $options = null) {
        $retorno = array();
        if (is_array($options)) {
            $retorno = $this->_recorrerOpciones($options, $datos);
        }
        return implode(' ', $retorno);
    }

    private function _recorrerOpciones($options, $datos) {
        $retorno = array();
        foreach ($options as $key => $value) {
            if ($key != 'class' and $key != 'mostrar' and $key != 'html' and $key != 'css_condicional' and $key != 'css_condicion'){
                if (is_object($datos)){
                    $retorno[] = $key . '= "' . $datos->__get($value) . '"';
                }elseif(is_array($datos)) {
                    $retorno[] = $key . '= "' . $datos[$value] . '"';
                }else{
                    $retorno[] = $key . '= ""';
                }
            }
        }
        return $retorno;
    }

    /**
     * Regresa el html a mostrar
     * @return string 
     */
    public function render() {
        return implode(' ', $this->_celda_parcial) . '</div>';
    }

}
