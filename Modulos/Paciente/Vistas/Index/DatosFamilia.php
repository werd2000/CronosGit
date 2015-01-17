<form id="nuevo_paciente" method="post" action="" class="form-horizontal" role="form">
    <input type="hidden" id="guardarTerapiaPaciente" name="guardar" value="1">
    <input type="hidden" name="idPaciente" value="<?php if ($this->datos->getId()) echo $this->datos->getId(); ?>" id="idPaciente">
    <div class="form-group">
        <label for="Parentesco" class="col-sm-2 control-label">Parentesco:</label> 
        <div class="col-sm-10">
            <select name="parentesco" id="parentesco" class="form-control">
                <option value="Padre" label="Padre">Padre</option>
                <option value="Madre" label="Madre">Madre</option>
                <option value="Tutor" label="Tutor">Tutor</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="nombreFamilia" class="col-sm-2 control-label">Nombre:</label> 
        <div class="col-sm-10">
            <input type="text" class="form-control" name="nombreFamilia" id="nombreFamilia" value="">
        </div>
    </div>
    <div class="form-group">
        <label for="observacionesFamilia" class="col-sm-2 control-label">Observaciones:</label> 
        <div class="col-sm-10">
            <input type="text" class="form-control" name="observacionesFamilia" id="observacionesFamilia" value="">
        </div>
    </div>
    <?php if ($this->datos->getId()): ?> 
        <button name="agregarFamiliaPaciente" id="agregarFamiliaPaciente" 
                type="button" class="btn btn-lg btn-primary btn-block">
            Agregar
        </button>
    <?php endif ?>

    <?php if (isset($this->datosFamilia)): ?>  
        <div id="contenedorDetalleDatosFamilia" class="panel-body ui-corner-all">
            <table id="contenedorDetalleDatosTerapia"
                   class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="tituloDetalle">Parentesco</th>
                        <th class="tituloDetalle">Nombre</th>
                        <th class="tituloDetalle">Observaciones</th>
                        <th class="tituloDetalle">Acción</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($this->datosFamilia as $familia): ?>
                    <tr id="<?php if ($familia->getId()) echo $familia->getId(); ?>" class="detalleDato">
                        <td class="datoDetalle"><?php if ($familia->getParentesco()) echo $familia->getParentesco(); ?></td>
                        <td class="datoDetalle"><?php if ($familia->getNombre()) echo $familia->getNombre(); ?></td>
                        <td class="datoDetalle"><?php
                            if ($familia->getObservaciones()) {
                                echo $familia->getObservaciones();
                            } else {
                                echo '-';
                            }
                            ?></td>
                        <td class="editcontrol">
                            <a href="JavaScript:void(0);" 
                                   idFamilia=<?php if ($familia->getId()) echo $familia->getId(); ?> 
                                   idPaciente="<?php if ($this->datos->getId()) echo $this->datos->getId(); ?>" 
                                   contexto="familia_paciente" title="Eliminar">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </a>
                        </td>

                    </tr>
        <?php endforeach ?>
                </tbody>
            </table>
        </div>
<?php endif ?>
</form>