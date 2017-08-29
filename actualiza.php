<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
* Actualiza base de datos después de actualizar fuentes
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

/** Actualiza base de datos después de actualizar fuentes */
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';

$aut_usuario = "";
$db = autentica_usuario($dsn, $aut_usuario, 21);

require_once $_SESSION['dirsitio'] . '/conf_int.php';
require_once "confv.php";
require_once "misc.php";
require_once "DataObjects/Categoria.php";
require_once "misc_actualiza.php";

if (!in_array(63, $_SESSION['opciones'])) {
    hace_consulta(
        $db, "INSERT INTO opcion VALUES ('63', 'Actualizar', '60', " .
        "'actualiza')", false, false
    );
    hace_consulta(
        $db, "INSERT INTO opcion_rol (id_opcion, id_rol) " .
        "VALUES (63, 1)", false, false
    );
    include "terminar.php";
    die("");
}
$db = autentica_usuario($dsn, $aut_usuario, 63);

/**
 * Regenera esquemas para base de datos base.ini y base.links.in
 * sacando informacion de los archivos estructura.ini y estructura.links.ini
 * de SIVeL basico y de los modulos.
 *
 * @return void
 */
function regenera_esquemas()
{
    global $dirserv, $dirsitio, $dbnombre, $dirchroot, $modulos;
    $nini = "$dirserv/$dirsitio/DataObjects/$dbnombre.ini";
    $nlinksini = "$dirserv/$dirsitio/DataObjects/$dbnombre.links.ini";
    $nestini = "$dirserv/$dirsitio/DataObjects/estructura-dataobject.ini";
    if (is_writable($nini) && is_writable($nlinksini)) {
        lee_escritura($dirserv, $dbnombre, "$dirserv/$dirsitio", "w");
        foreach (explode(" ", $modulos) as $i) {
            lee_escritura("$dirserv/$i", $dbnombre, "$dirserv/$dirsitio", "a");
        }
        if (file_exists($nestini)) {
            lee_escritura(
                "$dirserv/$dirsitio", $dbnombre, "$dirserv/$dirsitio", "a"
            );
        }
        echo_esc(
            "Regenerados $dirserv/$dirsitio/DataObjects/$dbnombre.ini y "
            . "$dirserv/$dirsitio/DataObjects/$dbnombre.ini "
        );
        echo "<br><font color='#FF9999'>Se sugiere quitar "
            . "permiso de escritura desde el servidor web a estos archivos.</font> "
            . "Desde la línea de comandos intente el siguiente comando y vuelva "
            . " a cargar esta página: <br><tt>";
        echo_esc(
            "  doas chmod a-w $dirchroot/$dirserv/$dirsitio/DataObjects/$dbnombre.*"
        );
        echo "</tt>";
    } else {
        echo_esc(
            "No se regenerará esquema, rengerelo manualmente o de permiso "
            . " de escritura a "
            . "$dirchroot/$dirserv/$dirsitio/DataObjects/$dbnombre.ini y "
            . "$dirchroot/$dirserv/$dirsitio/DataObjects/$dbnombre.links.ini "
            . "Puede ser desde una línea de comandos con:"
        );
        echo "<tt>";
        echo_esc(
            "  doas chown www:www "
            . "$dirchroot/$dirserv/$dirsitio/DataObjects/$dbnombre.*"
        );
        echo_esc(
            "  doas chmod u+w "
            . "$dirchroot/$dirserv/$dirsitio/DataObjects/$dbnombre.*"
        );
        echo "</tt>";
    }
}

encabezado_envia(_('Actualizando'));
echo '<table width="100%"><td style="white-space: nowrap; '
    . 'background-color: #CCCCCC;" align="left" valign="top" colspan="2">'
    . '<b><div align=center>' . _('Actualizando') . '</div></b></td></table>';
$r = $db->getOne('SELECT COUNT(*) FROM actualizacionbase');
if (PEAR::isError($r)) {
    $r = $db->query(
        'ALTER TABLE actualizacion_base RENAME TO actualizacionbase'
    );
    regenera_esquemas();
}


echo "Preactualizando sitio<br>";
$ap = $_SESSION['dirsitio'] . '/preactualiza.php';
if (file_exists($ap)) {
    echo "Preactualizando personalización<br>";
    include_once "$ap";
}

$act = objeto_tabla('Actualizacionbase');
if (PEAR::isError($act)) {
    die("No pudo act");
}

$idact = '0.92post';
if (!aplicado($idact)) {
    hace_consulta(
        $db, "ALTER TABLE categoria ADD COLUMN contada_en INTEGER " .
        " REFERENCES categoria"
    );
    $cat = objeto_tabla('categoria');
    // EJECUCIÓN EXTRAJUDICIAL
    $cat = DataObjects_Categoria::staticGet('10'); $cat->contada_en='701';
    $cat->update();
    // AMENAZA
    $cat = DataObjects_Categoria::staticGet('15'); $cat->contada_en='73';
    $cat->update();
    // COLECTIVO AMENAZADO
    $cat = DataObjects_Categoria::staticGet('18'); $cat->contada_en='706';
    $cat->update();
    // TORTURA
    $cat = DataObjects_Categoria::staticGet('12'); $cat->contada_en='72';
    $cat->update();
    // HERIDO
    $cat = DataObjects_Categoria::staticGet('13'); $cat->contada_en='702';
    $cat->update();
    // VIOLENCIA SEXUAL
    $cat = DataObjects_Categoria::staticGet('19'); $cat->contada_en='77';
    $cat->update();
    // COLECTIVO DESPLAZADO
    $cat = DataObjects_Categoria::staticGet('102'); $cat->contada_en='903';
    $cat->update();

    aplicaact($act, $idac, 'Categorias repetidas marcadas');
}

$idact = '0.94';
if (!aplicado($idact)) {
    hace_consulta(
        $db, "UPDATE opcion " .
        " SET descripcion='Conteos V. Individuales' " .
        " WHERE id_opcion='51';"
    );
    hace_consulta(
        $db, "UPDATE opcion " .
        " SET descripcion='Conteos V. Combatientes' " .
        " WHERE id_opcion='52';"
    );
    hace_consulta(
        $db, "UPDATE opcion " .
        " SET descripcion='Menú Conteos' " .
        " WHERE id_opcion='50';"
    );
    aplicaact($act, $idac, 'Conteos');
}

$idact = '1.0';
if (!aplicado($idact)) {
    hace_consulta(
        $db, "ALTER TABLE victima " .
        " ADD COLUMN anotaciones VARCHAR(1000)"
    );
    aplicaact($act, $idac, 'Anotaciones en víctima');
}

$idact = '1.0';
if (!aplicado($idact)) {

    // Consistencia en combatiente

    $nr = $db->getOne(
        "SELECT COUNT(id) FROM combatiente " .
        "WHERE id_resultado_agresion IS NULL"
    );
    if (PEAR::isError($nr)) {
        die($nr->getMessage() . " - " . $nr->getUserInfo());
    }
    if ($nr > 0) {
        echo "<font color='red'>Antes de aplicar la actualización " .
        "debe definir 'Resultados " .
        "de Agresión' no especificados contra " . (int)$nr .
        "combatientes de los siguientes casos: <br>";
        $r = hace_consulta(
            $db, "SELECT DISTINCT id_caso " .
            "FROM combatiente WHERE id_resultado_agresion IS NULL"
        );
        $row = array();
        $sep = "";
        while ($r->fetchInto($row)) {
            echo htmlentities($sep . $row[0], ENT_COMPAT, 'UTF-8');
            $sep = ", ";
        }
        echo "</font>";
        die("");
    }

    hace_consulta(
        $db, "ALTER TABLE combatiente ALTER " .
        "COLUMN id_resultado_agresion SET NOT NULL"
    );

    // Consistencia en demográficas, no sabe y otros = SIN INFORMACIÓN
    hace_consulta(
        $db, "UPDATE victima SET id_rango_edad='6' " .
        "WHERE id_rango_edad IS NULL"
    );
    hace_consulta($db, "ALTER TABLE victima ALTER id_rango_edad SET NOT NULL");
    hace_consulta(
        $db, "UPDATE victima SET id_profesion='22' " .
        "WHERE id_profesion IS NULL"
    );
    hace_consulta($db, "ALTER TABLE victima ALTER id_profesion SET NOT NULL");
    hace_consulta(
        $db, "UPDATE victima SET id_filiacion='10' " .
        "WHERE id_filiacion IS NULL"
    );
    hace_consulta($db, "ALTER TABLE victima ALTER id_filiacion SET NOT NULL");
    hace_consulta(
        $db, "UPDATE victima SET id_sector_social='15' " .
        "WHERE id_sector_social IS NULL"
    );
    hace_consulta($db, "ALTER TABLE victima ALTER id_sector_social SET NOT NULL");
    hace_consulta(
        $db, "UPDATE victima SET id_organizacion='16' " .
        "WHERE id_organizacion IS NULL"
    );
    hace_consulta($db, "ALTER TABLE victima ALTER id_organizacion SET NOT NULL");
    hace_consulta(
        $db, "UPDATE victima SET id_vinculo_estado='38' " .
        "WHERE id_vinculo_estado IS NULL"
    );
    hace_consulta($db, "ALTER TABLE victima ALTER id_vinculo_estado SET NOT NULL");
    hace_consulta(
        $db, "UPDATE victima SET id_organizacion_armada='35' " .
        "WHERE id_organizacion_armada IS NULL"
    );
    hace_consulta(
        $db, "ALTER TABLE victima ALTER id_organizacion_armada " .
        "SET NOT NULL"
    );


    // Nuevas categorias en marco NyN

    hace_consulta(
        $db, "UPDATE parametros_reporte_consolidado " .
        "SET rotulo = 'RECLUTAMIENTO DE MENORES' WHERE no_columna='20'"
    );
    hace_consulta(
        $db, "UPDATE parametros_reporte_consolidado " .
        "SET rotulo = 'TOMA DE REHENES' WHERE no_columna='21'"
    );
    hace_consulta(
        $db, "INSERT INTO parametros_reporte_consolidado " .
        "(no_columna, rotulo, tipo_violencia, clasificacion) VALUES " .
        "(25, 'COLECTIVO CONFINADO', 'DIH', 'INTEGRIDAD')", false
    );

    $preins = "INSERT INTO categoria (id, fecha_creacion, " .
        " fecha_deshabilitacion, id_supracategoria, id_tipo_violencia, " .
        " col_rep_consolidado, nombre) VALUES ";
    hace_consulta(
        $db, $preins .
        "(196, '2008-10-20', NULL, 1, 'A', NULL, 'V.S. - ABUSO SEXUAL')",
        false
    );
    hace_consulta(
        $db, $preins .
        "(291, '2008-10-20', NULL, 2, 'A', 8, 'V.S. - VIOLACIÓN')",
        false
    );
    hace_consulta(
        $db, $preins .
        "(292, '2008-10-20', NULL, 2, 'A', 8, 'V.S. - EMBARAZO FORZADO')",
        false
    );
    hace_consulta(
        $db, $preins .
        "(293, '2008-10-20', NULL, 2, 'A', 8, 'V.S. - PROSTITUCIÓN FORZADA')",
        false
    );
    hace_consulta(
        $db, $preins .  "(294, '2008-10-20', NULL, 2, " .
        "'A', 8, 'V.S. - ESTERILIZACIÓN FORZADA')", false
    );
    hace_consulta(
        $db, $preins .  "(295, '2008-10-20', NULL, 2, " .
        "'A', 8, 'V.S. - ESCLAVITUD SEXUAL')", false
    );
    hace_consulta(
        $db, $preins .
        "(296, '2008-10-20', NULL, 2, 'A', NULL, 'V.S. - ABUSO SEXUAL')", false
    );
    hace_consulta(
        $db, $preins .
        "(391, '2008-10-20', NULL, 3, 'A', 8, 'V.S. - VIOLACIÓN')", false
    );
    hace_consulta(
        $db, $preins .
        "(392, '2008-10-20', NULL, 3, 'A', 8, 'V.S. - EMBARAZO FORZADO')", false
    );
    hace_consulta(
        $db, $preins .
        "(393, '2008-10-20', NULL, 3, 'A', 8, 'V.S. - PROSTITUCIÓN FORZADA')",
        false
    );
    hace_consulta(
        $db, $preins .
        "(394, '2008-10-20', NULL, 3, 'A', 8, 'V.S. - ESTERILIZACIÓN FORZADA')",
        false
    );
    hace_consulta(
        $db, $preins .
        "(395, '2008-10-20', NULL, 3, 'A', 8, 'V.S. - ESCLAVITUD SEXUAL')",
        false
    );
    hace_consulta(
        $db, $preins .
        "(771, '2008-10-20', NULL, 1, 'D', 12, 'VIOLACIÓN')", false
    );
    hace_consulta(
        $db, $preins .
        "(772, '2008-10-20', NULL, 1, 'D', 12, 'EMBARAZO FORZADO')", false
    );
    hace_consulta(
        $db, $preins .
        "(773, '2008-10-20', NULL, 1, 'D', 12, 'PROSTITUCIÓN FORZADA')", false
    );
    hace_consulta(
        $db, $preins .
        "(774, '2008-10-20', NULL, 1, 'D', 12, 'ESTERILIZACIÓN FORZADA')", false
    );
    hace_consulta(
        $db, $preins .
        "(775, '2008-10-20', NULL, 1, 'D', 12, 'ESCLAVITUD SEXUAL')", false
    );
    hace_consulta(
        $db, $preins .
        "(776, '2008-10-20', NULL, 1, 'D', 12, 'ABUSO SEXUAL')", false
    );
    hace_consulta(
        $db, $preins .
        "(191, '2008-10-20', NULL, 1, 'A', 8, 'V.S. - VIOLACIÓN')", false
    );
    hace_consulta(
        $db, $preins .
        "(192, '2008-10-20', NULL, 1, 'A', 8, 'V.S. - EMBARAZO FORZADO');",
        false
    );
    hace_consulta(
        $db, $preins .
        "(193, '2008-10-20', NULL, 1, 'A', 8, 'V.S. - PROSTITUCIÓN FORZADA')",
        false
    );
    hace_consulta(
        $db, $preins .
        "(194, '2008-10-20', NULL, 1, 'A', 8, 'V.S. - ESTERILIZACIÓN FORZADA')",
        false
    );
    hace_consulta(
        $db, $preins .
        "(195, '2008-10-20', NULL, 1, 'A', 8, 'V.S. - ESCLAVITUD SEXUAL')",
        false
    );
    hace_consulta(
        $db, $preins .
        "(906, '2008-10-21', NULL, 1, 'D', NULL, 'CONFINAMIENTO')",
        false
    );
    hace_consulta(
        $db, $preins .
        "(104, '2008-10-17', NULL, 1, 'A', 25, 'COLECTIVO CONFINADO')",
        false
    );


    aplicaact($act, $idac, 'Consistencia demográficos, nuevas categorías');
}


$idact = '1.0f';
if (!aplicado($idact)) {

    hace_consulta(
        $db, "INSERT INTO prensa " .
        " VALUES (0,'SIN INFORMACIÓN','Indirecta');", false
    );

    aplicaact(
        $act, $idac, 'SIN INFORMACIÓN en Fuente Frecuente '
        . 'para permitir consulta externa'
    );
}

$idact = '1.0g';
if (!aplicado($idact)) {

    hace_consulta($db, "ALTER TABLE usuario ADD COLUMN npass VARCHAR(64);");
    hace_consulta($db, "UPDATE usuario SET npass=password;");
    hace_consulta($db, "ALTER TABLE usuario DROP COLUMN password;");
    hace_consulta($db, "ALTER TABLE usuario RENAME COLUMN npass TO password;");

    hace_consulta($db, "ALTER TABLE funcionario ADD UNIQUE(nombre);", false);

    aplicaact($act, $idac, 'Condensado de clave es sha1 en lugar de md5');
}


$idac = '1.1a1-sld';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "SELECT id_caso, id_departamento " .
        " FROM departamento_caso " .
        " WHERE id_caso NOT IN (SELECT id_caso FROM ubicacion_caso,ubicacion " .
        " WHERE ubicacion_caso.id_ubicacion=ubicacion.id " .
        " AND ubicacion.id_departamento=departamento_caso.id_departamento);",
        true
    );
    $ndep = 0;
    $row = array();
    $herr = false;
    $lc = $sep = '';
    while ($r->fetchInto($row)) {
        $nubi = (int)$db->getOne('SELECT nextval(\'ubicacion_seq\')');
        $ri = hace_consulta(
            $db, "INSERT INTO ubicacion (id, lugar, sitio, " .
            " id_clase, id_municipio, id_departamento) " .
            " VALUES ('$nubi', '', '', " .
            " NULL, NULL, ${row[1]})", false
        );
        $herr = $herr || PEAR::isError($ri);
        $ri = hace_consulta(
            $db, "INSERT INTO ubicacion_caso " .
            "(id_caso, id_ubicacion) " .
            " VALUES (${row[0]}, $nubi) ", false
        );
        $herr = $herr || PEAR::isError($ri);
        $ri = hace_consulta(
            $db, "DELETE FROM departamento_caso " .
            " WHERE id_caso = '${row[0]}' " .
            " AND id_departamento = '${row[1]}' ", false
        );
        $herr = $herr || PEAR::isError($ri);
        $lc .= $sep . $row[0];
        $sep = ",";
        $ndep++;
    }
    if (!$herr) {
        echo_esc("Como ubicaciones insertados $ndep deptos de casos $lc.");
        hace_consulta($db, "DROP VIEW cons2", false, false);
        hace_consulta($db, "DROP VIEW vestcomb", false, false);
        hace_consulta($db, "DROP VIEW comb", false, false);
        hace_consulta($db, "DROP TABLE departamento_caso");
        aplicaact($act, $idac, 'Sin departamento_caso');
    }
}

$idac = '1.1a1-slm';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "SELECT id_caso, id_departamento, id_municipio " .
        " FROM municipio_caso " .
        " WHERE id_caso NOT IN (SELECT id_caso FROM ubicacion_caso,ubicacion " .
        " WHERE ubicacion_caso.id_ubicacion=ubicacion.id " .
        " AND ubicacion.id_departamento=municipio_caso.id_departamento  " .
        " AND ubicacion.id_municipio=municipio_caso.id_municipio);",
        true
    );
    $nu = 0;
    $row = array();
    $herr = false;
    $lc = $sep = '';
    while ($r->fetchInto($row)) {
        $nubi = (int)$db->getOne('SELECT nextval(\'ubicacion_seq\')');
        $ri = hace_consulta(
            $db, "INSERT INTO ubicacion (id, lugar, sitio, " .
            " id_clase, id_municipio, id_departamento) " .
            " VALUES ('$nubi', '', '', NULL, ${row[2]}, ${row[1]})", false
        );
        $herr = $herr || PEAR::isError($ri);
        $ri = hace_consulta(
            $db, "INSERT INTO ubicacion_caso " .
            "(id_caso, id_ubicacion) " .
            " VALUES (${row[0]}, $nubi) ", false
        );
        $herr = $herr || PEAR::isError($ri);
        $ri = hace_consulta(
            $db, "DELETE FROM municipio_caso " .
            " WHERE id_caso = '${row[0]}' AND id_departamento = '${row[1]}' " .
            " AND id_municipio = '${row[2]}'", false
        );
        $herr = $herr || PEAR::isError($ri);
        $lc .= $sep . $row[0];
        $sep = ",";
        $nu++;
    }
    if (!$herr) {
        echo_esc(
            "Como ubicaciones insertados $nu municipios de casos $lc."
        );
        hace_consulta($db, "DROP VIEW cons2", false, false);
        hace_consulta($db, "DROP TABLE municipio_caso");
        aplicaact($act, $idac, 'Sin municipio_caso');
    }
}

