<form id="nuevo_personal" method="post" action="">
    <input type="hidden" id="guardarDatosLaborales" name="guardar" value="1" />
    <input type="hidden" name="idProfesional" value="<?php echo $this->datos->getId(); ?>" id="idProfesional">
    <input type="hidden" name="id" value="<?php echo $this->datosLaborales->getId(); ?>" id="id">
        <label for="valorOcupacionEmpresa" class="optional">Ocupación en la Empresa:</label> 
        <select name="valorOcupacionEmpresa" id="valorOcupacionEmpresa">
        <?php foreach ($this->listaTerapias as $terapia): ?>
            <?php if ($terapia['id'] == $this->datosLaborales->getPuesto()): ?>
                <option value=<?php echo $terapia['id']; ?> label="<?php echo $terapia['terapia']; ?>" selected="selected" ><?php echo $terapia['terapia']; ?></option>
            <?php else: ?>
                <option value=<?php echo $terapia['id']; ?> label="<?php echo $terapia['terapia']; ?>"><?php echo $terapia['terapia']; ?></option>
            <?php endif ?>
        <?php endforeach ?>
        </select>
        <label for="fechaIngreso" class="optional">Fecha Ingreso:</label> 
        <input type="text" name="fechaIngreso" id="fechaIngreso" value="<?php echo $this->datosLaborales->getFechaIngreso(); ?>" class="input" placeholder="Haga click para ingresar la fecha de ingreso" class="hasDatepicker">
        <label for="observacionesDatosLaborales" class="optional">Observaciones:</label> 
        <textarea name="observacionesDatosLaborales" id="observacionesDatosLaborales" rows="4" cols="80">
        <?php echo $this->datosLaborales->getObservaciones(); ?>
        </textarea> 
    <?php if ($this->datos->getId() > 1):?>        
        <button name="agregarDatosLaborales" id="agregarDatosLaborales" type="button" class="boton">Agregar</button>            
    <?php endif ?>
</form>

