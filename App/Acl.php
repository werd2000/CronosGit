<?php

class App_Acl
{

    /**
     * Objeto de la BD
     * @var type 
     */
    private $_db;
    /**
     * id del usuario
     */
    private $_id;
    /**
     * @var type  el id del role que estamos trabajando
     */
    private $_role;
    /**
     * @var type  los permisos que a procesado
     */
    private $_permisos;

    public function __construct($id = false)
    {
        if ($id) {
            $this->_id = (int) $id;
        } else {
            if (App_Session::get('id_usuario')) {   //Si el usuario inició sesion saca de la sesion
                $this->_id = App_Session::get('id_usuario');
            } else {        //sino sera 0 y no tendrá ningun permiso
                $this->_id = 278;
            }
        }

        $this->_db = new App_Database();
        $this->_role = $this->getRole();
        $this->_permisos = $this->getPermisosRole();
        $this->compilarAcl();
    }

    /**
     * Combina los array de los permisos 
     */
    public function compilarAcl()
    {
        $this->_permisos = array_merge(
                $this->_permisos, $this->getPermisosUsuario()
        );
    }

    /**
     * Obtiene el role del usuario ($this->_id) desde la tabla usuario en la BD
     * @return int $role['categoria']
     */
    public function getRole()
    {
        $this->_db->setTipoDatos('Array');
        $sql = "select categoria from conta_usuarios " .
                "where id = $this->_id";
        $this->_db->query($sql);
        $role = $this->_db->fetchRow();
        return $role['categoria'];
    }

    /**
     * Obtiene los permisos que están asignados a un role
     * @return array $id 
     */
    public function getPermisosRoleId()
    {
        $this->_db->setTipoDatos('Array');
        $sql = "select permiso from cronos_permisos_role where role = $this->_role";
        $this->_db->query($sql);
       
        $ids = $this->_db->fetchAll();

        $id = array();

        for ($i = 0; $i < count($ids); $i++) {
            $id[] = $ids[$i]['permiso'];
        }

        return $id;
    }

    /**
     * Obtiene los permisos del role ya procesados
     * @return array $data 
     */
    public function getPermisosRole()
    {
        $this->_db->setTipoDatos('Array');
        $sql = "select * from cronos_permisos_role where role = $this->_role";
        $this->_db->query($sql);
        $permisos = $this->_db->fetchAll();

        $data = array();

        for ($i = 0; $i < count($permisos); $i++) {
            $key = $this->getPermisoKey($permisos[$i]['permiso']);
            if ($key == '') {
                continue;
            }

            if ($permisos[$i]['valor'] == 1) {
                $v = true;
            } else {
                $v = false;
            }

            $data[$key] = array(
                'key' => $key,
                'permiso' => $this->getPermisoNombre($permisos[$i]['permiso']),
                'valor' => $v,
                'heredado' => true,
                'id' => $permisos[$i]['permiso']
            );
        }

        return $data;
    }

    /**
     * Obtiene el 'key' del permiso
     * @param int $permisoID
     * @return string  $key['key']
     */
    public function getPermisoKey($permisoID)
    {
        $permisoID = (int) $permisoID;
        $this->_db->setTipoDatos('Array');
        $sql = "select * from cronos_permisos where id_permiso = $permisoID";
        $this->_db->query($sql);
        $key = $this->_db->fetchRow();
        return $key['key'];
    }

    /**
     * Obtiene el nombre del permiso
     * @param int $permisoID
     * @return string $key['permiso'] 
     */
    public function getPermisoNombre($permisoID)
    {
        $permisoID = (int) $permisoID;
        $this->_db->setTipoDatos('Array');
        $sql = "select permiso from cronos_permisos where id_permiso = $permisoID";
        $this->_db->query($sql);
        $key = $this->_db->fetchRow();
        return $key['permiso'];
    }

    /**
     * Obtiene los permisos del usuario
     * @return array $data 
     */
    public function getPermisosUsuario()
    {
        $ids = $this->getPermisosRoleId();
        $permi = implode(",", $ids);
        if (count($ids)) {
            $sql = "select * from cronos_permisos_usuario " .
                   "where usuario = $this->_id " .
                   "and permiso in (" . $permi . ")";
            $this->_db->setTipoDatos('Array');
            $this->_db->query($sql);
            $permisos = $this->_db->fetchAll();
        } else {
            $permisos = array();
        }

        $data = array();

        for ($i = 0; $i < count($permisos); $i++) {
            $key = $this->getPermisoKey($permisos[$i]['permiso']);
            if ($key == '') {
                continue;
            }

            if ($permisos[$i]['valor'] == 1) {
                $v = true;
            } else {
                $v = false;
            }

            $data[$key] = array(
                'key' => $key,
                'permiso' => $this->getPermisoNombre($permisos[$i]['permiso']),
                'valor' => $v,
                'heredado' => false,
                'id' => $permisos[$i]['permiso']
            );
        }

        return $data;
    }

    /**
     * Obtiene los permisos
     * @return type 
     */
    public function getPermisos()
    {
        if (isset($this->_permisos) && count($this->_permisos))
            return $this->_permisos;
    }

    /**
     * Obtiene un valor verdadero o falso de acuerdo al $key
     * Es para usar en las vistas
     * @param string $key
     * @return boolean 
     */
    public function permiso($key)
    {
        if (array_key_exists($key, $this->_permisos)) {
            if ($this->_permisos[$key]['valor'] == true || $this->_permisos[$key]['valor'] == 1) {
                return true;
            }
        }

        return false;
    }

    /**
     * Controla el permiso del usuario de acuerdo al $key
     * Es para usar en los controladores. Si tiene acceso renueva el tiempo de sesion
     * Si no tiene acceso redirije a una pagina de error.
     * @param string $key
     */
    public function acceso($key)
    {
        if ($this->permiso($key)) {
//            Session::tiempo();
            return;
        }

        header("location:" . BASE_URL . "?option=error&sub=acceso&id=5050");
        exit;
    }

}

?>
