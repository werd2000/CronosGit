<form id="nueva_foto" enctype="multipart/form-data" method="post" action="?option=Personal&sub=foto&met=nuevo&id=<?php echo $this->datos->getId(); ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="33554432" id="MAX_FILE_SIZE">

    <label for="mostrarFoto">Foto Actual:</label>
    <?php if ($this->datos->getId() > 1):?>
    <input type="image" name="mostrarFoto" id="mostrarFoto" src="<?php echo $this->datos->getFoto(); ?>" 
           alt="Foto Actual:" onclick="return false">
    <?php else: ?>
    <input type="image" name="mostrarFoto" id="mostrarFoto" src="Public/Img/Fotos/Idsin_imagen.png" 
           alt="Foto Actual:" onclick="return false">
    <?php endif ?>
    <?php if ($this->datos->getId() > 1):?>
    <label for="foto" class="optional">Subir una imagen:</label>
    <input type="file" class="input" name="foto" id="foto" data-url="?option=Personal&sub=index&met=editar&id=<?php echo $this->datos->getId(); ?>"></dd>
    <input type="submit" class="boton" name="boton_enviar" value="Enviar">
    <?php endif ?>

</form>