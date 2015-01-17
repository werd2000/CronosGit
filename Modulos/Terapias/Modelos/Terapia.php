<?php

/**
 * Clase Terapias
 *
 * @author WERD
 */
class Terapias_Modelos_Terapia
{

    protected $_id;
    protected $_terapia;
    private $_eliminado;
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getTerapia()
    {
        return $this->_terapia;
    }
    
    public function __construct($terapia)
    {
        if (isset($terapia['id'])){
            $this->_id = $terapia['id'];
        }
        if (isset($terapia['terapia'])){
            $this->_terapia = $terapia['terapia'];
        }
        if (isset($terapia['eliminado'])){
            $this->_eliminado = $terapia['eliminado'];
        }
    }

    public static function getTerapias($lista = array())
    {
        foreach ($lista as $terapia) {
            $resultado[] = new Terapia($terapia);
        }
        return $resultado;
    }
    
    public function __toString()
    {
        return ''. $this->_terapia;
    }
    
    

}

?>
