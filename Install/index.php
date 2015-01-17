<?php
error_reporting(E_ALL | E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set("display_errors", 1);

define('DS', DIRECTORY_SEPARATOR);
defined('BASE_PATH') or define('BASE_PATH', realpath(dirname(dirname(__FILE__))) . DS);
define('APP_PATH', BASE_PATH . 'App' . DS);

require_once APP_PATH . 'Config.php';

$sql = "CREATE DATABASE IF NOT EXISTS gg000334_contasoft DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE gg000334_contasoft;";
if ((!$link = @mysqli_connect(DB_HOST, DB_USER, DB_PASS))) {
    throw new MySQLAdapterException('Error al conectar a MySQL : ' . mysqli_connect_error());
}
$insertar = mysqli_query($link, $sql);
var_dump($insertar);

require_once APP_PATH . 'DataBase.php';

$db = new App_DataBase();

echo '<h1>INSTALACION</h1>';
echo '<h2>Creando Tabla Clientes</h2>';

echo '<h2>Creando Tabla Clientes</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS conta_clientes (
  id int(4) NOT NULL AUTO_INCREMENT,
  razon_social varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  domicilio varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  condicion_iva varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  cuit varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  tel varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  cel varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  email varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT "0",
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Clientes</h3>';
}

echo '<h2>Creando Tabla Compras</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS conta_compras (
  id int(5) NOT NULL AUTO_INCREMENT,
  cuenta int(11) DEFAULT NULL,
  proveedor int(4) NOT NULL,
  fecha_comprobante date NOT NULL DEFAULT "0000-00-00",
  comprobante text COLLATE utf8_spanish_ci NOT NULL,
  tipo_comprobante text COLLATE utf8_spanish_ci NOT NULL,
  nro_comprobante int(12) unsigned zerofill NOT NULL,
  total decimal(10,2) NOT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT "0",
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Compras</h3>';
}

echo '<h2>Creando Tabla Cuentas</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS conta_cuentas (
  id int(11) NOT NULL AUTO_INCREMENT,
  cuenta varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  descripcion text COLLATE utf8_spanish_ci NOT NULL,
  grupo_cuenta int(11) NOT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT "0",
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Cuentas</h3>';
}

echo '<h2>Creando Tabla Proveedores</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS conta_proveedores2 (
  id int(4) NOT NULL AUTO_INCREMENT,
  razon_social varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  condicion_iva varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  cuit varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  tel varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  cel varchar(20) COLLATE utf8_spanish_ci DEFAULT NULL,
  email varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT "0",
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Proveedores</h3>';
}

echo '<h2>Creando Tabla Honorarios</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS conta_honorarios (
  id int(4) NOT NULL AUTO_INCREMENT,
  cuenta int(11) DEFAULT NULL,
  profesional int(4) NOT NULL,
  fecha_comprobante date NOT NULL DEFAULT "0000-00-00",
  comprobante text COLLATE utf8_spanish_ci NOT NULL,
  tipo_comprobante text COLLATE utf8_spanish_ci NOT NULL,
  nro_comprobante int(12) unsigned zerofill NOT NULL,
  total decimal(10,2) NOT NULL,
  observaciones varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT "0",
  PRIMARY KEY (id),
  KEY fecha (fecha_comprobante)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Honorarios</h3>';
}

echo '<h2>Creando Tabla Personal</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS cronos_personal (
  id int(11) NOT NULL AUTO_INCREMENT,
  nomina varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  apellidos varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  nombres varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  tipo_doc int(11) NOT NULL,
  nro_doc varchar(10) COLLATE utf8_spanish_ci NOT NULL DEFAULT "0",
  cuil varchar(11) COLLATE utf8_spanish_ci NOT NULL,
  domicilio varchar(100) COLLATE utf8_spanish_ci DEFAULT "",
  localidad varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  cargo varchar(50) COLLATE utf8_spanish_ci NOT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT "0",
  fecha_nac date NOT NULL DEFAULT "0000-00-00",
  nacionalidad varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  sexo varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY nro_doc (nro_doc)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Personal</h3>';
}

echo '<h2>Creando Tabla Sueldos</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS conta_sueldos (
  id int(4) NOT NULL AUTO_INCREMENT,
  idPersonal int(11) NOT NULL,
  periodo_pago date NOT NULL DEFAULT "0000-00-00",
  nro_recibo int(14) unsigned zerofill DEFAULT NULL,
  remuneracion_gravada decimal(10,2) NOT NULL,
  remuneracion_no_gravada decimal(10,2) DEFAULT "0.00",
  descuentos decimal(10,2) DEFAULT "0.00",
  total decimal(10,2) NOT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT "0",
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Sueldos</h3>';
}

