<?php

/**
 * Description of Buscar
 *
 * @author WERD
 */
class BuscarPlugin extends App_Plugin
{
    protected $_retorno;
    protected $_listaOSociales;
    protected $_apellidoABuscar;
    protected $_nombreABuscar;
    protected $_idObraSocial;
    protected $_diagnostico;

    public function __construct()
    {
        parent::usarLibreria('Input');
        if ($_POST){
            $this->_apellidoABuscar = Input::obtenerTexto('apellidos');
            $this->_nombreABuscar = Input::obtenerTexto('nombres');
            $this->_nro_docABuscar = Input::obtenerInt('nro_doc');
            $this->_idObraSocial = Input::obtenerInt('idObraSocial');
            $this->_diagnostico = Input::obtenerTexto('diagnostico');
        }else{
            $this->_apellidoABuscar = '';
            $this->_nombreABuscar = '';
            $this->_nro_docABuscar = '';
            $this->_idObraSocial = 0;
            $this->_diagnostico = '';
        }
        
        $this->_retorno = '';
    }
    
    public function setListaOSociales($datos)
    {
        $this->_listaOSociales = $datos;
    }


    public function render()
    {
        $retorno = '<div id="dialog-form-buscar" title="Buscar Paciente">';
        $retorno.='<form id="buscar_paciente" method="post" action="" class="ui-corner-all">';
        $retorno.='<span class="tituloBuscar">BUSCAR:</span>';
        $retorno.='<label for="apellidos">Apellidos</label>';
        $retorno.='<input type="text" name="apellidos" id="apellidos" class="text" value="'.$this->_apellidoABuscar.'"/>';
        $retorno.='<label for="nombres">Nombres</label>';
        $retorno.='<input type="text" name="nombres" id="nombres" class="text" value="'.$this->_nombreABuscar.'"/>';
        $retorno.='<label for="nro_doc">Nro. Doc.</label>';
        $retorno.='<input type="text" name="nro_doc" id="nro_doc" class="text" value="'.$this->_nro_docABuscar.'"/>';
        $retorno.='<input type="submit" name="boton_buscar" id="boton_buscar" value="Buscar" class="boton ui-corner-all" />';
        
        $retorno.='<label for="idObraSocial" class="optional">Obra Social:</label>';
        $retorno.='<select name="idObraSocial" id="idObraSocial">';
        if (isset($this->_listaOSociales)){
            $retorno.= '<option value=0 label=""></option>';
            foreach ($this->_listaOSociales as $oSocial){
                if ($oSocial['id'] == $this->_idObraSocial){
                    $retorno.= '<option value=' . $oSocial['id'] . ' label="' . $oSocial['denominacion'] . '"  selected="selected" >' . $oSocial['denominacion'] . '</option>';
                }else{
                    $retorno.= '<option value=' . $oSocial['id'] . ' label="' . $oSocial['denominacion'] . '">' . $oSocial['denominacion'] . '</option>';
                }
            }
        }
        $retorno.= '</select>';
        $retorno.='<label for="diagnostico">Diagnóstico</label>';
        $retorno.='<input type="text" name="diagnostico" id="diagnostico" class="text" value="'.$this->_diagnostico.'"/>';
        $retorno.='</form>';
        $retorno.='</div>';
        return $retorno;
    }

}

?>
