<form id="facturacion" method="post" action="index.php?option=pdfphsrl&sub=facturacion&id=<?php if (isset($this->datos['id'])) echo $this->datos['id']; ?>">
    <input type="hidden" id="facturacion" name="facturacion" value="1">
    <input type="hidden" name="idObraSocial" value="<?php if (isset($this->datos['id'])) echo $this->datos['id']; ?>" id="idObraSocial">
    <label>Pacientes:</label> 
        <div class="lista_checkbox">
    <?php $i= 1; foreach ($this->pacientes as $paciente): ?>
        <div>
            <input type="checkbox" class="checkbox" name="paciente[]" 
                   checked value=" <?php echo $paciente['apellidos'] .
                           ', ' . $paciente['nombres'] . ' - DNI:' . $paciente['nro_doc'];
                   ?>"><span><?php echo $i.' '.$paciente['apellidos'] . ', ' . trim($paciente['nombres']); $i++;?>
        </span></div>
    <?php endforeach ?>
        </div>
    <label for="leyenda" class="mceEditor defaultSimpleSkin">Leyenda:</label> 
    <textarea name="leyenda" id="leyenda" rows="4" placeholder="" cols="80">
        <?php if (isset($this->textoFacturacion)) echo $this->textoFacturacion; ?>
    </textarea>
    <?php if (isset($this->datos['id'])): ?> 
        <div>
            <input type="submit" class="boton" name="imprimir" value="Imprimir">
        </div>
    <?php endif ?>
</form>