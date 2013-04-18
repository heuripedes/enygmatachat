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
* @since      File available since Release 3.2
* @deprecated File deprecated in Release 3.3
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
* Verifica se o chat está bloqueado
*/
if (EC_BLOC != 0 || $cfg['EC_BLOC'] != 0) {
    exit;    
}

/**
* Define a variável $abre com o valorde $_GET['abre']
*/
$abre = $_GET['abre'];

/**
* Corrige erros com o nome da sala
*/
if (!trim($abre)) {
	$abre = 'sala1';
}

/**
* Define qual arquivo abrir
*/
$arq = 'texto/' . $PREFIXO . $abre . '.txt';

/**
* Cria uma instância da classe Enygmata_Chat
*/
$ec = ec($arq,$lng['anonimo'],$lag['admin'],$lng['entrou'],$lng['saiu']);

/**
* Verifica se o arquivo existe da sala existe
*/
if (!@file_exists($arq)) {
    exit;
}

/**
* Abre e lê o arquivo da sala
*/
$fr = $ec->ec_ler($arq);

$m = $ec->ec_get_msg($fr);
//$Nick . '<+>' . $ip .'<+>' . $Texto
for ($i=0; $i<count($m); $i++) {
	/*if($ec->ec_is_ban($m[$i][0]) || $ec->ec_is_ban($m[$i][1]))
	{
		$usr_ban == TRUE;	
	}else {
		$usr_ban == FALSE;	
	}*/
	if ($m[$i][2] && $m[$i][0] /*&& $usr_ban != TRUE*/	)
	{
		$mensagens .= '<TABLE width="100%" class="table1">'.
            '<TR><td><B>[' . $m[$i][0] . '</b> ' . 
            $lng['diz'] . ':<b>]&nbsp;</B>' . $m[$i][2] .
            '</TD></TR></table><br>';
	}
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo EC_CHAT; ?></TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<meta http-equiv="refresh" content="3; url=<?php echo $_SERVER['PHP_SELF'] . '?abre=' . $_GET['abre']?>">
<SCRIPT>
function r() {
         this.location.reload();
         setTimeout('r()',4000);
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

.table1 {border-style:solid; border-width:1px;border-color:#B0B0B0;
       background-color:#FDFDFD;font-family:Verdana, Tahoma;
       font-size:8pt;margin:0px;}
</style>
</HEAD>
<BODY bgcolor="#FFFFFF" onload="setTimeout('r()',4000);" scroll="auto">
<?php
echo $mensagens;
?>
<noframes>
</body></html>
