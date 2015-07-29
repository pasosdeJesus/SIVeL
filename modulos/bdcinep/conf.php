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
 * @copyright 2015 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */


// Opciones


// Opciones del menú

$GLOBALS['modulo'][1000] = 'modulos/bdcinep/filtrocons.php';

$GLOBALS['m_opcion'][57] = array(
    'nombre' => 'Tablas Consolidado General de Víctimas',
    'idpapa' => 50,
    'url' => 'opcion?num=1000',
);

require_once "modulos/bdcinep/tablasnyn.php";

$GLOBALS['gancho_ei_filtro'][] = 'agregar_categoria_nombre';
$GLOBALS['gancho_ei_creaconsulta'][] = 'consulta_categoria_nombre';
$GLOBALS['gancho_ei_creaconsulta3'][] = 'consulta3_categoria_nombre';
$GLOBALS['gancho_ei_muestraconsulta'][] = 'muestra_horizontal_html';

