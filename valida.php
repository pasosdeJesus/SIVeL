<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8 :
/**
 * Realiza validaciones a datos de base
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2010 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías
 * @link      http://sivel.sf.net
 */

require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "confv.php";
require_once "misc.php";

$aut_usuario = "";
$db = autenticaUsuario($dsn, $aut_usuario, 64);

$t = _("Reporte de consistencia del") ." " . date("Y-m-d H:m");
encabezado_envia($t);
echo '<table width="100%"><td style="white-space: ' .
    ' nowrap; background-color: #CCCCCC;" align="left" ' .
    ' valign="top" colspan="2"><b><div align=center>' . $t;
echo '</div></b></td></table>';
res_valida(
    $db, _("Casos con memo vacio"),
    "SELECT id, fecha FROM caso WHERE memo='' ORDER BY fecha;"
);

res_valida(
    $db, _("Casos con fecha de otras fuentes anterior a la del caso"),
    "SELECT caso.id, caso.fecha, fuente_directa.nombre
    FROM fuente_directa_caso, caso, fuente_directa
    WHERE fuente_directa_caso.id_caso = caso.id
    AND fuente_directa_caso.id_fuente_directa = fuente_directa.id
    AND fuente_directa_caso.fecha < caso.fecha order by fecha;"
);

res_valida(
    $db, _("Casos con fecha de fuente frecuente anterior a la del caso"),
    "SELECT caso.id, caso.fecha, prensa.nombre
    FROM escrito_caso, prensa, caso
    WHERE escrito_caso.id_caso = caso.id AND escrito_caso.id_prensa = prensa.id
    AND escrito_caso.fecha < caso.fecha
    ORDER BY fecha;"
);

hace_consulta($db, "DROP VIEW y", false, false);
hace_consulta(
    $db, "CREATE VIEW y AS SELECT caso.id, " .
    " min(funcionario_caso.fecha_inicio) " .
    " FROM funcionario_caso, caso " .
    " WHERE caso.id>'35000' AND caso.id=funcionario_caso.id_caso " .
    " GROUP BY caso.id order by caso.id"
);
res_valida(
    $db, _("Casos con fecha inicial de funcionario anterior o igual a la del caso"),
    "SELECT y.id, caso.fecha, y.min as fecha_funcionario, funcionario.nombre
    FROM y, caso, funcionario_caso, funcionario
    WHERE y.id = caso.id AND y.min <= caso.fecha
    AND funcionario_caso.id_caso = caso.id
    AND funcionario_caso.fecha_inicio = y.min
    AND funcionario_caso.id_funcionario = funcionario.id;"
);

res_valida(
    $db, _("Casos sin funcionario"),
    "SELECT caso.id FROM caso
    WHERE caso.id NOT IN (SELECT id_caso FROM funcionario_caso);"
);

res_valida(
    $db, _("Casos con m&aacute;s de una ubicaci&oacute;n (salen duplicados en conteos)"),
    "SELECT id, c from (SELECT caso.id, count(ubicacion.id) AS c
    FROM caso, ubicacion WHERE caso.id = ubicacion.id_caso
    GROUP BY caso.id order by 2) AS f WHERE c >= 2"
);


res_valida(
    $db, _("V&iacute;ctimas con categorias que no son para v&iacute;ctimas individuales"),
    "SELECT acto.id_caso, acto.id_categoria, acto.id_persona,
    persona.nombres || ' ' || persona.apellidos
    FROM acto, persona
    WHERE persona.id = acto.id_persona AND id_categoria
    NOT IN (SELECT id FROM categoria WHERE tipocat = 'I');"
);


res_valida(
    $db,
    _("V&iacute;ctimas colectivas con categorias que no son para v&iacute;ctimas colectivas"),
    "SELECT actocolectivo.id_caso, actocolectivo.id_categoria, grupoper.nombre
    FROM actocolectivo, grupoper
    WHERE grupoper.id = actocolectivo.id_grupoper
    AND id_categoria NOT IN (SELECT id FROM categoria WHERE tipocat = 'C');"
);


res_valida(
    $db, _("Registros en victima_colectiva que no est&aacute;n en actocolectivo"),
    "SELECT id_caso, id_grupoper, grupoper.nombre
    FROM victima_colectiva, grupoper
    WHERE grupoper.id = victima_colectiva.id_grupoper
    AND id NOT IN (SELECT id_grupoper FROM actocolectivo)"
);


echo '<table width="100%">
    <td style = "white-space: nowrap; background-color: #CCCCCC;"
    align = "left" valign="top" colspan="2"><b><div align=right>
    <a href = "index.php">' . _('Men&uacute; Principal') .
    '</a></div></b></td></table>';
pie_envia();
?>
