<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Permite descargar un anexo dada su id
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2017 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */


require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";

$aut_usuario = "";
autentica_usuario($dsn, $aut_usuario, 21);

require_once 'modulos/anexos/PagAnexo.php';

$id_caso = +$_GET['idcaso'];
$archivo = var_escapa($_GET['archivo']);

if ($id_caso == 0) {
    echo "Falta id_caso";
    exit(1);
}
if ($archivo == '') {
    echo "Falta archivo";
    exit(1);
}

VerAnexo::descarga_anexo($id_caso, $archivo);
    
