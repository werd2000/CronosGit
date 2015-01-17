<?php

$retorno = "<div id=\"ventana\" class=\"window ui-widget-content ui-corner-all\">\n";
$retorno .= "<div class=\"toolbar\">\n";
$retorno .= '<div class="boton_central boxshadowboton ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=imprimir&sub=agregar\" target=\"_self\" title=\"Agregar imprimir\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "imprimir/Vista/obrasSociales_add.png\" alt=\"Nuevo imprimir\" class=\"toolbar2\"/>Agregar Obras Sociales\n";
$retorno .= "</a></div>\n";
$retorno .= '<div class="boton_central boxshadowboton ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php?option=imprimir&sub=listar\" target=\"_self\" title=\"Lista de imprimir\">\n";
$retorno .= "<img src=\"" . DIR_MODULOS . "imprimir/Vista/lista_imprimir.png\" alt=\"imprimir\" class=\"toolbar2\"/>Lista de Obras Sociales\n";
$retorno .= "</a></div>\n";

$retorno .= '<div class="boton_central boxshadowboton ui-widget-content ui-corner-all">';
$retorno .= "<a href=\"index.php\" target=\"_self\" title=\"Salir\">\n";
$retorno .= "<img src=\"" . IMG . "backward.png\" alt=\"Volver\" class=\"toolbar2\"/>Volver\n";
$retorno .= "</a></div>\n";
$retorno .= "</div>\n";
$retorno .= "</div>\n";
echo $retorno;