echo '<h2>Creando Tabla Usuarios</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS conta_usuarios (
  id int(11) NOT NULL AUTO_INCREMENT,
  username varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  nombre varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  email varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `password` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  categoria varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  bloqueado tinyint(4) NOT NULL DEFAULT "0",
  enviarMail tinyint(4) DEFAULT "0",
  fechaRegistro datetime NOT NULL DEFAULT "0000-00-00 00:00:00",
  ultimaVisita datetime NOT NULL DEFAULT "0000-00-00 00:00:00",
  activo varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  ultima_ip varchar(15) COLLATE utf8_spanish_ci NOT NULL,
  eliminado tinyint(1) NOT NULL,
  codigo int(11) NOT NULL,
  PRIMARY KEY (id),
  KEY usertype (categoria)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Usuarios</h3>';
}

echo '<h2>Creando Tabla Ingresos</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS conta_ingresos (
  id int(4) NOT NULL AUTO_INCREMENT,
  cuenta int(11) DEFAULT NULL,
  cliente int(4) NOT NULL,
  fecha_comprobante date NOT NULL DEFAULT "0000-00-00",
  comprobante text COLLATE utf8_spanish_ci NOT NULL,
  tipo_comprobante text COLLATE utf8_spanish_ci NOT NULL,
  nro_comprobante int(12) unsigned zerofill NOT NULL,
  condicion_venta varchar(16) COLLATE utf8_spanish_ci DEFAULT NULL,
  total decimal(10,2) NOT NULL,
  fecha_cobro date NOT NULL DEFAULT "0000-00-00",
  recibo_nro varchar(14) COLLATE utf8_spanish_ci DEFAULT "0",
  observaciones text COLLATE utf8_spanish_ci NOT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT "0",
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Ingresos</h3>';
}

echo '<h2>Creando Tabla Permisos Rol</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS cronos_permisos_role (
  role int(11) NOT NULL,
  permiso int(11) NOT NULL,
  valor tinyint(4) NOT NULL,
  UNIQUE KEY role (role,permiso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Permisos Role</h3>';
}else{
    echo '<h3>Tabla Permisos Role creada</h3>';
}

echo '<h2>Creando Tabla Permisos</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS cronos_permisos (
  id_permiso int(11) NOT NULL AUTO_INCREMENT,
  permiso varchar(100) NOT NULL,
  `key` varchar(50) NOT NULL,
  PRIMARY KEY (id_permiso)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Permisos</h3>';
}else{
    echo '<h3>Tabla Permisos creada</h3>';
}

//echo '<h2>Cargando Tabla Permisos</h2>';
//$sql = 'INSERT INTO cronos_permisos VALUES
//("1", "Tareas de administracion", "admin_access"),
//("2", "Agregar Datos", "nuevo_post"),
//("3", "Editar Datos", "editar_post"),
//("4", "Eliminar Datos", "eliminar_post");';
//
//$res = $db->query($sql);
//if(is_null($res)){
//    echo '<h3>No se pudo cargar Tabla Permisos</h3>';
//}else{
//    echo '<h3>Tabla Permisos creada</h3>';
//}

echo '<h2>Creando Tabla Permisos Usuario</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS cronos_permisos_usuario (
  usuario int(11) NOT NULL,
  permiso int(11) NOT NULL,
  valor tinyint(4) DEFAULT NULL,
  UNIQUE KEY usuario (usuario,permiso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;';

$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Permisos Usuarios</h3>';
}else{
    echo '<h3>Tabla Permisos Usuarios creada</h3>';
}

echo '<h2>Creando Tabla Roles</h2>';
$sql = 'CREATE TABLE IF NOT EXISTS cronos_roles (
  id_role int(11) NOT NULL AUTO_INCREMENT,
  role varchar(100) NOT NULL,
  PRIMARY KEY (id_role)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;';
$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo crear Tabla Roles</h3>';
}else{
    echo '<h3>Tabla Roles creada</h3>';
}

echo '<h2>Cargando Tabla Roles</h2>';
$sql = "INSERT INTO cronos_roles (id_role, role) VALUES
(1, 'Administrador'),
(2, 'Gestor'),
(3, 'Editor'),
(4, 'Registrado');";
$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo cargar Tabla Roles</h3>';
}else{
    echo '<h3>Tabla Roles cargada</h3>';
}


echo '<h2>Creando Tabla GRUPO CUENTAS</h2>';
$sql = "CREATE TABLE IF NOT EXISTS conta_grupo_cuentas (
  id int(11) NOT NULL AUTO_INCREMENT,
  grupo_cuenta varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  descripcion text COLLATE utf8_spanish_ci NOT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;";
$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo CREAR Tabla GRUPO CUENTAS</h3>';
}else{
    echo '<h3>Tabla Roles CREADA</h3>';
}

echo '<h2>Creando Tabla DOMICILIO PROVEEDOR</h2>';
$sql = "CREATE TABLE IF NOT EXISTS conta_domicilios_proveedor (
  id int(4) NOT NULL AUTO_INCREMENT,
  id_proveedor int(11) NOT NULL,
  tipo_domicilio varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  calle varchar(50) COLLATE utf8_spanish_ci DEFAULT '',
  casa_nro varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  barrio varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  localidad varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  cp varchar(8) COLLATE utf8_spanish_ci DEFAULT NULL,
  provincia varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  pais varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;";
$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo CREAR Tabla DOMICILIO PROVEEDOR</h3>';
}else{
    echo '<h3>Tabla DOMICILIO PROVEEDOR CREADA</h3>';
}

