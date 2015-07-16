<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php if (isset($this->titulo)) echo $this->titulo ?></title>
        <link rel="stylesheet" href="<?php echo $_layoutParams['ruta_css']; ?>basico.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $_layoutParams['ruta_bootstrap']; ?>/css/bootstrap.css" type="text/css" />
        <link rel="stylesheet" href="<?php echo $_layoutParams['ruta_css']; ?>redmond/jquery-ui-1.10.1.custom.css" type="text/css" />
        <link rel="stylesheet" type="text/css" href="<?php echo $_layoutParams['ruta_css']; ?>dataTables/jquery.dataTables.css">
        <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.js"></script>
        <!--<script type="text/javascript" src="Vistas/Layout/Default/Js/jquery.js"></script>-->
        <script type="text/javascript" src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
        <!--<script type="text/javascript" src="Vistas/Layout/Default/Js/jquery-ui-1.9.2.custom.js"></script>-->
        <script type="text/javascript" src="<?php echo $_layoutParams['ruta_bootstrap']; ?>/js/bootstrap.js"></script>
        <script type="text/javascript" src="Vistas/Layout/Default/Js/jquery.dataTables.js"></script>
        <?php if (isset($_layoutParams['js']) && count($_layoutParams['js'])): ?>
            <?php foreach ($_layoutParams['js'] as $archivoJs) { ?>
                <script type = "text/javascript" src = "<?php echo $archivoJs; ?>"></script>
            <?php } ?>
        <?php endif; ?>
        
        <?php if (isset($_layoutParams['css']) && count($_layoutParams['css'])): ?>
            <?php foreach ($_layoutParams['css'] as $archivoCss) { ?>
                <link rel="stylesheet" href="<?php echo $archivoCss; ?>"/>
            <?php } ?>
        <?php endif; ?>
        <link rel="stylesheet" href="<?php echo $_layoutParams['ruta_bootstrap']; ?>css/bootstrap-theme.css" type="text/css" />
    </head>

