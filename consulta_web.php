<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker fileencoding=utf-8:
/**
 * Consulta para la página web.
 * Una versión inicial que sirvió de referencia fue desarrollada por
 * Mauricio Rivera (mauricio.rivera.p@gmail.com) en 2004.
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @link      http://sivel.sf.net
 */

/**
 * Consulta web.
 */

require_once "aut.php";
require_once $_SESSION['dirsitio'] . "/conf.php";
/* Autenticamos si la consulta pública está deshabilitada */
if (isset($GLOBALS['consulta_publica_deshabilitada']) 
    && $GLOBALS['consulta_publica_deshabilitada']
) {
        autentica_usuario($dsn, $aut_usuario, 0);
}

require_once 'HTML/QuickForm/Controller.php';

require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';

require_once 'PagTipoViolencia.php';
require_once 'ResConsulta.php';

foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
    list($n, $c, $o) = $tab;
    if (($d = strrpos($c, "/"))>0) {
        $c = substr($c, $d+1);
    }
    // @codingStandardsIgnoreStart
    require_once "$c.php";
    // @codingStandardsIgnoreEnd
}


/**
 * Responde a botón para hacer consulta.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 * @see      BuscaId
 */
class AccionConsultaWeb extends HTML_QuickForm_Action
{

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
        $d = objeto_tabla('departamento');
        if (PEAR::isError($d)) {
            die($d->getMessage());
        }
        $db =& $d->getDatabaseConnection();
        $pFini      = var_req_escapa('fini', $db);
        $pFfin      = var_req_escapa('ffin', $db);
        $pFiini     = var_req_escapa('fiini', $db);
        $pFifin     = var_req_escapa('fifin', $db);
        $pMostrar   = var_req_escapa('mostrar', $db, 32);
        $pOrdenar   = substr(var_req_escapa('ordenar', $db), 0, 32);
        $pUsuario   = substr(var_req_escapa('usuario', $db), 0, 32);
        $pIdCasos   = substr(var_req_escapa('id_casos', $db), 0, 1024);
        $pIdClase   = (int)var_req_escapa('id_clase', $db);
        $pIdMunicipio   = (int)var_req_escapa('id_municipio', $db);
        $pIdDepartamento= (int)var_req_escapa('id_departamento', $db);
        $pClasificacion = var_req_escapa('clasificacion', $db);
        $pPresponsable  = (int)var_req_escapa('presponsable', $db);
        $pSsocial   = (int)var_req_escapa('ssocial', $db);
        $pNomvic    = substr(var_req_escapa('nomvic', $db), 0, 32);
        $pDesc      = substr(var_req_escapa('descripcion', $db), 0, 32);
        $pMFuentes  = (int)var_req_escapa('m_fuentes', $db);
        $pRetroalimentacion = (int)var_req_escapa('retroalimentacion', $db);
        $pVarLineas = (int)var_req_escapa('m_varlineas', $db);
        $pConcoordenadas = (int)var_req_escapa('concoordenadas', $db);
        $pTeX       = (int)var_req_escapa('m_tex', $db);
        $pTitulo    = substr(var_req_escapa('titulo', $db), 0, 32);
        $pTvio    = substr(var_req_escapa('tviolencia', $db), 0, 1);
        $pPrimNom = var_req_escapa('primnom') == 'nombre';

        $campos = array(); //'caso_id' => 'Cód.');
        $tablas = "caso";
        $where = "";
        $ordCasos = array();
        if ($pIdCasos != '') {
            $ordCasos = explode(' ', $pIdCasos);
            $wc = "";
            foreach ($ordCasos as $cc) {
                consulta_and($db, $wc, "caso.id", (int)$cc, "=", "OR");
            }
            if ($wc != "") {
                $where .= "(" . $wc . ") ";
            }
        }
        if (trim($pTitulo) != '') {
            if ($where != "") {
                $where .= " AND ";
            }
            $consTitulo =  trim(a_minusculas(sin_tildes($pTitulo)));
            $consTitulo = preg_replace("/ +/", " & ", $consTitulo);
            $where .= " to_tsvector('spanish', unaccent(caso.titulo)) "
                . " @@ to_tsquery('spanish', '$consTitulo')";
        }
        if (trim($pDesc) != '') {
            if ($where != "") {
                $where .= " AND ";
            }
            $consDesc =  trim(a_minusculas(sin_tildes($pDesc)));
            $consDesc = preg_replace("/ +/", " & ", $consDesc);
            $where .= " to_tsvector('spanish', unaccent(caso.memo)) "
                . " @@ to_tsquery('spanish', '$consDesc')";
        }

