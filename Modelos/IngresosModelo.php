<?php

class ingresosModelo extends Modelo
{
    private $_verEliminados = 0;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getUltimoIngresoByOs($os)
    {
        $sql = "SELECT * FROM conta_ingresos
                WHERE cliente = 2
                AND eliminado = $this->_verEliminados
                ORDER BY nro_comprobante DESC";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
   
}