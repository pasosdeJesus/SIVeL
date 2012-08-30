<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Presenta menu principal
 *
 * Basado en fuentes y documentación del paquete de Pear HTML_Menu_Renderer
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
 * Generador de un menu HTML_Menu
 */
require_once 'HTML/Menu/Renderer.php';


/**
 * Generador de un menu HTML_Menu
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
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
     * Termina el menú
     *
     * @param int $level profundidad actual en la estructura de árbol
     *
     * @return void
     */
    function finishMenu($level)
    {
        $this->_html = "<ul class='nav'>" . $this->_html."</ul></ul>";
    }


    /**
     * Completa el nivel del árbol (para los tipos 'tree' y 'sitemap')
     *
     * @param int $level profundidad actual en la estructura de árbol
     *
     * @return void
     */
    function finishLevel($level)
    {
    }


    /**
     * Completa fila en el menú
     *
     * @param int $level profundidad actual en la estructura de árbol
     *
     * @return void
     */
    function finishRow($level)
    {
    }


    /**
     * Genera el elmento del menú
     *
     * @param array $node  Elmento que se genera
     * @param int   $level profundidad actual en la estructura de árbol
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
        $this->_html .= _($node['title']);
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
