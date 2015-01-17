<?php

/**
 * Description of Buscar
 *
 * @author WERD
 */
class BuscarPlugin extends Plugin
{
    protected $_retorno;

    public function __construct()
    {
        parent::usarLibreria('Input');
        if ($_POST){
            $apellidoABuscar = Input::obtenerTexto('apellidos');
            $nombreABuscar = Input::obtenerTexto('nombres');
            $nro_docABuscar = Input::obtenerInt('nro_doc');
        }else{
            $apellidoABuscar = '';
            $nombreABuscar = '';
            $nro_docABuscar = '';
        }
        $retorno = '<div id="dialog-form-buscar" title="Buscar Paciente">';
        $retorno.='<form id="buscar_paciente" method="post" action="" class="ui-corner-all">';
        $retorno.='<span class="tituloBuscar">BUSCAR:</span>';
        $retorno.='<label for="apellidos">Apellidos</label>';
        $retorno.='<input type="text" name="apellidos" id="apellidos" class="text" value="'.$apellidoABuscar.'"/>';
        $retorno.='<label for="nombres">Nombres</label>';
        $retorno.='<input type="text" name="nombres" id="nombres" class="text" value="'.$nombreABuscar.'"/>';
        $retorno.='<label for="nro_doc">Nro. Doc.</label>';
        $retorno.='<input type="text" name="nro_doc" id="nro_doc" class="text" value="'.$nro_docABuscar.'"/>';
        $retorno.='<input type="submit" name="boton_buscar" id="boton_buscar" value="Buscar" class="boton ui-corner-all" />';
        $retorno.='</form>';
        $retorno.='</div>';
        $this->_retorno = $retorno;
    }
    
    public function render()
    {
        return $this->_retorno;
    }

}

?>
