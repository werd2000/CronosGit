<?php

class PlanTerapeuticoControlador extends pacienteControlador
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        parent::__construct();
        $this->_terapia = $this->cargarModelo('plantratamiento');
    }

    public function subir($id)
    {
        if (isset($_FILES)) {
            $this->getLibreria('upload' . DS . 'class.upload');
            $ruta = BASE_PATH . 'Public' . DS . 'Descargas' . DS . 'PT' . DS;
            echo $ruta . 'Id' . $id;
            $upload = new upload($_FILES['archivo'], 'es_ES');
            $upload->allowed = array('application/pdf');
            $upload->file_new_name_body = 'PT' . "_" . Date("Y") . "_" . $id;
            $upload->file_new_name_ext = 'pdf';
            $upload->file_overwrite = true;
            $upload->process($ruta);

            if ($upload->processed) {
                
                $this->redireccionar('option=Paciente&sub=index&cont=editar&id=' . $id);
            } else {
                $this->_msj_error = $upload->error;
                $this->redireccionar('option=Paciente&sub=index&cont=editar&id='.$id);
            }
        } else {
            echo 'no hay archivo';
        }
        $this->redireccionar('option=Paciente&sub=index&cont=editar&id='.$id);
    }

    

}

?>
