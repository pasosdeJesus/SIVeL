<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Funciones para hacer reporte revista por rótulos
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: reporteRotulos.php,v 1.14.2.3 2011/10/18 16:05:05 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */


/**
 * Agrega tipo de orden a consulta web
 *
 * @param string pOrden   Orden por poner
 * @param array  opciones Opciones del usuario autenticado
 * @param object obj      Objeto HTML_QuickForm con formulario
 * @param array  ae       Arreglo de opciones de ordenamiento
 * @param object t        Opción por defecto
 */
function rotulos_cwebordenar($pOrden, $opciones, $obj, &$ae, &$t)
{
    $x =& $obj->createElement(
        'radio', 'ordenar', 'rotulo', 'Rótulo',
        'rotulo'
    );
    $ae[] =& $x;
    if ($pOrden == 'rotulo') {
        $t =& $x;
    }
}


/**
 * Agrega orden a consulta
 *
 * @param string q        Restricciones
 * @param string pOrdenar Forma de ordenamiento
 * @return void  Modifica q
 */
function rotulos_orden_cons(&$q, $pOrdenar)
{
    //echo "OJO ignorando rotulos_orden_cons"; return;
    $nq = "";
    if ($pOrdenar == 'rotulo') {
        $excvi = $excvc = '';
        if (isset($GLOBALS['estrotulos_excluirsinfiliacion'])
            && $GLOBALS['estrotulos_excluirsinfiliacion']) {
            $excvi = ' AND acto.id_persona IN ' .
            '(SELECT id_persona FROM victima WHERE id_filiacion<>\'' .
            DataObjects_Filiacion::idSinInfo() . '\') ';
            $excvc = 'AND actocolectivo.id_grupoper IN ' .
            '(SELECT id_grupoper FROM filiacion_comunidad ' .
            ' WHERE id_filiacion<>\'' .
            DataObjects_Filiacion::idSinInfo() . '\') ';
        }

        $nq = 'SELECT subt.* FROM ((SELECT sub.*, ' .
            'parametros_reporte_consolidado.peso as peso ' .
            ' FROM parametros_reporte_consolidado,' .
            'categoria, acto, (' . $q . ') AS sub WHERE ' .
            '(parametros_reporte_consolidado.no_columna=categoria.col_rep_consolidado ' .
            ' AND categoria.id=acto.id_categoria ' .
            ' AND acto.id_caso=sub.id ' .
            $excvi.
            ') )' .
            // colectivas
            'UNION (SELECT subc.*, parametros_reporte_consolidado.peso ' .
            'FROM parametros_reporte_consolidado,' .
            'categoria, actocolectivo, (' . $q . ') AS subc WHERE ' .
            '(parametros_reporte_consolidado.no_columna=categoria.col_rep_consolidado ' .
            'AND categoria.id = actocolectivo.id_categoria ' .
            'AND actocolectivo.id_caso = subc.id ' .
            $excvc.
            ') ) ' .
            // otros
            ' UNION (SELECT subo.*, parametros_reporte_consolidado.peso ' .
            'FROM parametros_reporte_consolidado,' .
            'categoria, categoria_p_responsable_caso, (' . $q . ') AS subo WHERE ' .
            '(parametros_reporte_consolidado.no_columna=categoria.col_rep_consolidado ' .
            'AND categoria.id = categoria_p_responsable_caso.id_categoria ' .
            'AND categoria_p_responsable_caso.id_caso = subo.id) ' .
            ')) AS subt ORDER BY subt.peso, subt.fecha ';
            //echo "OJO nq=$nq"; //die("x");
    }
    $q = $nq;
}


/**
 * Comienzo de un regitro en reporte revista
 *
 * @param object db Base de datos
 * @param array  campos por mostrar
 * @param string idcaso Código de caso
 * @param string numcaso Número de caso
 * @return string Cadena por añadir al comienzo
 */
