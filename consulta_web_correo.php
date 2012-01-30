<?php
// vim: set expandtab tabstop=4 shiftwidth=4 foldmethod=marker:
/**
 * Formulario para enviar correo desde consulta web
 *
 * PHP version 5
 *
 * @category  SIVeL
 * @package   SIVeL
 * @author    Vladimir Támara <vtamara@pasosdeJesus.org>
 * @copyright 2005 Dominio público. Sin garantías.
 * @license   https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público. Sin garantías.
 * @version   CVS: $Id: consulta_web_correo.php,v 1.45.2.3 2011/10/18 16:05:02 vtamara Exp $
 * @link      http://sivel.sf.net
 * Acceso: CONSULTA PÚBLICA
 */

/**
 * Formulario para enviar correo desde consulta web
 */
require_once 'HTML/QuickForm/Controller.php';

require_once 'HTML/QuickForm/Action/Display.php';
require_once 'HTML/QuickForm/Action/Next.php';
require_once 'HTML/QuickForm/Action/Back.php';
require_once 'HTML/QuickForm/Action/Jump.php';
require_once 'HTML/QuickForm/header.php';
require_once 'HTML/QuickForm/date.php';
require_once 'HTML/QuickForm/text.php';

require_once "misc.php";
require_once "aut.php";
require_once $_SESSION['dirsitio'] . '/conf.php';

require_once 'DB/common.php';
require_once 'Mail.php';
require_once 'Mail/mime.php';
require_once 'confv.php';

/**
 * Responde a botón enviar
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class AccionEnviaCorreo extends HTML_QuickForm_Action
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

        verifica_sin_CSRF($page->_submitValues);

        encabezado_envia('Resultados');
        /* El siguiente pedazo pretende evitar posibles DoS
         (pueden ser distribuidos)
         pero tambien impide que dos personas envien informacion dentro de un
         lapso de cierto tiempo (3 s?).  Si no se espera mucho publico para el
         servicio puede resultar aceptable la limitación.
         */
        $ultimo= $_SESSION['dirsitio'] . '/ultimoenvio.txt';
        /* Manejo de archivos basado en ejemplo de
            http://www.php.net/manual/en/function.fopen.php */

        clearstatcache();
        // prevent refresh from aborting file operations and hosing file
        ignore_user_abort(true);
        $tiempoact = time();
        $tiempoult = 0;
        $fh = fopen($ultimo, 'r+b');
        if ($fh) {
            // don't do anything unless lock is successful
            if (flock($fh, LOCK_EX)) {
                $tiempoult = fread($fh, 15);
                rewind($fh);
                fwrite($fh, $tiempoact);
                fflush($fh);
                ftruncate($fh, ftell($fh));
                flock($fh, LOCK_UN);
            } else {
                echo "No pudo poner lock";
            }
            fclose($fh);
        } else {
            $fh = fopen($ultimo, 'w');
            if ($fh) {
                fwrite($fh, $tiempoact);
                fclose($fh);
            } else {
                echo_esc("No pudo crear archivo $ultimo");
            }
        }
        ignore_user_abort(false);    // put things back to normal

        if ($tiempoact-$tiempoult < 6) {
            die("Por favor vuelva a intentar en unos segundos");
        }

        $cuerposinenc = escapeshellarg(var_post_escapa("correo", null, 5000));
        //Evitamos inyección de encabezados
        $cuerposinenc = str_replace("\r", "", $cuerposinenc);
        $cuerposinenc = str_replace("\n", "", $cuerposinenc);

        $key = $GLOBALS['PALABRA_SITIO'];

        global $OPENSSL;

        //formula con bin2hex para enviarlo en le cuerpo del mensaje
        $adjuntoencriptado
            = `/bin/echo $cuerposinenc | $OPENSSL bf -e -nosalt -k "$key"`;
        $cuerpo = bin2hex(chop($adjuntoencriptado));


        //formula sin bin2hex enviandolo como un adjunto
        $indice = (int)var_post_escapa("indice", null, 10);

        $recip1=$GLOBALS['receptor_correo'];
        $oheaders = array();
        $oheaders['To'] = $recip1;
        $oheaders['From'] = $GLOBALS['emisor_correo'];
        $oheaders['Subject']="Comentario Indice: $indice";

        $ocorreo = Mail::factory('smtp', array('host' => 'localhost'));
        @$r = $ocorreo->send($recip1, $oheaders, $cuerpo);
        if (PEAR::isError($r)) {
            die($r->getMessage());
        }

        $anno = date("Y");
        $mes = date("m");
        $dia = date("d");
        $hora = date("H");
        $minutos = date("i");
        $segundos = date("s");

        $text_message = "Hola " .
            "\n\nEste mensaje es un comentario enviado desde el web \n";
        $text_message .= "viene encriptado con openssl.\n";
        $text_message .= "Vea instrucciones para desencriptar en
            http://sivel.sourceforge.net/1.1/admin2.html#retroalimentacion\n";
        $text_message .= "Dia: $dia-$mes-$anno, hora " .
            "$hora:$minutos:$segundos\n";

        $mime = new Mail_mime("\n");
        @$mime->setTXTBody($text_message);

        @$mime->addAttachment(
            $adjuntoencriptado, 'application/octet-stream',
            'adjunto-encriptado' . $anno . $mes . $dia, false
        );

        @$cuerpo_mime = $mime->get();
        @$mime_enc = $mime->headers($oheaders);

        @$r = $ocorreo->send($recip1, $mime_enc, $cuerpo_mime);
        if (PEAR::isError($r)) {
            die($r->getMessage());
        }

        echo "<p>Mensaje enviado.  Gracias por su aporte.\n</p>";
        echo $GLOBALS['pie_consulta_web_correo'];
        pie_envia();
    }
}

