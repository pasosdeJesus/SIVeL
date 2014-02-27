<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Funciones para uso de bcrypt.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2014 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

/**
 * Genera colchon de datos aleatorios de longitud $lon.
 *
 * @return string Cadena con caracteres aleatorios y longitud $lon
 */
function colchon_aleatorios($lon)
{
    $col = "";
    for ($i = 0 ; $i < $lon; $i++) {
        $col .= chr(mt_rand(1, 255));
    }

    return $col;
}

/**
 * Codifica sal para bcrypt
 * Se basa en función encode_salt de libc de OpenBSD 
 * @param array   $csal    Sal en binario
 * @param integer $lrondas Cantidad de rondas es 2^$lrondas
 *
 * @return string Cadena con sal para bcrypt
 */
function codificar_sal($csal, $lrondas)
{
    $sal = sprintf("$2a$%2.2u$%s", $lrondas, base64_encode($csal));

    return $sal;
}

/**
 * Genera sal para bcrypt.  Se basa en función bcrypt_gensalt de 
 * libc de OpenBSD.
 * @param integer lrondas Cantidad de rondas es 2^lrondas
 *
 * @return string Cadena con sal para bcrypt
 */
function gen_sal_bcrypt($lrondas)
{
    $csal = colchon_aleatorios(16);
    if ($lrondas < 4) {
        $lrondas = 4;
    } else if ($lrondas > 31) {
        $lrondas = 31;
    }
    $gsal = codificar_sal($csal, 16, $lrondas);

    return $gsal;
}

function condensado_bcrypt($c)
{
    do {
        $gsal=gen_sal_bcrypt(10);
	flush();
	$clavebf = crypt($c, $gsal);
    } while ($clavebf == '*0');
    return $clavebf;
}
