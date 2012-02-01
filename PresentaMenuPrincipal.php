<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Presenta menu principal
 *
 * Basado en fuentes y documentaci�n del paquete de Pear HTML_Menu_Renderer
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio p�blico. Sin garant�as.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico. Sin garant�as.
 * @version   CVS: $Id: PresentaMenuPrincipal.php,v 1.17.2.1 2011/09/14 14:56:18 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: S�LO DEFINICIONES
 */

/**
 * Generador de un menu HTML_Menu
 */
require_once 'HTML/Menu/Renderer.php';


/**
 * Generador de un menu HTML_Menu
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir T�mara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio P�blico.
 * @link     http://sivel.sf.net/tec
 */
class PresentaMenuPrincipal extends HTML_Menu_Renderer
{

    /**
     * HTML generado para el menu
     * @var string
     */
    var $_html = '';

    /**
     * Termina el men�
     *
     * @param int $level profundidad actual en la estructura de �rbol
     *
     * @return void
     */
    function finishMenu($level)
    {
        $this->_html = "<ul class='nav'>" . $this->_html."</ul></ul>";
    }


    /**
     * Completa el nivel del �rbol (para los tipos 'tree' y 'sitemap')
     *
     * @param int $level profundidad actual en la estructura de �rbol
     *
     * @return void
     */
    function finishLevel($level)
    {
    }


    /**
     * Completa fila en el men�
     *
     * @param int $level profundidad actual en la estructura de �rbol
     *
     * @return void
     */
    function finishRow($level)
    {
    }


    /**
     * Genera el elmento del men�
     *
     * @param array $node  Elmento que se genera
     * @param int   $level profundidad actual en la estructura de �rbol
     * @param int   $type  Tipo de elmento (una constante HTML_MENU_ENTRY_* )
     *
     * @return void
     */
    function renderEntry($node, $level, $type)
    {
        if ($level == 0 && $this->_html != '') {
            $this->_html .= "</ul></li>";
        }
        $this->_html .= "<li>";
        if ($level == 0) {
            $this->_html .= "<strong>";
        } else {
            $this->_html .= "<a href='" . $node['url'] . "'>";
        }
        $this->_html .= $node['title'];
        if ($level == 0) {
            $this->_html .= "</strong>";
            $this->_html .= "<ul>\n";
        } else {
            $this->_html .= "</a>";
            $this->_html .= "</li>\n";
        }
    }

     /**
      * Retorna el HTML del menu generado
      *
      * @return string
      */
    function toHtml()
    {
        return $this->_html;
    }

}

?>