$idac = '1.1a1-slc';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "SELECT id_caso, id_departamento, id_municipio, " .
        " id_clase FROM clase_caso " .
        " WHERE id_caso NOT IN (SELECT id_caso FROM ubicacion_caso,ubicacion " .
        " WHERE ubicacion_caso.id_ubicacion=ubicacion.id " .
        " AND ubicacion.id_departamento=clase_caso.id_departamento  " .
        " AND ubicacion.id_municipio=clase_caso.id_municipio " .
        " AND ubicacion.id_clase=clase_caso.id_clase);",
        true
    );
    $nu = 0;
    $row = array();
    $herr = false;
    $lc = $sep = '';
    while ($r->fetchInto($row)) {
        $nubi = (int)$db->getOne('SELECT nextval(\'ubicacion_seq\')');
        $ri = hace_consulta(
            $db, "INSERT INTO ubicacion (id, lugar, sitio, " .
            " id_clase, id_municipio, id_departamento) " .
            " VALUES ('$nubi', '', '', " .
            " ${row[3]}, ${row[2]}, ${row[1]})", false
        );
        $herr = $herr || PEAR::isError($ri);
        $ri = hace_consulta(
            $db, "INSERT INTO ubicacion_caso " .
            "(id_caso, id_ubicacion) " .
            " VALUES (${row[0]}, $nubi) ", false
        );
        $herr = $herr || PEAR::isError($ri);
        $lc .= $sep . $row[0];
        $sep = ",";
        $nu++;
    }
    if (!$herr) {
        echo_esc("Como ubicaciones insertados $nu centros de casos $lc.");
        hace_consulta($db, "DROP VIEW cons2", false, false);
        hace_consulta($db, "DROP TABLE clase_caso");
        aplicaact($act, $idac, 'Sin clase_caso');
    }
}


$idac = '1.1a1-ubi';
if (!aplicado($idac)) {

    hace_consulta($db, "CREATE SEQUENCE tipo_sitio_seq", false);
    hace_consulta(
        $db, "CREATE TABLE tipo_sitio (" .
        " id INTEGER PRIMARY KEY DEFAULT(nextval('tipo_sitio_seq')), " .
        " nombre VARCHAR(50) NOT NULL " .
        ")", false
    );
    hace_consulta(
        $db, "INSERT INTO tipo_sitio VALUES (1, 'SIN INFORMACIÓN')",
        false
    );
    hace_consulta($db, "INSERT INTO tipo_sitio VALUES (2, 'URBANO')", false);
    hace_consulta($db, "INSERT INTO tipo_sitio VALUES (3, 'RURAL')", false);
    hace_consulta(
        $db, "INSERT INTO tipo_sitio VALUES (4, 'URBANO Y RURAL')",
        false
    );

    hace_consulta(
        $db, "ALTER TABLE ubicacion ADD " .
        "COLUMN id_tipo_sitio INTEGER REFERENCES tipo_sitio NOT NULL DEFAULT 1",
        false
    );

    foreach (array('S' => 1, 'U' => 2, 'R' => 3, 'A' => 4) as $a => $n) {
        hace_consulta(
            $db, "UPDATE ubicacion SET id_tipo_sitio='$n' " .
            "FROM caso, ubicacion_caso WHERE " .
            "ubicacion.id=ubicacion_caso.id_ubicacion AND " .
            "ubicacion_caso.id_caso=caso.id AND " .
            "caso.tipo_ubicacion='$a';", false
        );
    }

    hace_consulta(
        $db, "ALTER TABLE caso DROP " .
        "COLUMN tipo_ubicacion", false
    );
    aplicaact($act, $idac, 'Tipo de ubicación en ubicación');
}



$idac = '1.1a1-uc';
if (!aplicado($idac) && aplicado('1.1a1-slc') && aplicado('1.1a1-slm')
    && aplicado('1.1a1-sld')
) {

    // Replica en ubicacion cuando hay una misma ubicacion en varios casos
    hace_consulta($db, "DROP TABLE ubicacion2", false, false);
    $q = "CREATE TABLE ubicacion2 (" .
        "id INTEGER PRIMARY KEY DEFAULT (nextval('ubicacion_seq')), " .
        "lugar VARCHAR(260), " .
        "sitio VARCHAR(260), " .
        "id_clase INTEGER, " .
        "id_municipio INTEGER, " .
        "id_departamento INTEGER REFERENCES departamento, " .
        "id_tipo_sitio INTEGER REFERENCES tipo_sitio NOT NULL, " .
        "id_caso INTEGER NOT NULL REFERENCES caso, " .
        "latitud FLOAT, " .
        "longitud FLOAT, " .
        "FOREIGN KEY (id_municipio, id_departamento) REFERENCES " .
        "        municipio (id, id_departamento), " .
        "FOREIGN KEY (id_clase, id_municipio, id_departamento) REFERENCES " .
        "        clase (id, id_municipio, id_departamento) " .
        ")";
    //echo $q;
    hace_consulta($db, $q);
    hace_consulta(
        $db, "INSERT INTO ubicacion2 (id_caso, lugar, sitio, " .
        "id_clase, id_municipio, id_departamento, id_tipo_sitio) " .
        "SELECT ubicacion_caso.id_caso, lugar, sitio, " .
        "id_clase, id_municipio, id_departamento, id_tipo_sitio  " .
        "FROM ubicacion, ubicacion_caso " .
        "WHERE ubicacion.id=ubicacion_caso.id_ubicacion "
    );
    $n = (int)$db->getOne(
        "SELECT COUNT(*) FROM ubicacion WHERE " .
        "id_caso IS NULL"
    );
    if ($n > 0) {
        echo_esc("  Eliminando $n ubicaciones no asociadas a caso alguno");
        echo "<br>";
    }
    hace_consulta($db, "DROP TABLE ubicacion_caso CASCADE");
    hace_consulta($db, "DROP TABLE ubicacion CASCADE");
    hace_consulta($db, "ALTER TABLE ubicacion2 RENAME TO ubicacion");

    aplicaact($act, $idac, 'Sin ubicacion_caso y con latitud, longitud');
}

$idac = '1.1a1-per';
if (!aplicado($idac)) {
    $r = hace_consulta($db, "DROP VIEW cons;", false, false);
    $r = hace_consulta($db, "DROP VIEW t1;", false, false);
    $r = hace_consulta($db, "DROP VIEW t2;", false, false);
    $r = hace_consulta($db, "CREATE SEQUENCE persona_seq;", false);
    $r = hace_consulta(
        $db, "CREATE TABLE persona (
        id INTEGER PRIMARY KEY DEFAULT(nextval('persona_seq')),
        nombres VARCHAR(100) NOT NULL,
        apellidos VARCHAR(100) NOT NULL,
        anionac         INTEGER,
        mesnac          INTEGER CHECK (mesnac IS NULL OR
            (mesnac>='1' AND mesnac <='12')),
        dianac          INTEGER CHECK (dianac IS NULL
            OR (dianac>='1' AND (((mesnac='1' OR mesnac='3' OR mesnac='5'
            OR mesnac = '7' OR mesnac='8' OR mesnac='10' OR mesnac='12')
            AND dianac<='31')) OR ((mesnac='4' OR mesnac='6' OR mesnac='9'
            OR mesnac = '11') AND dianac<='30')
            OR (mesnac = '2' AND dianac<='29'))),
        sexo CHAR(1) NOT NULL CHECK (sexo = 'S' OR sexo='F' OR sexo='M'),
        id_departamento INTEGER REFERENCES departamento ON DELETE CASCADE,
        id_municipio    INTEGER,
        id_clase        INTEGER,
        tipo_documento VARCHAR(2),
        numero_documento VARCHAR(50)
        );", false
    );
    $r = hace_consulta(
        $db, "CREATE TABLE tipo_relacion (
            id      CHAR(2) PRIMARY KEY,
            nombre VARCHAR(50) NOT NULL,
            dirigido BOOLEAN NOT NULL,
            observaciones VARCHAR(200)
        );", false
    );
    $r = hace_consulta(
        $db, "CREATE TABLE relacion_personas (
        id_persona1 INTEGER NOT NULL REFERENCES persona,
        id_persona2 INTEGER NOT NULL REFERENCES persona,
        id_tipo CHAR(2) NOT NULL REFERENCES tipo_relacion,
        observaciones VARCHAR(200),
        PRIMARY KEY(id_persona1, id_persona2, id_tipo)
        );", false
    );

    $r = hace_consulta(
        $db, "INSERT INTO persona "
        . " (id, nombres, apellidos, anionac, sexo) "
        . " (SELECT victima.id, nombre, '', "
        . " extract(year from caso.fecha) - edad, sexo "
        . " FROM caso, victima WHERE caso.id=victima.id_caso);", false
    );

    $r = hace_consulta(
        $db, "ALTER TABLE victima ADD COLUMN id_persona INTEGER "
        . " REFERENCES persona", false
    );
    $r = hace_consulta($db, "UPDATE victima SET id_persona=id", false);
    foreach (array(
        'categoria_personal'  => 'id_tipo_violencia, id_supracategoria, '
            . 'id_categoria, id_persona, id_caso',
        'p_responsable_agrede_persona' => 'id_p_responsable, id_persona, '
            . 'id_caso',
        'antecedente_victima' => 'id_antecedente, id_persona, id_caso'
    ) as
        $tabla => $llave
    ) {
        $r = hace_consulta(
            $db, "ALTER TABLE $tabla ADD COLUMN id_persona "
            . " INTEGER REFERENCES persona", false
        );
        $r = hace_consulta(
            $db, "ALTER TABLE $tabla ADD COLUMN id_caso "
            . " INTEGER REFERENCES caso", false
        );
        $r = hace_consulta(
            $db, "UPDATE $tabla SET id_persona=victima.id, "
            . "id_caso=victima.id_caso "
            . " FROM victima WHERE id_victima=victima.id", false
        );
        $r = hace_consulta(
            $db, "ALTER TABLE $tabla DROP COLUMN id_victima",
            false
        );
        $r = hace_consulta(
            $db, "ALTER TABLE $tabla ADD PRIMARY KEY ($llave)",
            false
        );
        $r = hace_consulta(
            $db, "ALTER TABLE $tabla ALTER COLUMN id_persona "
            . " SET NOT NULL", false
        );
        $r = hace_consulta(
            $db, "ALTER TABLE $tabla ALTER COLUMN id_caso SET NOT NULL", false
        );
    }
    $r = hace_consulta($db, "ALTER TABLE victima DROP COLUMN id", false);
    $r = hace_consulta(
        $db, "ALTER TABLE victima ADD "
        . " PRIMARY KEY (id_persona, id_caso)", false
    );
    $r = hace_consulta($db, "ALTER TABLE victima DROP COLUMN nombre", false);
    $r = hace_consulta($db, "ALTER TABLE victima DROP COLUMN sexo", false);
    $r = hace_consulta($db, "ALTER TABLE victima DROP COLUMN edad", false);
    foreach (array('categoria_personal', 'p_responsable_agrede_persona',
        'antecedente_victima') as $tabla
    ) {
        $r = hace_consulta(
            $db, "ALTER TABLE $tabla ADD "
            . " FOREIGN KEY (id_persona, id_caso) "
            . " REFERENCES victima(id_persona, id_caso)", false
        );
    }
    $r = hace_consulta($db, "DROP SEQUENCE victima_seq", false);
    aplicaact($act, $idac, 'Persona');
}

$idac = '1.1a1-col';
if (!aplicado($idac)) {
    $r = hace_consulta($db, "DROP VIEW cons;", false, false);
    $grave = false;
    $np = $db->getOne(
        "SELECT COUNT(*) FROM victima_colectiva "
        . " WHERE id NOT IN "
        . " (SELECT id_v_colectiva FROM victima_colectiva_caso)"
    );
    if (PEAR::isError($np)) {
        die($np->getMessage() . " - " . $np->getUserInfo());
    }
    if ((int)$np > 0) {
        echo_esc(
            "Hay $np registros en victima_colectiva que no están en "
            . " victima_colectiva_caso. Resolver primero"
        );
        $grave = true;
    }
    foreach (array('categoria_comunidad', 'p_responsable_agrede_comunidad',
    ) as $tabla
    ) {
        $np = $db->getOne(
            "SELECT COUNT(*) FROM $tabla "
            . " WHERE  (id_v_colectiva, id_caso) NOT IN "
            . " (SELECT id_v_colectiva, id_caso FROM victima_colectiva_caso)"
        );
        if (PEAR::isError($np)) {
            die($np->getMessage() . " - " . $np->getUserInfo());
        }
        if ((int)$np > 0) {
            echo_esc(
                "Hay $np registros en $tabla que no están en "
                . " victima_colectiva_caso"
            );
            $grave = true;
        }
    }
    if ($grave) {
        die("Solucionar primero");
    }

    $r = hace_consulta($db, "CREATE SEQUENCE grupoper_seq;", false);
    $r = hace_consulta(
        $db, "CREATE TABLE grupoper (
        id INTEGER PRIMARY KEY DEFAULT(nextval('grupoper_seq')),
        nombre VARCHAR(150) NOT NULL,
        anotaciones VARCHAR(1000)
    );", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO grupoper (id, nombre, anotaciones)
        (SELECT victima_colectiva.id, nombre, anotacion
        FROM victima_colectiva);"
    );

    $r = hace_consulta(
        $db, "ALTER TABLE victima_colectiva
        ADD COLUMN id_grupoper INTEGER REFERENCES grupoper"
    );
    $r = hace_consulta(
        $db, "ALTER TABLE victima_colectiva
        ADD COLUMN id_caso INTEGER REFERENCES caso"
    );
    $r = hace_consulta(
        $db, "UPDATE victima_colectiva
        SET id_grupoper = victima_colectiva_caso.id_v_colectiva,
        id_caso = victima_colectiva_caso.id_caso
        FROM victima_colectiva_caso
        WHERE victima_colectiva.id = victima_colectiva_caso.id_v_colectiva"
    );
    $r = hace_consulta($db, "UPDATE victima_colectiva SET id_grupoper=id");

    foreach (array('p_responsable_agrede_comunidad' => 'id_p_responsable, '
        . 'id_grupoper, id_caso',
        'categoria_comunidad'  => 'id_tipo_violencia, id_supracategoria, '
        . 'id_categoria, id_grupoper, id_caso',) as $tabla => $llave
    ) {
            $r = hace_consulta(
                $db, "ALTER TABLE $tabla
                ADD COLUMN id_grupoper INTEGER REFERENCES grupoper"
            );
            $r = hace_consulta(
                $db, "UPDATE $tabla
                SET id_grupoper = id_v_colectiva"
            );
            $r = hace_consulta(
                $db, "ALTER TABLE $tabla
                DROP COLUMN id_v_colectiva"
            );
            $r = hace_consulta(
                $db, "ALTER TABLE $tabla
                ADD PRIMARY KEY ($llave)"
            );
    }
    foreach (array(
        'vinculo_estado_comunidad'  => 'id_vinculo_estado, id_grupoper, id_caso',
        'profesion_comunidad'  => 'id_profesion, id_grupoper, id_caso',
        'antecedente_comunidad'  => 'id_antecedente, id_grupoper, id_caso',
        'filiacion_comunidad'  => 'id_filiacion, id_grupoper, id_caso',
        'organizacion_comunidad'  => 'id_organizacion, id_grupoper, id_caso',
        'rango_edad_comunidad'  => 'id_rango, id_grupoper, id_caso',
        'sector_social_comunidad'  => 'id_sector, id_grupoper, id_caso',
        ) as $tabla => $llave
    ) {
            $r = hace_consulta(
                $db, "ALTER TABLE $tabla " .
                " ADD COLUMN id_grupoper INTEGER REFERENCES grupoper"
            );
            $r = hace_consulta(
                $db, "ALTER TABLE $tabla " .
                " ADD COLUMN id_caso INTEGER REFERENCES caso"
            );
            $r = hace_consulta(
                $db, "UPDATE $tabla " .
                " SET id_grupoper=victima_colectiva_caso.id_v_colectiva, " .
                " id_caso=victima_colectiva_caso.id_caso " .
                " FROM victima_colectiva_caso " .
                " WHERE $tabla.id_v_colectiva=" .
                " victima_colectiva_caso.id_v_colectiva"
            );
            $r = hace_consulta(
                $db, "ALTER TABLE $tabla " .
                " DROP COLUMN id_v_colectiva"
            );
            $r = hace_consulta(
                $db, "DELETE FROM $tabla " .
                " WHERE id_caso IS NULL"
            );
            $r = hace_consulta(
                $db, "ALTER TABLE $tabla " .
                " ADD PRIMARY KEY ($llave)"
            );
    }
    $r = hace_consulta($db, "DROP TABLE victima_colectiva_caso");
    $r = hace_consulta(
        $db, "DELETE FROM victima_colectiva " .
        " WHERE id_caso IS NULL"
    );
    $r = hace_consulta(
        $db, "DELETE FROM victima_colectiva " .
        " WHERE id_grupoper IS NULL"
    );
    $r = hace_consulta($db, "ALTER TABLE victima_colectiva DROP COLUMN id");
    $r = hace_consulta(
        $db, "ALTER TABLE victima_colectiva " .
        " ADD PRIMARY KEY (id_grupoper, id_caso)"
    );
    $r = hace_consulta(
        $db, "ALTER TABLE victima_colectiva " .
        " DROP COLUMN nombre"
    );
    $r = hace_consulta(
        $db, "ALTER TABLE victima_colectiva " .
        " DROP COLUMN anotacion"
    );
    foreach (array('categoria_comunidad', 'p_responsable_agrede_comunidad',
        'antecedente_comunidad', 'vinculo_estado_comunidad',
        'profesion_comunidad', 'antecedente_comunidad', 'filiacion_comunidad',
        'organizacion_comunidad', 'rango_edad_comunidad',
        'sector_social_comunidad',
    ) as $tabla
    ) {
        $q = "ALTER TABLE $tabla ADD FOREIGN KEY (id_grupoper, id_caso) " .
            " REFERENCES victima_colectiva(id_grupoper, id_caso)";
        $r = hace_consulta($db, $q);
    }
    aplicaact($act, $idac, 'Víctimas colectivas');
}

