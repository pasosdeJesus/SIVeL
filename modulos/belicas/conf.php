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
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

// Opciones del menú

$GLOBALS['modulo'][200] = 'modulos/belicas/estadisticas_comb.php';

if (!esta_nueva_ficha('belicas')) {
    $GLOBALS['nueva_ficha_tabuladores'][] =  array(
        8,'belicas', 'modulos/belicas/PagVictimaCombatiente', 4
    );
}

$GLOBALS['m_opcion'][52] = array(
    'nombre' => _('V. Combatientes'),
    'idpapa' => 50,
    'url' => 'opcion?num=200'
);
$GLOBALS['m_opcion'][46] = array(
    'nombre' => _('Revista Bélicas'),
    'idpapa' => 40,
    'url' => 'consulta_web?mostrar=revista&categoria=belicas&sincampos=caso_id'
);
$GLOBALS['m_opcion'][47] = array(
    'nombre' => _('Revista Memo Bélicas'),
    'idpapa' => 40,
    'url' => 'consulta_web?mostrar=revista&categoria=belicas&' 
    . 'sincampos=caso_id,m_victimas,m_presponsables,'
    . 'm_tipificacion,m_fuentes'
);
$GLOBALS['m_opcion'][48] = array(
    'nombre' => _('Revista NO Bélicas'),
    'idpapa' => 40,
    'url' => 'consulta_web?mostrar=revista&categoria=nobelicas&sincampos=caso_id'
);
$GLOBALS['m_opcion'][49] = array(
    'nombre' => _('Revista Memo NO Bélicas'),
    'idpapa' => 40,
    'url' => 'consulta_web?mostrar=revista&categoria='
    . 'nobelicas&sincampos=caso_id,m_victimas,m_presponsables,'
    . 'm_tipificacion,m_fuentes'
);
$GLOBALS['m_opcion'][54] = array(
    'nombre' => _('Colectivas con Rotulos de Rep. Cons.'),
    'idpapa' => 50,
    'url' => 'opcion?num=101'
);

$GLOBALS['m_opcion_rol'][46] = array(1, 2);
$GLOBALS['m_opcion_rol'][47] = array(1, 2);
$GLOBALS['m_opcion_rol'][48] = array(1, 2);
$GLOBALS['m_opcion_rol'][49] = array(1, 2);

