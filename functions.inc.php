<?php
/**
 * Enygmata Chat
 * --------------------
 * Arquivo..: functions.inc.php 
 * Autor....: Higor Euripedes "Enygmata" (heuripedes@hotmail.com)
 * Editor...: Higor Euripedes "Enygmata" (heuirpedes@hotmail.com)
 * Versão...: 4
 * PHP......: 4.1+
 * 
 * +-Aviso:--------------------------------------------[_][ ][x]+
 * | Este programa é livre e vocÊ pode editá-lo avontade desde  |
 * | desde que mantenha o nome do criador no campo Autor acima. |
 * +------------------------------------------------------------+
 */

// Constante de segurança do chat
define('EC_OK',TRUE);

// Inclusões
require_once ('config.php');           // Arquivo de configurações
require_once ('classes/ec.class.php'); // Classe Enygmata_Chat
require_once ('classes/bw.class.php'); // Classe BarWordFilter
require_once('functions.inc.php');     // Arquivo de Funções adicionais

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
	$ch = array( 'Á' => '&Aacute;', 'Í' => '&Iacute;', 'Ó' => '&Oacute;', 'Ú' => '&Uacute;', 'É' => '&Eacute;', 'Ä' => '&Auml;', 'Ï' => '&Iuml;', 'Ö' => '&Ouml;', 'Ü' => '&Uuml;', 'Ë' => '&Euml;', 'À' => '&Agrave;', 'Ì' => '&Igrave;', 'Ò' => '&Ograve;', 'Ù' => '&Ugrave;', 'È' => '&Egrave;', 'Ã' => '&Atilde;', 'Õ' => '&Otilde;', 'Â' => '&Acirc;', 'Î' => '&Icirc;', 'Ô' => '&Ocirc;', 'Û' => '&Ucirc;', 'Ê' => '&Ecirc;', 'á' => '&aacute;', 'í' => '&iacute;', 'ó' => '&oacute;', 'ú' => '&uacute;', 'é' => '&eacute;', 'ä' => '&auml;', 'ï' => '&iuml;', 'ö' => '&ouml;', 'ü' => '&uuml;', 'ë' => '&euml;', 'à' => '&agrave;', 'ì' => '&igrave;', 'ò' => '&ograve;', 'ù' => '&ugrave;', 'è' => '&egrave;', 'ã' => '&atilde;', 'õ' => '&otilde;', 'â' => '&acirc;', 'î' => '&icirc;', 'ô' => '&ocirc;', 'û' => '&ucirc;', 'ê' => '&ecirc;', 'Ç' => '&Ccedil;', 'ç' => '&ccedil;;'
    );
    foreach($ch as $c => $e){
        $txt = str_replace($c,$e,$txt);
    }
    return $txt;
}


?>