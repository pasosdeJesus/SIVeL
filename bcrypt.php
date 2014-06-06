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
 * Acceso: SÓLO DEFINICIONES
 */


/**
 * Genera colchon de datos aleatorios de longitud $lon.
 * Como generador de números aleatorios emplea /dev/random 
 * (que es criptografícamente bueno en OpenBSD >=5.1) o si no está emplea 
 * la función insegura mt_rand
 *
 * @param integer $lon Longitud del colchón por generar
 *
 * @return string Cadena con caracteres aleatorios y longitud $lon
 */
function colchon_aleatorios($lon)
{
    $f = null;
    if (file_exists("/dev/random")) {
        //echo "OJO Si hay /dev/random\n";
        $f = fopen("/dev/random", "rb");
        $a = fread($f, 1);
        if ($a<0 || $a>255) {
            trigger_error("No se pudo usar /dev/random");
            fclose($f);
            $f = null;
        }
    } else {
        trigger_error("No existe /dev/random, se recomienda adJ>=5.4");
    }

    $col = "";
    for ($i = 0 ; $i < $lon; $i++) {
        if ($f != null) {
            $a = fread($f, 1);
            //echo "OJO a= " . ord($a) . "\n";
        } else {
            $a = chr(mt_rand(1, 255));
        }
        $col .= $a;
    }

    if ($f != null) {
        fclose($f);
    }
    return $col;
}

/**
 * Codifica sal para bcrypt
 * Se basa en función encode_salt de libc de OpenBSD.
 *
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
 *
 * @param integer $lrondas Cantidad de rondas es 2^$lrondas
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

/**
 * Retorna condensado bcrypt de cadena $c
 *
 * @param string $c Cadena de la cual obtener condensado
 *
 * @return string Condensado
 */
function condensado_bcrypt($c)
{
    do {
        $gsal=gen_sal_bcrypt(10);
        flush();
        $clavebf = crypt($c, $gsal);
    } while ($clavebf == '*0');
    return $clavebf;
}