/**
 * Formulario con correo.
 *
 * @category SIVeL
 * @package  SIVeL
 * @author   Vladimir Támara <vtamara@pasosdeJesus.org>
 * @license  https://www.pasosdejesus.org/dominio_publico_colombia.html Dominio Público.
 * @link     http://sivel.sf.net/tec
 */
class ConsultaWebCorreo extends HTML_QuickForm_Page
{

    /**
     * Constructora
     *
     * @return void
     */
    function ConsultaWebCorreo()
    {
        $this->HTML_QuickForm_Page('consultaWebCorreo', 'post', '_self', null);
        $this->addAction('envia', new AccionEnviaCorreo());
    }

    /**
     * Construye formulario.
     *
     * @return object formulario
     */
    function buildForm()
    {
        $this->_formBuilt = true;
        $ubicacion      = var_req_escapa("m_ubicacion", null, 200);
        $indice         = (int)var_req_escapa("caso_id", null, 200);
        $descripcion    = var_req_escapa("caso_memo", null, 5000);
        $victimas       = var_req_escapa("m_victimas", null, 200);
        $tipificacion   = var_req_escapa("m_tipificacion", null, 200);
        $presponsables  = var_req_escapa("m_presponsables", null, 200);

        $e =& $this->addElement(
            'header', null,
            'Correo para complementar datos'
        );
        $sel =& $this->addElement(
            'textarea', 'correo',
            'Mensaje:'
        );
        $sel->updateAttributes(
            array('wrap' => 'physical',
            //'cols'=\"50\" rows=\"20\"
            'onKeyDown' => 'textCounter(document.myForm.correo, ' .
                'document.myForm.remLen1, 10000)',
            'onKeyUp' => 'textCounter(document.myForm.correo, ' .
                    'document.myForm.remLen1, 10000)'
        )
        );
        $sel->setRows(20);
        $sel->setCols(50);
        $sel->setValue(
            'Indice: ' . $indice .
            'Ubicación: ' . $ubicacion .
            'Descripcion: ' . $descripcion .
            'Responsables: ' . $presponsables .
            'Victimas: ' . $victimas .
            'Tipificacion: ' . $tipificacion
        );

        $sel =& $this->addElement('hidden', 'indice', $indice);
        $opch = array();
        $sel =& $this->createElement(
            'submit',
            $this->getButtonName('envia'), 'Enviar correo'
        );
        $opch[] =& $sel;

        $this->addGroup($opch, null, '', '&nbsp;', false);


        agrega_control_CSRF($this);

        $this->setDefaultAction('envia');
    }
}

/* No autenticamos porque pueden usarlo usuarios no autenticados */
$snru = nomSesion();
if (!isset($_SESSION) || session_name()!=$snru) {
    session_name($snru);
    session_start();
}

$controla =& new HTML_QuickForm_Controller('Correo', false);
$correo = new ConsultaWebCorreo();

$controla ->addPage($correo);

$controla->addAction('display', new HTML_QuickForm_Action_Display());
$controla->addAction('jump', new HTML_QuickForm_Action_Jump());
$controla->addAction('process', new AccionEnviaCorreo());

encabezado_envia(
    'Correo para Complementar Datos',
    $GLOBALS['cabezote_consulta_web_correo']
);
$controla->run();
pie_envia($GLOBALS['pie_consulta_web_correo']);


?>

