<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Página del multi-formulario para capturar caso (captura_caso.php).
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2006 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Pestaña Anexo de la ficha de captura de caso
 */
require_once 'PagBaseSimple.php';
require_once 'PagBaseMultiple.php';
require_once 'HTML/QuickForm/Action.php';

/**
 * Acción que responde al botón ver anexo
 *
 * PHP version 5
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  Dominio público.
 * @link     http://sivel.sf.net/tec
 */
class VerAnexo extends HTML_QuickForm_Action
{
    static function descarga_anexo($id_caso, $archivo) 
    { 
        $inin = $id_caso . "_";
        if (strpos($archivo, '/')  !== false) {
            die("No puede tener el caracter /");
        }
        if ((substr($archivo, 0, strlen($inin))) != $inin) {
            die("El archivo del archivo es incorrecto, " .
                "porque no comienza con '$inin'"
            );
        }
        $arch = $GLOBALS['dir_anexos'] . "/" . $archivo;
        //echo "OJO arch=$arch<br>";
        if (!file_exists($arch)) {
            die("No existe el archivo especificado");
        }
        $archivo = substr($archivo, strlen($inin));
        // Eliminado número de caso
        $ps = (int)strpos($archivo, "_");
        if ($ps < 1) {
            die("El nombre del archivo no es estándar");
        }
        $archivo = substr($archivo, $ps+1);
        header('HTTP/1.1 200 OK');
        header('Status: 200 OK');
        header('Accept-Ranges: bytes');
        header('Content-Transfer-Encoding: Binary');
        header('Content-Type: application/octet-stream');
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        //    header('Content-Disposition: inline');
        header("Content-Disposition: attachment; filename=\"{$archivo}\"");
        header("Content-Transfer-Encoding: binary");

        readfile($arch);
        exit(0);
    }


    /**
     * Ejecuta acción
     *
     * @param object &$page      Página
     * @param string $actionName Acción
     *
     * @return void
     */
    function perform(&$page, $actionName)
    {
        //echo "OJO <hr>"; debug_print_backtrace(); //die("x");
        if ($page->procesa($page->_submitValues)) {
            if (isset($page->_submitValues['archivo'])) {
                $nombre = var_escapa($page->_submitValues['archivo']);
                VerAnexo::descarga_anexo(
                    $_SESSION['basicos_id'], $nombre);
            }
        }
        $page->handle('display');
    }
}


/**
 * Anexo
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagBaseMultiple
 */
class PagAnexo extends PagBaseMultiple
{
    var $banexo;

    var $pref = "a";

    var $nuevaCopia = false;

    var $clase_modelo = 'anexo';

    var $titulo  = 'Anexos';

    /**
     * Pone en null variables asociadas a tablas de la pestaña.
     *
     * @return null
     */
    function nullVar()
    {
        $this->banexo = null;
    }

    /**
     * Retorna una identificación del registro actual.
     *
     * @return string Identifación
     */
    function copiaId()
    {
        return $this->banexo->id;
    }

    /**
     * Elimina de base de datos el registro actual.
     *
     * @param array &$valores Valores enviados por formulario.
     *
     * @return null
     */
    function elimina(&$valores)
    {
        $this->iniVar();
        if ($this->banexo->_do->id != null) {
            $this->banexo->_do->delete();
            $_SESSION['a_total']--;
        }
    }


    /**
     * Inicializa variables y datos de la pestaña.
     * Ver documentación completa en clase base.
     *
     * @param array $aper Arreglo de parametros. Vacio aqui.
     *
     * @return handle Conexión a base de datos
     */
    function iniVar($aper = null)
    {
        list($db, , $idcaso) = parent::iniVar(array(true, true));

        $ida = array();
        $tot = 0;
        $d =& objeto_tabla('anexo');
        if (PEAR::isError($d)) {
            die($d->getMessage());
        }
        $d->id_caso = $idcaso;
        $d->orderBy('id');
        $d->find();
        while ($d->fetch()) {
            $ida[] = $d->id;
            $tot++;
        }
        $_SESSION['a_total'] = $tot;
        $danexo =& objeto_tabla('anexo');
        if ($_SESSION['a_pag'] < 0 || $_SESSION['a_pag'] >= $tot) {
            $danexo->id = null;
            $danexo->id_caso = $idcaso;
        } else {
            $danexo->get($ida[$_SESSION['a_pag']]);
        }

        $this->banexo =& DB_DataObject_FormBuilder::create(
            $danexo,
            array('requiredRuleMessage' => $GLOBALS['mreglareq'],
                  'ruleViolationMessage' => $GLOBALS['mreglavio']
            )
        );
        return $db;
    }


    /**
     * Constructora.
     * Ver documentación completa en clase base.
     *
     * @param string $nomForma Nombre
     *
     * @return void
     */
    function PagAnexo($nomForma)
    {
        $this->PagBaseMultiple($nomForma);

        $this->titulo  = _('Anexos');
        $this->tcorto = _('Anexo');
        if (isset($GLOBALS['etiqueta']['Anexos'])) {
            $this->titulo = $GLOBALS['etiqueta']['Anexos'];
            $this->tcorto = $GLOBALS['etiqueta']['Anexos'];
        }
        $this->addAction('siguiente', new Siguiente());
        $this->addAction('anterior', new Anterior());
        $this->addAction('veranexo', new VerAnexo());
    }


