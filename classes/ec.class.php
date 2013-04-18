<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Este é o arquivo da classe principal
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
 * Declara os valores das variáveis da classe
 *
 * Exemplo de uso:
 * <code>
 * require_once('ec.class.php');
 * 
 * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
 *
 * </code>
 * @param string $arq      o arquivo de sala atual
 * @param string $anonimo  a string do usuário anonimo
 * @param string $admin    a string do admnistrador
 * @param string $entra    a string de entrada na sala
 * @param string $sai      a string de saida da sala
 * 
 * @return bool  Retorna TRUE em caso de sucesso e FALSE em caso de falha
 * @throws exceptionclass  [description]
 *
 * @access public
 * @static
 * 
 */
function ec($arq = '',$anonimo = '',$admin = '',$entra = '',$sai = '',$tpl = ''){
    $ob = new Enygmata_chat($arq,$anonimo,$admin,$entra,$sai,$tpl);
    return $ob;
}

/**
 * Esta classe é responsável pelas principais funções do EC
 *
 * 
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
 */
class Enygmata_chat
{
    // {{{ propriedades
    
    /**
     * Armazena o arquivo atual
     * 
     * É este arquivo que armazena as mensagens
     *
     * @var string
     */
    var $arq = NULL;

    /**
     * String para usuário anônimo
     *
     * É a string usilizada quando o usuários não tem nick
     *
     * @var string
     */
    var $anonimo = NULL;

    /**
     * String para o administrador
     * 
     * Esta string é usada para identificar o admnistrador
     * 
     * @var string
     */ 
	var $admin = NULL;
    
    /**
     * String para a entrada de usuário
     * 
     * Este é o texto escrito quando alguem entra na sala
     *
     * @var string
     */
	var $entra = NULL;
    /**
     * Nome do template
     *
     * @var string
     */
    var $tpl = NULL;

    /**
     * Indica se a classe está ocupada
     *
     * @var int
     */ 
    var $busy = 0;

    /**
     * Cache de informações
     *
     * @var array
     */
    var $infCache = array('msg'=>NULL);

    // }}}
    // {{{ Construtor

    /**
     * Declara os valores das variáveis da classe
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     *
     * </code>
     * @param string $arq      o arquivo de sala atual
     * @param string $anonimo  a string do usuário anonimo
     * @param string $admin    a string do admnistrador
     * @param string $entra    a string de entrada na sala
     * @param string $sai      a string de saida da sala
     * @param string $
     * 
     * @return bool  Retorna TRUE em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see ec(),Enygmata_chat::start()
     */
    function Enygmata_chat ($arq = '',$anonimo = '',$admin = '',$entra = '',$sai = '')
	{
        if ($arq && $anonimo && $admin && $entra && $sai) {
            $this->start($arq,$anonimo,$admin,$entra,$sai);
        	Return true;
        }
    	Return false;
    }
    
    /**
     * Alias de Enygmata_chat::Enygmata_chat
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat;
     * $ec->start($arq, $anonimo, $admin, $entra, $sai);
     *
     * </code>
     * @param string $arq      o arquivo de sala atual
     * @param string $anonimo  a string do usuário anonimo
     * @param string $admin    a string do admnistrador
     * @param string $entra    a string de entrada na sala
     * @param string $sai      a string de saida da sala
     * 
     * @return bool  Retorna TRUE em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see ec(),Enygmata_chat::Enygmata_chat()
     */
    function start ($arq,$anonimo,$admin,$entra,$sai)
	{
        $this->arq     = $arq;
        $this->anonimo = $anonimo;
        $this->admin   = $admin;
        $this->entra   = $entra;
        $this->sai     = $sai;
        Return true;
    }
    
    /**
     * Retorna o número de mensagens
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->getNumMsgs();
     *
     * </code>
     *
     * @param void
     *
     * @return int  Retorna inteiro em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see 
     */
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
    
    /**
     * Retorna o tamanho total dos arquivosde mensagens
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->getFilesSizes($num, $pre);
     *
     * </code>
     * @param int $num     numero de arquivos de sala existentes
     * @param string $pre  prefixo dos arquivos
     * 
     * @return int  Retorna inteiro em caso de sucesso
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::getNumMsgs()
     */
    function getFilesSizes ($num,$pre)
	{
        settype($num,'integer');
        for ($i=1; $i<$num; $i++) {
            $f += filesize('texto/' . $pre . 'sala' .$i . '.txt');        	
        }
        Return $f;
    }

