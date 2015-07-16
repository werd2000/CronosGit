<?php
require_once BASE_PATH . 'Modulos' . DS . 'Usuarios' . DS . 'Modelos' . DS . 'LoginModelo.php';

class Usuarios_Controladores_loginControlador extends App_Controlador
{
    
    private $_login;
    
    public function __construct(){
        parent::__construct();
        $this->_login = new Usuarios_Modelos_loginModelo();
    }
    
    public function index()
    {

        $this->_vista->setVistaCss(array('login'));
        
        $this->_vista->titulo = 'Iniciar Sesion';
        
        if($this->getIntPost('enviar') == 1){
            $this->_vista->datos = $_POST;
            
            if(!$this->getAlphaNum('usuario')){
                $this->_vista->_msj_error = 'Debe introducir su nombre de usuario';
                $this->_vista->renderizar('index','login');
                exit;
            }
            
            if(!$this->getSql('password')){
                $this->_vista->_msj_error = ' Debe introducir su password';
                $this->_vista->renderizar('index','login');
                exit;
            }
            echo $this->getAlphaNum('usuario');
            echo $this->getSql('password');
            $row = $this->_login->getUsuario(
                    $this->getAlphaNum('usuario'),
                    $this->getSql('password')
                    );
                    var_dump($row);
            
            if(!$row){
                $this->_vista->_msj_error = 'Usuario y/o password incorrectos';
                $this->_vista->renderizar('index','login');
                exit;
            }
            
            if($row['bloqueado'] != 0){
                $this->_vista->_msj_error = 'Este usuario no esta habilitado';
                $this->_vista->renderizar('index','login');
                exit;
            }
        
            App_Session::set('autenticado', true);
            App_Session::set('level', $row['categoria']);
            App_Session::set('usuario', $row['nombre']);
            App_Session::set('id_usuario', $row['id']);
            App_Session::set('tiempo', time());
            
            $this->redireccionar();
        }
        
        $this->_vista->renderizar('index','login');
    }
    
    public function logout()
    {
        App_Session::destroy();
        $this->redireccionar();
    }
}
