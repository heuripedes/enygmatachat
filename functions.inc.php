<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Este contem algumas funções auxiliares ao EC
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
function ping_server($n = 4,$t = 5)
{
    $s = microtime();
    for($i=0;$i<$n;$i++) {
        $fp = @fsockopen($HTTP_SERVER_VARS['SERVER_ADDR'], $HTTP_SERVER_VARS['SERVER_PORT'], $errno, $errstr, $t);
        @fclose($fp);
    }
        return microtime() - $s;
}   

?>