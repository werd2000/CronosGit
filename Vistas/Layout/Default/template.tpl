<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title> {$titulo|default:"Sin titulo"} </title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="{$_layoutParams.ruta_css}basico.css" type="text/css" />
        <link rel="stylesheet" href="{$_layoutParams.ruta_css}ui.jqgrid.css" type="text/css" />
        <link rel="stylesheet" href="{$_layoutParams.ruta_css}redmond/jquery-ui-1.8.21.custom.css" type="text/css" />
        <script type="text/javascript" src="{$_layoutParams.root}Public/Js/jquery.js"></script>

        {if isset($_layoutParams.js) && count($_layoutParams.js)}
            {foreach item=js from=$_layoutParams.js}
                <script type = "text/javascript" src = "{$js}"></script>
            {/foreach}
        {/if}
    </head>

<body>
    <div id="contenedor" class="ui-corner-all boxshadow">
        <div id="encabezado">
            <h1>
                <a href="{$_layoutParams.root}" class="NombreSitio text-shadow">{$_layoutParams.configs.app_name}</a>
            </h1>            
            <h2 class="SloganSitio">{$_layoutParams.configs.app_slogan}</h2>
        </div>
        
        {if (Session::get('autenticado'))}
            <div class="saludo">Hola <img src="site_media/imagenes/usuarios/id278.png" class="foto_usuario_16"> Administrador</div>
            <div class="logout"><a href="{$_layoutParams.root}?option=login&sub=logout"> Salir </a></div>
        {else}
            <div class="logout"><a href="{$_layoutParams.root}?option=login"> Ingresar </a></div>            
        {/if}
        
        
        <div id="ventana" class="window ui-widget-content ui-corner-all">
            <noscript>
            <p>Para el correcto funcionamiento debe tener javascript habilitado</p>
            </noscript>
            {if (isset($_msj_error))}
                <div id="msj_error">{$_msj_error}</div>
            {/if}
            
            {if (isset($_mensaje))}
                <div id="_mensaje">{$_mensaje}</div>
            {/if}

            {include file=$_contenido}
            
            <div id="pie">
{$_layoutParams.configs.app_name}  - Copyrigth &copy; Posadas - Misiones - Argentina - 2009
</div>
</body>
</html>