    /**
     * Função BBCode Parser (adaptada)
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->bbCode($bbcode);
     *
     * </code>
     * @param string $pre  texto com bbcode
     * 
     * @return string  Retorna string em caso de sucesso e false em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::smilies()
     */
    function bbCode ( $bbcode)
	{ 
        if (!trim($bbcode)) {
            Return false;
        }
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

        Return $bbcode;
    }

    /**
     * Edita o texto colocando imagens
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->smilies($txt);
     *
     * </code>
     * @param string $txt  texto com cdigos ´de smilies
     * 
     * @return string  Retorna string em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::bbCode()
     */
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
    
    /**
     * Cria mensagens
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->msg($Nick,$Texto);
     *
     * </code>
     * @param string $Nick  texto com nick do usuário
     * @param string $Texto texto da mensagem
     * 
     * @return string  Retorna true em caso de sucesso e false em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::getMsg(),Enymata_chat::msg2();
     */
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
    
    /**
     * Cria mensagens(de aviso ou de erro)
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->msg2($texto,$notice);
     *
     * </code>
     * @param string $texto texto da mensagem
     * @param string $notice string : Notícia ou Erro
     * 
     * @return bool Returna sempre TRUE
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::getMsg(),Enymata_chat::msg();
     */
    function msg2 ($texto,$notice)
    {
        echo '<B>' . $notice . ': </B>' . $texto .'<br>';
    }

    /**
     * Interpreta mensagens
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->getMsg($txt);
     *
     * </code>
     * @param string $txt texto não interpretado
     * 
     * @return array Returna array em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::msg(),Enymata_chat::msg2();
     */
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

    /**
     * Lê arquivos
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $fr = $ec->ler($file);
     * echo $fr;
     *
     * </code>
     * @param string $file nome do arquivo à ser lido
     * 
     * @return string Returna string em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::escreve(),Enygmata_chat::limpa(),
     */
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

    /**
     * Escreve arquivos
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $str = 'oi mamae';
     * $ec->escreve($file, $oq);
     *
     * </code>
     * @param string $file nome do arquivo à ser gravado
     * @param mixed $oq    o que vai ser escrito no arquivo
     * 
     * @return bool Returna TRUE em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::ler(),Enygmata_chat::limpa()
     */
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

    /**
     * Registra usuários
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->regNick($nick);
     *
     * </code>
     * @param string $nick nome do usuário à ser registrado
     * 
     * @return bool Returna TRUE em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::unregNick();
     */
    function regNick ($nick)
	{
        if (trim($nick))
        {
            if (!$_SESSION['nick'] == $nick)
            {
                $_SESSION['nick'] = $nick;
                $this->msg($this->admin,$_SESSION['nick'] . $this->entra);
            }
        }else{
            return false;
        }
        
    }

    /**
     * Desregistra usuário
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $str = 'oi mamae';
     * $ec->unregNick();
     *
     * </code>
     * @param void
     * 
     * @return bool Returna TRUE em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_reg_nick()
     */
    function unregNick ()
    {
        if (isset($_SESSION['nick'])) {
            $this->msg($this->admin,$_SESSION['nick'] . $this->sai);
            session_destroy();    
        }
    }

    /**
     * Limpa arquivo sem apagar
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->limpa($arq);
     *
     * </code>
     * @param string $arq nome do arquivo á ser limpo
     * 
     * @return bool Returna TRUE em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::escreve(),ler()
     */
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

    /**
     * Verifica se um determinado IP está banido
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->isBan($ip);
     *
     * </code>
     * @param string $ip ip do usuário
     * 
     * @return bool Returna TRUE em caso se o usuário está banido e FALSE em caso contrário
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::ban(),Enygmata_chat::unBan()
     */
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
    /**
     * Desbloqueia usuário
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->unBan($ip);
     *
     * </code>
     * @param void 
     * 
     * @return bool Returna TRUE em caso de sucesso FALSE em caso contrário
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::isBan(),Enygmata_chat::ban()
     */
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
    /**
     * Bloqueia usuário
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->ban($ip);
     *
     * </code>
     * @param void 
     * 
     * @return bool Returna TRUE em caso de sucesso FALSE em caso contrário
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::isBan(),Enygmata_chat::unBan()
     */
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

    /**
     * Lista os usuários online
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->lsOnline();
     *
     * </code>
     * @param void 
     * 
     * @return array Returna uma array com os nome dos usuários e FALSE em caso contrário
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::unregNick(),Enygmata_chat::regNick()
     */
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