$idac = '1.1a1-act';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "CREATE TABLE acto (
            id_p_responsable INTEGER REFERENCES presuntos_responsables,
            id_categoria INTEGER REFERENCES categoria,
            id_persona INTEGER REFERENCES persona,
            id_caso INTEGER REFERENCES caso,
            FOREIGN KEY (id_persona, id_caso) REFERENCES victima,
            PRIMARY KEY(id_p_responsable, id_categoria, id_persona, id_caso)
        );", false
    );
    $r = hace_consulta(
        $db, "CREATE TABLE actocolectivo (
        id_p_responsable INTEGER REFERENCES presuntos_responsables,
        id_categoria INTEGER REFERENCES categoria,
        id_grupoper INTEGER REFERENCES grupoper,
        id_caso INTEGER REFERENCES caso,
        FOREIGN KEY (id_grupoper, id_caso)
        REFERENCES victima_colectiva(id_grupoper, id_caso),
            PRIMARY KEY(id_p_responsable, id_categoria, id_grupoper, id_caso)
        );", false
    );


    $r = hace_consulta(
        $db, "INSERT INTO acto
        (id_p_responsable, id_categoria, id_persona, id_caso)
        (SELECT DISTINCT id_p_responsable, id_categoria,
        categoria_personal.id_persona, categoria_personal.id_caso
        FROM categoria_personal, p_responsable_agrede_persona
        WHERE p_responsable_agrede_persona.id_caso =
        categoria_personal.id_caso
        AND p_responsable_agrede_persona.id_persona =
        categoria_personal.id_persona)"
    );

    $r = hace_consulta(
        $db, "INSERT INTO actocolectivo
        (id_p_responsable, id_categoria, id_grupoper, id_caso)
        (SELECT DISTINCT id_p_responsable, id_categoria,
        categoria_comunidad.id_grupoper,
        categoria_comunidad.id_caso
        FROM categoria_comunidad, p_responsable_agrede_comunidad
        WHERE categoria_comunidad.id_caso =
        p_responsable_agrede_comunidad.id_caso
        AND categoria_comunidad.id_grupoper =
        p_responsable_agrede_comunidad.id_grupoper)"
    );

    $r = hace_consulta($db, "DROP VIEW cons2", false, false);
    $r = hace_consulta($db, "DROP VIEW cons", false, false);
    $r = hace_consulta($db, "DROP TABLE categoria_personal");
    $r = hace_consulta($db, "DROP TABLE categoria_comunidad");
    $r = hace_consulta($db, "DROP TABLE p_responsable_agrede_persona");
    $r = hace_consulta($db, "DROP TABLE p_responsable_agrede_comunidad");

    aplicaact($act, $idac, 'Actos');
}

$idac = '1.1a1-com';
if (!aplicado($idac)) {

    $r = hace_consulta(
        $db, "DELETE FROM opcion " .
        " WHERE descripcion = 'Conteos V. Combatientes'"
    );
    aplicaact($act, $idac, 'Bélicas es módulo');
}

$idac = '1.1a1-jp';
if (!aplicado($idac)) {

    $r = hace_consulta(
        $db, "ALTER TABLE presuntos_responsables " .
        " DROP COLUMN polo", false
    );
    $r = hace_consulta(
        $db, "ALTER TABLE presuntos_responsables " .
        " ADD COLUMN id_papa INTEGER REFERENCES presuntos_responsables", false
    );
    $r = hace_consulta(
        $db, "UPDATE presuntos_responsables " .
        " SET id_papa='1' WHERE id in ('2', '12', '14', '38', '39')"
    );
    $r = hace_consulta(
        $db, "UPDATE presuntos_responsables " .
        " SET id_papa='2' WHERE id in ('3', '4', '5', '6', '7', '11')"
    );
    $r = hace_consulta(
        $db, "UPDATE presuntos_responsables " .
        " SET id_papa='3' WHERE id in ('8')"
    );
    $r = hace_consulta(
        $db, "UPDATE presuntos_responsables " .
        " SET id_papa='7' WHERE id in ('9', '10')"
    );
    $r = hace_consulta(
        $db, "UPDATE presuntos_responsables " .
        " SET id_papa='14' WHERE id in ('15', '16', '17', '18', '19', " .
        " '20', '21', '22', '23')"
    );
    $r = hace_consulta(
        $db, "UPDATE presuntos_responsables " .
        " SET id_papa='25' WHERE id in ('26', '27', '28', '29', " .
        " '30', '31', '32')"
    );
    $r = hace_consulta(
        $db, "UPDATE presuntos_responsables " .
        " SET id_papa='38' WHERE id in ('13')"
    );

    aplicaact($act, $idac, 'Jerarquía presuntos responsables');
}

$idac = '1.1a1-tc';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "ALTER TABLE categoria " .
        " ADD COLUMN tipocat CHAR DEFAULT 'I' " .
        " CHECK (tipocat='I' OR tipocat='C' OR tipocat='O')",
        false
    );
    hace_consulta(
        $db, "UPDATE categoria SET tipocat='C' " .
        " WHERE id IN ('501', '902', '102', '18', '28', '38', " .
        " '49', '59', '706', '401', '903', '104')"
    );
    hace_consulta(
        $db, "UPDATE categoria SET tipocat='O' " .
        " WHERE id IN ('61', '66', '67', '69', '80', '81', " .
        " '82', '83', '84', '85', '86', '89', '94', '95', '707', " .
        " '708', '709', '801' )"
    );

    hace_consulta(
        $db, "ALTER TABLE categoria_p_responsable_caso " .
        " DROP CONSTRAINT categoria_p_responsable_caso_id_caso_fkey1", false
    );
    hace_consulta($db, "DROP TABLE categoria_caso CASCADE");

    aplicaact($act, $idac, 'Tipo de categoria (Individual, Colectiva u Otra)');
}



$idac = '1.1a1-org';
if (!aplicado($idac)) {
    foreach (array('organizacion', 'filiacion', 'profesion',
        'presuntos_responsables', 'antecedente', 'categoria', 'departamento',
        'municipio', 'clase', 'tipo_clase', 'frontera', 'fuente_directa',
        'prensa', 'region', 'tipo_sitio', 'sector_social',
        'vinculo_estado'
    ) as $t
    ) {
        cambia_tipocol($db, "$t", 'nombre', 'VARCHAR(500)');
    }
    foreach (array('vinculo_estado', 'sector_social',
        'organizacion', 'filiacion', 'profesion'
    ) as $t
    ) {
        hace_consulta(
            $db, "ALTER TABLE $t " .
            "ADD COLUMN fechacreacion DATE NOT NULL DEFAULT '2001-01-01'",
            false
        );
        hace_consulta(
            $db, "ALTER TABLE $t " .
            "ADD COLUMN fechadeshabilitacion DATE " .
            "CHECK (fechadeshabilitacion IS NULL OR " .
            "fechadeshabilitacion >= fechacreacion)", false
        );
    }

    aplicaact($act, $idac, 'Personalizabilidad de más básicas');
}




$idac = '1.1a2-ccg';  // Gracias Carlos Garavis
if (!aplicado($idac)) {
    $m = $db->getOne("SELECT max(id) FROM presuntos_responsables");
    if ($m > 100) {
        echo "No aplica porque presuntos_responsables tiene codigos "
            . " mayores que 100 <br>";
    } else {
        hace_consulta(
            $db, "INSERT INTO presuntos_responsables
            (id, nombre, fecha_creacion, fecha_deshabilitacion, id_papa)
            (SELECT id+100, nombre, fecha_creacion, fecha_deshabilitacion,
             NULL FROM presuntos_responsables
             WHERE id>='39' AND id<'100')", false
        );
        hace_consulta(
            $db, "INSERT INTO presuntos_responsables_caso " .
            " (id_caso, id_p_responsable, tipo, bloque, frente, brigada, " .
            " batallon, division, otro, id) " .
            " (SELECT id_caso, id_p_responsable+100, tipo, bloque, frente, " .
            " brigada, batallon, division, otro, id " .
            " FROM presuntos_responsables_caso " .
            " WHERE id_p_responsable>='39' " .
            " AND id_p_responsable<'100')", false
        );
        foreach (array('categoria_p_responsable_caso', 'acto',
            'actocolectivo'
        ) as $t
        ) {
            hace_consulta(
                $db, "UPDATE $t " .
                "SET id_p_responsable=id_p_responsable+100 " .
                " WHERE id_p_responsable>='39' " .
                " AND id_p_responsable<'100'", false
            );
        }
        foreach (array('victima',  'victima_colectiva', 'combatiente') as $t) {
            hace_consulta(
                $db, "UPDATE $t SET " .
                "id_organizacion_armada=id_organizacion_armada+100 " .
                " WHERE id_organizacion_armada>='39' " .
                " AND id_organizacion_armada<'100'", false
            );
        }
        hace_consulta(
            $db, "DELETE FROM presuntos_responsables_caso " .
            " WHERE id_p_responsable>='39' AND id_p_responsable<'100'", false
        );
        hace_consulta(
            $db, "DELETE FROM presuntos_responsables " .
            " WHERE id>='39' AND id<'100'", false
        );
    }

    hace_consulta(
        $db, "UPDATE presuntos_responsables SET id_papa='36' " .
        " WHERE id in ('24', '37', '33')", false
    );
    hace_consulta(
        $db, "INSERT INTO presuntos_responsables " .
        " (id, nombre, fecha_creacion, fecha_deshabilitacion, id_papa) " .
        " VALUES (39, 'POLO ESTATAL', '2001-01-30', NULL, NULL)", false
    );
    hace_consulta(
        $db, "UPDATE presuntos_responsables SET id_papa='39' " .
        " WHERE id in ('12', '1', '38', '2', '14')", false
    );
    hace_consulta(
        $db, "INSERT INTO presuntos_responsables " .
        " (id, nombre, fecha_creacion, fecha_deshabilitacion, id_papa) " .
        " VALUES (40, 'POLO INSURGENTE', '2001-01-30', NULL, NULL)", false
    );
    hace_consulta(
        $db, "UPDATE presuntos_responsables SET id_papa='40' " .
        " WHERE id in ('25')", false
    );

    hace_consulta(
        $db, "UPDATE categoria SET tipocat='I' " .
        " WHERE id IN ('10', '11', '12', '13', '14', '15', '16', '19',
        '20', '21', '22', '23', '24', '25', '26', '29', '30', '33',
        '35', '36', '37', '39', '101', '191', '192', '193', '194', '195',
        '196', '291' ,'292', '293', '294', '295', '296', '301', '302', '391',
        '392', '393', '394', '395', '40', '41', '43', '45', '46', '47',
        '48', '50', '53', '55', '56', '57', '58', '72', '73', '74',
        '75', '77', '78', '79', '87', '88', '97', '98', '701', '702',
        '703', '704', '771', '772', '773', '774', '775', '776')"
    );
    hace_consulta(
        $db, "UPDATE categoria SET tipocat='C' " .
        " WHERE id IN ('18', '28', '38', '102', '104', '49', '59', " .
        " '401', '501', '706', '902', '903', '904', '906')"
    );
    hace_consulta(
        $db, "UPDATE categoria SET tipocat='O' " .
        " WHERE id IN ('62', '63', '64', '65', '66', '67', '68', '69', " .
        " '910', '80', '84', '85', '86', '89', '90', '91', '92', '93', " .
        " '95', '707', '708', '709', '801')"
    );
    hace_consulta(
        $db, "DELETE FROM categoria_p_responsable_caso " .
        " WHERE id_categoria IN (SELECT id FROM categoria  " .
        " WHERE tipocat<>'O');"
    );


    aplicaact($act, $idac, 'Jerarquía refinada');
}

$idac = '1.1a2-imp';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "UPDATE opcion SET id_opcion='69' " .
        " WHERE descripcion='Salir';", false
    );
    hace_consulta(
        $db, "INSERT INTO opcion " .
        " (id_opcion, descripcion, id_mama, nomid) " .
        " VALUES ('61', 'Importar Relatos', '60', 'importaRelato')", false
    );

    aplicaact($act, $idac, 'Importa relatos');
}


$idac = '1.1b1-vs';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "UPDATE categoria SET contada_en='771' " .
        " WHERE id='191';", false
    );
    hace_consulta(
        $db, "UPDATE categoria SET contada_en='772' " .
        " WHERE id='192';", false
    );
    hace_consulta(
        $db, "UPDATE categoria SET contada_en='773' " .
        " WHERE id='193';", false
    );
    hace_consulta(
        $db, "UPDATE categoria SET contada_en='774' " .
        " WHERE id='194';", false
    );
    hace_consulta(
        $db, "UPDATE categoria SET contada_en='775' " .
        " WHERE id='195';", false
    );
    hace_consulta(
        $db, "UPDATE categoria SET contada_en='776' " .
        " WHERE id='196';", false
    );
    hace_consulta(
        $db, "UPDATE categoria SET contada_en='906' " .
        " WHERE id='104';", false
    );

    aplicaact($act, $idac, 'Categorias recientes replicadas');
}

$idac = '1.1b1-cr';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "UPDATE opcion set descripcion='Completar actos', " .
        " id_mama='60', nomid='completaActos' WHERE id_opcion='62'", false
    );
    hace_consulta(
        $db, "INSERT INTO opcion (id_opcion, descripcion, " .
        " id_mama, nomid) " .
        " VALUES ('62', 'Completar actos', '60', 'completaActos')", false
    );

    aplicaact($act, $idac, 'Completar actos');
}

$idac = '1.1b1-ref';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "UPDATE categoria SET nombre='RAPTO' " .
        " WHERE id='48' AND nombre='DESAPARCIÓN';", false
    );
    hace_consulta(
        $db, "UPDATE categoria SET nombre='ATENTADO' " .
        " WHERE id='16';", false
    );

    aplicaact($act, $idac, 'Categorias refinadas');
}

$idac = '1.1b1-ren';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE contexto " .
        " RENAME COLUMN fecha_creacion TO fechacreacion", false
    );
    hace_consulta(
        $db, "ALTER TABLE contexto " .
        " RENAME COLUMN fecha_deshabilitacion TO fechadeshabilitacion", false
    );

    aplicaact($act, $idac, 'Renombra');
}



$idac = '1.1b1-pes';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE parametros_reporte_consolidado " .
        "ADD COLUMN peso INTEGER DEFAULT '0'", false
    );

    aplicaact($act, $idac, 'Pesos en rótulos de reporte consolidado');
}

$idac = '1.1b1-ctx';
if (!aplicado($idac)) {

    $m = $db->getOne("SELECT nombre FROM contexto WHERE id='29'");
    if ($m != '' && trim(a_mayusculas($m)) != 'FALSO POSITIVO') {
        $mi = $db->getOne("SELECT max(id) FROM contexto");
        if ($mi > 100) {
            echo "No aplica porque contexto tiene codigos mayores que 100 <br>";
        } else {
            hace_consulta(
                $db, "INSERT INTO contexto " .
                " (id, nombre, fechacreacion, fechadeshabilitacion) " .
                " (SELECT id+71, nombre, fechacreacion, fechadeshabilitacion " .
                " FROM contexto WHERE id>='29')", false
            );
            hace_consulta(
                $db, "INSERT INTO caso_contexto " .
                " (id_caso, id_contexto) (SELECT id_caso, id_contexto+71 " .
                " FROM caso_contexto WHERE id_contexto>='29')", false
            );
            hace_consulta(
                $db, "DELETE FROM caso_contexto " .
                " WHERE id_contexto>='29' AND id_contexto<'100'", false
            );
            hace_consulta(
                $db, "DELETE FROM contexto " .
                " WHERE id>='29' AND id<'100'", false
            );
        }
    }
    if (trim(a_mayusculas($m)) != 'FALSO POSITIVO') {
        hace_consulta(
            $db, "INSERT INTO contexto " .
            " (id, nombre, fechacreacion, fechadeshabilitacion) " .
            " VALUES ('29', 'FALSO POSITIVO', '2007-07-06', NULL)", false
        );
    }

    hace_consulta(
        $db, "UPDATE opcion SET descripcion='Consulta Detallada' " .
        " WHERE descripcion='Consulta Externa'", false
    );

    cambia_tipocol(
        $db, 'presuntos_responsables_caso', 'otro',
        'VARCHAR(500)'
    );
    hace_consulta(
        $db, "INSERT INTO opcion " .
        " VALUES ('63', 'Actualizar', '60', 'actualiza')", false
    );

    aplicaact($act, $idac, 'Contexto y detalles pequeños requeridos por RB');
}

$idac = '1.1b1-tr';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion " .
        " (id, nombre, dirigido, observaciones) " .
        " VALUES ('AB', 'Abuela', true, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion " .
        " (id, nombre, dirigido, observaciones) " .
        " VALUES ('AO','Abuelo', true, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion " .
        " (id, nombre, dirigido, observaciones) " .
        " VALUES ('CO','Conyuge y/o Companero Permanente', false, NULL)",
        false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion " .
        " (id, nombre, dirigido, observaciones) " .
        " VALUES ('HA','Hija', true, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion " .
        " (id, nombre, dirigido, observaciones) " .
        " VALUES ('HE','Hermano', false, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion " .
        " (id, nombre, dirigido, observaciones) " .
        " VALUES ('HI','Hijo', true, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion " .
        " (id, nombre, dirigido, observaciones) " .
        " VALUES ('HR','Hermana', false, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion " .
        " (id, nombre, dirigido, observaciones) " .
        " VALUES ('MA','Madrina', true, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion
        (id, nombre, dirigido, observaciones)
        VALUES ('ME','Madre', true, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion
        (id, nombre, dirigido, observaciones)
        VALUES ('PA','Padre', true, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion (id, nombre, dirigido, observaciones)
        VALUES ('PO','Padrino', true, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion (id, nombre, dirigido, observaciones)
        VALUES ('TA','Tia', true, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion (id, nombre, dirigido, observaciones)
        VALUES ('TO','Tio', true, NULL)", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO tipo_relacion (id, nombre, dirigido, observaciones)
        VALUES ('SI','SIN INFORMACIÓN', true, NULL)", false
    );

    aplicaact($act, $idac, 'Tipos de relaciones familiares');
}


$idac = '1.1b1-esp';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "UPDATE  tipo_violencia SET nombre='VIOLENCIA POLÍTICO SOCIAL'
        WHERE id = 'B'", false
    );
    $r = hace_consulta(
        $db, "UPDATE  categoria SET
        nombre = 'CONFINAMIENTO COMO REPRESALIA O CASTIGO COLECTIVO'
        WHERE id = '104'", false
    );
    $r = hace_consulta(
        $db, "UPDATE  categoria SET
        nombre = 'CONFINAMIENTO COMO REPRESALIA O CASTIGO COLECTIVO'
        WHERE id = '906'", false
    );
    $r = hace_consulta(
        $db, "UPDATE  presuntos_responsables SET
        nombre = 'GRUPOS DE INTOLERANCIA' WHERE id='33'", false
    );

    $r = hace_consulta(
        $db, "INSERT INTO categoria
        (id, fecha_creacion, fecha_deshabilitacion, id_supracategoria,
        id_tipo_violencia, col_rep_consolidado, nombre, tipocat)
        VALUES (420, '2010-04-17', NULL, 1,
        'B', NULL, 'VIOLENCIA SEXUAL', 'I')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat) VALUES (421, '2010-04-17', NULL, 1, 'B', NULL, 'VIOLACIÓN',
        'I')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat) VALUES (422, '2010-04-17', NULL, 1, 'B', NULL,
        'EMBARAZO FORZADO', 'I')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat) VALUES (423, '2010-04-17', NULL, 1, 'B', NULL,
        'PROSTITUCIÓN FORZADA', 'I')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat) VALUES (424, '2010-04-17', NULL, 1, 'B', NULL,
        'ESTERILIZACIÓN FORZADA', 'I')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat) VALUES (425, '2010-04-17', NULL, 1, 'B', NULL,
        'ESCLAVITUD SEXUAL', 'I')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat) VALUES (426, '2010-04-17', NULL, 1, 'B', NULL, 'ABUSO SEXUAL',
        'I')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat) VALUES (427, '2010-04-17', NULL, 1, 'B', NULL,
        'ABORTO FORZADO', 'I');", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat) VALUES (297, '2010-04-17', NULL, 2, 'A', NULL,
        'V.S. - ABORTO FORZADO', 'I')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat)
        VALUES (396, '2008-10-20', NULL, 3, 'A', 8, 'V.S. - ABUSO SEXUAL',
        'I')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat)
        VALUES (397, '2010-04-17', NULL, 3, 'A', 8, 'V.S. - ABORTO FORZADO',
        'I')", false
    );
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion, fecha_deshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat)
        VALUES (777, '2010-04-17', NULL, 1, 'D', 12, 'ABORTO FORZADO', 'I')",
        false
    );

    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='701' WHERE id='10'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='72' WHERE id='12'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='702' WHERE id='13'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='73' WHERE id='15'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='706' WHERE id='18'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='77' WHERE id='19'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='903' WHERE id='102'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='906' WHERE id='104'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='771' WHERE id='191'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='772' WHERE id='192'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='773' WHERE id='193'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='774' WHERE id='194'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='775' WHERE id='195'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='776' WHERE id='196'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='777' WHERE id='197'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='906' WHERE id='104'", false
    );

    aplicaact($act, $idac, 'Acuerdos Esperanza 2010');
}