        if (isset($pFini['Y']) && $pFini['Y'] != '') {
            consulta_and(
                $db, $where, "caso.fecha",
                arr_a_fecha($pFini, true), ">="
            );
        }
        if (isset($pFfin['Y']) && $pFfin['Y'] != '') {
            consulta_and(
                $db, $where, "caso.fecha",
                arr_a_fecha($pFfin, false), "<="
            );
        }

        consulta_and(
            $db, $where, "caso.fecha",
            $GLOBALS['consulta_web_fecha_min'], ">="
        );
        consulta_and(
            $db, $where, "caso.fecha",
            $GLOBALS['consulta_web_fecha_max'], "<="
        );

        if ($pTvio != '') {
            $where .= ' AND caso.id IN '
                . "(SELECT id_caso FROM acto, categoria WHERE
                acto.id_categoria = categoria.id
                AND categoria.id_tviolencia = '$pTvio'
                UNION
                SELECT id_caso FROM actocolectivo, categoria WHERE
                actocolectivo.id_categoria = categoria.id
                AND categoria.id_tviolencia = '$pTvio'
                UNION
                SELECT id_caso FROM caso_categoria_presponsable WHERE
                id_tviolencia = '$pTvio')";
        }
        if ($pClasificacion != '') {
            $ini = '(';
            $so = '';
            $tind = false;
            $tcol = false;
            $totr = false;
            foreach ($pClasificacion as $cla) {
                $r = explode(":", $cla);
                $so2='';
                $dcatc = objeto_tabla('categoria');
                $dcatc->get((int)$r[2]);
                if ($dcatc->tipocat == 'I') {
                    consulta_and(
                        $db, $so2, "acto.id_categoria",
                        (int)$r[2]
                    );
                    $tind = true;
                } else if ($dcatc->tipocat == 'C') {
                    consulta_and(
                        $db, $so2, "actocolectivo.id_categoria",
                        (int)$r[2]
                    );
                    $tcol = true;
                } else if ($dcatc->tipocat == 'O') {
                    consulta_and(
                        $db, $so2,
                        "caso_categoria_presponsable.id_categoria",
                        (int)$r[2]
                    );
                    $totr = true;
                } else {
                    die(
                        _("Falta especificar tipo de categoria")
                        . " {$dcatc->id}" . " ({$dcatc->tipocat})"
                    );
                }
                $so .= $ini . $so2 . ')';
                $ini = ' OR (';
            }
            if ($so != '') {
                $where .= ' AND (' . $so . ')';
            }
            $nt = 0;
            if ($tind) {
                $tablas .= " LEFT JOIN acto ON caso.id=acto.id_caso";
                $nt++;
            }
            if ($tcol) {
                $tablas .= " LEFT JOIN actocolectivo ON " .
                    "caso.id=actocolectivo.id_caso";
                $nt++;
            }
            if ($totr) {
                $tablas .= " LEFT JOIN caso_categoria_presponsable ON " .
                    "caso.id=caso_categoria_presponsable.id_caso";
                $nt++;
            }
        }


        $oconv = array(); // Campos resultado además de conv
        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'consultaWebCreaConsulta'))) {
                call_user_func_array(
                    array($c, 'consultaWebCreaConsulta'),
                    array(
                        &$db, $pMostrar, &$where, &$tablas,
                        &$pOrdenar, &$campos, &$oconv
                    )
                );
            } else {
                echo_esc(
                    _("Falta") . " consultaWebCreaConsulta "
                    . _("en") . " $n, $c"
                );
            }
        }
        if (isset($GLOBALS['gancho_cw_creaconsulta'])) {
            foreach ($GLOBALS['gancho_cw_creaconsulta'] as $k => $f) {
                if (is_callable($f)) {
                    call_user_func_array(
                        $f,
                        array(
                            $db, $pMostrar, &$where, &$tablas,
                            &$pOrdenar, &$campos, &$oconv, $page
                        )
                    );
                } else {
                    echo_esc(
                        _("Falta") ." $f " . _("de")
                        . " gancho_cw_creaconsulta[$k]"
                    );
                }
            }
        }


        if ($pIdClase != '') {
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and(
                $db, $where, "ubicacion.id_departamento", $pIdDepartamento
            );
            consulta_and($db, $where, "ubicacion.id_municipio", $pIdMunicipio);
            consulta_and($db, $where, "ubicacion.id_clase", $pIdClase);
            $tablas .= ", ubicacion ";
        } else if ($pIdMunicipio != '') {
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and(
                $db, $where, "ubicacion.id_departamento", $pIdDepartamento
            );
            consulta_and($db, $where, "ubicacion.id_municipio", $pIdMunicipio);
            agrega_tabla($tablas, 'ubicacion');
        } else if ($pIdDepartamento != '') {
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and(
                $db, $where, "ubicacion.id_departamento", $pIdDepartamento
            );
            agrega_tabla($tablas, 'ubicacion');
        }
        if ($pConcoordenadas) {
            consulta_and_sinap($where, "ubicacion.id_caso", "caso.id");
            consulta_and_sinap(
                $where, "ubicacion.latitud", "NULL", " IS NOT "
            );
            agrega_tabla($tablas, 'ubicacion');
        }

        if ($pPresponsable != '') {
            consulta_and_sinap(
                $where, "caso_presponsable.id_caso",
                "caso.id"
            );
            consulta_and(
                $db, $where, "caso_presponsable.id_presponsable",
                $pPresponsable
            );
            agrega_tabla($tablas, 'caso_presponsable');
        }


        if (in_array(42, $page->opciones)
            && ($pUsuario != '' || (isset($pFiini['Y']) && $pFiini['Y'] != '')
            || (isset($pFifin['Y']) && $pFifin['Y'] != ''))
        ) {
            agrega_tabla($tablas, 'caso_usuario');
            consulta_and_sinap($where, "caso_usuario.id_caso", "caso.id");
        }
        if (in_array(42, $page->opciones)
            && isset($pFiini['Y']) && $pFiini['Y'] != ''
        ) {
            consulta_and(
                $db, $where, "caso_usuario.fechainicio",
                arr_a_fecha($pFiini, true), ">="
            );
        }
        if (in_array(42, $page->opciones)
            && isset($pFifin['Y']) && $pFifin['Y'] != ''
        ) {
            consulta_and(
                $db, $where, "caso_usuario.fechainicio",
                arr_a_fecha($pFifin, false), "<="
            );
        }

        if (in_array(42, $page->opciones) && $pUsuario != '') {
            consulta_and(
                $db, $where, "caso_usuario.id_usuario", $pUsuario
            );
        }

        if ($pNomvic != "") {
            agrega_tabla($tablas, 'persona');
            consulta_and_sinap($where, "victima.id_persona", "persona.id");
        }
        if ($pNomvic != "" || $pSsocial != "") {
            agrega_tabla($tablas, 'victima');
            consulta_and_sinap($where, "victima.id_caso", "caso.id");
        }
        if ($pSsocial != '') {
            consulta_and($db, $where, "victima.id_sectorsocial", $pSsocial);
        }

        if (trim($pNomvic) != '') {
            if ($where != "") {
                $where .= " AND";
            }
            $consNomVic =  trim(a_minusculas(sin_tildes($pNomvic)));
            $consNomvic = preg_replace("/ +/", " & ", $consNomVic);
            $where .= " to_tsvector('spanish', unaccent(persona.nombres) "
                . " || ' ' || unaccent(persona.apellidos)) @@ "
                . "to_tsquery('spanish', '$consNomvic')";
        }

        // Búsqueda por víctima no incluye combatientes para evitar sobreconteos
        // Emplear consulta_externa
        $conv = array('caso_id' => 0, 'caso_fecha' => 1, 'caso_memo' =>2);
        $q = "SELECT DISTINCT ";
        $sep = "";
        foreach ($conv as $k => $v) {
            $q .= $sep . str_replace("_", ".", $k);
            $sep = ", ";
        }
        foreach ($oconv as $k) {
            $q .= $sep . str_replace("_", ".", $k);
        }

        $q .= " FROM " . $tablas
            ."  WHERE caso.id<>'" . $GLOBALS['idbus'] . "'" ;
        if ($where != "") {
            $q .= " AND " . $where;
        }
        consulta_orden($q, $pOrdenar);

        //echo "OJO q es $q"; die("x");

        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'consultaWebOrden'))) {
                call_user_func_array(
                    array($c, 'consultaWebOrden'),
                    array(&$q, &$pOrdenar)
                );
            } else {
                echo_esc(
                    _("Falta") . " consultaWebOrden "
                    . _("en") . " $n, $c"
                );
            }
        }

        $result = hace_consulta($db, $q);
        sin_error_pear($result);
        foreach ($GLOBALS['cw_ncampos'] as $idc => $dc) {
            if (isset($_REQUEST[$idc]) && $_REQUEST[$idc] == 1) {
                $campos[$idc] = $dc;
            }
        }
        if ($pMFuentes == 1 && in_array(42, $page->opciones)) {
            $campos['m_fuentes'] = 'Fuentes';
        }

        if ($pMostrar != 'csv'
            && $pMostrar != 'revista'
            && $pMostrar != 'tabla'
            && $pMostrar != 'relato'
            && !in_array(42, $page->opciones)
        ) {
            die('No es posible');
        }


        $ar =& $result;
        $r = new ResConsulta(
            $campos, $db, $ar, $conv, $pMostrar,
            array('varlineas' => $pVarLineas, 'tex' => $pTeX),
            $ordCasos, null, $pOrdenar, $pPrimNom
        );
        $r->aHtml($pRetroalimentacion == 1);
    }
}


