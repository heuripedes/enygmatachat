<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Este é o arquivo que gera o visual das mensagens
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
 * Verifica se o chat está bloqueado
 */
if (EC_BLOQ != 0 || $cfg['EC_BLOQ'] != 0) {
    exit;    
}

/**
 * Inicia a sessão atual
 */
session_start();

/**
 * Define a variável $abre com o valorde $HTTP_GET_VARS['abre']
 */
$abre = $HTTP_GET_VARS['abre'];

/**
 * Corrige erros com o nome da sala
 */
if (!trim($abre)) {
	$abre = '1';
}

/**
 * Define qual arquivo abrir
 */
$arq = 'texto/' . $PREFIXO . 'sala' . $abre . '.txt';

/**
 * Cria uma instância da classe Enygmata_Chat
 */
$ec = ec($arq,$lng['anonimo'],$lag['admin'],$lng['entrou'],$lng['saiu']);

/**
 * Verifica se o arquivo existe da sala existe
 */
if (!file_exists($arq)) {
    die('<FONT COLOR="#FF0000"><B>' . $lng['erro'] . ':</B> ' . $lng['erro1'] .'</FONT>');
}

/**
 * Verifica se o usuário está banido
 */
 if($ec->ec_is_ban($HTTP_SERVER_VARS['REMOTE_ADDR'])) {
     die($lng['vc_banido']);
 }

/**
 * Abre e lê o arquivo da sala
 */
$fr = $ec->ec_ler($arq);

/**
 * Interpreta as mensagens
 */
$m = $ec->ec_get_msg($fr);

for ($i=0; $i<count($m); $i++) {

    /**
     * Siplifica o nome das variáveis
     */
	$muser = $m[$i][0];
	$mip   = $m[$i][1];
	$mtext = $m[$i][2];
    
    /**
     * Correção do erro que fazia aparecer um "A" antes do nick
     */
    if($_SESSION['nick'] || $HTTP_COOKIE_VARS['nick']) {
        if($muser == 'A' . $_SESSION['nick'] || $muser == 'A' . $_SESSION['nick']) {
            $muser = substr($muser,1,strlen($muser));
        }
    }
    
    if($muser && $mtext) {
        if(strstr($mtext,'[m]')) {
            $mtext = explode('[m]',$mtext);
            if($mtext[0] == $_SESSION['nick'] || $mtext[0] == $HTTP_COOKIE_VARS['nick']) {
                $MSG[0] = $muser;
                $MSG[1] = $mtext[1];
                $TAG = 2;
            }
        }elseif($mtext && $muser)
        {
            $MSG[0] = $muser;
            $MSG[1] = $mtext;
            $TAG = 1;
        }
        $mensagens .= 
            "<TR><TD class=\"msg{$TAG}\"><B>[{$MSG[0]}</b> {$lng['diz']}<B>]&nbsp;</B>{$MSG[1]}</TD></TR>\n";
    }

}

echo "<TABLE WIDTH=\"100%\">$mensagens</TABLE>" ;
?>