<form id="nuevo_personal" method="post" action="" class="form-horizontal" role="form">
    <input type="hidden" id="guardarDatosContacto" name="guardar" value="1" />
    <input type="hidden" name="idPersonal" value="<?php echo $this->datos->getId(); ?>" id="idPersonal">
    <div class="form-group">
        <label for="tipoContacto" class="optional col-sm-2 control-label">
            Tipo de Contacto:</label> 
        <div class="col-sm-10">
            <select name="tipoContacto" id="tipoContacto" class="form-control">
                <option value="Cel" label="Cel">Cel.</option>
                <option value="Tel" label="Tel">Tel.</option>
                <option value="Email" label="Email">Email</option>
                <option value="SitioWeb" label="SitioWeb">Sitio Web</option>
                <option value="Facebook" label="Facebook">Facebook</option>
                <option value="Twitter" label="Twitter">Twitter</option>
                <option value="Linkedin" label="Linkedin">Linkedin</option>
            </select>
        </div>
    </div>
    <div class="form-group">
        <label for="valorContacto" class="optional col-sm-2 control-label">
            Contacto:
        </label> 
        <div class="col-sm-10">
            <input type="text" class="form-control" 
                   name="valorContacto" id="valorContacto" 
                   value="" placeholder="Ingrese contacto" maxlength="50">
        </div>
    </div>
    <div class="form-group">
        <label for="observacionesContactoProfesional" 
               class="optional col-sm-2 control-label">
            Observaciones:
        </label> 
        <div class="col-sm-10">
            <input type="text" class="form-control" 
                   name="observacionesContactoProfesional" 
                   id="observacionesContactoProfesional" value="">
        </div>
    </div>
    <?php if ($this->datos->getId() > 0) { ?>        
        <button name="agregarContactoProfesional" id="agregarContactoProfesional" type="button" class="btn btn-lg btn-primary btn-block">Agregar</button>
    <?php } ?>
</form>
<div id="detalleDatosContactoPersonal" class="panel-body ui-corner-all">
<?php if (isset($this->datosContacto)) { ?> 
    <table id="contenedorDetalleContactoPersonal"
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
            <?php
            if (is_array($this->datosContacto)) {
                foreach ($this->datosContacto as $contacto) {
                    if ($contacto->getId()) {
                        echo '<tr id="' . $contacto->getId() . '" class="detalleDato">';
                    } else {
                        echo '<tr id="0" class="detalleDato">';
                    }
                    ?>
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
                    <?php
                    if ($contacto->getId()) {
                        echo '<a href="JavaScript:void(0);" ' .
                        'idContacto=' . $contacto->getId() . ' idPersonal="' .
                        $this->datos->getId() . '" contexto="contacto_personal" title="Eliminar">';
                        echo '<span class="glyphicon glyphicon-remove"></span>';
                        echo '</a>';
                    }
                    ?>
                </td>
                <?php
                echo '</tr>';
            }
            }
            ?>
        </tbody>
    </table>
<?php } ?>
</div>