$idac = '1.1b2-lu';
if (!aplicado($idac)) {
    cambia_tipocol($db, 'ubicacion', 'sitio', 'VARCHAR(260)');
    cambia_tipocol($db, 'ubicacion', 'lugar', 'VARCHAR(260)');

    aplicaact($act, $idac, 'Tamaño de ubicación');
}

$idac = '1.1b2-vc';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "UPDATE categoria SET tipocat='C'
        WHERE id = '902'", false
    );

    aplicaact($act, $idac, 'Categoria colectiva');
}

$idac = '1.1b2-val';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid)
        VALUES ('64', 'Validar', '60', 'valida')", false
    );

    aplicaact($act, $idac, 'Validaciones de consistencia');
}

$idac = '1.1b2-br';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "INSERT INTO opcion (id_opcion, descripcion, id_mama, nomid)
        VALUES ('65', 'Buscar Repetidos', '60', 'buscaRepetidos')", false
    );

    aplicaact($act, $idac, 'Buscar repetidos');
}

$idac = '1.1b2-or';
if (!aplicado($idac)) {
    foreach (array(61, 64, 65) as $idop) {
        foreach (array(1, 2, 3) as $idrol) {
            hace_consulta(
                $db, "INSERT INTO opcion_rol (id_opcion, id_rol) " .
                "VALUES ($idop, $idrol)", false
            );
        }
    }
    foreach (array(62, 63) as $idop) {
        hace_consulta(
            $db, "INSERT INTO opcion_rol (id_opcion, id_rol) " .
            "VALUES ($idop, 1)", false
        );
    }

    aplicaact($act, $idac, 'Opciones de rol');
}

$idac = '1.1b3-cat';
if (!aplicado($idac)) {

    $r = hace_consulta(
        $db, "UPDATE categoria SET tipocat='O' WHERE id='902'",
        false
    );
    aplicaact($act, $idac, 'Reversa categoria colectiva. Es por métodos');
}

$idac = '1.1b3-is';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "UPDATE  presuntos_responsables SET
        nombre = 'GRUPOS DE INTOLERANCIA' where id='33'", false
    );
    aplicaact($act, $idac, 'Grupos de intolerancia es 33');
}

$idac = '1.1b3-v';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fecha_creacion,
        fecha_deshabilitacion, id_supracategoria, id_tipo_violencia,
        col_rep_consolidado, nombre, tipocat)
        VALUES (291, '2008-10-20', NULL, 2, 'A', 8, 'V.S. - VIOLACIÓN', 'I');",
        false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='291' WHERE contada_en='221'",
        false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria_p_responsable_caso SET id_categoria='291'
        WHERE id_categoria = '221'", false
    );
    $r = hace_consulta(
        $db, "UPDATE acto SET id_categoria='291' WHERE id_categoria='221'",
        false
    );
    $r = hace_consulta(
        $db, "UPDATE actocolectivo SET id_categoria='291'
        WHERE id_categoria = '221'", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria_personal SET id_categoria='291'
        WHERE id_categoria = '221'", false
    );
    $r = hace_consulta($db, "DELETE FROM categoria WHERE id='221'", false);

    aplicaact(
        $act, $idac, 'Renumerada categoria 221 a 291 para que coincida '
        . 'en Marco Conceptual'
    );
}


$idac = '1.1b3-ran';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE rango_edad " .
        "ADD COLUMN limiteinferior INTEGER DEFAULT '0' NOT NULL", false
    );
    hace_consulta(
        $db, "ALTER TABLE rango_edad " .
        "ADD COLUMN limitesuperior INTEGER DEFAULT '0' NOT NULL", false
    );

    hace_consulta(
        $db, "UPDATE rango_edad SET limiteinferior='0', " .
        "limitesuperior='15' WHERE id='1'", false
    );
    hace_consulta(
        $db, "UPDATE rango_edad SET limiteinferior='16', " .
        "limitesuperior='25' WHERE id='2'", false
    );
    hace_consulta(
        $db, "UPDATE rango_edad SET limiteinferior='26', " .
        "limitesuperior='45' WHERE id='3'", false
    );
    hace_consulta(
        $db, "UPDATE rango_edad SET limiteinferior='46', " .
        "limitesuperior='60' WHERE id='4'", false
    );
    hace_consulta(
        $db, "UPDATE rango_edad SET limiteinferior='61', " .
        "limitesuperior='130' WHERE id='5'", false
    );
    hace_consulta(
        $db, "UPDATE rango_edad SET limiteinferior='-1', " .
        "limitesuperior='-1' WHERE id='6'", false
    );

    aplicaact($act, $idac, 'Limites en rango de edad');
}

$idac = '1.1cp1-r';
if (!aplicado($idac)) {
    foreach (array('antecedente', 'categoria', 'presuntos_responsables',
        'supracategoria', 'tipo_violencia'
    ) as $t
    ) {
        hace_consulta(
            $db, "ALTER TABLE $t RENAME COLUMN fecha_creacion TO fechacreacion",
            false
        );
        hace_consulta(
            $db, "ALTER TABLE $t RENAME COLUMN fecha_deshabilitacion
            TO fechadeshabilitacion", false
        );
    }

    aplicaact($act, $idac, 'Renombra fecha_creacion y fecha_deshabilitacion');
}

$idac = '1.1cp1-mf';
if (!aplicado($idac)) {
    foreach (array('clase', 'intervalo', 'departamento',
        'municipio', 'parametros_reporte_consolidado', 'tipo_clase',
        'tipo_sitio', 'frontera', 'region', 'prensa', 'rango_edad',
        'resultado_agresion', 'tipo_relacion'
    )  as $t
    ) {
        hace_consulta(
            $db, "ALTER TABLE $t " .
            "ADD COLUMN fechacreacion DATE NOT NULL DEFAULT '2001-01-01'",
            false
        );
        hace_consulta(
            $db, "ALTER TABLE $t ADD COLUMN
            fechadeshabilitacion DATE CHECK (fechadeshabilitacion IS NULL OR
            fechadeshabilitacion >= fechacreacion)", false
        );
    }
    aplicaact(
        $act, $idac, 'Añade fechacreacion y fechadeshabilitacion '
        . ' a otras tablas básicas'
    );
}

$idac = '1.1cp2-c';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "INSERT INTO contexto " .
        " (id, nombre, fechacreacion, fechadeshabilitacion) " .
        " VALUES ('30', 'INTOLERANCIA SOCIAL', '2011-04-26', NULL)", false
    );

    hace_consulta($db, "CREATE SEQUENCE etnia_seq;", false);
    hace_consulta(
        $db, "CREATE TABLE etnia (
        id INTEGER PRIMARY KEY DEFAULT(nextval('etnia_seq')),
        nombre VARCHAR(200) NOT NULL,
        descripcion VARCHAR(1000),
        fechacreacion    DATE NOT NULL,
        fechadeshabilitacion    DATE CHECK (fechadeshabilitacion IS NULL
        OR fechadeshabilitacion >= fechacreacion)
        );", false
    );
    hace_consulta(
        $db, "INSERT INTO etnia (id, nombre, descripcion, "
        . " fechacreacion, fechadeshabilitacion) "
        . " VALUES (1, 'SIN INFORMACIÓN', '', '2011-04-26', NULL)", false
    );
    hace_consulta(
        $db, "ALTER TABLE victima " .
        "ADD COLUMN id_etnia INTEGER REFERENCES etnia",
        false
    );
    $na = 'datos-implicado.sql';
    $r = consulta_archivo($db, $na, false, false, false);
    if ($r) {
        aplicaact(
            $act, $idac,
            'Añade etnia y contexto intolerancia social'
        );
    } else {
        echo_esc("No pudo abrir $na");
    }
}

$idac = '1.1cp2-i';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "INSERT INTO contexto " .
        " (id, nombre, fechacreacion, fechadeshabilitacion) " .
        " VALUES ('31', 'SEGURIDAD INFORMÁTICA', '2011-04-28', NULL)", false
    );

    hace_consulta($db, "CREATE SEQUENCE iglesia_seq;", false);
    hace_consulta(
        $db, "CREATE TABLE iglesia (
        id INTEGER PRIMARY KEY DEFAULT(nextval('iglesia_seq')),
        nombre VARCHAR(200) NOT NULL,
        descripcion VARCHAR(1000),
        fechacreacion    DATE NOT NULL,
        fechadeshabilitacion    DATE CHECK (fechadeshabilitacion IS NULL
        OR fechadeshabilitacion >= fechacreacion)
        );", false
    );
    hace_consulta(
        $db, "INSERT INTO iglesia (id, nombre, descripcion, "
        . " fechacreacion, fechadeshabilitacion) "
        . " VALUES (1, 'SIN INFORMACIÓN', '', '2011-04-28', NULL)", false
    );
    hace_consulta(
        $db, "ALTER TABLE victima " .
        "ADD COLUMN id_iglesia INTEGER REFERENCES iglesia",
        false
    );
    $na = 'datos-implicado.sql';
    $r = consulta_archivo($db, $na, false, false, false);
    if ($r) {
        aplicaact(
            $act, $idac,
            'Añade iglesia y contexto Seguridad Informática'
        );
    } else {
        echo_esc("No pudo abrir $na");
    }
}

$idac = '1.1-vs';
if (!aplicado($idac)) {
    $r = hace_consulta(
        $db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado, nombre,
        tipocat) VALUES (197, '2010-04-17', NULL, 1, 'A', '12',
        'V.S. - ABORTO FORZADO', 'I')", false
    );
    $r = hace_consulta(
        $db, "UPDATE categoria SET contada_en='777' WHERE id='197'", false
    );
    $r = hace_consulta(
        $db,
        "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado,
        nombre, tipocat)
        VALUES (520, '2011-07-07', NULL, 2, 'B', 12, 'VIOLENCIA SEXUAL', 'I');",
        false
    );

    $r = hace_consulta(
        $db,
        "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado,
        nombre, tipocat)
        VALUES (521, '2011-07-07', NULL, 2, 'B', 12, 'VIOLACIÓN', 'I'); ",
        false
    );
    $r = hace_consulta(
        $db,
        "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado,
        nombre, tipocat)
        VALUES (522, '2011-07-07', NULL, 2, 'B', 12, 'EMBARAZO FORZADO', 'I');",
        false
    );
    $r = hace_consulta(
        $db,
        "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado,
        nombre, tipocat)
        VALUES (523, '2011-07-07', NULL, 2, 'B', 12,
        'PROSTITUCIÓN FORZADA', 'I'); ",
        false
    );
    $r = hace_consulta(
        $db,
        "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado,
        nombre, tipocat)
        VALUES (524, '2011-07-07', NULL, 2, 'B', 12,
        'ESTERILIZACIÓN FORZADA', 'I'); ",
        false
    );
    $r = hace_consulta(
        $db,
        "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado,
        nombre, tipocat)
        VALUES (525, '2011-07-07', NULL, 2, 'B', 12, 'ESCLAVITUD SEXUAL', 'I');",
        false
    );
    $r = hace_consulta(
        $db,
        "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado,
        nombre, tipocat)
        VALUES (526, '2011-07-07', NULL, 2, 'B', 12, 'ABUSO SEXUAL', 'I'); ",
        false
    );
    $r = hace_consulta(
        $db,
        "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion,
        id_supracategoria, id_tipo_violencia, col_rep_consolidado,
        nombre, tipocat)
        VALUES (527, '2011-07-07', NULL, 2, 'B', 12, 'ABORTO FORZADO', 'I'); ",
        false
    );


    aplicaact(
        $act, $idac, 'Violencia sexual mas completa'
    );
}


$idac = '1.1-nd';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE persona" .
        " RENAME COLUMN tipo_documento TO tipodocumento", false
    );
    hace_consulta(
        $db, "ALTER TABLE persona" .
        " RENAME COLUMN numero_documento TO numerodocumento", false
    );
    aplicaact(
        $act, $idac, 'Nomenclatura en tabla persona'
    );
}

$idac = '1.1-os';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE victima" .
        " ADD COLUMN orientacionsexual CHAR(1) DEFAULT 'H' " .
        " CHECK (orientacionsexual='L' OR orientacionsexual='G' OR " .
        " orientacionsexual='B' OR orientacionsexual='T' OR " .
        " orientacionsexual='I' OR orientacionsexual='H' " .
        " )", false
    );
    hace_consulta(
        $db, "ALTER TABLE victima ALTER " .
        "COLUMN orientacionsexual SET NOT NULL"
    );
    aplicaact(
        $act, $idac, 'Orientación sexual'
    );
}
$idac = '1.2-sm';
if (!aplicado($idac)) {
    hace_consulta($db, 'DROP TABLE opcion_rol', false);
    hace_consulta($db, 'DROP TABLE opcion', false);
    hace_consulta(
        $db, "ALTER TABLE usuario DROP CONSTRAINT usuario_id_rol_fkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario DROP CONSTRAINT usuario_id_rol_check", 
        false, false
    );
    hace_consulta(
        $db,
        "ALTER TABLE usuario ADD CONSTRAINT usuario_id_rol_check "
        . " CHECK (rol>='1' AND rol<='4')", false
    );
    hace_consulta($db, 'DROP TABLE rol CASCADE', false);
    hace_consulta($db, 'DROP SEQUENCE rol_seq CASCADE', false);

    aplicaact(
        $act, $idac, 'Menú pasa de base de datos a interfaz'
    );
}

$idac = '1.2-lu';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN idioma "
        . " VARCHAR(6) NOT NULL DEFAULT 'es_CO'", false, false
    );

    aplicaact($act, $idac, 'Idioma preferido por usuario');
}


$idac = '1.2-rt';
if (!aplicado($idac)) {
    hace_consulta(
        $db,
        "ALTER TABLE categoria_p_responsable_caso RENAME TO "
        . "caso_categoria_presponsable", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE presuntos_responsables RENAME TO "
        . "presponsable ", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE presuntos_responsables_seq RENAME TO "
        . "presponsable_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE presuntos_responsables_caso RENAME TO "
        . "caso_presponsable", false
    );

    aplicaact($act, $idac, 'Renombrando tablas con presunto responsable');
}

$idac = '1.2-rt2';
if (!aplicado($idac)) {
    hace_consulta(
        $db,
        "ALTER TABLE vinculo_estado RENAME TO "
        . "vinculoestado ", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE vinculo_estado_seq RENAME TO "
        . "vinculoestado_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE vinculo_estado_comunidad RENAME TO "
        . "comunidad_vinculoestado", false
    );

    aplicaact($act, $idac, 'Renombrando tablas vinculoestado');
}

$idac = '1.2-rt3';
if (!aplicado($idac)) {
    hace_consulta(
        $db,
        "ALTER TABLE filiacion_comunidad RENAME TO "
        . "comunidad_filiacion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE frontera_caso RENAME TO "
        . "caso_frontera", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE funcionario_caso RENAME TO "
        . "caso_funcionario", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE organizacion_comunidad RENAME TO "
        . "comunidad_organizacion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE profesion_comunidad RENAME TO "
        . "comunidad_profesion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE region_caso RENAME TO "
        . "caso_region", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE victima_colectiva RENAME TO "
        . "victimacolectiva", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE tipo_violencia RENAME TO "
        . "tviolencia", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE tipo_sitio RENAME TO "
        . "tsitio", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE tipo_sitio_seq RENAME TO "
        . "tsitio_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE tipo_relacion RENAME TO "
        . "trelacion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE tipo_clase RENAME TO "
        . "tclase", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE sector_social RENAME TO "
        . "sectorsocial", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE sector_social_seq RENAME TO "
        . "sectorsocial_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE sector_social_comunidad RENAME TO "
        . "comunidad_sectorsocial", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE resultado_agresion RENAME TO "
        . "resagresion", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE resultado_agresion_seq RENAME TO "
        . "resagresion_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE relacion_personas RENAME TO "
        . "persona_trelacion", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE rango_edad RENAME TO "
        . "rangoedad", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE rango_edad_seq RENAME TO "
        . "rangoedad_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE rango_edad_comunidad RENAME TO "
        . "comunidad_rangoedad", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE parametros_reporte_consolidado RENAME TO "
        . "pconsolidado", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE parametros_reporte_consolidado_seq RENAME TO "
        . "pconsolidado_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE prensa RENAME TO "
        . "ffrecuente", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE prensa_seq RENAME TO "
        . "ffrecuente_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE escrito_caso RENAME TO "
        . "caso_ffrecuente", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE fuente_directa RENAME TO "
        . "fotra", false
    );
    hace_consulta(
        $db,
        "ALTER SEQUENCE fuente_directa_seq RENAME TO "
        . "fotra_seq ", false
    );
    hace_consulta(
        $db,
        "ALTER TABLE fuente_directa_caso RENAME TO "
        . "caso_fotra", false
    );

    aplicaact($act, $idac, 'Renombrando tablas para seguir estándares SQL');
}

