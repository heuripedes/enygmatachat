<?php
/**
 * Enygmata Chat
 * --------------------
 * Arquivo..: ec.class.php 
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

// Função de iniciação da classe
function ec($arq = '',$anonimo = '',$admin = '',$entra = '',$sai = '',$tpl = ''){
    $ob = new Enygmata_chat($arq,$anonimo,$admin,$entra,$sai,$tpl);
    return $ob;
}

/**
 * Esta classe é responsável pelas principais funções do EC
 */
class Enygmata_chat
{
    var $arq = NULL;
    var $anonimo = NULL;
	var $admin = NULL;
	var $entra = NULL;
    var $tpl = NULL;
    var $busy = 0;
    var $infCache = array('msg'=>NULL);

    function Enygmata_chat ($arq = '',$anonimo = '',$admin = '',$entra = '',$sai = '')
	{
        if ($arq && $anonimo && $admin && $entra && $sai) {
            $this->start($arq,$anonimo,$admin,$entra,$sai);
        	Return true;
        }
    	Return false;
    }
    
    function start ($arq,$anonimo,$admin,$entra,$sai)
	{
        $this->arq     = $arq;
        $this->anonimo = $anonimo;
        $this->admin   = $admin;
        $this->entra   = $entra;
        $this->sai     = $sai;
        Return true;
    }
    
    function getNumMsgs ()
	{
        $fr  = $this->ler($this->arq);
        if (!$fr) {
            return false;
        }
        $fr = explode("\n", $fr);
        $fr = @count($fr) - $n;
        return $fr;
    }
    
    function getFilesSizes ($num,$pre)
	{
        settype($num,'integer');
        for ($i=1; $i<$num; $i++) {
            $f += filesize('texto/' . $pre . 'sala' .$i . '.txt');        	
        }
        Return $f;
    }

    function bbCode ( $bbcode)
	{ 
        if (!trim($bbcode)) {
            Return false;
        }
		if(function_exists('preg_replace')) {
			$pat = array(
				'#\[color=(.+?)\](.*?)\[\/color\]#is',
				'#\[i\](.+?)\[\/i\]#is',
				'#\[u\](.+?)\[\/u\]#is',
				'#\[b\](.+?)\[\/b\]#is',
				'#\[url\](.+?)\[\/url\]#is'
			);
			
			$rep = array(
				'<FONT COLOR="\\1">\\2</FONT>',
				'<I>\\1</I>',
				'<U>\\1</U>',
				'<B>\\1</B>',
				'<A HREF="\\1" target="_blank">\\1</A>'
			);
			
			$bbcode =  preg_replace($pat,$rep,$bbcode);
		}else{
			$pat = array(
				'\[color=(.*)\](.*)\[/color\]',
				'\[i\](.*)\[/i\]',
				'\[u\](.*)\[/u\]',
				'\[b\](.*)\[/b\]',
				'\[url\](.*)\[/url\]'
			);
			
			$rep = array(
				'<FONT COLOR="\\1">\\2</FONT>',
				'<I>\\1</I>',
				'<U>\\1</U>',
				'<B>\\1</B>',
				'<A HREF="\\1" target="_blank">\\1</A>'
			);
			for($i=0; $i<count($pat); $i++) {
				$bbcode =  eregi_replace($pat[$i],$rep[$i],$bbcode);	
			}
		}

        Return $bbcode;
    }

    function smilies ($txt)
	{
        if (!trim($txt)) {
            Return false;
        }
        for ($i=1; $i<=10; $i++) {
            $txt = str_replace("[$i]", "<img src=\"images/$i.gif\" border=\"0\" >",$txt);
        }
        Return $txt;
    }
    
