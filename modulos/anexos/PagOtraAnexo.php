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
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Pestaña Otras Fuentes con Anexo del multi-formulario capturar caso
 */

require_once 'PagOtrasFuentes.php';
require_once 'ResConsulta.php';


/**
 * Página otras fuentes con anexo.
 * Ver documentación de funciones en clase base.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      PagOtrasFuentes
 */
class PagOtraAnexo extends PagOtrasFuentes
{

    var $titulo = 'Otras Fuentes';

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
        if ($this->bcaso_fotra->_do->id_fotra != null) {
            $idcaso = $this->bcaso_fotra->_do->id_caso;
            $vf = "'{$this->bcaso_fotra->_do->id_fotra}'";
            $q =  "UPDATE anexo SET id_fotra=NULL " .
                "WHERE id_caso='$idcaso' AND id_fotra=$vf";
            $db = $this->bcaso_fotra->_do->getDatabaseConnection();
            hace_consulta($db, $q, false);
        }

        parent::elimina($valores);
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
        parent::eliminaDep($db, $idcaso);
        $q =  "UPDATE anexo SET id_fotra=NULL " .
            "WHERE id_caso='$idcaso'";
        hace_consulta($db, $q, false);
    }


    /**
     * Constructora.
     * Ver documentación completa en clase base.
     *
     * @param string $nomForma Nombre
     *
     * @return void
     */
    function PagOtraAnexo($nomForma)
    {
        parent::PagOtrasFuentes($nomForma);
        $this->titulo  = _('Otras Fuentes');
        if (isset($GLOBALS['etiqueta']['Otras Fuentes'])) {
            $this->titulo = $GLOBALS['etiqueta']['Otras Fuentes'];
            $this->tcorto = $GLOBALS['etiqueta']['Otras Fuentes'];
        }
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

        parent::formularioAgrega($db, $idcaso);

        if (!isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda'
        ) {
            if ($this->bcaso_fotra->_do->id_fotra != null) {
                $cor = "OR id_fotra=" .
                    "'{$this->bcaso_fotra->_do->id_fotra}' ";
            } else {
                $cor = "";
            }
            $condb = "WHERE id_caso='" . (int)$_SESSION['basicos_id'] . "' " .
                "AND (id_ffrecuente IS NULL) " .
                "AND (id_fotra IS NULL $cor)  " ;
            $an = $this->addElement(
                'select', 'id_anexo', _('Anexo'),
                array()
            );
            $q = "SELECT  id, archivo FROM anexo " .
                $condb .
                "ORDER BY archivo ";
            $options = array('' => '') +
                htmlentities_array($db->getAssoc($q));
            $an->loadArray($options);

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
        parent::formularioValores($db, $idcaso);

        $puesto = false;
        $sel = $this->getElement('id_anexo');
        if ($this->bcaso_fotra->_do->id_fotra != null) {
            $danexo = objeto_tabla('anexo');
            $danexo->id_caso = $_SESSION['basicos_id'];
            $danexo->id_fotra 
                = $this->bcaso_fotra->_do->id_fotra;
            $danexo->find();
            if ($danexo->fetch()) {
                $sel->setValue($danexo->id);
                $puesto = true;
            }
        }


        if ((!isset($_SESSION['forma_modo'])
            || $_SESSION['forma_modo'] != 'busqueda') 
            && !$puesto
        ) {
            $sel->setValue('');
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
        $idcaso = $_SESSION['basicos_id'];

        $db = $this->iniVar();

        $r = parent::procesa($valores);
        if (in_array(31, $_SESSION['opciones'])
            && !in_array(21, $_SESSION['opciones'])
        ) {
            return true;
        }


        if ($this->bcaso_fotra->_do->id_fotra != null) {
            $vf = "'{$this->bcaso_fotra->_do->id_fotra}'";
            if (isset($valores['id_anexo']) && $valores['id_anexo'] != '') {
                $ida = var_escapa($valores['id_anexo'], $db);
                $q =  "UPDATE anexo SET id_fotra=$vf " .
                    "WHERE id_caso='$idcaso' AND id='$ida'";
            } else {
                $q =  "UPDATE anexo SET id_fotra=NULL " .
                    " WHERE id_caso='$idcaso' AND id_fotra=$vf";
            }
            //echo $q;
            hace_consulta($db, $q, false);
        }

        caso_funcionario($_SESSION['basicos_id']);
        return $r;
    }

}

?>
