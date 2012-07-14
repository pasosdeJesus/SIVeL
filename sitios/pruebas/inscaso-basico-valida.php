<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
 * Verifica inserción en pestaña básicos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2011 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
*/

// Necesario porque la pestaña termina pasando por
// QuickForm/Actions/Jump.php que intenta enviar encabezado Location
// para cargar otra pagina y eso no es soportado en modo CLI.

if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

$nume = verificaInsercion($db, array('caso'), array('caso' => 0));
if ($nume > 0) {
    echo "Cantidad de errores $nume\n";
    exit(1);
}
exit(0);
?>