function rotulos_inicial(&$db, $campos, $idcaso, $numcaso)
{
    $r = "";
    if ($numcaso != null) {
        $r .= " CASO $numcaso\n";
    }
    if (array_key_exists('m_victimas', $campos) && $numcaso != null) {
        $dvictima = objeto_tabla('victima');
        if (PEAR::isError($dvictima)) {
            die($dvictima->getMessage());
        }
        $dvictima->id_caso = $idcaso;
        $dvictima->orderBy('id_persona');
        $dvictima->find();
        $nns = array();
        $sep = "";
        while ($dvictima->fetch()) {
            $dpersona = $dvictima->getLink('id_persona');
            $nvt = strip_tags(
                trim($dpersona->nombres) . " " .
                trim($dpersona->apellidos)
            );
            if (substr($nvt, 0, 3) == "N N") {
                $rnvt = substr($nvt, 3);
                if ($rnvt == "") {
                    if (isset($nns['personas sin identificar'])) {
                        $rnvt = 'personas sin identificar';
                    } else if (isset($nns['1 persona sin identificar'])) {
                        $rnvt = 'personas sin identificar';
                        $nns['personas sin identificar'] = 1;
                        unset($nns['1 persona sin identificar']);
                    } else {
                        $rnvt = '1 persona sin identificar';
                    }
                }
                if (isset($nns[$rnvt])) {
                    $nns[$rnvt]++;
                } else {
                    $nns[$rnvt] = 1;
                }
            } else {
                $r .= $sep . $nvt;
                if (isset($dvictima->id_profesion)
                && $dvictima->id_profesion !=
                DataObjects_Profesion::idSinInfo()
                ) {
                    $dprofesion = $dvictima->getLink('id_profesion');
                    $r .= " - " .
                        prim_may(trim(strip_tags($dprofesion->nombre)));
                }
                if ($dvictima->id_filiacion !=
                DataObjects_Filiacion::idSinInfo()
                ) {
                    $dfiliacion = $dvictima->getLink('id_filiacion');
                    $r .= " - " .
                        prim_may(trim(strip_tags($dfiliacion->nombre)));
                }
                $r .= "\n";
            }
        }
        foreach ($nns as $tnn => $nnn) {
            if ($nnn > 1) {
                $r .= "$nnn " . trim($tnn) . "\n";
            } else {
                $r .= trim($tnn) . "\n";
            }
        }
        $dvictimac = objeto_tabla('victima_colectiva');
        if (PEAR::isError($dvictimac)) {
            die($dvictimac->getMessage());
        }
        $dvictimac->id_caso = $idcaso;
        $dvictimac->orderBy('id_grupoper');
        $dvictimac->find();
        while ($dvictimac->fetch()) {
            $duvc = $dvictimac->getLink('id_grupoper');
            $r .= $duvc->nombre . "\n";
        }
        $r .= "\n";
    }
    return $r;
}

/**
 * Termina un regitro en reporte revista
 *
 * @param object db Base de datos
 * @param array  campos por mostrar
 * @param string idcaso Código de caso
 * @param string numcaso Número de caso
 * @return string Cadena por añadir al final
 */

