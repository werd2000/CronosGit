<form id="nuevo_paciente" method="post" action="" class="form-horizontal" role="form">
    <input type="hidden" id="guardarEducPaciente" name="guardar" value="1">
    <input type="hidden" name="idPaciente" 
           value="<?php if ($this->datos->getId()) echo $this->datos->getId(); ?>" 
           id="idPaciente">
    <input type="hidden" name="idEduc" 
           value="<?php if (!is_null($this->datosEducacion->getId())) echo $this->datosEducacion->getId(); ?>" 
           id="idEduc">
    <div class="form-group">
        <label for="idEscuela" class="col-sm-2 control-label">Escuela:</label> 
        <div class="col-sm-10">
            <select name="idEscuela" id="idEscuela" class="form-control">
                <?php foreach ($this->listaEscuelas as $escuela) { ?>
                        <?php if ($escuela['id'] == $this->datosEducacion->getIdEscuela()) { ?>
                            <option 
                                value="<?php echo $escuela['id']; ?>"
                                label="<?php echo $escuela['denominacion']; ?>" 
                                selected="selected" >
                                    <?php echo $escuela['denominacion']; ?>
                            </option>
                        <?php } else { ?>
                            <option value="<?php echo $escuela['id']; ?>" 
                                    label="<?php echo $escuela['denominacion']; ?>">
                                        <?php echo $escuela['denominacion']; ?>
                            </option>
                        <?php } ?>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="curso" class="col-sm-2 control-label">Curso:</label> 
        <div class="col-sm-10">
            <input type="text" class="form-control" name="curso" id="curso" 
                   value="<?php if (!is_null($this->datosEducacion->getCurso()))
                       echo $this->datosEducacion->getCurso(); ?>">
        </div>
    </div>
    <div class="form-group">
        <label for="observacionesEducacion" class="col-sm-2 control-label">Observaciones:</label> 
        <div class="col-sm-10">
            <textarea name="observacionesEducacion" id="observacionesEducacion" rows="4" 
                      class="form-control">
                          <?php if ($this->datosEducacion->getObservaciones()) echo $this->datosEducacion->getObservaciones(); ?>
            </textarea>
        </div>
    </div>
    <?php if ($this->datos->getId()) { ?> 
        <button name="guardarEducacionPaciente" id="guardarEducacionPaciente" 
                type="button" class="btn btn-lg btn-primary btn-block">
            Guardar
        </button>
    <?php } ?>
</form>