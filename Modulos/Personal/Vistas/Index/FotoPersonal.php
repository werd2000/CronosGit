<form id="nueva_foto" 
      class="form-horizontal" role="form"
      enctype="multipart/form-data" 
      method="post" 
      action="?option=Personal&sub=foto&met=nuevo&id=<?php if ($this->datos->getId()) echo $this->datos->getId(); ?>">
    <input type="hidden" name="MAX_FILE_SIZE" value="33554432" id="MAX_FILE_SIZE">
    <div class="form-group">
        <label class="col-sm-2 control-label" for="mostrarFoto">Foto Actual:</label>
        <?php if ($this->datos->getId() AND is_readable(BASE_PATH . 'Public' . DS . 'Img' . DS . 'Fotos' . DS . 'Personal' . DS . 'Id'.$this->datos->getId().'.png')) { ?>
            <div class="col-xs-6 col-md-3">
                <a href="#" class="thumbnail">
                    <img src="Public/Img/Fotos/Personal/Id<?php if ($this->datos->getId()) echo $this->datos->getId(); ?>.png" alt="...">
                </a>
            </div>
        <?php }else { ?>
            <div class="col-xs-6 col-md-3">
                <input type="image" name="mostrarFoto" id="mostrarFoto" src="Public/Img/Fotos/Personal/Idsin_imagen.png" 
                       alt="Foto Actual:" onclick="return false" class="thumbnail">
            </div>
        <?php } ?>
        <?php if ($this->datos->getId()): ?>
        </div>
        <div class="form-group">
            <label for="foto" class="col-sm-2 control-label">
                Subir una imagen:
            </label>
            <div class="col-sm-10">
                <input type="file" class="input" name="foto" id="foto" class="form-control"
                       data-url="?option=Personal&sub=index&met=editar&id=<?php if ($this->datos->getId()) echo $this->datos->getId(); ?>">
            </div>
        </div>
        <input type="submit" name="boton_enviar" value="Enviar" class="btn btn-lg btn-primary btn-block">
    <?php endif ?>

</form>