echo '<h2>Creando Tabla DOMICILIO CUENTAS PROVEEDOR</h2>';
$sql = "CREATE TABLE IF NOT EXISTS `conta_cuentas_proveedor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cuenta` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `eliminado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ";
$res = $db->query($sql);
if(is_null($res)){
    echo '<h3>No se pudo CREAR Tabla DOMICILIO CUENTAS PROVEEDOR</h3>';
}else{
    echo '<h3>Tabla DOMICILIO PROVEEDOR CUENTAS CREADA</h3>';
}

echo '<h2>Creando Tabla CONTACTOS PROVEEDOR</h2>';
$sql = "CREATE TABLE IF NOT EXISTS conta_contactos_proveedor (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_proveedor int(11) NOT NULL,
  tipo varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  valor varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  observaciones varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  eliminado tinyint(1) NOT NULL,
  UNIQUE KEY id (id),
  KEY idAlumno (id_proveedor)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;";
$res = $db->query($sql);
if(is_null($res) or !$res){
    echo '<h3>No se pudo CREAR Tabla CONTACTOS PROVEEDOR</h3>';
}else{
    echo '<h3>Tabla CONTACTOS PROVEEDOR CREADA</h3>';
}

echo '<h2>Creando Tabla IMPUESTOS</h2>';
$sql = "CREATE TABLE IF NOT EXISTS conta_impuestos (
  id int(5) NOT NULL AUTO_INCREMENT,
  impuesto varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  caracter varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  tipo_vencimiento text COLLATE utf8_spanish_ci NOT NULL,
  observaciones varchar(110) COLLATE utf8_spanish_ci NOT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;";
$res = $db->query($sql);
if(is_null($res) or !$res){
    echo '<h3>No se pudo CREAR Tabla IMPUESTOS</h3>';
}else{
    echo '<h3>Tabla IMPUESTOS CREADA</h3>';
}

echo '<h2>Creando Tabla PAGO IMPUESTOS</h2>';
$sql = "CREATE TABLE IF NOT EXISTS conta_pagoimpuestos (
  id int(5) NOT NULL AUTO_INCREMENT,
  impuesto varchar(30) COLLATE utf8_spanish_ci DEFAULT NULL,
  fecha_comprobante date NOT NULL DEFAULT '0000-00-00',
  total double NOT NULL,
  observaciones varchar(110) COLLATE utf8_spanish_ci NOT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;";
$res = $db->query($sql);
if(is_null($res) or !$res){
    echo '<h3>No se pudo CREAR Tabla PAGO IMPUESTOS</h3>';
}else{
    echo '<h3>Tabla PAGO IMPUESTOS CREADA</h3>';
}

echo '<h2>Creando Tabla DOMICILIO CLIENTES</h2>';
$sql = "CREATE TABLE IF NOT EXISTS conta_domicilios_cliente (
  id int(4) NOT NULL AUTO_INCREMENT,
  id_cliente int(11) NOT NULL,
  tipo_domicilio varchar(25) COLLATE utf8_spanish_ci NOT NULL,
  calle varchar(50) COLLATE utf8_spanish_ci DEFAULT '',
  casa_nro varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  barrio varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  localidad varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  cp varchar(8) COLLATE utf8_spanish_ci DEFAULT NULL,
  provincia varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  pais varchar(25) COLLATE utf8_spanish_ci DEFAULT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;";
$res = $db->query($sql);
if(is_null($res) or !$res){
    echo '<h3>No se pudo CREAR Tabla DOMICILIO CLIENTES</h3>';
}else{
    echo '<h3>Tabla DOMICILIO CLIENTES CREADA</h3>';
}

echo '<h2>Creando Tabla CONTACTOS CLIENTES</h2>';
$sql = "CREATE TABLE IF NOT EXISTS conta_contactos_cliente (
  id int(11) NOT NULL AUTO_INCREMENT,
  id_cliente int(11) NOT NULL,
  tipo varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
  valor varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  observaciones varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL,
  eliminado tinyint(1) NOT NULL,
  UNIQUE KEY id (id),
  KEY idAlumno (id_cliente)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;";
$res = $db->query($sql);
if(is_null($res) or !$res){
    echo '<h3>No se pudo CREAR Tabla CONTACTOS CLIENTES</h3>';
}else{
    echo '<h3>Tabla CONTACTOS CLIENTES CREADA</h3>';
}

echo '<h2>Creando Tabla CUENTAS CLIENTES</h2>';
$sql = "CREATE TABLE IF NOT EXISTS conta_cuentas_cliente (
  id int(11) NOT NULL AUTO_INCREMENT,
  cuenta int(11) NOT NULL,
  id_cliente int(11) NOT NULL,
  eliminado tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci AUTO_INCREMENT=1 ;";
$res = $db->query($sql);
if(is_null($res) or !$res){
    echo '<h3>No se pudo CREAR Tabla CUENTAS CLIENTES</h3>';
}else{
    echo '<h3>Tabla CUENTAS CLIENTES CREADA</h3>';
}
