<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
# coding: iso-8859-1
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
 * @version   CVS: $Id: valida.php,v 1.15.2.2 2011/10/18 16:17:58 vtamara Exp $
 * @link      http://sivel.sf.net
 */

require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "confv.php";
require_once "misc.php";

$aut_usuario = "";
$db = autenticaUsuario($dsn, $accno, $aut_usuario, 64);

echo '<table width="100%"><td style="white-space: ' .
    ' nowrap; background-color: #CCCCCC;" align="left" ' .
    ' valign="top" colspan="2"><b><div align=center>' ;
echo _("Reporte de consistencia del ") . date("Y-m-d H:m");
echo '</div></b></td></table>';
resValida(
    $db, _("Casos con memo vacio"),
    "SELECT id, fecha FROM caso WHERE memo='' ORDER BY fecha;"
);

resValida(
    $db, _("Casos con fecha de otras fuentes anterior a la del caso"),
    "SELECT caso.id, caso.fecha, fuente_directa.nombre
    FROM fuente_directa_caso, caso, fuente_directa
    WHERE fuente_directa_caso.id_caso = caso.id
    AND fuente_directa_caso.id_fuente_directa = fuente_directa.id
    AND fuente_directa_caso.fecha < caso.fecha order by fecha;"
);

resValida(
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
resValida(
    $db, _("Casos con fecha inicial de funcionario anterior o igual a la del caso"),
    "SELECT y.id, caso.fecha, y.min as fecha_funcionario, funcionario.nombre
    FROM y, caso, funcionario_caso, funcionario
    WHERE y.id = caso.id AND y.min <= caso.fecha
    AND funcionario_caso.id_caso = caso.id
    AND funcionario_caso.fecha_inicio = y.min
    AND funcionario_caso.id_funcionario = funcionario.id;"
);

resValida(
    $db, _("Casos sin funcionario"),
    "SELECT caso.id FROM caso
    WHERE caso.id NOT IN (SELECT id_caso FROM funcionario_caso);"
);

resValida(
    $db, _("Casos con más de una ubicación (salen duplicados en conteos)"),
    "SELECT id, c from (SELECT caso.id, count(ubicacion.id) AS c
    FROM caso, ubicacion WHERE caso.id = ubicacion.id_caso
    GROUP BY caso.id order by 2) AS f WHERE c >= 2"
);


resValida(
    $db, _("Víctimas con categorias que no son para víctimas individuales"),
    "SELECT acto.id_caso, acto.id_categoria, acto.id_persona,
    persona.nombres || ' ' || persona.apellidos
    FROM acto, persona
    WHERE persona.id = acto.id_persona AND id_categoria
    NOT IN (SELECT id FROM categoria WHERE tipocat = 'I');"
);


resValida(
    $db, 
    _("Víctimas colectivas con categorias que no son para víctimas colectivas"),
    "SELECT actocolectivo.id_caso, actocolectivo.id_categoria, grupoper.nombre
    FROM actocolectivo, grupoper
    WHERE grupoper.id = actocolectivo.id_grupoper
    AND id_categoria NOT IN (SELECT id FROM categoria WHERE tipocat = 'C');"
);


resValida(
    $db, _("Registros en victima_colectiva que no están en actocolectivo"),
    "SELECT id_caso, id_grupoper, grupoper.nombre
    FROM victima_colectiva, grupoper
    WHERE grupoper.id = victima_colectiva.id_grupoper
    AND id NOT IN (SELECT id_grupoper FROM actocolectivo)"
);



echo '<table width="100%">
    <td style = "white-space: nowrap; background-color: #CCCCCC;"
    align = "left" valign="top" colspan="2"><b><div align=right>
    <a href = "index.php">' . _('Menú Principal') . 
    '</a></div></b></td></table>';
?>
