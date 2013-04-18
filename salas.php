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
if (EC_BLOQUEAR != 0 || $cfg['EC_BLOQUEAR'] != 0) {
    exit;    
}

/**
 * Define a variável $abre com o valorde $_GET['abre']
 */
$abre = $_GET['abre'];

/**
 * Salva o tema do usuário
 */
if($_POST['thema']) {
    $_SESSION['style'] = $_POST['thema'];
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo EC_NOME_CHAT; ?></TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<SCRIPT LANGUAGE="JavaScript" src="script.js"></script>
<SCRIPT LANGUAGE="JavaScript" src="finddom.js"></script>
<SCRIPT>
var IdToWrite = 'body';

function GetPost(){
    var myajax=ajaxpack.ajaxobj
    var myfiletype=ajaxpack.filetype
    var ob = findDOM(IdToWrite,0);
    if (myajax.readyState == 4){ //if request of file completed
        if (myajax.status==200 || window.location.href.indexOf("http")==-1){
            if (myfiletype=="txt"){
                ob.innerHTML=myajax.responseText;
            }
            else{
                ob.innerHTML=myajax.responseText;

            }
        }
    }
}

function LoadMsgs() {
    ajaxpack.getAjaxRequest('gerasala.php',"abre=<?php echo $abre;?>",GetPost,'txt');
    setTimeout('LoadMsgs()',<?php echo EC_REFRESH;?>000);
}
</SCRIPT>
<link rel="stylesheet" type="text/css" href="templates/<?php echo $_SESSION['style'] ; ?>/style.css">
</HEAD>
<BODY bgcolor="#FFFFFF" onload="LoadMsgs()" scroll="auto" >
<DIV ALIGN="" id="body" style="width:100%;height:100%;"></DIV>
</body></html>
