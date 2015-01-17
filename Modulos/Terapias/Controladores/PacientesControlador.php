<?php
/**
 * Clase Terapias Controlador 
 */
class pacientesControlador extends terapiasControlador
{

    private $_terapias;

    public function __construct()
    {
        parent::__construct();
        $this->_terapias = $this->cargarModelo('terapia','paciente');
    }

    
    public function listaTerapiasPaciente($id)
    {
        foreach ($this->_terapias->getTerapias($this->filtrarInt($id)) as $indice=>$terapia) {
            $listaIdTerapia[] = $terapia['idTerapia'];
        } 
        $lista = implode(',', $listaIdTerapia);      
        echo $lista;
    }
    
    

}