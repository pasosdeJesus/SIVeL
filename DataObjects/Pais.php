<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Objeto asociado a una tabla de la base de datos.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2014 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * Acceso: SÓLO DEFINICIONES
 */

/**
 * Definicion para la tabla pais
 */
require_once 'DataObjects/Basica.php';

/**
 * Definicion para la tabla pais
 * Ver documentación de DataObjects_Basica.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      DataObjects_Basica
 */
class DataObjects_Pais extends DataObjects_Basica
{
    var $__table = 'pais';                       
    var $latitud;
    var $longitud;
    var $div1;
    var $div2;
    var $div3;
    var $alfa2;
    var $alfa3;
    var $nombreiso;

    /**
     * Constructora
     * return @void
     */
    public function __construct()
    {
        parent::__construct();

        $this->nom_tabla = _('Pais');
        $this->fb_fieldLabels = array(
            'nombre' => _('Nombre'),
            'latitud'=> _('Latitud'),
            'longitud'=> _('Longitud'),
            'div1'=> _('Primer Nivel Divisón Politico-Administrativa'),
            'div2'=> _('Segundo Nivel Divisón Politico-Administrativa'),
            'div3'=> _('Tercer Nivel Divisón Politico-Administrativa'),
            'alfa2'=> _('Identificación ISO de 2 letras'),
            'alfa3'=> _('Identificación ISO de 3 letras'),
            'nombreiso'=> _('Nombre ISO'),
            'fechacreacion' => _('Fecha de Creación'),
            'fechadeshabilitacion' => _('Fecha de Deshabilitación'),
        );
    }

    var $fb_preDefOrder = array('id', 'nombre', 'latitud', 'longitud',
         'div1', 'div2', 'div3', 'alfa2', 'alfa3', 'nombreiso',
        'fechacreacion', 'fechadeshabilitacion'
    );
    var $fb_fieldsToRender = array('id', 'nombre', 'latitud', 'longitud',
         'div1', 'div2', 'div3', 'alfa2', 'alfa3', 'nombreiso',
        'fechacreacion', 'fechadeshabilitacion'
    );

    /**
     * Identificacion de registro 'SIN INFORMACIÓN'
     *
     * @return integer Id del registro SIN INFORMACIÓN
     */
    static function idSinInfo()
    {
        return 0;
    }
}
?>
