<?php

define('BASE_URL', 'http://localhost/Contasoft_Bakhita/');
//define('BASE_URL', 'http://www.acsjbakhita.org/Contasoft/');
define('DEFAULT_CONTROLADOR', 'index');
define('DEFAULT_LAYOUT', 'Default');
define('IMAGEN_PUBLICA', BASE_URL . 'Public/Img/');
define('ICONOS', BASE_URL . 'Vistas/Layout/Default/Img/iconos/');

//define('APP_NAME', 'CONTASOFT');
//define('APP_DESCRIPCION', 'Sistema de administracion contable');
//define('APP_AUTOR', 'werd');
//define('APP_SLOGAN', 'Sistema de administracion contable');

define('SESSION_TIME',1);

define('HASH_KEY', '50d8bab41b8c2');

define('DB_HOST','localhost');
define('DB_USER','root');
//define('DB_USER','gg000334_conta');
define('DB_PASS','');
//define('DB_PASS','ProyectoBakhita2000');
define('DB_NAME','gg000334_contasoft');
define('DB_CHAR','utf8');

define('FPDF_FONTPATH', BASE_PATH . 'LibQ/Fpdf/font/');

define('LIMITE_REGISTROS', 15);

define('MOSTRAR_ICONOS',1);
define('MAX_FILE_SIZE',600000);

define('DATOS_GUARDADOS', '<div id="mensaje" class="ui-state-highlight ui-corner-all">'
        . '<span class="ui-icon ui-icon-info" style="float: left; margin-right: .3em;"></span>'
        . 'Los datos se guardaron correctamente</div>');
define('DATOS_NO_GUARDADOS', '<div id="mensaje" class="ui-state-error ui-corner-all">'
        . '<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>'
        . 'NO se pudieron guardar los datos. Verifique</div>');
