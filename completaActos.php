<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Completa categorias replicadas
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2010 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';
require_once "confv.php";
require_once "misc.php";
require_once "DataObjects/Categoria.php";

$aut_usuario = "";
$db = autenticaUsuario($dsn, $aut_usuario, 62);

$t = 'Completa actos ' . date('Y-m-d H:m');
encabezado_envia($t);
echo '<table width="100%">'
    . '<td style="white-space: nowrap; background-color: #CCCCCC;" '
    . 'align="left" valign="top" colspan="2"><b>'
    . '<div align=center>' . $t
    . '</div></b></td></table><p/>';
echo "Individuales<br>";
hace_consulta($db, "DROP VIEW replicadas", false, false);
hace_consulta(
    $db, "CREATE VIEW replicadas AS " .
    " SELECT id_presponsable, contadaen, id_persona, id_caso " .
    " FROM acto, categoria WHERE acto.id_categoria=categoria.id " .
    " AND categoria.contadaen IS NOT NULL ORDER BY id;"
);
$pres = " FROM replicadas WHERE (id_presponsable, contadaen, " .
    " id_persona, id_caso) NOT IN (select id_presponsable, " .
    " id_categoria, id_persona, id_caso from acto)";
$s = "SELECT COUNT(*) $pres";
$ni = $db->getOne($s);
sin_error_pear($ni);
echo " Se insertarán " . (int)$ni . " actos<br>";
hace_consulta(
    $db, "INSERT INTO acto (id_presponsable, id_categoria, " .
    " id_persona, id_caso) SELECT * $pres"
);
hace_consulta($db, "DROP VIEW replicadas");


echo "Colectivos<br>";
hace_consulta($db, "DROP VIEW replicadasc", false, false);
hace_consulta(
    $db, "CREATE VIEW replicadasc AS " .
    " SELECT id_presponsable, contadaen, id_grupoper, id_caso " .
    " FROM actocolectivo, categoria " .
    " WHERE actocolectivo.id_categoria=categoria.id " .
    " AND categoria.contadaen IS NOT NULL ORDER BY id;"
);
$pres = " FROM replicadasc " .
    " WHERE (id_presponsable, contadaen, id_grupoper, id_caso) " .
    " NOT IN (SELECT id_presponsable, id_categoria, id_grupoper, id_caso " .
    " FROM actocolectivo)";
$s = "SELECT COUNT(*) $pres ";
$ni = $db->getOne($s);
sin_error_pear($ni);
echo " Se insertarán " . (int)$ni . " actos colectivos<br>";
hace_consulta(
    $db, "INSERT INTO actocolectivo " .
    " (id_presponsable, id_categoria, id_grupoper, id_caso) SELECT * $pres"
);
hace_consulta($db, "DROP VIEW replicadasc");

echo '<table width="100%">' .
    '<td style="white-space: nowrap; background-color: #CCCCCC;" ' .
    'align="left" valign="top" colspan="2"><b>' .
    '<div align=right><a href="index.php">' .
    _('Men&uacute; Principal') . '</a>' .
    '</div></b></td></table>';
/* Completa casos de paramilitares en DIH en DH

 CREATE VIEW parasdh AS select id_presponsable, categoria.id, id_persona, id_caso
  FROM acto, categoria
  WHERE acto.id_categoria = categoria.contadaen and id_presponsable = '14';
  SELECT * from parasdh WHERE (id_presponsable, id, id_persona, id_caso)
  NOT IN (SELECT id_presponsable, id_categoria, id_persona, id_caso FROM acto);
  INSERT INTO acto (id_presponsable, id_categoria, id_persona, id_caso)
  SELECT * from parasdh WHERE (id_presponsable, id, id_persona, id_caso)
  NOT IN (SELECT id_presponsable, id_categoria, id_persona, id_caso FROM acto);
  DROP VIEW parasdh;

 CREATE VIEW parasdhc AS select id_presponsable, categoria.id, id_grupoper, id_caso
  FROM actocolectivo, categoria
  WHERE actocolectivo.id_categoria = categoria.contadaen
  AND id_presponsable = '14';
  SELECT * from parasdhc WHERE (id_presponsable, id, id_grupoper, id_caso)
  NOT IN (SELECT id_presponsable, id_categoria, id_grupoper, id_caso
  FROM actocolectivo
);
  INSERT INTO actocolectivo (id_presponsable, id_categoria, id_grupoper, id_caso)
  SELECT * from parasdhc WHERE (id_presponsable, id, id_grupoper, id_caso)
  NOT IN (SELECT id_presponsable, id_categoria, id_grupoper, id_caso
  FROM actocolectivo
);
  DROP VIEW parasdhc;

 */

    pie_envia();
?>
