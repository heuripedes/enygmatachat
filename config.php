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
 * @access     public
 */

/**
 * Verifica se o chat estс ativado
 */
if (EC_OK != TRUE) {
    die('Hacking attempt!');
}

/**
 * Cria a array de configuraчуo interpretando um arquivo *.ini
 */
$ini_arq = 'ec_config.ini';
$cfg = parse_ini_file($ini_arq);

/**
 * Transforma as chaves de $cfg em constantes
 */
while(list($k,$v) = each($cfg)) {
    if (!defined($k)) {
        define($k,$v,TRUE); 
    }
    $GLOBALS['_EC'][$k] = $v;
    unset($cfg[$k]);
}

/**
 * Se solicitado ativa o modo de depuraчуo
 */
if(EC_MODO_DEPURACAO != 0) {
    if(@phpversion() >= '5.0.0' && EC_MODO_DEPURACAO == 2) {
        error_reporting(E_ALL | E_STRICT);
    }else{
        error_reporting(E_ALL);
    }
}

/**
 * Inclui o arquivo de linguagem
 */
 $Langs = 'ptbr,enus';
include('lang/' . EC_LINGUAGEM . '.php'); 
?>