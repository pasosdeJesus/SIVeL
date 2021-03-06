<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Variables de configuración del módulo
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2012 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin Garantías
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

// Opciones del menú

$GLOBALS['modulo'][100] = 'modulos/estrotulos/estrotulos.php';
$GLOBALS['modulo'][101] = 'modulos/estrotulos/estcolectivas.php';

// Posibilidades de módulos
$GLOBALS['consultaweb_ordenarpor'][0] = "rotulos_cwebordenar";
$GLOBALS['gancho_rc_reginicial'][0] = "rotulos_inicial";
$GLOBALS['gancho_rc_regfinal'][0] = "rotulos_final";
$GLOBALS['misc_ordencons'][0] = "rotulos_orden_cons";

// Para excluir en el ordenamiento por categorias las víctimas
// cuya filiación es SIN INFORMACIÓN
$GLOBALS['estrotulos_excluirsinfiliacion'] = false;

$GLOBALS['m_opcion'][53] = array(
    'nombre' =>  _('Individuales con Rotulos de Rep. Cons.'),
    'idpapa' => 50,
    'url' => 'opcion?num=100'
);
$GLOBALS['m_opcion'][54] = array(
    'nombre' =>  _('Colectivas con Rotulos de Rep. Cons.'),
    'idpapa' => 50,
    'url' => 'opcion?num=101'
);

$GLOBALS['m_opcion_rol'][53] = array(1, 2);
$GLOBALS['m_opcion_rol'][54] = array(1, 2);

require_once "modulos/estrotulos/reporte_rotulos.php";


