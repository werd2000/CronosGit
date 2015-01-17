<form id="nuevo_diagnostico_paciente" method="post" action="">
    <input type="hidden" id="diagnostico" name="guardar" value="1">
    <input type="hidden" name="idPaciente" value="<?php if ($this->datos->getId()) echo $this->datos->getId(); ?>" id="idPaciente">
    <input type="hidden" name="listadiagnostico" id="listadiagnostico" value="<?php if (isset($this->listaDiagnostico)) echo $this->listaDiagnostico; ?>">
    <input type="hidden" name="diagnostico" id="diagnostico" value="<?php if ($this->datos->getDiagnostico()) echo $this->datos->getDiagnostico(); ?>">
    <ul id="singleFieldTags">
        <li></li>
    </ul>
   
</form>