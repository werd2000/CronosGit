<?php

class registroControlador extends Controlador
{

    private $_registro;

    public function __construct()
    {
        parent::__construct();

        $this->_registro = $this->cargarModelo('registro');
    }

    public function index()
    {
        if (Session::get('autenticado')) {
            $this->redireccionar();
        }

        $this->_vista->titulo = 'Registro';

        if ($this->getInt('enviar') == 1) {
            $this->_vista->datos = $_POST;

            if (!$this->getSql('nombre')) {
                $this->_vista->_msj_error = 'Debe introducir su nombre';
                $this->_vista->renderizar('index', 'registro');
                exit;
            }

            if (!$this->getAlphaNum('usuario')) {
                $this->_vista->_msj_error = 'Debe introducir su nombre usuario';
                $this->_vista->renderizar('index', 'registro');
                exit;
            }

            if ($this->_registro->verificarUsuario($this->getAlphaNum('usuario'))) {
                $this->_vista->_msj_error = 'El usuario ' . $this->getAlphaNum('usuario') . ' ya existe';
                $this->_vista->renderizar('index', 'registro');
                exit;
            }

            if (!$this->validarEmail($this->getPostParam('email'))) {
                $this->_vista->_msj_error = 'La direccion de email es inv&aacute;lida';
                $this->_vista->renderizar('index', 'registro');
                exit;
            }

            if ($this->_registro->verificarEmail($this->getPostParam('email'))) {
                $this->_vista->_msj_error = 'Esta direccion de correo ya esta registrada';
                $this->_vista->renderizar('index', 'registro');
                exit;
            }

            if (!$this->getSql('password')) {
                $this->_vista->_msj_error = 'Debe introducir su password';
                $this->_vista->renderizar('index', 'registro');
                exit;
            }

            if ($this->getPostParam('password') != $this->getPostParam('confirmar')) {
                $this->_vista->_msj_error = 'Los passwords no coinciden';
                $this->_vista->renderizar('index', 'registro');
                exit;
            }

            $this->getLibreria('class.phpmailer');
            $email = new PHPMailer();

            $this->_registro->registrarUsuario(array(
                'nombre' => $this->getSql('nombre'),
                'username' => $this->getAlphaNum('usuario'),
                'password' => Hash::getHash('sha1', $this->getSql('password'), HASH_KEY),
                'email' => $this->getPostParam('email'),
                'categoria' => 'usuario',
                'bloqueado' => 0,
                'fechaRegistro' => date("Y-m-d H:i:s")
            ));

            $usuario = $this->_registro->verificarUsuario($this->getAlphaNum('usuario'));

            if (!$usuario) {
                $this->_vista->_msj_error = 'Error al registrar el usuario';
                $this->_vista->renderizar('index', 'registro');
                exit;
            }

            $email->From = 'www.pequehogar.com.ar';
            $email->FromName = 'Registro Cronos';
            $email->Subject = 'Activacion de cuenta de usuario';
            $email->Body = 'Hola <strong>' . $this->getSql('nombre') . '</strong>,' .
                    '<p>Se ha registrado en CRONOS, para activar ' .
                    'su cuenta haga clic sobre el siguiente enlace:<br>' .
                    '<a href="' . BASE_URL . '?option=Registro&sub=activar&' .
                    'id=' . $usuario['id'] . '&codigo=' . $usuario['codigo'] . '">' .
                    BASE_URL . '?option=registro&sub=activar&' .
                    'id=' . $usuario['id'] . '&codigo=' . $usuario['codigo'] . '</a>';

            $email->AltBody = 'Su servidor de correo no soporta html';
            $email->AddAddress($this->getPostParam('email'));
            $email->Send();

            $this->_vista->datos = false;
            $this->_vista->_mensaje = 'Registro Completado, revise su email para activar su cuenta';
        }

        $this->_vista->renderizar('index', 'registro');
    }

    /**
     * Activa la cuenta del usuario
     * @param int $id
     * @param int $codigo 
     */
    public function activar($id, $codigo)
    {
        if (!$this->filtrarInt($id) || !$this->filtrarInt($codigo)) {
            $this->_vista->_msj_error = 'Esta cuenta no existe';
            $this->_vista->renderizar('activar', 'registro');
            exit;
        }

        $row = $this->_registro->getUsuario(
                $this->filtrarInt($id), $this->filtrarInt($codigo)
        );

        if (!$row) {
            $this->_vista->_msj_error = 'Esta cuenta no existe';
            $this->_vista->renderizar('activar', 'registro');
            exit;
        }

        if ($row['bloqueado'] == 0) {
            $this->_vista->_msj_error = 'Esta cuenta ya ha sido activada';
            $this->_vista->renderizar('activar', 'registro');
            exit;
        }

        $this->_registro->activarUsuario(
                $this->filtrarInt($id), $this->filtrarInt($codigo)
        );

        $row = $this->_registro->getUsuario(
                $this->filtrarInt($id), $this->filtrarInt($codigo)
        );

        if ($row['bloqueado'] == 1) {
            $this->_vista->_msj_error = 'Error al activar la cuenta, por favor intente mas tarde';
            $this->_vista->renderizar('activar', 'registro');
            exit;
        }

        $this->_vista->_mensaje = 'Su cuenta ha sido activada';
        $this->_vista->renderizar('activar', 'registro');
    }

}