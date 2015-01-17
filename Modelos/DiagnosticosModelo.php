<?php

class diagnosticosModelo extends Modelo
{
    private $_verEliminados = 0;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function getDiagnosticos()
    {
        $sql = "SELECT * FROM cronos_dsmiv
                WHERE padre != 0
                ORDER BY id ASC";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchAll();
    }
    
   
}