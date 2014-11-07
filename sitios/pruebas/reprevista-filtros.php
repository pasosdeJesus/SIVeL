<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
 * Reporte revista con filtros.
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

if (PHP_SAPI !== 'cli') {
    die("Acceso: INTERPRETE DE COMANDOS");
}
require_once "ambiente.php";

/**
 * Incrementa cuenta y si es mayor que 0 ejecuta runController
 *
 * @param string $post Post
 *
 * @return nada
 */
function consultaweb($post)
{
    static $cuenta = 0;
    $_REQUEST = $_POST = $post;
    $_REQUEST['evita_csrf'] = $_SESSION['sin_csrf'] = $_POST['evita_csrf'] = 1234;
    include_once "consulta_web.php";
    if ($cuenta > 0) {
        runController();
    }
    $cuenta++;
}

$post = array();

$post['_qf_consultaWeb_consulta'] = 'Consulta';
$post['_qf_default'] = 'consultaWeb:consulta';
$post['nomvic'] = '';
$post['caso_fecha'] = '1';
$post['caso_id'] = '1';
$post['caso_memo'] = '1';
$post['ffin']['M'] = '';
$post['ffin']['Y'] = '';
$post['ffin']['d'] = '';
$post['fini']['M'] = '';
$post['fini']['Y'] = '';
$post['fini']['d'] = '';
$post['id_casos'] = '1';
$post['id_departamento'] = '';
$post['m_fuentes'] = '1';
$post['m_localizacion'] = '1';
$post['m_presponsables'] = '1';
$post['m_tipificacion'] = '1';
$post['m_varlineas'] = '1';
$post['m_victimas'] = '1';
$post['mostrar'] = 'tabla';
$post['ordenar'] = 'fecha';
$post['presponsable'] = '';
$post['retroalimentacion'] = '1';
$post['ssocial'] = '';
$post['titulo'] = '';
$post['usuario'] = '';
echo "** Por Código\n";
consultaweb($post);

$post['id_casos'] = '';
$post['titulo'] = 'Título';
echo "** Por Título\n";
consultaweb($post);

$post['titulo'] = '';
$post['id_departamento'] = '1';
echo "** Por Departamento\n";
consultaweb($post);

$post['id_municipio'] = '1';
echo "** Por Municipio\n";
consultaweb($post);

$post['id_clase'] = '1';
echo "** Por Clase\n";
consultaweb($post);

$post['nomvic'] = 'nombre';
echo "** Por Nombre\n";
consultaweb($post);

$post['nomvic'] = '';
$post['fini']['Y'] = '2007';
echo "** Por Año inicial\n";
consultaweb($post);

$post['fini']['M'] = '08';
echo "** Por Mes inicial\n";
consultaweb($post);

$post['fini']['d'] = '07';
echo "** Por día inicial\n";
consultaweb($post);

$post['fini']['Y'] = '';
$post['fini']['M'] = '';
$post['fini']['d'] = '';
$post['ffin']['Y'] = '2007';
echo "** Por año final\n";
consultaweb($post);

$post['ffin']['M'] = '08';
echo "** Por mes final\n";
consultaweb($post);

$post['ffin']['d'] = '07';
echo "** Por dia final\n";
consultaweb($post);

$post['ffin']['Y'] = '';
$post['ffin']['M'] = '';
$post['ffin']['d'] = '';
$post['presponsable'] = '1';
echo "** Por presunto responsable\n";
consultaweb($post);

$post['presponsable'] = '';
$post['clasificacion']['0'] = 'T:1000:1000';
echo "** Por clasificación\n";
consultaweb($post);

unset($post['clasificacion']);
$post['ssocial'] = '1';
echo "** Por sector social\n";
consultaweb($post);

$post['ssocial'] = '';
$post['usuario'] = '1';
echo "** Por usuario\n";
consultaweb($post);


