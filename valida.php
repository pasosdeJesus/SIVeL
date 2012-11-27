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
$db = autentica_usuario($dsn, $aut_usuario, 64);

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
    "SELECT caso.id, caso.fecha, fotra.nombre
    FROM caso_fotra, caso, fotra
    WHERE caso_fotra.id_caso = caso.id
    AND caso_fotra.id_fotra = fotra.id
    AND caso_fotra.fecha < caso.fecha order by fecha;"
);

res_valida(
    $db, _("Casos con fecha de fuente frecuente anterior a la del caso"),
    "SELECT caso.id, caso.fecha, ffrecuente.nombre
    FROM caso_ffrecuente, ffrecuente, caso
    WHERE caso_ffrecuente.id_caso = caso.id 
    AND caso_ffrecuente.id_ffrecuente = ffrecuente.id
    AND caso_ffrecuente.fecha < caso.fecha
    ORDER BY fecha;"
);

hace_consulta($db, "DROP VIEW y", false, false);
hace_consulta(
    $db, "CREATE VIEW y AS SELECT caso.id, " .
    " min(caso_funcionario.fechainicio) " .
    " FROM caso_funcionario, caso " .
    " WHERE caso.id>'35000' AND caso.id=caso_funcionario.id_caso " .
    " GROUP BY caso.id order by caso.id"
);
res_valida(
    $db, _("Casos con fecha inicial de funcionario anterior o igual a la del caso"),
    "SELECT y.id, caso.fecha, y.min as fecha_funcionario, funcionario.nombre
    FROM y, caso, caso_funcionario, funcionario
    WHERE y.id = caso.id AND y.min <= caso.fecha
    AND caso_funcionario.id_caso = caso.id
    AND caso_funcionario.fechainicio = y.min
    AND caso_funcionario.id_funcionario = funcionario.id;"
);

res_valida(
    $db, _("Casos sin funcionario"),
    "SELECT caso.id FROM caso
    WHERE caso.id NOT IN (SELECT id_caso FROM caso_funcionario);"
);

res_valida(
    $db,
    _("Casos con más de una ubicaci&oacute;n (salen duplicados en conteos)"),
    "SELECT id, c from (SELECT caso.id, count(ubicacion.id) AS c
    FROM caso, ubicacion WHERE caso.id = ubicacion.id_caso
    GROUP BY caso.id order by 2) AS f WHERE c >= 2"
);


res_valida(
    $db, _("Víctimas con categorias que no son para víctimas individuales"),
    "SELECT acto.id_caso, acto.id_categoria, acto.id_persona,
    persona.nombres || ' ' || persona.apellidos
    FROM acto, persona
    WHERE persona.id = acto.id_persona AND id_categoria
    NOT IN (SELECT id FROM categoria WHERE tipocat = 'I');"
);


res_valida(
    $db,
    _("Víctimas colectivas con categorias que no son para víctimas colectivas"),
    "SELECT actocolectivo.id_caso, actocolectivo.id_categoria, grupoper.nombre
    FROM actocolectivo, grupoper
    WHERE grupoper.id = actocolectivo.id_grupoper
    AND id_categoria NOT IN (SELECT id FROM categoria WHERE tipocat = 'C');"
);


res_valida(
    $db, _("Registros en victimacolectiva que no est&aacute;n en actocolectivo"),
    "SELECT id_caso, id_grupoper, grupoper.nombre
    FROM victimacolectiva, grupoper
    WHERE grupoper.id = victimacolectiva.id_grupoper
    AND id NOT IN (SELECT id_grupoper FROM actocolectivo)"
);


echo '<table width="100%">
    <td style = "white-space: nowrap; background-color: #CCCCCC;"
    align = "left" valign="top" colspan="2"><b><div align=right>
    <a href = "index.php">' . _('Men&uacute; Principal') .
    '</a></div></b></td></table>';
pie_envia();
?>
