<?php
/**
 * Description of Usuario
 *
 * @author WERD
 */
class Usuario
{
    protected $_id;
    protected $_username;
    protected $_nombre;
    
    public function getId()
    {
        return $this->_id;
    }
    
    public function getUsername()
    {
        return $this->_username;
    }

    public function getNombre()
    {
        return $this->_nombre;
    }

    public function __construct(array $usuario)
    {
        $this->_id = $usuario['id'];
        $this->_nombre = $usuario['nombre'];
        $this->_username = $usuario['username'];
    }
    
    public static function getUsuarios(array $usuarios)
    {
        $listaUsuarios = array();
        foreach ($usuarios as $usuario) {
            $listaUsuarios[] = new Usuario($usuario);
        }
        return $listaUsuarios;
    }
}
