<?php
require_once APP_PATH . 'Modelo.php';

class Usuarios_Modelos_indexModelo extends App_Modelo
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getUsuarios()
    {
        $sql = 'SELECT usuarios.*, roles.role FROM conta_usuarios as usuarios, cronos_roles as roles 
            WHERE usuarios.categoria = roles.id_role';
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchall();
        
//        $usuarios = $this->_db->query(
//                "select u.*,r.role from usuarios u, roles r ".
//                "where u.role = r.id_role"
//                );
//        return $usuarios->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getUsuario($usuarioID)
    {
        $id = (int) $usuarioID;
        $sql = "SELECT usuarios.*, roles.role FROM conta_usuarios as usuarios, cronos_roles as roles WHERE
            usuarios.categoria = roles.id_role AND usuarios.id = $usuarioID";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
    public function getPermisosUsuario($usuarioID)
    {
        $acl = new ACL($usuarioID);
        return $acl->getPermisos();
    }
    
    public function getPermisosRole($usuarioID)
    {
        $acl = new ACL($usuarioID);
        return $acl->getPermisosRole();
    }
    
    public function eliminarPermiso($usuarioID, $permisoID)
    {
        $this->_db->query(
                "delete from cronos_permisos_usuario where ".
                "usuario = $usuarioID and permiso = " . $permisoID
                );
    }
    
    public function editarPermiso($usuarioID, $permisoID, $valor)
    {
        $this->_db->query(
                "replace into cronos_permisos_usuario set ".
                "usuario = $usuarioID , permiso = $permisoID, valor ='$valor'"
                );
    }
}