function rotulos_final(&$db, $campos, $idcaso, $numcaso = null)
{
    //echo "OJO rotulos_final de idcaso=$idcaso, numcaso=$numcaso<br>";
    $r = "";
    $peso = 0;
    $rotulo = "";
    if ($numcaso != null) {
        if (array_key_exists('m_presponsables', $campos)) {
            $dprespcaso = objeto_tabla('presuntos_responsables_caso');
            if (PEAR::isError($dprespcaso)) {
                die($dprespcaso->getMessage());
            }
            $dprespcaso->id_caso = $idcaso;
            $dprespcaso->orderBy('id');
            $dprespcaso->find();
            $sep = "Presuntos Responsables: ";
            while ($dprespcaso->fetch()) {
                $dresponsable = $dprespcaso->getLink('id_p_responsable');
                $r .= $sep . trim($dresponsable->nombre);
                $sep = " - ";
            }
        }
        if (array_key_exists('m_tipificacion', $campos)) {
            $lr = array();
            $dacto = objeto_tabla('acto');
            $dacto->id_caso = $idcaso;
            $dacto->orderBy('id_categoria');
            $dacto->find();
            while ($dacto->fetch()) {
                $dcategoria = $dacto->getLink('id_categoria');
                $dtipoviolencia = $dcategoria->getLink('id_tipo_violencia');
                $dsupracategoria = objeto_tabla('supracategoria');
                $dsupracategoria->id = $dcategoria->id_supracategoria;
                $dsupracategoria->id_tipo_violencia =
                    $dcategoria->id_tipo_violencia;
                $dsupracategoria->find(1);

                if ($dcategoria->col_rep_consolidado == null) {
                    echo "<hr>La categoria " . (int)$dcategoria->id .
                        " no tiene asociada " .
                        "una columna del reporte consolidado\n<hr>";
                }
                $drot = $dcategoria->getLink('col_rep_consolidado');
                $lr[trim($drot->rotulo)] = trim($drot->rotulo);
                if ($peso == 0 || $drot->peso < $peso) {
                    $peso = $drot->peso;
                    $rotulo = $drot->rotulo;
                }
            }
            $dactocolectivo = objeto_tabla('actocolectivo');
            $dactocolectivo->id_caso = $idcaso;
            $dactocolectivo->orderBy('id_categoria');
            $dactocolectivo->find();
            while ($dactocolectivo->fetch()) {
                $dcategoria = $dactocolectivo->getLink('id_categoria');
                $dtipoviolencia = $dcategoria->getLink('id_tipo_violencia');
                $dsupracategoria = objeto_tabla('supracategoria');
                $dsupracategoria->id = $dcategoria->id_supracategoria;
                $dsupracategoria->id_tipo_violencia =
                    $dcategoria->id_tipo_violencia;
                $dsupracategoria->find(1);

                if ($dcategoria->col_rep_consolidado == null) {
                    echo "<hr>La categoria " . (int)$dcategoria->id .
                        " no tiene asociada " .
                        "una columna del reporte consolidado\n<hr>";
                }
                $drot = $dcategoria->getLink('col_rep_consolidado');
                $lr[trim($drot->rotulo)] = trim($drot->rotulo);
                if ($peso == 0 || $drot->peso < $peso) {
                    $peso = $drot->peso;
                    $rotulo = $drot->rotulo;
                }
            }
            $catpresp = objeto_tabla('categoria_p_responsable_caso');
            $catpresp->id_caso = $idcaso;
            $catpresp->orderBy('id_categoria');
            $catpresp->find();
            while ($catpresp->fetch()) {
                $dcategoria = $catpresp->getLink('id_categoria');
                $dtipoviolencia = $dcategoria->getLink('id_tipo_violencia');
                $dsupracategoria = objeto_tabla('supracategoria');
                $dsupracategoria->id = $dcategoria->id_supracategoria;
                $dsupracategoria->id_tipo_violencia =
                    $dcategoria->id_tipo_violencia;
                $dsupracategoria->find(1);

                if ($dcategoria->col_rep_consolidado == null) {
                    echo "<hr>La categoria " . (int)$dcategoria->id .
                        " no tiene asociada " .
                        "una columna del reporte consolidado\n<hr>";
                } else {
                    $drot = $dcategoria->getLink('col_rep_consolidado');
                    $lr[trim($drot->rotulo)] = trim($drot->rotulo);
                    if ($peso == 0 || $drot->peso < $peso) {
                        $peso = $drot->peso;
                        $rotulo = $drot->rotulo;
                    }
                }
            }

            $r .= "\n";
            foreach ($lr as $k => $v) {
                $r .= $k . "\n";
            }
        }
    }

    return array($r, $peso, $rotulo);
}

?>