$idac = '1.2-rc1';
if (!aplicado($idac)) {
    foreach (array(
        array("acto", "id_p_responsable", "id_presponsable"),
        array("actocolectivo", "id_p_responsable", "id_presponsable"),
        array("caso", "gr_confiabilidad", "grconfiabilidad"),
        array("caso", "gr_esclarecimiento", "gresclarecimiento"),
        array("caso", "gr_impunidad", "grimpunidad"),
        array("caso", "gr_informacion", "grinformacion"),
        array("caso_categoria_presponsable", "id_p_responsable", "id_presponsable"),
        array("caso_categoria_presponsable", "id_tipo_violencia", "id_tviolencia"),
        array("caso_presponsable", "id_p_responsable", "id_presponsable"),
        array("caso_fotra", "ubicacion_fisica", "ubicacionfisica"),
        array("caso_fotra", "tipo_fuente", "tfuente"),
        array("pconsolidado", "no_columna", "id"),
        array("pconsolidado", "tipo_violencia", "tipoviolencia"),
        array("supracategoria", "id_tipo_violencia", "id_tviolencia"),
        array("categoria", "col_rep_consolidado", "id_pconsolidado"),
        array("categoria", "contada_en", "contadaen"),
        array("categoria", "id_tipo_violencia", "id_tviolencia"),
        array("clase", "id_tipo_clase", "id_tclase"),
        array("comunidad_rangoedad", "id_rango", "id_rangoedad"),
        array("comunidad_sectorsocial", "id_sector", "id_sectorsocial"),
        array("ffrecuente", "tipo_fuente", "tfuente"),
        array("presunto_responsable_caso", "id_p_responsable", "id_presponsable"),
        array("ubicacion", "id_tipo_sitio", "id_tsitio"),
        array("usuario", "id_rol", "rol"),
        array("usuario", "id_usuario", "id"),
        array("usuario", "dias_edicion_caso", "diasedicion"),
        array("victima", "id_rango_edad", "id_rangoedad"),
        array("victima", "id_sector_social", "id_sectorsocial"),
        array("victima", "id_vinculo_estado", "id_vinculoestado"),
        array("victima", "id_organizacion_armada", "organizacionarmada"),
        array("comunidad_vinculoestado", "id_vinculo_estado", "id_vinculoestado"),
        array("presponsable", "id_papa", "papa"),
        array("persona_trelacion", "id_persona1", "persona1"),
        array("persona_trelacion", "id_persona2", "persona2"),
        array("persona_trelacion", "id_tipo", "id_trelacion"),
        array("victimacolectiva", "personas_aprox", "personasaprox"),
        array("victimacolectiva", "id_organizacion_armada", "organizacionarmada"),
        array("caso_ffrecuente", "ubicacion_fisica", "ubicacionfisica"),
        array("caso_ffrecuente", "id_prensa", "id_ffrecuente"),
        array("caso_fotra", "id_fuente_directa", "id_fotra"),
        array("caso_funcionario", "fecha_inicio", "fechainicio"),
    ) as $v) {
        $tabla = $v[0];
        $ant = $v[1];
        $nue = $v[2];
        hace_consulta(
            $db,
            "ALTER TABLE $tabla RENAME COLUMN $ant TO $nue", false
        );
    }

    aplicaact($act, $idac, 'Renombrando campos para seguir estándares SQL');
}

$idac = '1.2-gc';
if (!aplicado($idac)) {
    foreach (array('departamento', 'municipio', 'clase') as $tabla) {
        hace_consulta(
            $db, "ALTER TABLE $tabla " .
            " ADD COLUMN latitud FLOAT", false
        );
        hace_consulta(
            $db, "ALTER TABLE $tabla " .
            " ADD COLUMN longitud FLOAT", false
        );
    }
    aplicaact($act, $idac, 'Latitud y Longitud en departamento, municipio y clase');
}

$idac = '1.1-dp';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE tclase ALTER COLUMN id TYPE VARCHAR(10)", false
    );
    hace_consulta(
        $db, "ALTER TABLE clase ALTER COLUMN id_tclase TYPE VARCHAR(10)", false
    );

    aplicaact($act, $idac, 'Actualiza info. geográfica con DIVIPOLA 2012');
}

$idac = '1.1-dp1';
if (!aplicado($idac)) {
    consulta_archivo($db, 'act-nom2012.sql');
    aplicaact($act, $idac, 'Actualiza info. geográfica con DIVIPOLA 2012');
}

$idac = '1.1-dp13';
if (!aplicado($idac)) {
    consulta_archivo($db, 'act-nom2013.sql');
    aplicaact($act, $idac, 'Actualiza info. geográfica con DIVIPOLA 2013');
}

$idac = '1.2-co';
if (!aplicado($idac)) {
    consulta_archivo($db, 'act-coor.sql');
    aplicaact($act, $idac, 'Agrega coordenadas a departamentos y municipios');
}

$idac = '1.2-coc';
if (!aplicado($idac)) {
    $n = (int)$db->getOne(
        "SELECT COUNT(*) FROM ubicacion, municipio 
        WHERE municipio.id_departamento=ubicacion.id_departamento 
        AND municipio.id=ubicacion.id_municipio
        AND ubicacion.latitud IS NULL; "
    );
    echo_esc("  Agregando coordenadas a $n ubicaciones con municipio");
    echo "<br>";
    hace_consulta(
        $db, "UPDATE ubicacion 
        SET latitud = municipio.latitud+random()/1000-0.0005, 
        longitud=municipio.longitud+random()/1000-0.0005 
        FROM municipio 
        WHERE municipio.id_departamento=ubicacion.id_departamento 
        AND municipio.id=ubicacion.id_municipio
        AND ubicacion.latitud IS NULL; "
    );

    aplicaact($act, $idac, 'Agrega coordenadas a casos que no tienen');
}


$idac = '1.2-loc';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "CREATE COLLATION es_co_utf_8 (LOCALE = 'es_CO.UTF-8')", false
    );
    hace_consulta(
        $db, "DROP VIEW IF EXISTS vestcomb", false
    );
    foreach (array(
        'Antecedente', 'Categoria', 'Clase', 'Contexto',
        'Departamento', 'Etnia', 'Ffrecuente', 'Filiacion', 'Frontera', 
        'Fotra', 'Ffrecuente', 'Grupoper',
        'Iglesia', 'Intervalo', 'Municipio', 'Organizacion', 
        'Presponsable', 'Profesion', 'Rangoedad', 'Region', 'Resagresion', 
        'Sectorsocial', 'Supracategoria', 'Tclase', 'Trelacion', 'Tsitio', 
        'Tviolencia', 'Vinculoestado', 
    ) as $t) {
        hace_consulta(
            $db, "ALTER TABLE $t ALTER nombre "
            . " TYPE VARCHAR(500) COLLATE es_co_utf_8", false
        );
    }
    hace_consulta(
        $db, "ALTER TABLE persona ALTER nombres "
        . " TYPE VARCHAR(100) COLLATE es_co_utf_8", false
    );
    hace_consulta(
        $db, "ALTER TABLE persona ALTER apellidos "
        . " TYPE VARCHAR(100) COLLATE es_co_utf_8", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ALTER nombre "
        . " TYPE VARCHAR(50) COLLATE es_co_utf_8", false
    );
    hace_consulta(
        $db, "ALTER TABLE ubicacion ALTER lugar "
        . " TYPE VARCHAR(500) COLLATE es_co_utf_8", false
    );
    hace_consulta(
        $db, "ALTER TABLE ubicacion ALTER sitio "
        . " TYPE VARCHAR(500) COLLATE es_co_utf_8", false
    );
    hace_consulta(
        $db, "ALTER TABLE pconsolidado ALTER rotulo "
        . " TYPE VARCHAR(500) COLLATE es_co_utf_8", false
    );

    aplicaact($act, $idac, 'Localización');
}


$idac = '1.2-btc';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "CREATE EXTENSION unaccent", false
    );
    hace_consulta(
        $db, "ALTER TEXT SEARCH DICTIONARY unaccent (RULES='unaccent')", false
    );
    hace_consulta(
        $db, "ALTER FUNCTION unaccent(text) IMMUTABLE", false
    );
    hace_consulta(
        $db, "CREATE INDEX persona_nombres_apellidos ON persona "
        . " USING gin(to_tsvector('spanish', unaccent(persona.nombres) "
        . "|| ' ' || unaccent(persona.apellidos)))", 
        false
    );
    hace_consulta(
        $db, "CREATE INDEX persona_apellidos_nombres ON persona "
        . " USING gin(to_tsvector('spanish', unaccent(persona.apellidos) "
        . " || ' ' || unaccent(persona.nombres)))", 
        false
    );
    hace_consulta(
        $db, "CREATE INDEX persona_nombres_apellidos_doc ON persona "
        . " USING gin(to_tsvector('spanish', unaccent(persona.nombres) "
        . "|| ' ' || unaccent(persona.apellidos) "
        . "|| ' ' || persona.numerodocumento))", 
        false
    );
    hace_consulta(
        $db, "CREATE INDEX persona_apellidos_nombres_doc ON persona "
        . " USING gin(to_tsvector('spanish', unaccent(persona.apellidos) "
        . " || ' ' || unaccent(persona.nombres) "
        . " || ' ' || persona.numerodocumento))", 
        false
    );

    hace_consulta(
        $db, "CREATE INDEX caso_titulo ON caso "
        . " USING gin(to_tsvector('spanish', unaccent(caso.titulo))) ",
        false
    );
    hace_consulta(
        $db, "CREATE INDEX caso_memo ON caso "
        . " USING gin(to_tsvector('spanish', unaccent(caso.memo))) ",
        false
    );

    aplicaact($act, $idac, 'Búsqueda de textos');
}

$idac = '1.2-idn';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "UPDATE persona SET numerodocumento = "
        . " regexp_replace(numerodocumento, '[^0-9]', '', 'g') ", false
    );
    hace_consulta(
        $db, "UPDATE persona SET numerodocumento = NULL "
        . " WHERE numerodocumento = '' ", false
    );
    hace_consulta(
        $db, "ALTER TABLE persona ALTER numerodocumento TYPE BIGINT USING 
        CAST (numerodocumento AS BIGINT)"
    );
    hace_consulta(
        $db, "ALTER TABLE persona DROP CONSTRAINT numerodocumento_key ", 
        false, false
    );
    hace_consulta(
        $db, "ALTER TABLE persona ADD CONSTRAINT numerodocumento_key "
        . " UNIQUE (tipodocumento, numerodocumento)"
    );
    aplicaact($act, $idac, 'Numero de documento entero');
}

$idac = '1.2-ext';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "INSERT INTO departamento 
        (id, nombre, latitud, longitud, fechacreacion, fechadeshabilitacion) 
        VALUES (10000, 'EXTERIOR', NULL, NULL, '2013-06-13', NULL);", false
    );
    hace_consulta(
        $db, "INSERT INTO municipio
        (id, nombre, id_departamento, latitud, longitud, 
        fechacreacion, fechadeshabilitacion)
        (SELECT id, nombre, 10000, latitud, longitud,
        fechacreacion, fechadeshabilitacion FROM municipio
        WHERE id_departamento='0');", false
    );
    foreach (array('clase', 'ubicacion', 'persona') as $t) {
        hace_consulta(
            $db, "UPDATE $t SET id_departamento = 10000
            WHERE id_departamento = 0", false
        );
    }
    hace_consulta(
        $db, "DELETE FROM municipio WHERE id_departamento = '0'", false
    );
    hace_consulta(
        $db, "DELETE FROM departamento WHERE id = '0'", false
    );
    aplicaact($act, $idac, 'Cambio de código EXTERIOR de 0 a 10000');
}


$idac = '1.2-tb';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "INSERT INTO vinculoestado(id, nombre, fechacreacion) 
        VALUES (40, 'VICEPRESIDENCIA', '2013-07-05')", false
    );
    hace_consulta(
        $db, "INSERT INTO organizacion(id, nombre, fechacreacion) 
        VALUES (17, 'VÍCTIMAS', '2013-07-05')", false
    );
    hace_consulta(
        $db, "INSERT INTO etnia (id, nombre, descripcion, fechacreacion) 
              VALUES ('60', 'ROM', '', '2013-07-05')" , false, false
    );

    aplicaact($act, $idac, 'Aumentadas tablas básicas');
}
$idac = '1.2-pr1';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "UPDATE presponsable set papa='39' WHERE id='1'", false
    );
    hace_consulta(
        $db, "UPDATE presponsable set papa='39' WHERE id='14'", false
    );
    hace_consulta(
        $db, "UPDATE presponsable set papa='40' WHERE id='25'", false
    );
    hace_consulta(
        $db, "UPDATE presponsable set papa='36' WHERE id='33'", false
    );

    aplicaact($act, $idac, 'Renombrando tablas');
} 

$idac = '1.2-sx';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "
-- Tomado de http://wiki.postgresql.org/wiki/SoundexESP
-- Oliver Mazariegos http://www.grupovesica.com

CREATE OR REPLACE FUNCTION soundexesp(input text) RETURNS text
IMMUTABLE STRICT COST 500 LANGUAGE plpgsql
AS $$
DECLARE
	soundex text='';	
	-- para determinar la primera letra
	pri_letra text;
	resto text;
	sustituida text ='';
	-- para quitar adyacentes
	anterior text;
	actual text;
	corregido text;
BEGIN
       -- devolver null si recibi un string en blanco o con espacios en blanco
	IF length(trim(input))= 0 then
		RETURN NULL;
	end IF;
 
 
	-- 1: LIMPIEZA:
		-- pasar a mayuscula, eliminar la letra \"H\" inicial, los acentos y la enie
		-- 'holá coñó' => 'OLA CONO'
        input=translate(ltrim(trim(upper(input)),'H'),'ÑÁÉÍÓÚÀÈÌÒÙÜ',
            'NAEIOUAEIOUU');
 
		-- eliminar caracteres no alfabéticos (números, símbolos como &,%,\",*,!,+, etc.
		input=regexp_replace(input, '[^a-zA-Z]', '', 'g');
 
	-- 2: PRIMERA LETRA ES IMPORTANTE, DEBO ASOCIAR LAS SIMILARES
	--  'vaca' se convierte en 'baca'  y 'zapote' se convierte en 'sapote'
	-- un fenomeno importante es GE y GI se vuelven JE y JI; CA se vuelve KA, etc
	pri_letra =substr(input,1,1);
	resto =substr(input,2);
	CASE 
		when pri_letra IN ('V') then
			sustituida='B';
		when pri_letra IN ('Z','X') then
			sustituida='S';
		when pri_letra IN ('G') AND substr(input,2,1) IN ('E','I') then
			sustituida='J';
		when pri_letra IN('C') AND substr(input,2,1) NOT IN ('H','E','I') then
			sustituida='K';
		else
			sustituida=pri_letra;
 
	end case;
	--corregir el parametro con las consonantes sustituidas:
	input=sustituida || resto;		
 
	-- 3: corregir \"letras compuestas\" y volverlas una sola
	input=REPLACE(input,'CH','V');
	input=REPLACE(input,'QU','K');
	input=REPLACE(input,'LL','J');
	input=REPLACE(input,'CE','S');
	input=REPLACE(input,'CI','S');
	input=REPLACE(input,'YA','J');
	input=REPLACE(input,'YE','J');
	input=REPLACE(input,'YI','J');
	input=REPLACE(input,'YO','J');
	input=REPLACE(input,'YU','J');
	input=REPLACE(input,'GE','J');
	input=REPLACE(input,'GI','J');
	input=REPLACE(input,'NY','N');
	-- para debug:    --return input;
 
	-- EMPIEZA EL CALCULO DEL SOUNDEX
	-- 4: OBTENER PRIMERA letra
	pri_letra=substr(input,1,1);
 
	-- 5: retener el resto del string
	resto=substr(input,2);
 
	--6: en el resto del string, quitar vocales y vocales fonéticas
	resto=translate(resto,'@AEIOUHWY','@');
 
    --7: convertir las letras foneticamente equivalentes a numeros  
    --   (esto hace que B sea equivalente a V, C con S y Z, etc.)
	resto=translate(resto, 'BPFVCGKSXZDTLMNRQJ', '111122222233455677');
	-- así va quedando la cosa
	soundex=pri_letra || resto;
 
	--8: eliminar números iguales adyacentes (A11233 se vuelve A123)
	anterior=substr(soundex,1,1);
	corregido=anterior;
 
	FOR i IN 2 .. length(soundex) LOOP
		actual = substr(soundex, i, 1);
		IF actual <> anterior THEN
			corregido=corregido || actual;
			anterior=actual;			
		END IF;
	END LOOP;
	-- así va la cosa
	soundex=corregido;
 
	-- 9: siempre retornar un string de 4 posiciones
	soundex=rpad(soundex,4,'0');
	soundex=substr(soundex,1,4);		
 
	-- YA ESTUVO
	RETURN soundex;	
END;	
$$
        ", false
    );
    aplicaact($act, $idac, 'Función Soundex en Español');
} 

$idac = '1.2-fun';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE usuario DROP COLUMN diasedicion", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario RENAME id TO nusuario", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ADD column id INTEGER", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario 
        ADD COLUMN fechacreacion DATE NOT NULL DEFAULT '2001-01-01'", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario " .
        "ADD COLUMN fechadeshabilitacion DATE DEFAULT NULL " .
        "CHECK (fechadeshabilitacion IS NULL OR " .
        "fechadeshabilitacion >= fechacreacion)", false
    );
    hace_consulta(
        $db, "UPDATE usuario set id=funcionario.id, fechadeshabilitacion=NULL 
        FROM funcionario WHERE 
        funcionario.nombre = usuario.nusuario", false
    );
    hace_consulta(
        $db, "INSERT INTO usuario (id, nusuario, password, nombre, descripcion,
        rol, idioma, fechadeshabilitacion) 
        (SELECT id, nombre, '', nombre, '', 4, 'es_CO', current_date 
        FROM funcionario WHERE nombre NOT IN 
        (SELECT nusuario FROM usuario))", false
    );
    hace_consulta(
        $db, "CREATE UNIQUE INDEX usuario_nusuario ON usuario 
        USING btree (nusuario)", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario DROP CONSTRAINT usuario_pkey", false
    );
    aplicaact($act, $idac, 'Fusiona tablas usuario y funcionario 1');
} 


$idac = '1.2-fu2';
if (!aplicado($idac)) {
    # Si hay inconsistencias en usuarios el siguiente falla
    
    hace_consulta(
        $db, "ALTER TABLE usuario DROP CONSTRAINT IF EXISTS usuario_pkey CASCADE"
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ADD CONSTRAINT usuario_pkey
        PRIMARY KEY (id)"
    );
    hace_consulta(
        $db, "ALTER TABLE etiquetacaso RENAME TO caso_etiqueta", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta DROP CONSTRAINT caso_etiqueta_pkey", 
        false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta DROP CONSTRAINT etiquetacaso_pkey", 
        false, false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_funcionario 
        DROP CONSTRAINT caso_funcionario_pkey", false
    );

    hace_consulta(
        $db, "ALTER TABLE caso_funcionario 
        DROP CONSTRAINT caso_funcionario_id_funcionario_fkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta 
        DROP CONSTRAINT caso_etiqueta_id_funcionario_fkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta 
        DROP CONSTRAINT etiquetacaso_id_funcionario_fkey", false, false
    );

    hace_consulta(
        $db, "ALTER TABLE funcionario DROP CONSTRAINT funcionario_pkey", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_funcionario RENAME TO caso_usuario", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_usuario 
        RENAME id_funcionario TO id_usuario", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta 
        RENAME id_funcionario TO id_usuario", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_usuario DROP
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_usuario DROP CONSTRAINT \"$1\"", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta
        ADD CONSTRAINT caso_etiqueta_id_usuario_fkey
        FOREIGN KEY (id_usuario) REFERENCES usuario(id)", false
    );
    hace_consulta(
        $db, "ALTER TABLE caso_etiqueta
        ADD CONSTRAINT caso_etiqueta_pkey
        PRIMARY KEY (id_caso, id_etiqueta, id_usuario, fecha)", false
    );
    hace_consulta(
        $db, "ALTER TABLE funcionario RENAME TO obsoleto_funcionario", false
    );
    hace_consulta(
        $db, "ALTER TABLE obsoleto_funcionario DROP CONSTRAINT 
        funcionario_nombre_key", false
    );
    hace_consulta(
        $db, "ALTER TABLE obsoleto_funcionario ALTER COLUMN id 
        SET DEFAULT NULL", false
    );
    hace_consulta(
        $db, "ALTER SEQUENCE funcionario_seq RENAME TO usuario_seq", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ALTER COLUMN id 
        SET DEFAULT nextval('usuario_seq')"
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ALTER COLUMN rol 
        SET DEFAULT '4'"
    );

    aplicaact($act, $idac, 'Fusiona tablas usuario y funcionario 2');
}