$post['id_casos'] = '1';
$post['titulo'] = 'Título';
$post['id_departamento'] = '1';
$post['id_municipio'] = '1';
$post['id_clase'] = '1';
$post['nomvic'] = 'nombre';
$post['ffin']['d'] = '07';
$post['ffin']['M'] = '08';
$post['ffin']['Y'] = '2007';
$post['fini']['d'] = '07';
$post['fini']['M'] = '08';
$post['fini']['Y'] = '2007';
$post['presponsable'] = '1';
$post['clasificacion']['0'] = 'T:1000:1000';
$post['ssocial'] = '1';
$post['usuario'] = '1';
echo "** Todas las restricciones";
consultaweb($post);

$post['id_casos'] = '11';
echo "** Código errado";
consultaweb($post);

$post['id_casos'] = '1';
$post['titulo'] = 'Tot';
echo "** Título errado";
consultaweb($post);

$post['titulo'] = 'Título';
$post['id_departamento'] = '2';
echo "** Departamento errado";
consultaweb($post);

$post['id_departamento'] = '1';
$post['id_municipio'] = '2';
echo "** Municipio errado";
consultaweb($post);

$post['id_municipio'] = '1';
$post['id_clase'] = '2';
echo "** Clase errado";
consultaweb($post);

$post['id_clase'] = '1';
$post['nomvic'] = 'ape';
echo "** Nombre errado";
consultaweb($post);

$post['nomvic'] = 'nombre';
$post['ffin']['d'] = '06';
echo "** Dia final errado";
consultaweb($post);

$post['ffin']['d'] = '07';
$post['ffin']['M'] = '07';
echo "** Mes final errado";
consultaweb($post);

$post['ffin']['M'] = '08';
$post['ffin']['Y'] = '2006';
echo "** Año final errado";
consultaweb($post);

$post['ffin']['Y'] = '2007';
$post['fini']['d'] = '08';
echo "** Día inicial errado";
consultaweb($post);

$post['fini']['d'] = '07';
$post['fini']['M'] = '09';
echo "** Mes inicial errado";
consultaweb($post);

$post['fini']['M'] = '08';
$post['fini']['Y'] = '2008';
echo "** Año inicial errado";
consultaweb($post);

$post['fini']['Y'] = '2007';
$post['presponsable'] = '2';
echo "** P. Resp errado";
consultaweb($post);

/*$post['presponsable'] = '1';
$post['clasificacion']['0'] = 'T:1:0';
echo "** Clasificación errada";
consultaweb($post);
**/
$post['clasificacion']['0'] = 'T:1000:1000';
$post['ssocial'] = '2';
echo "** Sector Social errado";
consultaweb($post);

$post['ssocial'] = '1';
$post['usuario'] = '2';
echo "** Usuario errado";
consultaweb($post);

$post['usuario'] = '';
$post['ordenar'] = 'localizacion';
echo "** Ordenado por localización\n";
consultaweb($post);

$post['ordenar'] = 'codigo';
echo "** Ordenado por código\n";
consultaweb($post);

unset($post['retroalimentacion']);
echo "** Sin retroalimentación\n";
consultaweb($post);

unset($post['m_fuentes']);
echo "** Sin fuentes\n";
consultaweb($post);

unset($post['m_tipificacion']);
echo "** Sin tipificacion\n";
consultaweb($post);

unset($post['m_presponsables']);
echo "** Sin P. Responsables\n";
consultaweb($post);

unset($post['m_victimas']);
echo "** Sin Víctimas\n";
consultaweb($post);

unset($post['m_localizacion']);
echo "** Sin Localización\n";
consultaweb($post);

unset($post['caso_fecha']);
echo "** Sin Fecha\n";
consultaweb($post);

unset($post['caso_memo']);
echo "** Sin Memo\n";
consultaweb($post);

unset($post['caso_id']);
echo "** Sin Código\n";
consultaweb($post);


?>