    /**
     * Agrega elementos al formulario.
     * Ver documentación completa en clase base.
     *
     * @param handle &$db    Conexión a base de datos
     * @param string $idcaso Id del caso
     *
     * @return void
     *
     * @see PagBaseSimple
     */
    function formularioAgrega(&$db, $idcaso)
    {
        $this->banexo->createSubmit = 0;
        $this->banexo->useForm($this);
        $this->banexo->getForm();
        if (isset($this->banexo->_do->id)) {
            $this->addElement(
                'submit',
                $this->getButtonName('veranexo'), 'Ver anexo ' .
                $this->banexo->_do->archivo,
                ''
            );
        }
    }

    /**
     * Llena valores del formulario.
     * Ver documentación completa en clase base.
     *
     * @param handle  &$db    Conexión a base de datos
     * @param integer $idcaso Id del caso
     *
     * @return void
     * @see PagBaseSimple
     */
    function formularioValores(&$db, $idcaso)
    {
    }

    /**
     * Elimina registros de tablas relacionadas con caso de este formulario.
     * Ver documentación completa en clase base.
     *
     * @param handle  &$db    Conexión a base de datos
     * @param integer $idcaso Id del caso
     *
     * @return void
     * @see PagBaseSimple
     */
    static function eliminaDep(&$db, $idcaso)
    {
        assert($db != null);
        assert(isset($idcaso));
        $q = "DELETE FROM anexo " .
            "WHERE id_caso='$idcaso'";
        $result = hace_consulta($db, $q);
        if (PEAR::isError($result)) {
            echo_esc("Anexo: al eliminar ".$result->getMessage());
        }

    }

    /**
     * Procesa valores del formulario enviados por el usuario.
     * Ver documentación completa en clase base.
     *
     * @param handle &$valores Valores ingresados por usuario
     *
     * @return bool Verdadero si y solo si puede completarlo con éxito
     * @see PagBaseSimple
     */
    function procesa(&$valores)
    {
        $es_vacio
            = !isset($valores['descripcion'])
            || $valores['descripcion'] == '';

        if ($es_vacio) {
            return true;
        }
        if (!$this->validate() ) {
            return false;
        }
        if (in_array(31, $_SESSION['opciones'])
            && !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        $db = $this->iniVar();
        $this->banexo->_do->fecha = call_user_func(
            $this->banexo->dateToDatabaseCallback,
            var_escapa($valores['fecha'], $db)
        );
        $this->banexo->_do->descripcion
            = var_escapa($valores['descripcion'], $db);

        if (!isset($this->banexo->_do->id) || $this->banexo->_do->id <= 0) {
            $f = $this->banexo->getForm();
            $s = $f->getElement('archivo_sel');
            $v = $s->getValue();
            if (!isset($v['name']) || $v['name'] == '') {
                 die('Falta archivo por anexar');
            }
            $r=preg_match("/^[-0-9A-Za-z_\.\/]+$/", $v['name']);
            if ($r != 1) {
                die('Nombre tiene caracteres no admisibles " .
                    "--solo debe constar de digitos, letras, _ y .');
            }

            $this->banexo->_do->archivo = '';
            $this->banexo->_do->insert();

            $ida = $this->banexo->_do->id_caso."_".$this->banexo->_do->id;
            $nnom = $ida . "_".$v['name'];
            $nloc = $GLOBALS['dir_anexos'] . "/$nnom";
            if (file_exists($nloc)) {
                 die('Ya existe un archivo con ese nombre');
            }
            if (mb_strlen($nloc, "UTF-8") > 255) {
                die('Nombre tiene mas de 255 caracteres');
            }
            $rmuf = $s->moveUploadedFile($GLOBALS['dir_anexos'], $nnom);
            if (!$rmuf) {
                error_valida(
                    "No pudo moverse el archivo $nnom al directorio: " .
                    $GLOBALS['dir_anexos'] .
                    ".\n ¿El tamaño supera " .
                    ini_get('upload_max_filesize') . " o " . 
                    ini_get('post_max_size') . "?", $valores
                );
                 return false;
            }
            $this->banexo->_do->archivo = $nnom;
        }
        $valores['archivo'] = $this->banexo->_do->archivo;
        $this->banexo->_do->update();
        $ret = $this->process(array(&$this->banexo, 'processForm'), false);

        if (PEAR::isError($ret)) {
            die($ret->getMessage());
        }
        caso_usuario($_SESSION['basicos_id']);
        return  $ret;
    }

    /**
     * Prepara consulta SQL para buscar datos de este formulario.
     * Ver documentación completa en clase base.
     *
     * @param string &$w       Consulta que se construye
     * @param string &$t       Tablas
     * @param object &$db      Conexión a base de datos
     * @param object $idcaso   Identificación del caso
     * @param string &$subcons Subconsulta
     *
     * @return void
     * @see PagBaseSimple
     */
    function datosBusqueda(&$w, &$t, &$db, $idcaso, &$subcons)
    {
        consulta_or_muchos($w, $t, 'anexo');
    }

}
?>