    function msg ($Nick =NULL,$Texto=NULL)
	{
        clearstatcache();
        $temp = tmpfile();
        @fflush ( $temp );
        fclose($temp);
        if ($Texto) {
            if($this->isBan($_SERVER['REMOTE_ADDR'])) {
                return;
            }
            if($Nick == $this->admin && $_SERVER['REMOTE_ADDR'] != $_SERVER['SERVER_ADDR']) {
                return;
            }
                  
            $Nick  = htmlspecialchars($Nick);
            $Texto = htmlspecialchars($Texto);
            $Nick  = $this->bbCode($Nick);
            $Nick  = $this->smilies($Nick);
            $Texto = $this->bbCode($Texto);
            $Texto = $this->smilies($Texto);
            $Nick  = stripslashes($Nick);
            $Texto = stripslashes($Texto);
            
            unset($fr);
            $fr = $this->ler($this->arq);
            
            $ip  = $_SERVER['REMOTE_ADDR'];	
            $tpl = "$Nick<+>$ip<+>$Texto\n";

            $fr .= $tpl;
           
            $this->escreve($this->arq,$fr);
            unset($fr);
            unset($Nick);
            unset($Texto);
            Return true;
        }else{
            return false;
        }
       
    }
    
    function msg2 ($texto,$notice)
    {
        echo '<B>' . $notice . ': </B>' . $texto .'<br>';
    }

    function getMsg ($txt = ''){
        if(!$txt) {
            $txt = $this->ler($this->arq);
        }
        $msg = explode("\n" , $txt);
        $msg = array_reverse($msg);

        for ($i=0; $i<count($msg); $i++) {
            $msg[$i] = explode('<+>', $msg[$i]);
            if($msg[$i][0] == 'A'.$_SESSION['nick']) {
                $msg[$i][0] = substr($msg[$i][0],1);
            }
        }
        return $msg;
    }

    function ler ($file)
	{
        $fp = @fopen($file, 'r');
        if (!$fp) {
            Return false;
        }
        $fr = fread($fp, @filesize( $file) + 1);
        fclose($fp);
        return $fr;
    }

    function escreve ($file, $oq)
	{
        $fp = fopen($file, 'w');
        if (!$fp) {
            Return false;
        }
        fwrite($fp, $oq);
        fclose($fp);
        Return true;
    }

    function regNick ($nick)
	{
        if (trim($nick))
        {   
            if($_SESSION['nick'] != $nick) {
                
                $_SESSION['nick'] = $nick;
                $this->msg($this->admin,$_SESSION['nick'] . $this->entra);
            }
            
        }else{
            return false;
        }
        
    }

    function unregNick ()
    {
        if (isset($_SESSION['nick'])) {
            $this->msg($this->admin,$_SESSION['nick'] . $this->sai);
            session_destroy();    
        }
    }

    function limpa ($arq)
	{
        $fp = fopen($arq, 'wb');
        if (!$fp) {
            Return false;
        }
        fwrite($fp,'');
        fclose($fp);
        Return true;
    }

     function isBan($ip) {
         $fnam = 'texto/banidos.txt';
         
         if(!@file_exists($fnam) ) {
             return;
         }
         if($ip == $_SERVER['SERVER_ADDR']) {
             return;
         }

         $arq = $this->ler($fnam);
         if(stristr($arq,$ip)) {
             return true;
         }else{
             return false;
         }
     }
     function unBan($ip) {
         $sb = $this->isBan($ip);
         if($sb) {
             $l = $this->ler('texto/banidos.txt');
             $l = str_replace($ip . "<ip>", '',$l);
             $this->escreve('texto/banidos.txt',$l);
             return true;
         }else{
             return false;
         }
     }
     function ban($ip) {
         if($ip && md5($ip) != md5($_SERVER['SERVER_ADDR'])) {
             $l = $this->ler('texto/banidos.txt');
             if(!strstr($l,$ip)) {
                 $l .= $ip . "<ip>";
                 $this->escreve('texto/banidos.txt',$l);
                 return true;
             }else{
                 return false;
             }
         }else{
             return false;
         }
     }

     function lsOnline() {
         for($i=1;$i<EC_NUM_SALAS;$i++) {
             $t .= $this->ler('texto/' . EC_PREFIX . 'sala' . $i . '.txt');
             
         }
            $msg = explode("\n" , $t);
            $msg = array_reverse($msg);

            for ($i=0; $i<count($msg); $i++) {
                $u[$i] = explode('<+>', $msg[$i]);
                $u[$i]   = $u[$i][0] . '<>'.$u[$i][1];
            }
         return $u;
     }
}


?>