<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto tabla taccion
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

require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla taccion
 * Ver documentación de DataObjects_Caso.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Caso
 */
class DataObjects_Taccion extends DataObjects_Basica
{
    var $__table = 'taccion';                  // table name

    var $observaciones;                   // varchar(-1)  not_null

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Tipo de Acción');
    }


    var $fb_preDefOrder = array(
        'id',
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );

    var $fb_fieldsToRender = array(
        'nombre',
        'observaciones',
        'fechacreacion',
        'fechadeshabilitacion',
    );

    /**
     * Retorna valor SIN INFORMACION
     *
     * @return integer valor SIN INFORMACION
     */
    static function idSinInfo()
    {
        return 1;
    }

}

?>
