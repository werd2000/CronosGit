<form id="nuevo_paciente" method="post" action="<?php echo BASE_URL; ?>?option=Paciente&sub=admision&met=editarTerapiaAdmision&id=<?php if (isset($this->datos['id'])) echo $this->datos['id']; ?>">
    <input type="hidden" id="guardar" name="guardar" value="1">
    <input type="hidden" name="idPaciente" value="<?php if (isset($this->datos['id'])) echo $this->datos['id']; ?>" id="idPaciente">

    <label for="diagnostico" class="optional">Diagnóstico:</label>
    <textarea name="diagnostico" id="diagnostico" rows="4" cols="60">
        <?php if (isset($this->datos['diagnostico']))
            echo $this->datos['diagnostico'];
        ?>
    </textarea>
    <label for="idObraSocial" class="optional">Obra Social:</label> 
    <select name="idObraSocial" id="idObraSocial">
        <?php foreach ($this->listaOSociales as $oSocial): ?>
            <?php if ($oSocial['id'] == $this->datosOSocial['idOSocial']): ?>
                <option value=<?php echo $oSocial['id']; ?> label="<?php echo $oSocial['denominacion']; ?>" selected="selected" ><?php echo $oSocial['denominacion']; ?></option>
            <?php else: ?>
                <option value=<?php echo $oSocial['id']; ?> label="<?php echo $oSocial['denominacion']; ?>"><?php echo $oSocial['denominacion']; ?></option>
            <?php endif ?>
<?php endforeach ?>
    </select>
    <label for="carnet_discapacidad" class="optional">Carnet Discapacidad:</label> 
    <?php echo $this->datosTerapia['carnet_discapacidad']; ?> 
    <select name="carnet_discapacidad" id="carnet_discapacidad">
            <option value="NO" label="NO" selected="<?php ($this->datosTerapia['carnet_discapacidad']=='NO') ? 'Selected' :''; ?>" >NO</option>
            <option value="SI" label="SI" selected="<?php ($this->datosTerapia['carnet_discapacidad']=='SI') ? 'Selected' :''; ?>" >SI</option>
    </select>

    <label for="escolarizado" class="optional">Escolarizado</label> 
    <input name="escolarizado" class="input" type="checkbox" checked="checked" />

    <label for="escolarizado_en" class="optional">Escolarizado en:</label>
    <input type="text" class="input" name="escolarizado_en" id="escolarizado_en" 
           value="<?php if (isset($this->datos['escolarizado_en']))
           echo $this->datos['escolarizado_en'];?>">
    <label for="recibe_terapia" class="optional">Recibe Terapia</label>
    <input name="recibe_terapia" class="input" type="checkbox" />
    <label for="terapia_que_recibe" class="optional">Terapia que recibe</label>
    <textarea name="terapia_que_recibe" id="diagnostico" rows="4" cols="60">
        <?php if (isset($this->datos['terapia_que_recibe']))
            echo $this->datos['terapia_que_recibe'];
        ?>
    </textarea>
    <label for="derivado" class="optional">Derivado</label>
    <input name="derivado" class="input" type="checkbox" />
    <label for="derivado_por" class="optional">Derivado por:</label>
    <input type="text" class="input" name="derivado_por" id="derivado_por" 
           value="<?php if (isset($this->datos['derivado_por']))
           echo $this->datos['derivado_por'];?>">
    <label for="turno_preferente" class="optional">Turno preferente:</label> 
    <select name="turno_preferente" id="turno_preferente">
        <?php foreach ($this->listaOSociales as $oSocial): ?>
            <?php if ($oSocial['id'] == $this->datosOSocial['idOSocial']): ?>
                <option value=<?php echo $oSocial['id']; ?> label="<?php echo $oSocial['denominacion']; ?>" selected="selected" ><?php echo $oSocial['denominacion']; ?></option>
            <?php else: ?>
                <option value=<?php echo $oSocial['id']; ?> label="<?php echo $oSocial['denominacion']; ?>"><?php echo $oSocial['denominacion']; ?></option>
            <?php endif ?>
        <?php endforeach ?>
    </select>
    <label for="Observaciones" class="optional">Observaciones</label>
    <textarea name="Observaciones" id="Observaciones" rows="4" cols="60">
        <?php if (isset($this->datos['Observaciones']))
            echo $this->datos['Observaciones'];
        ?>
    </textarea>
    
    <?php if (isset($this->datos['id'])): ?> 
        <input type="submit" id="guardar_paciente" name="guardar_paciente" class="boton" value="Guardar">
<?php endif ?>

</form>