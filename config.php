<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Este щ o arquivo de configuraчуo de sistema
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
 * Verifica se o chat estс ativado
 */
if (EC_OK != TRUE) {
    die('Hacking attempt!');
}

/**
 * Configuraчѕes
 */
$cfg['EC_CHAT']      = 'Enygmata Chat';           //Nome do chat
$cfg['EC_SALAS']      = 10;                       //Nњmero de salas
$cfg['EC_MAX_MSG']    = 20;                       //Maximo de mensagens
$cfg['EC_BLOQ']       = 0;                        //1 = Bloqueio do chat; 0 = desbloqueio do chat
$cfg['EC_NOME']       = 'Enygmata';               //Nome do admin
$cfg['EC_SENHA']      = '1234';                   //Senha do chat
$cfg['EC_EMAIL']      = 'heuripedes@hotmail.com'; //Email do admin
$cfg['EC_NICK']       = 25;                       //Tamanho do nick
$cfg['EC_TEXTO']      = 52;                       //Tamanho do texto
$cfg['EC_PREFIX']     = '';                       //Prefixo dos arquivosde sala
$cfg['EC_TPL1']       = 'tpl1.html';              //Template do chat
$cfg['EC_VERSAO']     = '3.2';                    // Versуo

/**
 * Linguagens oficiais desta versуo:
 *    ptbr = Portuguъs Brasileiro
 *    enus = Inglъs Estados unidos
 */
$cfg['EC_LANG']       = 'ptbr';

/**
 * Transforma as chaves de $cfg em constantes
 */
while(list($k,$v) = each($cfg)) {
    if (!defined($k)) {
        define($k,$v); 
    }
    unset($cfg[$k]);
}


/**
 * Inclui o arquivo de linguagem
 */
include('lang/' . EC_LANG . '.php'); 
?>