$idac = '1.2-bc';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN 
        email VARCHAR(255) NOT NULL DEFAULT ''", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN 
        encrypted_password VARCHAR(255) NOT NULL DEFAULT ''", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN 
        sign_in_count INTEGER NOT NULL DEFAULT 0", 
        false
    );
    hace_consulta(
        $db, "UPDATE usuario SET email=(nusuario || '@localhost') 
        WHERE email = ''", false
    );
    hace_consulta(
        $db, "CREATE UNIQUE INDEX index_usuario_on_email ON usuario 
        USING btree (email)",
        false
    );
 
    aplicaact(
        $act, $idac, 'Emplea bcrypt para calcular condensado de claves '
        . ' y agrega inforación a tablas para hacer compatible con '
        . ' autenticación con Devise/Ruby'
    );
}

$idac = '1.2-nc';
if (!aplicado($idac)) {

    hace_consulta($db, $q, false);
    hace_consulta(
        $db,
        "ALTER TABLE comunidad_sectorsocial 
        RENAME COLUMN id_sector TO id_sectorsocial", false
    );

    aplicaact($act, $idac, 'Nombre en Sector Social de Victima Colectiva');
}

$idac = '1.2-def';
if (!aplicado($idac)) {
    $basicas = html_menu_toma_url($GLOBALS['menu_tablas_basicas']);
    global $dbnombre;
    $v = null;
    $enl = parse_ini_file(
        $_SESSION['dirsitio'] . "/DataObjects/" .
        $GLOBALS['dbnombre'] . ".links.ini",
        true
    );
    foreach ($enl as $t => $e) {
        $db2 = new DB_DataObject();
        sin_error_pear($db2);
        $do = $db2->factory($t);
        if (!PEAR::isError($do)) {
            foreach ($e as $c => $rel) {
                if (strpos($c, ',') === false) {
                    $pd = strpos($rel, ':');
                    $ndo = substr($rel, 0, $pd);
                    $ids = valorSinInfo($do, $c);
                    if ($ids >= 0 && ($ndo != 'presponsable' 
                        || $c == 'organizacionarmada')
                    ) {
                        $q = "ALTER TABLE $t ALTER COLUMN $c SET DEFAULT '$ids'";
                        hace_consulta($db, $q, false);
                    }
                } 
            }
        }
    }
    aplicaact($act, $idac, 'Valores por defecto en referencias a tablas básicas');
}

$idac = '1.2-db';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "
        INSERT INTO filiacion (id, nombre, fechacreacion) 
        SELECT 15, 'MARCHA PATRIÓTICA', '2014-02-14'
        WHERE
        NOT EXISTS (
            SELECT id FROM filiacion WHERE id = 15
        );"
    );


    aplicaact($act, $idac, 'Datos para tablas básicas');
}

$idac = '1.2-fam';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE trelacion ADD COLUMN 
        inverso VARCHAR(2) REFERENCES trelacion(id)", false
    );
    hace_consulta(
        $db, "ALTER TABLE trelacion DROP COLUMN dirigido", false
    );
    hace_consulta(
        $db, "INSERT INTO trelacion (id, nombre, fechacreacion)
        VALUES ('PM', 'PRIMA(O)', '2014-02-18')", false
    );
    hace_consulta(
        $db, "INSERT INTO trelacion (id, nombre, fechacreacion)
        VALUES ('YE', 'NUERA/YERNO', '2014-02-18')", false
    );
    hace_consulta(
        $db, "INSERT INTO trelacion (id, nombre, fechacreacion)
        VALUES ('NO', 'NIETA(O)', '2014-02-18')", false
    );
    hace_consulta(
        $db, "INSERT INTO trelacion (id, nombre, fechacreacion)
        VALUES ('AH', 'AHIJADA(O)', '2014-02-18')", false
    );
    hace_consulta(
        $db, "INSERT INTO trelacion (id, nombre, fechacreacion)
        VALUES ('OO', 'SOBRINA(O)', '2014-02-18')", false
    );
    hace_consulta(
        $db, "INSERT INTO trelacion (id, nombre, fechacreacion)
        VALUES ('SG', 'SUEGRA(O)', '2014-02-18')", false
    );
    hace_consulta(
        $db, "INSERT INTO trelacion 
        (id, nombre, observaciones, fechacreacion, 
        fechadeshabilitacion, inverso) VALUES ('HO', 'HIJASTRA(O)', '', 
        '2011-05-02', NULL, NULL);", false
    );
    hace_consulta(
        $db, "INSERT INTO trelacion (id, nombre, observaciones, 
        fechacreacion, fechadeshabilitacion, inverso) 
        VALUES ('PD', 'MADRASTRA(PADRASTRO)', '', 
        '2011-09-21', NULL, 'HO');", false
    );

    hace_consulta(
        $db, "INSERT INTO trelacion (id, nombre, observaciones, 
        fechacreacion, fechadeshabilitacion, inverso) 
        VALUES ('SO', 'ESPOSA(O)/COMPAÑERA(O)', '', 
        '2001-01-01', NULL, 'SO');", false
    );

    foreach (array("AO" => "AB", "HA" => "HO", "HR" => "HE", "MA" => "PO",
        "ME" => "PA", "TA" => "TO", "CO" => "SO", "SA" => "SO",
        "NA" => "NO", "HT" => "HO", "OA" => "OO", "Pr" => "PM",
        "Pm" => "PM", "MD" => "PD", "NU" => "YE") as $ant => $nue
    ) {
        hace_consulta(
            $db, "UPDATE persona_trelacion SET id_trelacion='$nue' 
            WHERE id_trelacion='$ant'"
        );
        hace_consulta(
            $db, "DELETE FROM trelacion WHERE id='$ant'"
        );
    }
    hace_consulta(
        $db, "UPDATE trelacion SET 
        nombre='ESPOSA(O)/COMPAÑERA(O)' WHERE id='SO'"
    );
    hace_consulta($db, "UPDATE trelacion SET nombre='ABUELA(O)' WHERE id='AB'");
    hace_consulta($db, "UPDATE trelacion SET nombre='NIETA(O)' WHERE id='NO'");
    hace_consulta(
        $db, "UPDATE trelacion SET nombre='MADRE/PADRE' WHERE id='PA'"
    );
    hace_consulta($db, "UPDATE trelacion SET nombre='HIJA(O)' WHERE id='HI'");
    hace_consulta(
        $db, "UPDATE trelacion SET nombre='HERMANA(O)' WHERE id='HE'"
    );
    hace_consulta(
        $db, "UPDATE trelacion SET nombre='MADRINA/PADRINO' WHERE id='PO'"
    );
    hace_consulta(
        $db, "UPDATE trelacion SET nombre='AHIJADA(O)' WHERE id='AH'"
    );
    hace_consulta($db, "UPDATE trelacion SET nombre='TIA(O)' WHERE id='TO'");
    hace_consulta(
        $db, "UPDATE trelacion SET nombre='SOBRINA(O)' WHERE id='OO'"
    );
    hace_consulta(
        $db, "UPDATE trelacion SET nombre='MADRASTRA(PADRASTRO)' WHERE id='PD'"
    );
    hace_consulta(
        $db, "UPDATE trelacion SET nombre='HIJASTRA(O)' WHERE id='HO'"
    );
    hace_consulta($db, "UPDATE trelacion SET nombre='SUEGRA(O)' WHERE id='SG'");

    foreach (array("SO" => "SO", "AB" => "NO", "PA" => "HI",
        "HE" => "HE", "PO" => "AH", "TO" => "OO",
        "PD" => "HO", "SG" => "YE", "PM" => "PM") as $r1 => $r2
    ) {
        hace_consulta(
            $db, "UPDATE trelacion SET inverso='$r2' WHERE id='$r1'"
        );
        hace_consulta(
            $db, "UPDATE trelacion SET inverso='$r1' WHERE id='$r2'"
        );
    }

    hace_consulta(
        $db, "INSERT INTO persona_trelacion 
        (persona1, persona2, id_trelacion) 
        (SELECT persona2, persona1, inverso  
        FROM persona_trelacion, trelacion 
        WHERE (persona2, persona1, inverso) NOT IN 
        (SELECT persona1 as p1, persona2 as p2, id_trelacion as it 
        FROM persona_trelacion) 
        AND persona_trelacion.id_trelacion=trelacion.id
        AND trelacion.inverso IS NOT NULL)"
    );
    aplicaact($act, $idac, 'Relaciones familiares refinadas');
}

$idac = '1.2-et';
if (!aplicado($idac)) {

    // 24 y 21 repetidos
    hace_consulta($db, "UPDATE victima SET id_etnia='21' WHERE id_etnia='24'");
    hace_consulta($db, "DELETE FROM etnia WHERE id='24'");

    $ae =array(
        array(1, 'SIN INFORMACIÓN', ''),
        array(2, 'MESTIZO', ''),
        array(3, 'BLANCO', ''),
        array(4, 'NEGRO', '200'),
        array(5, 'INDÍGENA', ''),
        array(6, 'ACHAGUA', '1'),
        array(7, 'ANDAKÍ', ''),
        array(8, 'ANDOQUE', '3'),
        array(9, 'ARHUACO', '4'),
        array(10, 'AWA', '5'),
        array(11, 'BARÁ', '6'),
        array(12, 'BARASANA', '7'),
        array(13, 'BARÍ', '8'),
        array(14, 'CAMSA - KAMSA', '35'),
        array(15, 'CARIJONA', '13'),
        array(16, 'COCAMA', '16'),
        array(17, 'COFÁN', '18'),
        array(18, 'COREGUAJE - KOREGUAJE', '37'),
        array(19, 'CUBEO', '20'),
        array(20, 'CUIBA', '21'),
        array(21, 'CHIMILA', ''),
        array(22, 'DESANO', '23'),
        array(23, 'EMBERA', '25'),
        array(25, 'GUAMBIANO', '29'),
        array(26, 'GUANANO - GUANACA', '30'),
        array(27, 'GUAYABERO', '31'),
        array(28, 'HUITOTO - WITOTO', '73'),
        array(29, 'INGA', '34'),
        array(30, 'JUPDA', ''),
        array(31, 'KARAPANA - CARAPANA', '12'),
        array(32, 'KOGUI', '36'),
        array(33, 'CURRIPACO', '22'),
        array(34, 'MACUNA', '41'),
        array(35, 'MACAGUAJE', '39'),
        array(36, 'MOCANÁ', ''),
        array(37, 'MUISCA', '46'),
        array(38, 'NASA - PAÉZ', '49'),
        array(39, 'NUKAK', ''),
        array(40, 'PASTOS', '50'),
        array(41, 'PIAPOCO', '51'),
        array(42, 'PIJAO', ''),
        array(43, 'PIRATAPUYO', '53'),
        array(44, 'PUINAVE', '55'),
        array(45, 'SÁLIBA', '56'),
        array(46, 'SIKUANI', '57'),
        array(47, 'SIONA', '58'),
        array(48, 'TATUYO', '64'),
        array(49, 'TINIGUA', ''),
        array(50, 'TUCANO', '67'),
        array(51, 'UMBRÁ', ''),
        array(52, 'U´WA', '70'),
        array(53, 'WAYUU', '72'),
        array(54, 'WIWA - WIWUA', '74'),
        array(55, 'WOUNAAN', '75'),
        array(56, 'YAGUA', '76'),
        array(57, 'YANACONA', '77'),
        array(58, 'YUCUNA', '79'),
        array(59, 'YUKPA', ''),
        array(60, 'ROM', '400')
    );
    $ue = "http://www.mineducacion.gov.co/1621/"
        . "articles-255690_archivo_xls_listado_etnias.xls";
    foreach ($ae as $g) {
        $d = $g[2];
        if ($d != '') {
            $d .= " en $ue";
        }
        $q = "UPDATE etnia SET nombre='{$g[1]}', 
            descripcion='{$d} '
            WHERE id='{$g[0]}'";
        hace_consulta($db, $q);
    }

    $ng = array(
        array(61, 'AMORUA', '2'),
        array(62, 'BETOYE', '9'),
        array(63, 'BORA', '10'),
        array(64, 'CABIYARI', '11'),
        array(65, 'CARAMANTA', '84'),
        array(66, 'CHAMI', '86'),
        array(67, 'CHIMILA', '14'),
        array(68, 'CHIRICOA', '15'),
        array(69, 'COCONUCO', '17'),
        array(70, 'COROCORO', '87'),
        array(71, 'COYAIMA-NATAGAIMA', '19'),
        array(72, 'DATUANA', '88'),
        array(73, 'DUJOS', '24'),
        array(74, 'EMBERA CATIO', '26'),
        array(75, 'EMBERA CHAMI', '27'),
        array(76, 'EMBERA SIAPIDARA', '28'),
        array(77, 'KATIO', '85'),
        array(78, 'LETUAMA', '38'),
        array(79, 'MASIGUARE', '42'),
        array(80, 'MATAPI', '43'),
        array(81, 'MUINANE', '45'),
        array(82, 'MURA', '90'),
        array(83, 'NONUYA', '47'),
        array(84, 'OCAINA', '48'),
        array(85, 'PAYOARINI', '91'),
        array(86, 'PIAROA', '52'),
        array(87, 'PISAMIRA', '54'),
        array(88, 'POLINDARA', '94'),
        array(89, 'QUIYASINGAS', '93'),
        array(90, 'SIRIANO', '59'),
        array(91, 'SIRIPU', '60'),
        array(92, 'TAIWANO', '61'),
        array(93, 'TAMA', '92'),
        array(94, 'TANIMUKA', '62'),
        array(95, 'TARIANO', '63'),
        array(96, 'TIKUNAS', '65'),
        array(97, 'TULE', '68'),
        array(98, 'TUYUCA', '69'),
        array(99, 'WANANO', '71'),
        array(100, 'YAUNA', '78'),
        array(101, 'YUKO', '80'),
        array(102, 'GARÚ', '89'),
        array(103, 'GUAYUÚ', '32'),
        array(104, 'HITNÚ', '33'),
        array(105, 'MACÚ', '40'),
        array(106, 'MIRAÑA', '44'),
        array(107, 'TOTORÓ', '66'),
        array(108, 'YURUTÍ', '82'),
        array(109, 'YURÍ', '81'),
        array(110, 'ZENÚ', '83 ')
    );
    foreach ($ng as $g) {
        $q = "INSERT INTO etnia (id, nombre, descripcion, fechacreacion) 
            VALUES ({$g[0]}, '{$g[1]}', '{$g[2]} en {$ue}', '2014-05-30')";
        hace_consulta($db, $q, false);
    }

    aplicaact($act, $idac, 'Listado de etnias mejorado');
}


$idac = '1.2-ig';
if (!aplicado($idac)) {

    hace_consulta($db, "UPDATE iglesia SET nombre=UPPER(nombre);", false);

    aplicaact($act, $idac, 'Listado de iglesias mejorado');
}

$idac = '1.2-lo';
if (!aplicado($idac)) {
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN failed_attempts INTEGER", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN unlock_token VARCHAR(64)", false
    );
    hace_consulta(
        $db, "ALTER TABLE usuario ADD COLUMN locked_at TIMESTAMP", false
    );

    aplicaact(
        $act, $idac, 
        'Cuentas se bloquean tras varios intentos fallidos de ingreso'
    );
}

$idac = '1.2-sxe';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "CREATE OR REPLACE FUNCTION soundexespm(in_text TEXT) 
        RETURNS TEXT AS
$$
SELECT ARRAY_TO_STRING(ARRAY_AGG(soundexesp(s)),' ')
FROM (SELECT UNNEST(STRING_TO_ARRAY(
		REGEXP_REPLACE(TRIM($1), '  *', ' '), ' ')) AS s                
	      ORDER BY 1) AS n;
$$
        LANGUAGE SQL IMMUTABLE;"
    );

    hace_consulta(
        $db, "CREATE MATERIALIZED VIEW vvictimasoundexesp AS
        SELECT victima.id_caso, persona.id AS id_persona,
        (persona.nombres || ' ' || persona.apellidos) AS nomap,
        soundexespm(nombres || ' ' || apellidos) as nomsoundexesp 
        FROM persona, victima
        WHERE persona.id=victima.id_persona;"
    );

    aplicaact(
        $act, $idac, 
        'Vista con soundexesp de nombres de personas'
    );
}

$idac = '1.2-sexo';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "CREATE OR REPLACE FUNCTION divarr(in_array ANYARRAY) 
RETURNS SETOF TEXT as
$$
    SELECT ($1)[s] FROM generate_series(1,array_upper($1, 1)) AS s;
$$
    LANGUAGE SQL IMMUTABLE;"
    );
    hace_consulta(
        $db, "CREATE TYPE nomcod AS (nombre VARCHAR(100), caso INTEGER);"
    );
    hace_consulta(
        $db, "CREATE OR REPLACE FUNCTION divarr_concod(in_array ANYARRAY, 
	in_integer INTEGER) RETURNS SETOF nomcod as
$$
    SELECT ($1)[s],$2 FROM generate_series(1,array_upper($1, 1)) AS s;
