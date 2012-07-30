<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
* Cierra sesión de Sivel
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: CONSULTA PÚBLICA
*/


/**
 * Cierra sesión de Sivel
 */
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'misc.php';

cierraSesion($dsn);
encabezado_envia(_('Cerrando sesión'));
#echo "<html><head><title>" 
#    . _('SIVeL: Sistema de Información de Violencia Política en Línea')
#    . "</title></head>";
#echo "<body>"; #
echo "<h1>" .
    _('SIVeL: Sistema de Información de Violencia Política en Línea') 
    . "</h1>";
echo _("Fin de sesión") . "<br>";
echo '<a href = "index.php">' . _('Iniciar nueva sesión') . '</a>';
#echo "</body>";
#echo "</html>";
pie_envia();
?>
