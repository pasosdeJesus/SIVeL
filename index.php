<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:

/**
 * Menú de SIVeL.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2004 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 * @link      http://pear.php.net/manual/en/package.html.html-menu.intro.php
 */

/**
 * Menú de SIVeL.
 */
require_once 'misc.php';
require_once 'aut.php';
if (!file_exists($_SESSION['dirsitio'] . '/conf.php')) {
    die("No ha configurado fuentes");
} 
$fc = fopen($_SESSION['dirsitio'] . '/conf.php', 'r');
if (!$fc) {
    die(
        "No puede leerse " . $_SESSION['dirsitio'] . '/conf.php' 
        . " Mejore permisos"
    );
}
fclose($fc);
require_once $_SESSION['dirsitio'] . '/conf.php';
$aut_usuario = "";
$db = autentica_usuario($dsn, $aut_usuario, 0);
require_once $_SESSION['dirsitio'] . '/conf_int.php';

require_once 'HTML/Menu.php';
require_once 'confv.php';
require_once 'misc_caso.php';
require_once 'PresentaMenuPrincipal.php';

/**
 * Lee menú de base de datos y construye una estructura apropiada para
 * HTML/Menu.
 *
 * @param integer $id Identificación de menú por revisar
 *
 * @return array apropiado para HTML_Menu
 */
function bd_a_menu($id)
{
    //echo "OJO bd_a_menu($id)<br>";
    $r = array();
    foreach ($GLOBALS['m_opcion'] as $idop => $dop) {
        if ($dop['idpapa'] == $id) {
            if ($dop['url'] != '') {
                if (strchr($dop['url'], '?')) {
                    $url = str_replace('?', '.php?', $dop['url']);
                } else {
                    $url = $dop['url'] . '.php';
                }
            } else {
                $url = '';
            }
            $r[$idop] = array(
                'title' => $dop['nombre'],
                'url' => $url,
                'sub' => bd_a_menu($idop)
            );
        }
    }
    ksort($r);
    return $r;
}


/**
 * Presenta menú principal.
 *
 * @param handle &$db Conexión a base de datos
 *
 * @return void
 */
function menu_principal(&$db)
{

    $datMenu = bd_a_menu(0);
    $menu = new HTML_Menu($datMenu, 'sitemap');

    encabezado_envia(
        'SIVeL ' . $GLOBALS['PRY_VERSION'].
        ': ' . 'Sistema de Información de Violencia Política'
    );
    if ($GLOBALS['cabezote_principal'] != ''
        && file_exists($GLOBALS['cabezote_principal'])
    ) {
        muestra_archivo($GLOBALS['cabezote_principal']);
    } else {
        echo '<table width = "100%" align = "center" cellpadding = "0" '
            . ' border = "0" cellspacing = "0">';
        echo '<tr><td align = "center" padding = "0" class = "blanco"> '
            . ' <br><br></td></tr>';
        echo '</table>';
    }

    $renderer = new PresentaMenuPrincipal();
    $menu->render($renderer, '');
    $html_r = $renderer->toHtml();
    echo $html_r;
    if (isset($GLOBALS['centro_principal'])
        && file_exists($GLOBALS['centro_principal'])
    ) {
        $fh = fopen($GLOBALS['centro_principal'], 'rb');
        $html_contents = fread($fh, filesize($GLOBALS['centro_principal']));
        fclose($fh);
        echo $html_contents;
    } else {
        echo '<p>&nbsp;</p>';
    }
    echo '<table border = "0" width = "100%" ' .
        'style = "white-space: nowrap; background-color:#CCCCCC;"><tr>' .
        '<tr><td> ' .  $GLOBALS['REPORTA_FALLAS'] .
        '</td></tr></table>';

    pie_envia();

}

/**
 * Inicializa variables cada vez que llega al índice (en particular para
 * búsquedas).
 *
 * @param handle &$db Conexión a base de datos
 *
 * @return void
 */
function inicializa(&$db)
{
    $_SESSION['forma_modo'] = 'editar';
    $idbus = $GLOBALS['idbus'];
    if ($idbus <= 0) {
        elimina_caso($db, $idbus);
    } else {
        die(
            "Variable Global idbus con id. de caso para búsquedas debería " .
            "ser no positiva"
        );
    }
}

$res = hace_consulta($db, 'SELECT count(*) FROM acto', false);
sin_error_pear(
    $res, 'Se recomienda que ejecute <a href="actualiza.php">actualiza.php</a>'
);

inicializa($db);

menu_principal($db);

unset_var_session();


?>
