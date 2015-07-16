<body>
    <div class="container">
        <section id="page-header" class="row">
            <h1>
                <a href="<?php echo BASE_URL; ?>" class="site-name text-shadow"><?php echo APP_NAME; ?></a>
            </h1>            
            <h2 class="small"><?php echo APP_SLOGAN; ?></h2>
            <div class="container">
                <?php if (App_Session::get('autenticado')) { ?>
                    <div class="col-md-6">Hola <img src="<?php // echo IMAGEN_PUBLICA . 'Fotos/Usuario' . SESSION::get('id_usuario'). 'png'           ?>"
                                                    class="foto_usuario_16">
                                                    <?php echo App_Session::get('usuario'); ?>
                    </div>
                    <div class="col-md-6"><a href="<?php echo BASE_URL; ?>?option=Usuarios&cont=login&sub=logout"> Salir </a></div>
                <?php } else { ?>
                    <div class="col-mod-6">
                        <a href="<?php echo BASE_URL; ?>?option=Usuarios&sub=login"> Ingresar </a>
                    </div>
                <?php } ?>
            </div>
        </section>
        <?php
        if (isset($this->_barraHerramientas)) {
            include 'Navegacion.php';
        }
        ?>

        <section id="ventana" class="row">
