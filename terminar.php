<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:

/**
* Cierra sesi�n de Sivel
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: terminar.php,v 1.24.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: CONSULTA P�BLICA
*/


/**
 * Cierra sesi�n de Sivel
 */
require_once 'aut.php';
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once 'misc.php';

cierraSesion($dsn);
echo "<html><head><title>SIVeL: Sistema de Informaci�n de Violencia " .
    "Pol�tica en L�nea</title></head>";
echo "<body>";
echo "<h1>SIVeL: Sistema de Informaci�n de Violencia Pol�tica en L�nea</h1>";
echo "Fin de sesi�n<br>";
echo '<a href = "index.php">Iniciar nueva sesi�n</a>';
echo "</body>";
echo "</html>";
?>
