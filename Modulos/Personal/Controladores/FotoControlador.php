<?php

/**
 * Clase Personal Controlador 
 */
class fotoControlador extends personalControlador
{

    private $_personal;

    public function __construct()
    {
        parent::__construct();
//        $this->_personal = $this->cargarModelo('contacto');
    }

    public function nuevo($id)
    {
        
        $imagen = '';

        if(isset($_FILES['foto']['name'])){
            echo 'hay foto';
            $this->getLibreria('upload' . DS . 'class.upload');
            $ruta = BASE_PATH . 'Public' . DS . 'Img' . DS . 'Fotos' . DS;
            $upload = new upload($_FILES['foto'], 'es_ES');
            $upload->allowed = array('image/*');
            $upload->file_new_name_body = 'Id' . $id;
            $upload->file_new_name_ext = 'png';
            $upload->file_overwrite = true;
            $upload->process($ruta);

            if ($upload->processed) {
                $this->redireccionar('option=Personal&sub=index&cont=editar&id='.$id);
//                $imagen = $upload->file_dst_name;
//                $thumb = new upload($upload->file_dst_pathname);
//                $thumb->image_resize = true;
//                $thumb->image_x = 100;
//                $thumb->image_y = 70;
//                $thumb->file_name_body_pre = 'thumb_';
//                $thumb->process($ruta . 'thumb' . DS);
            } else {
                $this->_msj_error = $upload->error;
                $this->redireccionar('option=Personal&sub=index&cont=editar&id='.$id);
            }
        }else{
            echo 'no hay imagen';
        }
                $this->redireccionar('option=Personal&sub=index&cont=editar&id='.$id);
//            $this->_vista->renderizar('editar', 'personal');
    }

    

}