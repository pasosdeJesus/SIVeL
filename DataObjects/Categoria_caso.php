<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto asociado a una tabla de la base de datos.
 * Parcialmente generado por DB_DataObject.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla categoria_caso.
 */
require_once 'DB_DataObject_SIVeL.php';
require_once 'HTML/QuickForm.php';
/**
 * Definicion para la tabla categoria_caso.
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Categoria_caso extends DB_DataObject_SIVeL
{

    var $__table = 'categoria_caso';                  // table name
    var $id_caso;                         // int4(4)  multiple_key
    var $id_tviolencia;               // varchar(-1)  multiple_key
    var $id_supracategoria;               // int4(4)  multiple_key
    var $id_categoria;                    // int4(4)  multiple_key


    var $fb_hidePrimaryKey = true;

    /**
     * Genera formulario
     *
     * @param handle &$db      Conexión a BD.
     * @param object &$f       Formulario
     * @param bool   $action   Acción
     * @param string $target   Objetivo
     * @param string $formName Nombre
     * @param string $method   Método de envío
     *
     * @return Formulario generado
     */
    function &getForm2(&$db, &$f, $action = false, $target = '_self',
        $formName = 'CategoriaCaso', $method = 'post'
    ) {
        if (!$action) {
            $action = htmlspecialchars($_SERVER['REQUEST_URI']);
        }
        if ($f == null) {
            $f = new HTML_QuickForm(
                $formName, $method, $action,
                $target, null
            );
        }

        $sel=&$f->addElement(
            'select', 'clasificacion',
            'Clasificación de Violencia'
        );
        if (isset($GLOBALS['etiqueta']['clasificacion'])) {
            $e->setLabel($GLOBALS['etiqueta']['clasificacion']);
        }
        $tvio =  htmlentities_array(
            $db->getAssoc("SELECT id, nombre FROM tviolencia;")
        );
        //Aqui podria ser con WHERE fechadeshabilitacion<>null para
        //registros nuevos. Pero en registros antiguos no.

        foreach ($tvio as $idtvio => $nomtvio) {
            $scat =  htmlentities_array(
                $db->getAssoc(
                    "SELECT id, nombre " .
                    "FROM supracategoria WHERE " .
                    "id_tviolencia='$idtvio';"
                )
            );
            foreach ($scat as $idscat => $nomscat) {
                $cat =  htmlentities_array(
                    $db->getAssoc(
                        "SELECT id, nombre " .
                        "FROM categoria WHERE " .
                        "id_tviolencia='$idtvio' AND " .
                        "id_supracategoria='$idscat';"
                    )
                );
                foreach ($cat as $idcat => $nomcat) {
                    $n = $nomtvio . ":" . $nomscat . ":" . $nomcat;
                    $i = $idtvio . ":" . $idscat . ":" . $idcat;
                    $sel->addOption($n, $i);
                }
            }
        }
        $sel->setMultiple(true);

        $f->addRule(
            'clasificacion',
            'Por favor ingrese una clasificación', 'required'
        );
        return $f;
    }
}

?>