$$
    LANGUAGE SQL IMMUTABLE;"
    );
    hace_consulta(
        $db, "CREATE MATERIALIZED VIEW nmujeres AS 
        SELECT  (p).nombre, COUNT((p).caso) AS frec
        FROM (SELECT 
            divarr_concod(string_to_array(trim(nombres), ' '), id_caso) AS p 
            FROM persona, victima WHERE victima.id_persona=persona.id 
            AND sexo='F' ORDER BY 1) AS r 
        GROUP BY 1 ORDER BY 2;"
    );
    hace_consulta(
        $db, "CREATE MATERIALIZED VIEW nhombres AS 
        SELECT  (p).nombre, COUNT((p).caso) AS frec
        FROM (SELECT 
            divarr_concod(string_to_array(nombres, ' '), id_caso) AS p 
            FROM persona, victima WHERE victima.id_persona=persona.id 
            AND sexo='M' ORDER BY 1) AS r 
        GROUP BY 1 ORDER BY 2;"
    );
    hace_consulta(
        $db, "CREATE OR REPLACE FUNCTION probcadm(in_text TEXT) 
        RETURNS NUMERIC AS
$$
	SELECT CASE WHEN (SELECT SUM(frec) FROM nmujeres)=0 THEN 0
		WHEN (SELECT COUNT(*) FROM nmujeres WHERE nombre=$1)=0 THEN 0
		ELSE (SELECT frec/(SELECT SUM(frec) FROM nmujeres) 
			FROM nmujeres WHERE nombre=$1)
		END
$$
LANGUAGE SQL IMMUTABLE;"
    );
    hace_consulta(
        $db, "CREATE OR REPLACE FUNCTION probcadh(in_text TEXT) 
        RETURNS NUMERIC AS
$$
	SELECT CASE WHEN (SELECT SUM(frec) FROM nhombres)=0 THEN 0
		WHEN (SELECT COUNT(*) FROM nhombres WHERE nombre=$1)=0 THEN 0
		ELSE (SELECT frec/(SELECT SUM(frec) FROM nhombres) 
			FROM nhombres WHERE nombre=$1)
		END
$$
LANGUAGE SQL IMMUTABLE;"
    );
    hace_consulta(
        $db, "CREATE OR REPLACE FUNCTION probhombre(in_text TEXT) 
        RETURNS NUMERIC AS
$$
	SELECT sum(ppar) FROM (SELECT p, peso*probcadh(p) AS ppar FROM (
		SELECT p, CASE WHEN rnum=1 THEN 100 ELSE 1 END AS peso 
		FROM (SELECT p, row_number() OVER () AS rnum FROM 
			divarr(string_to_array(trim($1), ' ')) AS p) 
		AS s) AS s2) AS s3;
$$
    LANGUAGE SQL IMMUTABLE;"
    );
    hace_consulta(
        $db, "CREATE OR REPLACE FUNCTION probmujer(in_text TEXT) 
        RETURNS NUMERIC AS
$$
	SELECT sum(ppar) FROM (SELECT p, peso*probcadm(p) AS ppar FROM (
		SELECT p, CASE WHEN rnum=1 THEN 100 ELSE 1 END AS peso 
		FROM (SELECT p, row_number() OVER () AS rnum FROM 
			divarr(string_to_array(trim($1), ' ')) AS p) 
		AS s) AS s2) AS s3;
$$
    LANGUAGE SQL IMMUTABLE;"
    );

    aplicaact($act, $idac, 'Valida sexo de víctimas con modelo prob.'); 
}

$idac = '1.2-apn';
if (!aplicado($idac)) {

    hace_consulta(
        $db, "CREATE MATERIALIZED VIEW napellidos AS 
        SELECT  (p).nombre as apellido, COUNT((p).caso) AS frec
        FROM (SELECT 
            divarr_concod(string_to_array(trim(apellidos), ' '), id_caso) 
            AS p 
            FROM persona, victima WHERE victima.id_persona=persona.id 
            ORDER BY 1) AS r 
        GROUP BY 1 ORDER BY 2;"
    );
    hace_consulta(
        $db, "CREATE OR REPLACE FUNCTION probcadap(in_text TEXT) 
            RETURNS NUMERIC AS
$$
    SELECT CASE WHEN (SELECT SUM(frec) FROM napellidos)=0 THEN 0
        WHEN (SELECT COUNT(*) FROM napellidos WHERE apellido=$1)=0 THEN 0
        ELSE (SELECT frec/(SELECT SUM(frec) FROM napellidos) 
            FROM napellidos WHERE apellido=$1)
        END
$$
        LANGUAGE SQL IMMUTABLE"
    );
    hace_consulta(
        $db, "CREATE OR REPLACE FUNCTION probapellido(in_text TEXT) 
        RETURNS NUMERIC AS
$$
	SELECT sum(ppar) FROM (SELECT p, probcadap(p) AS ppar FROM (
		SELECT p FROM divarr(string_to_array(trim($1), ' ')) AS p) 
		AS s) AS s2;
$$
        LANGUAGE SQL IMMUTABLE; "
    );
    
    aplicaact($act, $idac, 'Valida apellidos con modelo prob.'); 
}

$idac = '1.2-nre';
if (!aplicado($idac)) {
    hace_consulta($db, "UPDATE victima SET id_etnia='21' WHERE id_etnia='67'", false);
    hace_consulta($db, "UPDATE etnia SET descripcion='14 en http://www.mineducacion.gov.co/1621/articles-255690_archivo_xls_listado_etnias.xls' WHERE id='21'", false);
    hace_consulta($db, "DELETE FROM etnia WHERE id='67'", false);

    aplicaact($act, $idac, 'Eliminada etnia repetida');
}

$idac = '1.2-mp2';
if (!aplicado($idac)) {

    hace_consulta($db, "UPDATE victima SET id_filiacion='15' WHERE id_filiacion='16' AND 'MARCHA PATRIOTICA' IN (SELECT nombre FROM filiacion WHERE id='16') AND 'MARCHA PATRIÓTICA' IN (SELECT nombre FROM filiacion WHERE id='15')");
    hace_consulta($db, "DELETE FROM filiacion WHERE id='16' AND nombre='MARCHA PATRIOTICA'");
    hace_consulta($db, "UPDATE filiacion SET nombre='MOVIMIENTO POLÍTICO Y SOCIAL MARCHA PATRIÓTICA' WHERE id='15' AND nombre='MARCHA PATRIÓTICA'"); 

    aplicaact($act, $idac, 'Arreglada marcha patriotica redundante');
}

