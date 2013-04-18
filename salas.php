<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Este é o arquivo de exibição de mensagens
 *
 * 
 *
 * PHP versions 4.1.1 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Chat
 * @package    Enygmata Chat
 * @author     Higor Euripedes <heuripedes@hotmail.com>
 * @copyright  2006 The EC Group
 * @license    http://php.net/license/3_0.txt       PHP License 3.0
 * @license    http://gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id:$
 * @link       http://enygmata.orgfree.com/diretorio/enygmatachat/enygmatachat_3.2.rar
 * @see        
 * @access     public
 */

/**
 * Define a constante de segurança
 */
define('EC_OK',TRUE);

/**
 * Inclui o arquivo de configuração
 */
require_once ('config.php');

/**
 * Inclui o arquivo com a classe principal: Enygmata_Chat
 */
require_once ('classes/ec.class.php');

/**
 * Inicia a classe Enygmata Chat
 */
$ec = ec('',$lng['anonimo'],$lng['admin'],$lng['entrou'],$lng['saiu']);

/**
 * Verifica se o chat está bloqueado
 */
if (EC_BLOQ != 0 || $cfg['EC_BLOQ'] != 0) {
    exit;    
}

/**
 * Define a variável $abre com o valorde $HTTP_GET_VARS['abre']
 */
$abre = $HTTP_GET_VARS['abre'];

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo EC_CHAT; ?></TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<!-- <meta http-equiv="refresh" content="3; url=<?php echo $HTTP_SERVER_VARS['PHP_SELF'] . '?abre=' . $HTTP_GET_VARS['abre']?>"> -->
<SCRIPT LANGUAGE="JavaScript" src="script.js"></script>
<SCRIPT>
var el = 'body';
function processGetPost(){
    var myajax=ajaxpack.ajaxobj
    var myfiletype=ajaxpack.filetype
    if (myajax.readyState == 4){ //if request of file completed
        if (myajax.status==200 || window.location.href.indexOf("http")==-1){
            if (myfiletype=="txt")
            document.getElementById(el).innerHTML=myajax.responseText
            else
            document.getElementById(el).innerHTML=myajax.responseText
        }
    }
}

function r() {
	
ajaxpack.getAjaxRequest('gerasala.php',"abre=<?php echo $abre;?>",processGetPost,'txt');
setTimeout('r()',<?php echo EC_REFRESH;?>000);
}
</SCRIPT>
<style>
body {font-family:Verdana, Tahoma; font-size:8pt;margin:2px;
     border: 0px solid;border-right-width:1px;border-color:#000000;
	SCROLLBAR-FACE-COLOR:#EEEEEE;SCROLLBAR-ARROW-COLOR:#808080;
	SCROLLBAR-3DLIGHT-COLOR:#F0F0F0;SCROLLBAR-DARKSHADOW-COLOR:#808080;
	SCROLLBAR-HIGHLIGHT-COLOR:#FFFFFF;SCROLLBAR-SHADOW-COLOR:#C0C0C0;
	SCROLLBAR-TRACK-COLOR:#FFFFFF;}

a:link{font-family:Verdana, Tahoma; font-size:8pt;color:#0000A0;}
a:visited{font-family:Verdana, Tahoma; font-size:8pt;color:#0000A0;}
a:hover{font-family:Verdana, Tahoma; font-size:8pt;color:#F00000;}

.msg1 {border-style:solid; border-width:1px;border-color:#B0B0B0;
       background-color:#FDFDFD;font-family:Verdana, Tahoma;
       font-size:8pt;margin:0px;height:20px;}

.msg2 {border-style:solid; border-width:1px;border-color:#B0B0B0;
       background-color:#FFFFDD;font-family:Verdana, Tahoma;
       font-size:8pt;margin:0px;height:20px;}

</style>

</HEAD>
<BODY bgcolor="#FFFFFF" onload="r()" scroll="auto" >
<DIV ALIGN="" id="body" style="width:100%;height:100%;"></DIV>
</body></html>
