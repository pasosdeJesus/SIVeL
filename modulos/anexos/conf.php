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
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

if (!isset($GLOBALS['dir_anexos'])) { 
    /** Directorio donde se almacenan anexos */
    $GLOBALS['dir_anexos'] = '/resbase/anexos';
}

if (!esta_nueva_ficha('anexos')) {
    $GLOBALS['nueva_ficha_tabuladores'][] =  array(
        10, 'anexos', 'modulos/anexos/PagAnexo', 10
    );
}

$GLOBALS['remplaza_ficha_tabuladores'][] =  array(
    'frecuentes', 'modulos/anexos/PagFrecuenteAnexo'
);

$GLOBALS['remplaza_ficha_tabuladores'][] =  array(
    'otras', 'modulos/anexos/PagOtraAnexo'
);

