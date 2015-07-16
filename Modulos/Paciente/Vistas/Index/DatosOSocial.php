<form id="nuevo_paciente" method="post" action="" class="form-horizontal" role="form">
    <input type="hidden" id="guardarOSPaciente" name="guardar" value="1">
    <input type="hidden" name="idPaciente" value="<?php if ($this->datos->getId()) echo $this->datos->getId(); ?>" id="idPaciente">
    <input type="hidden" name="id" value="<?php if ($this->datosOSocial['id']) echo $this->datosOSocial['id']; ?>" id="id">
    <div class="form-group">
        <label for="idObraSocial" class="col-sm-2 control-label">Obra Social:</label> 
        <div class="col-sm-10">
            <select name="idObraSocial" id="idObraSocial" class="form-control">
                <?php foreach ($this->listaOSociales as $oSocial){ ?>
                    <?php if ($oSocial['id'] == $this->datosOSocial['idOSocial']){ ?>
                        <option 
                            value="<?php echo $oSocial['id']; ?>" 
                            label="<?php echo $oSocial['denominacion']; ?>" 
                            selected="selected" ><?php echo $oSocial['denominacion']; ?>
                        </option>
                    <?php } else { ?>
                        <option
                            value="<?php echo $oSocial['id']; ?>"
                            label="<?php echo $oSocial['denominacion']; ?>">
                                <?php echo $oSocial['denominacion']; ?>
                        </option>
                <?php } ?>
                <?php } ?>
            </select>
                <div>
        <a id="verObraSocial" class="btn btn-sm btn-default" href="index.php?mod=ObrasSociales&cont=index&met=editar&id=<?php echo $this->datosOSocial['id']; ?>">
            Ver Detalle de la Obra Social
        </a>
    </div>

        </div>
    </div>

    <div class="form-group">
        <label for="nroAfiliado" class="col-sm-2 control-label">Nro. Afiliado:</label> 
        <div class="col-sm-10">
            <?php if ($this->datosOSocial['nro_afiliado']) { ?>
                <input type="text" name="nroAfiliado" id="nroAfiliado" 
                   value="<?php echo $this->datosOSocial['nro_afiliado']; ?>"
                   class="form-control">
            <?php } else { ?>
                <input type="text" name="nroAfiliado" id="nroAfiliado" 
                   value=""
                   class="form-control">
            <?php } ?>
        </div>
    </div>
    <div class="form-group">
        <label for="observacionesOs" class="col-sm-2 control-label">Observaciones:</label> 
        <div class="col-sm-10">
            <textarea name="observacionesOs" id="observacionesOs" rows="4" 
                      placeholder="MODULO:                CODIGO:" cols="80"
                      class="form-control">
                          <?php if ($this->datosOSocial['pacos_observaciones']) echo $this->datosOSocial['pacos_observaciones']; ?>
            </textarea>
        </div>
    </div>
    <?php if ($this->datos->getId()) { ?> 
        <button name="guardarObraSocialPaciente" id="guardarObraSocialPaciente" 
                type="button" class="btn btn-lg btn-primary btn-block">
            Guardar
        </button>
    <?php } ?>
</form>