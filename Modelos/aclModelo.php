<?php

class aclModel extends Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function getRole($roleID)
    {
        $roleID = (int) $roleID;
        
        $sql = "SELECT * FROM cronos_roles WHERE id_role = $roleID";
        $this->_db->setTipoDatos('Array');
        $this->_db->query($sql);
        return $this->_db->fetchRow();
    }
    
    public function getRoles()
    {
        $roles = $this->_db->query("SELECT * FROM cronos_roles");
        
        return $roles->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getPermisosRole($roleID)
    {
        $data = array();
        
        $permisos = $this->_db->query(
                "SELECT * FROM cronos_permisos_role WHERE cronos_roles = {$roleID}"
                );
                
        $permisos = $permisos->fetchAll(PDO::FETCH_ASSOC);
        
        for($i = 0; $i < count($permisos); $i++){
            $key = $this->getPermisoKey($permisos[$i]['permiso']);
            
            if($key == ''){continue;}
            if($permisos[$i]['valor'] == 1){
                $v = true;
            }
            else{
                $v = false;
            }
            
            $data[$key] = array(
                'key' => $key,
                'valor' => $v,
                'nombre' => $this->getPermisoNombre($permisos[$i]['permiso']),
                'id' => $permisos[$i]['permiso']
            );
        }
        
        $todos = $this->getPermisosAll();
        $data = array_merge($todos, $data);
        
        return $data;
    }
    
    public function getPermisoKey($permisoID)
    {
        $permisoID = (int) $permisoID;
        
        $key = $this->_db->query(
                "SELECT `key` FROM cronos_permisos WHERE id_permiso = $permisoID"
                );
        
        $key = $key->fetch();
        return $key['key'];
    }
    
    public function getPermisoNombre($permisoID)
    {
        $permisoID = (int) $permisoID;
        
        $key = $this->_db->query(
                "SELECT permiso FROM cronos_permisos WHERE id_permiso = $permisoID"
                );
        
        $key = $key->fetch();
        return $key['permiso'];
    }
    
    public function getPermisosAll()
    {
        $permisos = $this->_db->query(
                "SELECT * FROM cronos_permisos"
                );
                
        $permisos = $permisos->fetchAll(PDO::FETCH_ASSOC);
        
        for($i = 0; $i < count($permisos); $i++){
            $data[$permisos[$i]['key']] = array(
                'key' => $permisos[$i]['key'],
                'valor' => 'x',
                'nombre' => $permisos[$i]['permiso'],
                'id' => $permisos[$i]['id_permiso']
            );
        }
        
        return $data;
    }
    
    public function insertarRole($role)
    {
        $this->_db->query("INSERT INTO cronos_roles VALUES(null, '{$role}')");
    }
    
    public function getPermisos()
    {
        $permisos = $this->_db->query("SELECT * FROM cronos_permisos");
        
        return $permisos->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function eliminarPermisoRole($roleID, $permisoID)
    {
        $this->_db->query(
                "DELETE FROM cronos_permisos_role " . 
                "WHERE permiso = {$permisoID} " .
                "AND role = {$roleID}"
                );
    }

    public function editarPermisoRole($roleID, $permisoID, $valor)
    {
        $this->_db->query(
                "replace into cronos_permisos_role set role = {$roleID}, permiso = {$permisoID}, valor = '{$valor}'"
                );
    }

    public function insertarPermiso($permiso, $llave)
    {
        $this->_db->query(
                "INSERT INTO cronos_permisos VALUES" .
                "(null, '{$permiso}', '{$llave}')"
                );
    }
}

?>