/**
 * Fórmulario para consulta web.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class ConsultaWeb extends HTML_QuickForm_Page
{

    /**
     * Opciones
     */
    var $opciones;

    /**
     * Constructora
     *
     * @param array $opciones opciones
     *
     * @return void
     */
    function ConsultaWeb($opciones)
    {
        $this->opciones = $opciones;
        $this->HTML_QuickForm_Page('consultaWeb', 'post', '_self', null);

        $this->addAction('consulta', new AccionConsultaWeb());
    }

    /**
     * Construye formulario.
     *
     * @return void
     */
    function buildForm()
    {
        $this->_formBuilt = true;

        $pMostrar   =var_req_escapa('mostrar');
        $pCategoria =var_req_escapa('categoria');
        $pSinCampos =var_req_escapa('sincampos');
        $pOrden     =var_req_escapa('orden');

        $slan = "es";
        if (isset($_SESSION['LANG'])) {
            $slan = substr($_SESSION['LANG'], 0, 2);
            idioma($_SESSION['LANG']);
        }

        encabezado_envia(_('Consulta Web'), $GLOBALS['cabezote_consulta_web']);
        $x =&  objeto_tabla('departamento');
        $db = $x->getDatabaseConnection();
        if (PEAR::isError($db)) {
            die($db->getMessage() . " - " . $db->getUserInfo());
        }

        $e =& $this->addElement(
            'header', null, _('Periodo de Consulta') . ' ' .
            $GLOBALS['consulta_web_fecha_min'].
            ' ' . _('hasta') . ' ' .
            $GLOBALS['consulta_web_fecha_max']
        );

        if ($this->opciones != array()) {
            $cod =& $this->addElement(
                'text', 'id_casos', _('Código(s)') . ': '
            );
            $cod->setSize(80);
        }

        $sel =& $this->addElement(
            'text', 'titulo', _('Título del caso') . ':'
        );
        $sel->setSize(80);

        $dep =& $this->addElement(
            'select', 'id_departamento',
            _('Departamento') . ': ', array()
        );
        $mun =& $this->addElement(
            'select', 'id_municipio',
            _('Municipio') .': ', array()
        );

        $cla =& $this->addElement(
            'select', 'id_clase',
            _('Centro Poblado') . ': ', array()
        );
        $vdep = isset($this->_submitValues['id_departamento']) ?
           $this->_submitValues['id_departamento'] : null;
        $vmun = isset($this->_submitValues['id_municipio']) ?
           $this->_submitValues['id_municipio'] : null;
        $vcla = isset($this->_submitValues['id_clase']) ?
           $this->_submitValues['id_clase'] : null;
        PagUbicacion::modCampos(
            $db, $this, 'id_departamento', 'id_municipio', 'id_clase',
            $vdep, $vmun, $vcla
        );

        $sel =& $this->addElement(
            'text', 'nomvic',
            _('Nombre o apellido de la víctima')
        );
        $sel->setSize(80);

        $cy = @date('Y');
        if ($cy < 2005) {
            $cy = 2005;
        }
        $ay = explode('-', $GLOBALS['consulta_web_fecha_min']);
        $e =& $this->addElement(
            'date', 'fini', _('Desde: '),
            array(
                'language' => $slan, 'addEmptyOption' => true,
                'minYear' => $ay[0], 'maxYear' => $cy
            )
        );
        $e =& $this->addElement(
            'date', 'ffin', _('Hasta:'),
            array(
                'language' => $slan, 'addEmptyOption' => true,
            'minYear' => $ay[0], 'maxYear' => $cy
            )
        );


        $sel =& $this->addElement(
            'select',
            'presponsable', _('Presunto Responsable')
        );
        $lpr = htmlentities_array(
            $db->getAssoc(
                "SELECT id, nombre FROM presponsable " .
                "WHERE fechadeshabilitacion is null"
            )
        );
        if (PEAR::isError($lpr)) {
            die($lpr->getMessage() . " - " . $lpr->getUserInfo());
        }
        $options = array('' => '') + $lpr;
        $sel->loadArray($options);

        $sel =& $this->addElement(
            'select', 'clasificacion',
            _('Clasificación de Violencia')
        );
        $sel->setMultiple(true);
        $sel->setSize(5);
        ResConsulta::llenaSelCategoria(
            $db,
            "SELECT id_tviolencia, id_supracategoria, " .
            "id FROM categoria ORDER BY id_tviolencia," .
            "id_supracategoria, id;",
            $sel
        );
        if ($pCategoria == 'belicas') {
            $valscc = array();
            $d =&  objeto_tabla('categoria');
            $d->id_tviolencia = 'C';
            $d->find();
            while ($d->fetch()) {
                $fc = PagTipoViolencia::cadenaDeCodcat(
                    $d->id_tviolencia,
                    $d->id_supracategoria,
                    $d->id
                );
                $valscc[] = $fc;
            }
            $sel->setValue($valscc);
        }
        if ($pCategoria == 'nobelicas') {
            $valscc = array();
            $d =&  objeto_tabla('categoria');
            $d->whereAdd('id_tviolencia<>\'C\'');
            $d->find();
            while ($d->fetch()) {
                $fc = PagTipoViolencia::cadenaDeCodcat(
                    $d->id_tviolencia,
                    $d->id_supracategoria,
                    $d->id
                );
                $valscc[] = $fc;
            }
            $sel->setValue($valscc);
        }

        $sel =& $this->addElement(
            'select', 'ssocial', _('Sector Social Víctima')
        );
        $options = array('' => '') + htmlentities_array(
            $db->getAssoc("SELECT id, nombre FROM sectorsocial")
        );
        $sel->loadArray($options);

        $sel =& $this->addElement('text', 'descripcion', _('Descripción'));
        $sel->setSize(80);


        /*$aut_usuario = "";
        if (!isset($_SESSION['id_usuario'])) {
            include $_SESSION['dirsitio'] . "/conf.php";
            autentica_usuario($dsn, $aut_usuario, 0);
        }
        echo "OJO <hr>"; var_dump($GLOBALS['ficha_tabuladores']); die("x");*/


        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'consultaWebFiltro'))) {
                call_user_func_array(
                    array($c, 'consultaWebFiltro'),
                    array(&$db, &$this)
                );
            }
        }

        if (isset($_SESSION['id_usuario'])) {
            if (in_array(42, $_SESSION['opciones'])) {
                $sel =& $this->addElement(
                    'select',
                    'usuario', _('Usuario')
                );
                $options= array(''=>' ') + htmlentities_array(
                    $db->getAssoc(
                        "SELECT id, nusuario FROM usuario 
                        WHERE fechadeshabilitacion IS NULL
                        ORDER by nusuario"
                    )
                );
                $sel->loadArray($options);
                $e =& $this->addElement(
                    'date', 'fiini', _('Ingreso Desde') . ': ',
                    array(
                        'language' => $slan, 'addEmptyOption' => true,
                    'minYear' => $ay[0], 'maxYear' => $cy
                    )
                );
                $e =& $this->addElement(
                    'date', 'fifin', _('Ingreso Hasta') . ':',
                    array(
                        'language' => $slan, 'addEmptyOption' => true,
                    'minYear' => $ay[0], 'maxYear' => $cy
                    )
                );

            }
        }

        $opch = array();
        $sel =& $this->createElement(
            'checkbox',
            'concoordenadas', _('Con Coordenadas'), _('Con Coordenadas')
        );
        $opch[] =& $sel;

        $this->addGroup(
            $opch, null, _('Coordenadas'), '&nbsp;', false
        );


        $ae = array();
        $x =& $this->createElement(
            'radio', 'ordenar', 'fecha',
            _('Fecha'), 'fecha'
        );
        $ae[] =&  $x;
        if ($pOrden == '' || $pOrden == 'fecha') {
            $t =& $x;
        }
        $x =& $this->createElement(
            'radio', 'ordenar', 'ubicacion',
            _('Ubicación'), 'ubicacion'
        );
        $ae[] =& $x;
        if ($pOrden == 'ubicacion') {
            $t =& $x;
        }

        if ($this->opciones != array()) {
            $x =& $this->createElement(
                'radio', 'ordenar', 'codigo',
                _('Código'), 'codigo'
            );
            $ae[] =& $x;
            if ($pOrden == 'codigo') {
                $t =& $x;
            }
        }
        $r = "";
        if (isset($GLOBALS['consultaweb_ordenarpor'])) {
            foreach ($GLOBALS['consultaweb_ordenarpor'] as $k => $f) {
                if (is_callable($f)) {
                    $r .= call_user_func_array(
                        $f,
                        array($pOrden, $this->opciones, $this, &$ae, &$t)
                    );
                } else {
                    echo_esc(
                        _("Falta ") . $f . " " . _("de")
                        . " consultaweb_ordenarpor[$k]"
                    );
                }
            }
        }


        $this->addGroup($ae, null, _('Ordenar por'), '&nbsp;', false);
        $t->setChecked(true);

        $ae = array();
        $t =& $this->createElement(
            'radio', 'mostrar', 'tabla',
            _('Tabla'), 'tabla'
        );
        $ae[] =&  $t;

        $x =&  $this->createElement(
            'radio', 'mostrar', 'revista',
            _('Reporte Revista'), 'revista'
        );
        $ae[] =& $x;
        if ($pMostrar == 'revista') {
            $t =& $x;
        }

        if (isset($this->opciones) && in_array(42, $this->opciones)) {
            $x =&  $this->createElement(
                'radio', 'mostrar',
                'general', _('Reporte General'), 'general'
            );
            $ae[] =& $x;
            if ($pMostrar == 'general') {
                $t =& $x;
            }
        }
        $x =&  $this->createElement(
            'radio', 'mostrar',
            'relato', _('Relato XML'), 'relato'
        );
        $ae[] =& $x;
        if ($pMostrar == 'relato') {
            $t =& $x;
        }

        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'consultaWebFormaPresentacion'))) {
                call_user_func_array(
                    array($c, 'consultaWebFormaPresentacion'),
                    array(
                        $pMostrar, $this->opciones, &$this,
                        &$ae, &$t
                    )
                );
            } else {
                echo_esc(
                    _("Falta") . " consultaWebFormaPresentacion "
                    . _("en")
                    . "$n, $c"
                );
            }
        }
        if (isset($GLOBALS['gancho_cw_formapresentacion'])) {
            foreach ($GLOBALS['gancho_cw_formapresentacion'] as $k => $f) {
                if (is_callable($f)) {
                    call_user_func_array(
                        $f,
                        array($pMostrar, $this->opciones, $this, &$ae, &$t)
                    );
                } else {
                    echo_esc(
                        _("Falta") . " " . $f . " " .
                        _("de") . " consultaWebFormaPresentacion[$k]"
                    );
                }
            }
        }

        if (!isset($GLOBALS['consultaweb_sin_csv'])) {
            $x =& $this->createElement(
                'radio', 'mostrar', 'csv', _('CSV'), 'csv'
            );
            $ae[] =&  $x;
            if ($pMostrar == 'csv') {
                $t =& $x;
            }
        }

        $this->addGroup($ae, null, _('Forma de presentación'), '&nbsp;', false);
        $t->setChecked(true);

        $asinc = array();
        if ($pSinCampos != '') {
            $asinc = explode(',', $pSinCampos);
        }
        $opch = array();
        foreach ($GLOBALS['cw_ncampos'] as $idc => $dc) {
            if ($this->opciones != array() || $idc != 'caso_id') {
                $sel =& $this->createElement(
                    'checkbox',
                    $idc, $dc, $dc
                );
                if (!in_array($idc, $asinc)) {
                    if (isset($GLOBALS['cw_ncampos_valor_inicial'][$idc])) {
                        $sel->setValue($GLOBALS['cw_ncampos_valor_inicial'][$idc]);
                    } else {
                        $sel->setValue(true);
                    }
                }
                $opch[] =& $sel;
            }
        };

        if (in_array(42, $this->opciones)) { // Podría ver rep. gen?
            $sel =& $this->createElement(
                'checkbox',
                'm_fuentes', _('Fuentes'), _('Fuentes')
            );
            if (!in_array('m_fuentes', $asinc)) {
                $sel->setValue(false);
            }
            $opch[] =& $sel;
        }
        $sel =& $this->createElement(
            'checkbox',
            'retroalimentacion', _('Retroalimentación'),
            _('Retroalimentación')
        );
        $sel->setValue(false);
        $opch[] =& $sel;

        $this->addGroup($opch, null, _('Campos por mostrar'), '&nbsp;', false);

        $opch = array();
        $sel =& $this->createElement(
            'checkbox',
            'm_varlineas', _('Memo en varias líneas'),
            _('Memo en varias líneas')
        );
        if (!in_array('m_varlineas', $asinc)) {
            $sel->setValue(false);
        }
        $opch[] =& $sel;
        $sel =& $this->createElement(
            'checkbox',
            'm_tex', _('Conversión a TeX'), _('Conversión a TeX')
        );
        if (!in_array('m_tex', $asinc)) {
            $sel->setValue(false);
        }
        $opch[] =& $sel;

        foreach ($GLOBALS['ficha_tabuladores'] as $tab) {
            list($n, $c, $o) = $tab;
            if (($d = strrpos($c, "/"))>0) {
                $c = substr($c, $d+1);
            }
            if (is_callable(array($c, 'consultaWebDetalle'))) {
                call_user_func_array(
                    array($c, 'consultaWebDetalle'),
                    array($pMostrar, $this->opciones, &$this, &$opch)
                );
            } else {
                echo_esc(
                    _("Falta") . " consultaWebDetalle "
                    . _("en") ." $n, $c"
                );
            }
        }

        $this->addGroup(
            $opch, null, _('Detalles de la presentación'),
            '&nbsp;', false
        );

        $opch = array();
        $sel =& $this->createElement(
            'submit',
            $this->getButtonName('consulta'), _('Consulta')
        );
        $opch[] =& $sel;

        $this->addGroup($opch, null, '', '&nbsp;', false);

        if (isset($this->opciones) && in_array(42, $this->opciones)) {
            $tpie = "<div align=right><a href=\"index.php\">" .
                _("Men&uacute; Principal") .  "</a></div>";
        } else if (isset($GLOBALS['pie_consulta_web_publica'])) {
            $tpie = $GLOBALS['pie_consulta_web_publica'];
        } else {
            $tpie = "&nbsp;";
        }
        $e =& $this->addElement('header', null, $tpie);

        agrega_control_CSRF($this);

        $this->setDefaultAction('consulta');

    }

}


/**
 * Inicia Controlador del formulario
 *
 * @return void
 */
function runController()
{
    $snru = nom_sesion();
    if (!isset($_SESSION) || session_name()!=$snru) {
        session_name($snru);
        session_start();
    }

    $nv = "_auth_" . $snru;
    $opciones = array();
    if (isset($_SESSION[$nv]['username'])) {
        $d = objeto_tabla('caso');
        $db =& $d->getDatabaseConnection();
        $rol = "";
        saca_opciones($_SESSION[$nv]['username'], $db, $opciones, $rol);
        //idioma('es_CO');
        include_once $_SESSION['dirsitio'] . "/conf_int.php";
    } 
    $wizard = new HTML_QuickForm_Controller('Consulta', false);
    $consweb = new ConsultaWeb($opciones);

    $wizard->addPage($consweb);

    $wizard->addAction('display', new HTML_QuickForm_Action_Display());
    $wizard->addAction('jump', new HTML_QuickForm_Action_Jump());
    $wizard->addAction('process', new AccionConsultaWeb());

    $wizard->run();
}

runController();

?>
