<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
 * Consulta externa
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL-pruebas
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2007 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
*/

/**
 * Consulta externa
 */
if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
$post = array();

require_once "ambiente.php";
require_once "misc.php";

/*** BÁSICOS ***/

$post['evita_csrf'] = '1234';
$post['_qf_default'] = 'basicos:siguiente';
$post['fini']['d'] = '';
$post['fini']['M'] = '';
$post['fini']['Y'] = '';
$post['ffin']['d'] = '';
$post['ffin']['M'] = '';
$post['ffin']['Y'] = '';
$post['titulo'] = '';
$post['hora'] = '';
$post['duracion'] = '';
$post['id_intervalo'] = '5';
$post['tipo_ubicacion'] = 'S';
$post['_qf_basicos_busqueda'] = 'Buscar';
$post['id_caso'] = '-1';
$post['id'] = '-1';
$post['_qf_default'] = 'basicos:siguiente';
$_SESSION['forma_modo'] = 'busqueda';
$_SESSION['basicos_id'] = '-1';
$_SESSION['fvloc_pag'] = 0;
$_SESSION['fvloc_total'] = 1;
$_SESSION['basicos_id'] = -1;
$_SESSION['busca_presenta']['ordenar'] = 'fecha';
$_SESSION['busca_presenta']['mostrar'] = 'tabla';
$_SESSION['busca_presenta']['caso_id'] = '1';
$_SESSION['busca_presenta']['caso_memo'] = '1';
$_SESSION['busca_presenta']['caso_fecha'] = '1';
$_SESSION['busca_presenta']['m_localizacion'] = '1';
$_SESSION['busca_presenta']['m_victimas'] = '1';
$_SESSION['busca_presenta']['m_presponsables'] = '1';
$_SESSION['busca_presenta']['m_tipificacion'] = '1';
$_SESSION['busca_presenta']['m_fuentes'] = '0';
$_SESSION['busca_presenta']['m_varlineas'] = '1';
$_SESSION['bus_fecha_final'] = '0000-00-00';
$_SESSION['bus_fecha_inicial'] = '0000-00-00';


hace_consulta($db, "DELETE FROM caso where id='-1';");
hace_consulta($db, "DELETE FROM intervalo where id='5';");
hace_consulta(
    $db, "INSERT INTO intervalo (id, nombre, rango, fechacreacion) " .
    " VALUES ('5', 'SIN INFORMACIÓN', 'SIN INFORMACIÓN', '2001-01-01');"
);
hace_consulta(
    $db, "INSERT INTO rangoedad (id, nombre, rango, limiteinferior,
    limitesuperior, fechacreacion)
    VALUES ('6', 'SN', 'SIN INFORMACIÓN', '-1', '-1', '2001-01-01');"
);

//hace_consulta($db, "INSERT INTO caso (id, fecha, memo, id_intervalo) " .
//    "VALUES ('-1', '2005-1-1', '', '5');");

@pasaPestanaFicha($db, array("caso"), $post, null);

exit(0);
?>
