<?php

$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central boxshadowboton ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=consultorios&sub=agregar\" target=\"_self\" title=\"Agregar Consultorios\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Consultorios/Vista/consultorios_add.png\" alt=\"Nuevo Salón\" class=\"toolbar2\"/>Agregar Consultorios\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central boxshadowboton ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=consultorios&sub=listar\" target=\"_self\" title=\"Lista de consultorios\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "Consultorios/Vista/lista_consultorios.png\" alt=\"Consultorios\" class=\"toolbar2\"/>Lista de Consultorios\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central boxshadowboton ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
