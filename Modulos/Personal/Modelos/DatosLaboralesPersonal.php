<?php
/**
 * Description of Datos Laborales Personal
 *
 * @author WERD
 */
class datosLaboralesPersonal
{
    protected $_id;
    protected $_idProfesional;
    protected $_fechaIngreso;
    protected $_puesto;
    protected $_observaciones;
    protected $_antiguedad;
    protected $_datosLaborales;


    public function __construct($datos)
    {
        $this->_id = $datos['id'];
        $this->_idProfesional = $datos['idProfesional'];
        $this->_fechaIngreso = $datos['fechaIngreso'];
        $this->_puesto = $datos['terapia'];
        $this->_observaciones = $datos['observaciones'];
        $this->_antiguedad = $this->_calcularAntiguedad();
    }
    
    public function getId()
    {
        return $this->_id;
    }

    public function getIdProfesional()
    {
        return $this->_idProfesional;
    }
    
    public function getFechaIngreso()
    {
        return $this->_fechaIngreso;
    }
    
    public function getPuesto()
    {
        return $this->_puesto;
    }
    
    public function getObservaciones()
    {
        return $this->_observaciones;
    }
    
    
    private function _calcularAntiguedad()
    {
        $anio_dif = '';
        if ($this->_fechaIngreso){
        list($anio, $mes, $dia) = explode("-", $this->_fechaIngreso);
        $anio_dif = date("Y") - $anio;
        $mes_dif = date("m") - $mes;
        $dia_dif = date("d") - $dia;
        if ($dia_dif < 0 || $mes_dif < 0)
            $anio_dif--;
        }
        return $anio_dif;
    }
    
    public function __toString()
    {
        return $this->_puesto;
    }
}

?>