$idac = '1.2-vv2';
if (!aplicado($idac)) {

    hace_consulta($db, "DROP MATERIALIZED VIEW IF EXISTS nmujeres");
    hace_consulta($db, "CREATE MATERIALIZED VIEW nmujeres AS 
	SELECT  s.nombre, COUNT(*) AS frec
	FROM (SELECT 
		divarr(string_to_array(trim(nombres), ' ')) AS nombre 
		FROM persona, victima WHERE victima.id_persona=persona.id 
		AND sexo='F') AS s 
	GROUP BY s.nombre ORDER BY frec;");
    hace_consulta($db, "DROP MATERIALIZED VIEW IF EXISTS nhombres");
    hace_consulta($db, "CREATE MATERIALIZED VIEW nhombres AS 
	SELECT  s.nombre, COUNT(*) AS frec
	FROM (SELECT 
		divarr(string_to_array(trim(nombres), ' ')) AS nombre
		FROM persona, victima WHERE victima.id_persona=persona.id 
		AND sexo='M') AS s
	GROUP BY s.nombre ORDER BY frec;");
    hace_consulta($db, "DROP MATERIALIZED VIEW IF EXISTS napellidos");
    hace_consulta($db, "CREATE MATERIALIZED VIEW napellidos AS 
	SELECT  s.apellido, COUNT(*) AS frec
	FROM (SELECT 
		divarr(string_to_array(trim(apellidos), ' ')) AS apellido
		FROM persona, victima WHERE victima.id_persona=persona.id) AS s 
	GROUP BY s.apellido ORDER BY frec;");
    hace_consulta($db, "DROP FUNCTION IF EXISTS 
        divarr_concod(ANYARRAY, INTEGER)");
    hace_consulta($db, "DROP TYPE IF EXISTS nomcod");

    aplicaact($act, $idac, 'Mejora velocidad de determinación probabilistica de sexo con base en nombres');
}

$idac = '1.2-in';
if (!aplicado($idac)) {
    hace_consulta($db, "CREATE OR REPLACE VIEW iniciador AS (
     SELECT caso_usuario.id_caso,
        caso_usuario.fechainicio AS fecha_inicio,
        min(caso_usuario.id_usuario) AS id_funcionario
       FROM caso_usuario,
        ( SELECT funcionario_caso_1.id_caso,
                min(funcionario_caso_1.fechainicio) AS m
               FROM caso_usuario funcionario_caso_1
              GROUP BY funcionario_caso_1.id_caso) c
      WHERE caso_usuario.id_caso = c.id_caso AND caso_usuario.fechainicio = c.m
      GROUP BY caso_usuario.id_caso, caso_usuario.fechainicio
      ORDER BY caso_usuario.id_caso, caso_usuario.fechainicio
      ); ");
    aplicaact($act, $idac, 'Vista de usuario que inicia caso');
}


$idac = '1.2-ma1i';
if (!aplicado($idac)) {
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (17, '2002-07-16', '2002-07-16', 1, 'A', NULL, NULL, 'I', 'SECUESTRO');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (31, '2002-07-16', '2002-07-16', 3, 'A', NULL, NULL, 'I', 'DESAPARICION POR INTOLERANCIA SOCIAL');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (52, '2002-07-16', '2002-07-16', 2, 'B', NULL, NULL, 'I', 'HERIDOS');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (60, '2002-07-16', '2002-07-16', 1, 'C', NULL, NULL, 'I', 'HOSTIGAMIENTO');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (61, '2002-07-16', '2002-07-16', 1, 'C', NULL, NULL, 'O', 'ASALTO - TOMA');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (70, '2002-07-16', '2002-07-16', 1, 'D', NULL, NULL, 'I', 'HOMICIDIO FC');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (71, '2002-07-16', '2002-07-16', 1, 'D', NULL, NULL, 'I', 'HERIDO FC');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (76, '2002-07-16', '2002-07-16', 1, 'D', NULL, NULL, 'I', 'DESPLAZAMIENTO FORZADO');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (81, '2002-07-16', '2002-07-16', 2, 'D', NULL, NULL, 'O', 'OLEODUCTOS');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (82, '2002-07-16', '2002-07-16', 2, 'D', NULL, NULL, 'O', 'INFRAESTRUCTURA ELECTRICA Y COMUNICACIONES');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (83, '2002-07-16', '2002-07-16', 2, 'D', NULL, NULL, 'O', 'INFRAESTRUCTURA VIAL');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (901, '2000-08-09', '2001-07-11', 3, 'D', NULL, NULL, 'I', 'COMUNIDAD DESPLAZADA');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (94, '2002-07-23', '2002-07-23', 3, 'D', NULL, NULL, 'O', 'MATERIAL BÉLICO ABANDONADO');", false);
    hace_consulta($db, "INSERT INTO categoria (id, fechacreacion, fechadeshabilitacion, id_supracategoria, id_tviolencia, id_pconsolidado, contadaen, tipocat, nombre) VALUES (99, '2000-08-09', '2001-05-23', 3, 'D', NULL, NULL, 'I', 'DESPLAZAMIENTO FORZADO');", false);

    aplicaact($act, $idac, 'Actualización marco conceptual, inserta');
}

$idac = '1.2-ma1r';
if (!aplicado($idac)) {
    hace_consulta($db, "UPDATE categoria SET nombre='VIOLACIÓN' WHERE id='291';");
    hace_consulta($db, "UPDATE categoria SET nombre='EMBARAZO FORZADO' WHERE id='292';");
    hace_consulta($db, "UPDATE categoria SET nombre='PROSTITUCIÓN FORZADA' WHERE id='293';");
    hace_consulta($db, "UPDATE categoria SET nombre='ESTERELIZACIÓN FORZADA' WHERE id='294';");
    hace_consulta($db, "UPDATE categoria SET nombre='ESCLAVITUD SEXUAL' WHERE id='295';");
    hace_consulta($db, "UPDATE categoria SET nombre='ABUSO SEXUAL' WHERE id='296';");
    hace_consulta($db, "UPDATE categoria SET nombre='ABORTO FORZADO' WHERE id='297';");
    hace_consulta($db, "UPDATE categoria SET nombre='VIOLACIÓN' WHERE id='391';");
    hace_consulta($db, "UPDATE categoria SET nombre=' EMBARAZO FORZADO' WHERE id='392';");
    hace_consulta($db, "UPDATE categoria SET nombre='PROSTITUCIÓN FORZADA' WHERE id='393';");
    hace_consulta($db, "UPDATE categoria SET nombre='ESTERELIZACIÓN FORZADA' WHERE id='394';");
    hace_consulta($db, "UPDATE categoria SET nombre='ESCLAVITUD SEXUAL' WHERE id='395';");
    hace_consulta($db, "UPDATE categoria SET nombre='ABUSO SEXUAL' WHERE id='396';");
    hace_consulta($db, "UPDATE categoria SET nombre='ABORTO FORZADO' WHERE id='397';");
    hace_consulta($db, "UPDATE categoria SET nombre='RAPTO' WHERE id='58';");
    hace_consulta($db, "UPDATE categoria SET nombre='DESAPARICIÓN' WHERE id='79';");
    hace_consulta($db, "UPDATE categoria SET nombre='VIOLACIÓN' WHERE id='191';");
    hace_consulta($db, "UPDATE categoria SET nombre='EMBARAZO FORZADO' WHERE id='192';");
    hace_consulta($db, "UPDATE categoria SET nombre='PROSTITUCIÓN FORZADA' WHERE id='193';");
    hace_consulta($db, "UPDATE categoria SET nombre='ESTERELIZACIÓN FORZADA' WHERE id='194';");
    hace_consulta($db, "UPDATE categoria SET nombre='ESCLAVITUD SEXUAL' WHERE id='195';");
    hace_consulta($db, "UPDATE categoria SET nombre='ABUSO SEXUAL' WHERE id='196';");
    hace_consulta($db, "UPDATE categoria SET nombre='ABORTO FORZADO' WHERE id='197';");
    hace_consulta($db, "UPDATE categoria SET nombre='BLOQUEO DE VÍAS' WHERE id='66';");

    aplicaact($act, $idac, 'Actualización marco conceptual, renombra');
}

$idac = '1.2-ma1p';
if (!aplicado($idac)) {
    hace_consulta($db, "UPDATE categoria SET id_pconsolidado='24' WHERE id='41';");
    hace_consulta($db, "UPDATE categoria SET id_pconsolidado='23' WHERE id='48';");
    hace_consulta($db, "UPDATE categoria SET id_pconsolidado='23' WHERE id='58';");
    hace_consulta($db, "UPDATE categoria SET id_pconsolidado='22' WHERE id='78';");

    aplicaact($act, $idac, 'Actualización marco conceptual, consolidado');
}

$idac = '1.2-ma1t';
if (!aplicado($idac)) {
    hace_consulta($db, "UPDATE categoria SET tipocat='O' WHERE id='64';");
    hace_consulta($db, "UPDATE categoria SET tipocat='O' WHERE id='65';");
    hace_consulta($db, "UPDATE categoria SET tipocat='O' WHERE id='910';");

    aplicaact($act, $idac, 'Actualización marco conceptual, tipo');
}

# Cambio a marco conceptual en 2017
$idac = '1.2-mc2017';
if (!aplicado($idac)) {
    hace_consulta($db, "
    BEGIN;
    UPDATE categoria SET nombre='LESIÓN FÍSICA' WHERE id in ('13', '23', '33', '43', '53'); -- HERIDO->LESIÓN FÍSICA
   
    -- A 
    UPDATE categoria SET nombre='DESAPARICIÓN FORZADA' WHERE id in ('11', '21', '302'); -- Añadida FORZADA

    UPDATE categoria SET fechadeshabilitacion=NULL, fechacreacion='2017-05-05',
    nombre='COLECTIVO LESIONADO' WHERE id='17';

    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('231', 'COLECTIVO LESIONADO', 'A', '2', 'C', '2017-05-05');
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('331', 'COLECTIVO LESIONADO', 'A', '3', 'C', '2017-05-05');

    UPDATE categoria SET nombre='DESPLAZAMIENTO FORZADO' WHERE id in ('102'); -- COLECTIVO -> FORZADO
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('27', 'DESPLAZAMIENTO FORZADO', 'A', '2', 'C', '2017-05-05');
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('34', 'DESPLAZAMIENTO FORZADO', 'A', '3', 'C', '2017-05-05');

    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('141', 'JUDICIALIZACIÓN ARBITRARIA', 'A', '1', 'I', '2017-05-05');
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('241', 'JUDICIALIZACIÓN ARBITRARIA', 'A', '2', 'I', '2017-05-05');
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('341', 'JUDICIALIZACIÓN ARBITRARIA', 'A', '3', 'I', '2017-05-05');

    UPDATE categoria SET nombre='VIOLACION' WHERE id IN  ('191', '291', '391'); -- Se quita V.S. -
    UPDATE categoria SET nombre='EMBARAZO FORZADO' WHERE id IN ('192', '292', '392'); -- Se quita V.S. -
    UPDATE categoria SET nombre='PROSTITUCIÓN FORZADA' WHERE id IN ('193', '293', '393'); -- Se quita V.S. -
    UPDATE categoria SET nombre='ESTERILIZACIÓN FORZADA' WHERE id IN ('194', '294', '394'); -- Se quita V.S. -
    UPDATE categoria SET nombre='ESCLAVITUD SEXUAL' WHERE id IN ('195', '295', '395'); -- Se quita V.S. -
    UPDATE categoria SET nombre='ABUSO SEXUAL' WHERE id IN ('196', '296', '396'); -- Se quita V.S. -
    UPDATE categoria SET nombre='ABORTO FORZADO' WHERE id IN ('197', '297', '397'); -- Se quita V.S. -
    
    UPDATE categoria SET nombre='CONFINAMIENTO COLECTIVO' WHERE id in ('104'); -- Se quita COMO REPRESALIA O CASTIGO
 
    -- B 
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('402', 'COLECTIVO LESIONADO', 'B', '1', 'C', '2017-05-05');
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('502', 'COLECTIVO LESIONADO', 'B', '2', 'C', '2017-05-05');

    UPDATE categoria SET nombre='RAPTO' WHERE id='58'; --Era DESAPARICIÓN


    -- C
    UPDATE categoria SET nombre='AMETRALLAMIENTO Y/O BOMBARDEO' WHERE id in ('65');
    UPDATE categoria SET nombre='ATAQUE A OBJETIVO MILITAR' WHERE id in ('67'); -- era OBJETIVOS MILITARES
    UPDATE categoria SET fechadeshabilitacion='2017-05-03' WHERE id in ('910'); -- ENFRENTAMIENTO INTERNO

    --D
    UPDATE categoria SET nombre='TOMA DE REHENES' WHERE id='74'; -- era DE REHÉN

    UPDATE categoria SET fechadeshabilitacion=NULL, fechacreacion='2017-05-05',
        nombre='DESAPARICIÓN FORZADA' WHERE id='76';

    UPDATE categoria SET nombre='ESCUDO INDIVIDUAL' WHERE id in ('78');
    UPDATE categoria SET nombre='MUERTO POR ATAQUE A BIENES CIVILES' WHERE id in ('87'); -- EN->POR
    UPDATE categoria SET nombre='LESIÓN POR ATAQUE A BIENES CIVILES' WHERE id in ('88'); -- EN->POR, HERIDO->LESIÓN
    UPDATE categoria SET nombre='MUERTO POR OBJETIVOS, MÉTODOS Y MEDIOS ILÍCITOS' WHERE id in ('97'); -- Añadido OBJETIVOS
    UPDATE categoria SET nombre='LESIÓN POR OBJETIVOS, MÉTODOS Y MEDIOS ILÍCITOS' WHERE id in ('98'); -- Añadido OBJETIVOS, HERIDO -> LESIÓN
    UPDATE categoria SET nombre='LESIÓN A PERSONA PROTEGIDA' WHERE id in ('702'); -- HERIDO INTENCIONAL -> LESIÓN A

    UPDATE categoria SET nombre='HOMICIDIO INTENCIONAL DE PERSONA PROTEGIDA ' WHERE id='701'; -- Se agregó DE
    UPDATE categoria SET nombre='CIVIL MUERTO EN ACCIÓN BÉLICA' WHERE id='703'; -- ACCIONES BÉLICAS -> ACCIÓN BÉLICA
    UPDATE categoria SET nombre='LESIÓN A CIVILES EN ACCIÓN BÉLICA' WHERE id in ('704'); -- CIVIL HERIDO -> LESIÓN A CIVILES

    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('705', 'COLECTIVO LESIONADO POR INFRACCIONES AL DIHC', 'D', '1', 'C', '2017-05-05'); 
   
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('714', 'ESCLAVITUD Y TRABAJOS FORZADOS', 'D', '1', 'I', '2017-05-05'); 
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('715', 'JUDICIALIZACIÓN ARBITRARIA', 'D', '1', 'I', '2017-05-05'); 
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('716', 'NEGACIÓN DE DERECHOS A PRISIONEROS DE GUERRA', 'D', '1', 'I', '2017-05-05'); 
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('717', 'NEGACIÓN DE ATENCIÓN A PERSONAS VULNERABLES', 'D', '1', 'I', '2017-05-05'); 
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('718', 'PROFANACIÓN Y OCULTAMIENTO DE CADÁVERES', 'D', '1', 'I', '2017-05-05'); 

    UPDATE categoria SET nombre='DESPLAZAMIENTO FORZADO' WHERE id ='903'; -- COLECTIVO DESPLAZADO -> DESPLAZAMIENTO FORZADO
    UPDATE categoria SET nombre='COLECTIVO ESCUDO' WHERE id ='904'; -- ESCUDO -> COLECTIVO ESCUDO
    UPDATE categoria SET nombre='CONFINAMIENTO COLECTIVO' WHERE id ='906'; -- COMO REPRESALIA O CASTIGO COLECTIVO -> COLECTIVO


    UPDATE supracategoria SET 
      nombre='OBJETIVOS, MÉTODOS Y MÉDIOS ILÍCITOS' WHERE id_tviolencia='D' AND id='2'; -- Era BIENES
    UPDATE supracategoria SET 
      fechadeshabilitacion='2017-05-03' WHERE id_tviolencia='D' AND id='3'; -- MÉTODOS
    UPDATE categoria SET id_supracategoria='2' 
      WHERE id_tviolencia='D' AND id_supracategoria='3';

    UPDATE categoria SET nombre='MEDIO AMBIENTE' 
      WHERE id ='84'; -- Se quitó INFRACCIÓN CONTRA
    UPDATE categoria SET nombre='BIENES CULTURALES Y RELIGIOSOS' 
      WHERE id ='85'; -- Se quitó INFRACCIÓN CONTRA
    UPDATE categoria SET nombre='HAMBRE COMO MÉTODO DE GUERRA' 
      WHERE id ='86'; -- Era BIENES INDISPENSABLES PARA LA SUPERV. DE LA POB.
    UPDATE categoria SET nombre='ESTRUCTURA VIAL' 
      WHERE id ='89'; -- Se quitó INFRACCIÓN CONTRA LA
    UPDATE categoria SET nombre='ATAQUE A OBRAS E INST. QUE CONT. FUERZAS PELIGR.' 
      WHERE id ='801'; -- Era ATAQUE A OBRAS / INSTALACIONES QUE CONT. FUERZAS PELGIROSAS
    UPDATE categoria SET nombre='ATAQUE INDISCRIMINADO' WHERE id ='90'; -- Era AMETRALLAMIENTO Y/O BOMBARDEO INDISCRIMINADO
    UPDATE categoria SET nombre='ARMAS ABSOLUTAMENTE PROHIBIDAS' WHERE id ='92'; -- Era ARMA PROHIBIDA
    UPDATE categoria SET nombre='EMPLEO ILÍCITO DE ARMAS DE USO RESTRINGIDO' WHERE id ='93'; -- Era MINA ILÍCITA / ARMA TRAMPA
    UPDATE categoria SET nombre='MISIÓN MÉDICA O SANITARIA' WHERE id ='707'; -- Era INFRACCIÓN CONTRA MISIÓN MÉDICA
    UPDATE categoria SET nombre='MISIÓN RELIGIOSA' WHERE id ='708'; -- Se quitó INFRACCIÓN CONTRA
    UPDATE categoria SET nombre='MISIÓN HUMANITARIA' WHERE id ='709'; -- Se quitó INFRACCIÓN CONTRA

    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('710', 'MISIONES DE PAZ', 'D', '2', 'O', '2017-05-05'); 
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('711', 'MISIÓN INFORMATIVA', 'D', '2', 'O', '2017-05-05'); 
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('712', 'ZONAS HUMANITARIAS', 'D', '2', 'O', '2017-05-05'); 
    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      VALUES ('713', 'CONVERSACIONES DE PAZ', 'D', '2', 'O', '2017-05-05'); 

    UPDATE categoria SET nombre='DESPLAZAMIENTO FORZADO' WHERE id ='902'; -- Se quitó COLECTIVO

    INSERT INTO categoria (id, nombre, id_tviolencia, id_supracategoria, tipocat, fechacreacion)
      vaLUES ('905', 'GUERRA SIN CUARTEL', 'D', '2', 'O', '2017-05-05'); 

    -- Pregunta, desplazamiento forzado es colectivo, que tal desplazamiento forzado colectivo
    COMMIT;
    ");

    aplicaact($act, $idac, 'Actualización marco conceptual 2017');
}


$idac = '1.2-cons17';
if (!aplicado($idac)) {
    hace_consulta($db, "
      BEGIN;
        UPDATE pconsolidado SET rotulo='MUERTOS-DH', tipoviolencia='DH', clasificacion='VIDA' WHERE id='1';
        UPDATE pconsolidado SET rotulo='MUERTOS-DIHC', tipoviolencia='DIHC', clasificacion='VIDA' WHERE id='2';
        UPDATE pconsolidado SET rotulo='MUERTOS-VPS', tipoviolencia='VPS', clasificacion='VIDA' WHERE id='3';
        UPDATE pconsolidado SET rotulo='DESAPARICIÓN-DH', tipoviolencia='DH', clasificacion='LIBERTAD' WHERE id='4';
        UPDATE pconsolidado SET rotulo='DESAPARICIÓN-DIHC', tipoviolencia='DIHC', clasificacion='LIBERTAD' WHERE id='5';
        UPDATE pconsolidado SET rotulo='TORTURA-DH', tipoviolencia='DH', clasificacion='INTEGRIDAD' WHERE id='6';
        UPDATE pconsolidado SET rotulo='TORTURA-VPS', tipoviolencia='VPS', clasificacion='INTEGRIDAD' WHERE id='7';
        UPDATE pconsolidado SET rotulo='TORTURA-DIHC', tipoviolencia='DIHC', clasificacion='INTEGRIDAD' WHERE id='8';
        UPDATE pconsolidado SET rotulo='LESIONADOS-DH', tipoviolencia='DH', clasificacion='INTEGRIDAD' WHERE id='9';
        UPDATE pconsolidado SET rotulo='LESIONADOS-VPS', tipoviolencia='VPS', clasificacion='INTEGRIDAD' WHERE id='10';
        UPDATE pconsolidado SET rotulo='LESIONADOS-DIHC', tipoviolencia='DIHC', clasificacion='INTEGRIDAD' WHERE id='11';
        UPDATE pconsolidado SET rotulo='DETENCIÓN ARBITRARÌA-DH', tipoviolencia='DH', clasificacion='LIBERTAD' WHERE id='12';
        UPDATE pconsolidado SET rotulo='AMENAZA-DH', tipoviolencia='DH', clasificacion='VIDA' WHERE id='13';
        UPDATE pconsolidado SET rotulo='AMENAZA-VPS', tipoviolencia='VPS', clasificacion='VIDA' WHERE id='14';
        UPDATE pconsolidado SET rotulo='AMENAZA-DIHC', tipoviolencia='DIHC', clasificacion='VIDA' WHERE id='15';
        UPDATE pconsolidado SET rotulo='ATENTADO-DH', tipoviolencia='DH', clasificacion='VIDA' WHERE id='16'; 
        UPDATE pconsolidado SET rotulo='ATENTADO-VPS', tipoviolencia='VPS', clasificacion='VIDA' WHERE id='17'; 
        UPDATE pconsolidado SET rotulo='JUDICIALIZACIÓN ARBITRARIA-DH', tipoviolencia='DH', clasificacion='LIBERTAD' WHERE id='18'; 
        UPDATE pconsolidado SET rotulo='JUDICIALIZACIÓN ARBITRARIA-DIHC', tipoviolencia='DIHC', clasificacion='LIBERTAD' WHERE id='19'; 
        UPDATE pconsolidado SET rotulo='VIOLENCIA SEXUAL-DH', tipoviolencia='DH', clasificacion='INTEGRIDAD' WHERE id='20'; 
        UPDATE pconsolidado SET rotulo='VIOLENCIA SEXUAL-VPS', tipoviolencia='VPS', clasificacion='INTEGRIDAD' WHERE id='21'; 
        UPDATE pconsolidado SET rotulo='VIOLENCIA SEXUAL-DIHC', tipoviolencia='DIHC', clasificacion='INTEGRIDAD' WHERE id='22'; 
        UPDATE pconsolidado SET rotulo='DEPORTACIÓN-DH', tipoviolencia='DH', clasificacion='LIBERTAD' WHERE id='23'; 
        UPDATE pconsolidado SET rotulo='SECUESTRO-VPS', tipoviolencia='VPS', clasificacion='LIBERTAD' WHERE id='24'; 
        UPDATE pconsolidado SET rotulo='RAPTO-VPS', tipoviolencia='VPS', clasificacion='LIBERTAD' WHERE id='25'; 
      COMMIT;
     ");
    hace_consulta($db, "
      BEGIN;
        UPDATE categoria SET id_pconsolidado=NULL;
        DELETE FROM pconsolidado WHERE id>='26' AND id<='32';
      COMMIT;
     ");
    hace_consulta($db, "
      BEGIN;
        INSERT INTO pconsolidado ( id, rotulo, tipoviolencia, clasificacion, peso, fechacreacion) VALUES ('26', 'TOMA DE REHENES-DIHC', 'DIHC', 'LIBERTAD', '0', '2017-08-29'); 
        INSERT INTO pconsolidado ( id, rotulo, tipoviolencia, clasificacion, peso, fechacreacion) VALUES ('27', 'ESCLAVITUD Y TRABAJOS FORZADOS-DIHC', 'DH', 'LIBERTAD', '0', '2017-08-29'); 
      BEGIN;
        INSERT INTO pconsolidado ( id, rotulo, tipoviolencia, clasificacion, peso, fechacreacion) VALUES ('28', 'NEGACIÓN DE DERECHOS A PRISIONEROS DE GUERRA-DIHC', 'DIHC', 'VIDA', '0', '2017-08-29'); 
        INSERT INTO pconsolidado ( id, rotulo, tipoviolencia, clasificacion, peso, fechacreacion) VALUES ('29', 'NEGACIÓN DE ATENCIÓN A PERSONAS VULNERABLES-DIHC', 'DIHC', 'VIDA', '0', '2017-08-29'); 
        INSERT INTO pconsolidado ( id, rotulo, tipoviolencia, clasificacion, peso, fechacreacion) VALUES ('30', 'PROFANACIÓN Y OCULTAMIENTO DE CADAVERES-DIHC', 'DIHC', 'LIBERTAD', '0', '2017-08-29'); 
        INSERT INTO pconsolidado ( id, rotulo, tipoviolencia, clasificacion, peso, fechacreacion) VALUES ('31', 'RECLUTAMIENTO DE MENORES-DIHC', 'DIHC', 'LIBERTAD', '0', '2017-08-29'); 
        INSERT INTO pconsolidado ( id, rotulo, tipoviolencia, clasificacion, peso, fechacreacion) VALUES ('32', 'ESCUDO INDIVIDUAL-DIHC', 'DIHC', 'VIDA', '0', '2017-08-29'); 
      COMMIT;
      ");

    hace_consulta($db, "
      BEGIN;
        UPDATE categoria SET id_pconsolidado='1' WHERE id IN ('10', '20', '30');
        UPDATE categoria SET id_pconsolidado='2' WHERE id IN ('87', '97', '701', '703');
        UPDATE categoria SET id_pconsolidado='3' WHERE id IN ('40', '50');
        UPDATE categoria SET id_pconsolidado='4' WHERE id IN ('11', '21', '302');
        UPDATE categoria SET id_pconsolidado='5' WHERE id IN ('76');
        UPDATE categoria SET id_pconsolidado='6' WHERE id IN ('12', '22', '36');
        UPDATE categoria SET id_pconsolidado='7' WHERE id IN ('47', '56');
        UPDATE categoria SET id_pconsolidado='8' WHERE id IN ('72');
        UPDATE categoria SET id_pconsolidado='9' WHERE id IN ('13', '23', '33');
        UPDATE categoria SET id_pconsolidado='10' WHERE id IN ('43', '53');
        UPDATE categoria SET id_pconsolidado='11' WHERE id IN ('88', '98', '702', '704');
        UPDATE categoria SET id_pconsolidado='12' WHERE id IN ('14', '24', '301');
        UPDATE categoria SET id_pconsolidado='13' WHERE id IN ('15', '25', '35');
        UPDATE categoria SET id_pconsolidado='14' WHERE id IN ('45', '55');
        UPDATE categoria SET id_pconsolidado='15' WHERE id IN ('73');
        UPDATE categoria SET id_pconsolidado='16' WHERE id IN ('16', '26', '37');
        UPDATE categoria SET id_pconsolidado='17' WHERE id IN ('46', '57');
        UPDATE categoria SET id_pconsolidado='18' WHERE id IN ('141', '241', '341');
        UPDATE categoria SET id_pconsolidado='19' WHERE id IN ('715');
        UPDATE categoria SET id_pconsolidado='20' WHERE id IN ('19', '29', '39');
        UPDATE categoria SET id_pconsolidado='21' WHERE id IN ('420', '520');
        UPDATE categoria SET id_pconsolidado='22' WHERE id IN ('77');
        UPDATE categoria SET id_pconsolidado='23' WHERE id IN ('101');
        UPDATE categoria SET id_pconsolidado='24' WHERE id IN ('41');
        UPDATE categoria SET id_pconsolidado='25' WHERE id IN ('48', '58');
        UPDATE categoria SET id_pconsolidado='26' WHERE id IN ('74');
        UPDATE categoria SET id_pconsolidado='27' WHERE id IN ('714');
        UPDATE categoria SET id_pconsolidado='28' WHERE id IN ('716');
        UPDATE categoria SET id_pconsolidado='29' WHERE id IN ('717');
        UPDATE categoria SET id_pconsolidado='30' WHERE id IN ('718');
        UPDATE categoria SET id_pconsolidado='31' WHERE id IN ('75');
        UPDATE categoria SET id_pconsolidado='32' WHERE id IN ('78');
            
        --
      COMMIT;
    ");
    aplicaact($act, $idac, ' Cambio a consolidado victimas con nuevas categorias DIHC');
}

if (isset($GLOBALS['menu_tablas_basicas'])) {
    $hayrep = false;
    foreach ($GLOBALS['menu_tablas_basicas'] as $a) {
        if ($a['title'] == 'Reportes'
            || $a['sub'][0]['url'] == 'pconsolidado'
        ) {
            $hayrep = true;
        }
    }
    if (!$hayrep) {
        $a = $_SESSION['dirsitio'] . "/conf_int.php";
        echo "<font color='red'>En el arreglo <tt>menu_tablas_basicas</tt> " .
            "del archivo <tt>" .  htmlentities($a, ENT_COMPAT, 'UTF-8') . 
            "</tt> falta:
<pre>
    array('title' => 'Reportes', 'url'=> null, 'sub' => array(
        array('title'=>'Columnas de Reporte Consolidado',
            'url'=>'parametros_reporte_consolidado', 'sub'=>null),
        ),
    ),
</pre></font>";
    }
} else {
    echo "<font color='red'>No se encontró variable global " .
        "<tt>menu_tablas_basicas</tt></font>";
}

 
// Creando esquema
regenera_esquemas();

/**
 * Agrega el contendio del archivo $fuente al $destino
 *
 * @param string $fuente  Nombre de archivo fuente
 * @param string $destino Nombre de archivo destino
 * @param string $modo    Modo para abrir archivo destino
 *
 * @return void
 */
function agrega_archivo($fuente, $destino, $modo = "w")
{
    if (!($fen = fopen($fuente, "r"))) {
        die ("No se pudo leer $fuente");
    }
    if (!($fsal = fopen($destino, $modo))) {
        die ("No se pudo escribir $destino");
    }
    while (!feof($fen)) {
        $ll = fread($fen, 1024);
        if (fwrite($fsal, $ll) === false) {
            die("No se pudo escribir completo $destino");
        }
    }
    fclose($fen);
    fclose($fsal);
}

/**
 * Lee estructura-dataobject.ini y estructura-dataobject.links.ini
 * del directorio $nd/DataObjects/ y agrega datos a
 * $dirap/DataObjects/$dbnombre.ini y $dirap/DataObjects/$dbnombre.links.ini
 *
 * @param string $nd       Ruta
 * @param string $dbnombre Nombre de la base de datos
 * @param string $dirap    Directorio de SIVeL
 * @param string $modo     Modo para abrir archivo que crea
 *
 * @return void
 */
function lee_escritura($nd, $dbnombre, $dirap, $modo)
{
    $a = "$nd/DataObjects/estructura-dataobject.ini";
    if (!file_exists($a)) {
        echo "No puede leerse " . htmlentities($a, ENT_COMPAT, 'UTF-8') 
            . "<br>";
        return;
    }
    agrega_archivo($a, "$dirap/DataObjects/$dbnombre.ini", $modo); 

    $a = "$nd/DataObjects/estructura-dataobject.links.ini";
    if (!file_exists($a)) {
        echo "No puede leerse " . htmlentities($a, ENT_COMPAT, 'UTF-8') 
            . "<br>";
        return;
    }
    agrega_archivo($a, "$dirap/DataObjects/$dbnombre.links.ini", $modo);
}

if (!isset($_SESSION['SIN_INDICES']) || !$_SESSION['SIN_INDICES']) {
    echo "Actualizando indices<br>";
    $na = "prepara_indices.sql";
    $r = consulta_archivo($db, $na, false, false, false);
}

echo "Actualizando módulos<br>";
$lm = explode(" ", $modulos);
foreach ($lm as $m) {
    if ($m != '') {
        echo_esc("  $m");
        include_once "$m/actualiza.php";
        if (!isset($_SESSION['SIN_INDICES']) || !$_SESSION['SIN_INDICES']) {
            $na = "$m/prepara_indices.sql";
            $r = consulta_archivo($db, $na, false, false, false);
        }
    }
}

$ap = $_SESSION['dirsitio'] . '/actualiza.php';
if (file_exists($ap)) {
    echo_esc("Actualizando personalización ($ap)");
    include_once $ap;
    if (!isset($_SESSION['SIN_INDICES']) || !$_SESSION['SIN_INDICES']) {
        $na = "$m/prepara_indices.sql";
        $r = consulta_archivo($db, $na, false, false, false);
    }
}

echo '<table width="100%"><td style="white-space: nowrap; '
    . 'background-color: #CCCCCC;" align="left" valign="top" colspan="2">'
    . '<b><div align=right><a href="index.php">'
    . _('Men&uacute; Principal') . '</a></div></b>'
    . '</td></table>';

pie_envia();
?>
