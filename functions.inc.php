<?php
/**
 * Enygmata Chat
 * --------------------
 * Arquivo..: functions.inc.php 
 * Autor....: Higor Euripedes "Enygmata" (heuripedes@hotmail.com)
 * Editor...: Higor Euripedes "Enygmata" (heuirpedes@hotmail.com)
 * Vers�o...: 4
 * PHP......: 4.1+
 * 
 * +-Aviso:--------------------------------------------[_][ ][x]+
 * | Este programa � livre e voc� pode edit�-lo avontade desde  |
 * | desde que mantenha o nome do criador no campo Autor acima. |
 * +------------------------------------------------------------+
 */

// Constante de seguran�a do chat
define('EC_OK',TRUE);

// Inclus�es
require_once ('config.php');           // Arquivo de configura��es
require_once ('classes/ec.class.php'); // Classe Enygmata_Chat
require_once ('classes/bw.class.php'); // Classe BarWordFilter
require_once('functions.inc.php');     // Arquivo de Fun��es adicionais

function ping_server($n = 4,$t = 5)
{
    $s = microtime();
    for($i=0;$i<$n;$i++) {
        $fp = @fsockopen($_SERVER['SERVER_ADDR'], $_SERVER['SERVER_PORT'], $errno, $errstr, $t);
        @fclose($fp);
    }
        return microtime() - $s;
}   

function imageID($text) {
  $im = @imagecreate(100, 30);
  $bg = imagecolorallocate($im, 255, 255, 255);
  $textcolor = imagecolorallocate($im, 0, 0, 255);
  imagestring($im, 5, 0, 0, $text, $textcolor);
  @header("Content-type: image/jpg");
  imagejpeg($im);
}

function IniSet($k,$v,$q=FALSE) {
    $ini = Enygmata_chat::ler('ec_config.ini');
    $iniarr = explode("\r\n",$ini);
    $ini = '';
   
    $len = strlen($k);
    sort($iniarr);
    reset($iniarr);
    for($i=0;$i<count($iniarr);$i++) {
        if(substr($iniarr[$i],0,$len) == $k) {
            if($q) {
                $iniarr[$i] = "$k=\"$v\";";
            }else{
                $iniarr[$i] = "$k=$v;";
            }
        }
    }

    Enygmata_chat::escreve('ec_config.ini',implode("\r\n",$iniarr));
}

function convertChars($txt) {
	$ch = array( '�' => '&Aacute;', '�' => '&Iacute;', '�' => '&Oacute;', '�' => '&Uacute;', '�' => '&Eacute;', '�' => '&Auml;', '�' => '&Iuml;', '�' => '&Ouml;', '�' => '&Uuml;', '�' => '&Euml;', '�' => '&Agrave;', '�' => '&Igrave;', '�' => '&Ograve;', '�' => '&Ugrave;', '�' => '&Egrave;', '�' => '&Atilde;', '�' => '&Otilde;', '�' => '&Acirc;', '�' => '&Icirc;', '�' => '&Ocirc;', '�' => '&Ucirc;', '�' => '&Ecirc;', '�' => '&aacute;', '�' => '&iacute;', '�' => '&oacute;', '�' => '&uacute;', '�' => '&eacute;', '�' => '&auml;', '�' => '&iuml;', '�' => '&ouml;', '�' => '&uuml;', '�' => '&euml;', '�' => '&agrave;', '�' => '&igrave;', '�' => '&ograve;', '�' => '&ugrave;', '�' => '&egrave;', '�' => '&atilde;', '�' => '&otilde;', '�' => '&acirc;', '�' => '&icirc;', '�' => '&ocirc;', '�' => '&ucirc;', '�' => '&ecirc;', '�' => '&Ccedil;', '�' => '&ccedil;;'
    );
    foreach($ch as $c => $e){
        $txt = str_replace($c,$e,$txt);
    }
    return $txt;
}


?>