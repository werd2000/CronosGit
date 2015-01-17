<?php

class indexControlador extends UsuariosControlador
{

    private $_usuarios;

    public function __construct()
    {
        parent::__construct();
        $this->_usuarios = $this->cargarModelo('index');
    }

    public function index()
    {
        $this->_acl->acceso('admin_access');
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=Usuarios",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "javascript:history.back(1)",
                'title' => 'Volver',
                'class'  => 'icono-volver32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Index",
                'title' => 'Inicio',
                'class'  => 'icono-inicio32'
            )
        );
        $this->_vista->_barraHerramientas = $menu;
        
        $this->_vista->setJs(array('prueba'));
        $this->_vista->titulo = 'Usuarios';
        $this->_vista->usuarios = $this->_usuarios->getUsuarios();
        $this->_vista->renderizar('index');
    }

    public function permisos($usuarioID)
    {
        $menu = array(
            array(
                'onclick'=> '',
                'href'   => "?option=exportExcel&sub=Usuarios",
                'title' => 'Exportar',
                'class'  => 'icono-exportar32'
            ),
            array(
                'onclick'=> '',
                'href'   => "javascript:history.back(1)",
                'title' => 'Volver',
                'class'  => 'icono-volver32'
            ),
            array(
                'onclick'=> '',
                'href'   => "?option=Index",
                'title' => 'Inicio',
                'class'  => 'icono-inicio32'
            )
        );
        $this->_vista->_barraHerramientas = $menu;
        
        $id = $this->filtrarInt($usuarioID);

        if (!$id) {
            $this->redireccionar('usuarios');
        }

        if ($this->getInt('guardar') == 1) {
            $values = array_keys($_POST);
            $replace = array();
            $eliminar = array();

            for ($i = 0; $i < count($values); $i++) {
                if (substr($values[$i], 0, 5) == 'perm_') {
                    $permiso = (strlen($values[$i]) - 5);
                    print_r($values);

                    if ($_POST[$values[$i]] == 'x') {
                        $eliminar[] = array(
                            'usuario' => $id,
                            'permiso' => substr($values[$i], -$permiso)
                        );
                    } else {
                        if ($_POST[$values[$i]] == 1) {
                            $v = 1;
                        } else {
                            $v = 0;
                        }

                        $replace[] = array(
                            'usuario' => $id,
                            'permiso' => substr($values[$i], -$permiso),
                            'valor' => $v
                        );
                    }
                }
            }

            for ($i = 0; $i < count($eliminar); $i++) {
                $this->_usuarios->eliminarPermiso(
                        $eliminar[$i]['usuario'], $eliminar[$i]['permiso']);
            }

            for ($i = 0; $i < count($replace); $i++) {
                $this->_usuarios->editarPermiso(
                        $replace[$i]['usuario'], $replace[$i]['permiso'], $replace[$i]['valor']);
            }
        }

        $permisosUsuario = $this->_usuarios->getPermisosUsuario($id);
        $permisosRole = $this->_usuarios->getPermisosRole($id);

        if (!$permisosUsuario || !$permisosRole) {
            $this->redireccionar('usuarios');
        }

        $this->_vista->titulo = 'Permisos de usuario';
        $this->_vista->permisos = array_keys($permisosUsuario);
        $this->_vista->usuario = $permisosUsuario;
        $this->_vista->role = $permisosRole;
//        echo '<pre>';print_r($this->_vista->permisos);
//        echo '<pre>';print_r($permisosRole);
        $this->_vista->info = $this->_usuarios->getUsuario($id);

        $this->_vista->renderizar('permisos');
    }

}

