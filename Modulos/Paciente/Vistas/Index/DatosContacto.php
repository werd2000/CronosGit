<form id="nuevo_paciente" method="post" action="" class="form-horizontal" role="form">
    <input type="hidden" id="guardarDatosContacto" name="guardar" value="1" />
    <input type="hidden" name="idPaciente" 
           value="<?php if ($this->datos->getId()) echo $this->datos->getId(); ?>" 
           id="idPaciente">
    <div class="form-group">
        <label for="tipoContacto" class="optional col-sm-2 control-label">
            Tipo de Contacto:
        </label> 
        <div class="col-sm-10">
            <select name="tipoContacto" id="tipoContacto" class="form-control">
                <option value="Cel" label="Cel">Cel.</option>
                <option value="Tel" label="Tel">Tel.</option>
                <option value="Email" label="Email">Email</option>
                <option value="SitioWeb" label="SitioWeb">Sitio Web</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="valorContacto" class="optional col-sm-2 control-label">
            Contacto:
        </label> 
        <div class="col-sm-10">
            <input type="valorContacto" class="form-control" 
                   name="valorContacto" id="valorContacto" 
                   value="" placeholder="Ingrese contacto" maxlength="50">
        </div>
    </div>
    <div class="form-group">
        <label for="observacionesContactoPaciente" class="optional col-sm-2 control-label">
            Observaciones:
        </label> 
        <div class="col-sm-10">
            <input type="text" class="form-control" name="observacionesContactoProfesional" id="observacionesContactoPaciente" value="">
        </div>
    </div>
    <?php if ($this->datos->getId()): ?>        
    <button name="agregarContactoPaciente" id="agregarContactoPaciente" 
            type="button" class="btn btn-lg btn-primary btn-block">
        Agregar
    </button>
    <?php endif ?>
    </form>
    
<?php if (isset($this->datosContacto)){ ?> 
    <div id="detalleDatosTerapia" class="panel-body ui-corner-all">
        <table id="contenedorDetalleDatosTerapia"
               class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Contacto</th>
                    <th>Observaciones</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->datosContacto as $contacto): ?>
                <tr id="<?php if ($contacto->getId()) echo $contacto->getId(); ?>"
                    class="detalleDato">
                    <td class="datoDetalle5c">
                        <?php if ($contacto->getTipo()) echo $contacto->getTipo(); ?>
                    </td>
                    <td class="datoDetalle5c">
                        <?php if ($contacto->getValor()) echo $contacto->getValor(); ?>
                    </td>
                    <td class="datoDetalle5c">
                        <?php if ($contacto->getObservaciones()) echo $contacto->getObservaciones(); ?>
                    </td>
                    <td class="editcontrol">
                        <a href="JavaScript:void(0);" 
                           idContacto=<?php if ($contacto->getId()) echo $contacto->getId(); ?>
                           idPaciente="<?php if ($this->datos->getId()) echo $this->datos->getId(); ?>" 
                           contexto="contacto_paciente" title="Eliminar">
                            <span class="glyphicon glyphicon-remove"></span>
                        </a>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
<?php } ?>