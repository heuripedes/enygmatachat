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
 * @since      File available since Release 3.2
 * @deprecated File deprecated in Release 3.3
 * @access     public
 */

/**
 * Função que instancia a classe
 */
function ec($arq = '',$anonimo = '',$admin = '',$entra = '',$sai = ''){
    $ob = new Enygmata_chat($arq,$anonimo,$admin,$entra,$sai);
    Return $ob;
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
 * @since      File available since Release 3.2
 * @deprecated File deprecated in Release 3.3
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
     * 
     * @return bool  Retorna TRUE em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see ec(),Enygmata_chat::start()
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
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
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
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
     * $ec->ec_get_num_msgs();
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
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_get_num_msgs ()
	{
        $fr  = $this->ec_ler($this->arq);
        if (!trim($fr)) {
            Return false;
        }
        if (!@filesize($this->arq) >= 1) {
            $n = 1;
        }
        else {
            $n = 0;
        }

        $fr = explode("\n", $fr);
        $fr = @count($fr) - $n;
        Return $fr;
    }
    
    /**
     * Retorna o tamanho total dos arquivosde mensagens
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->ec_get_files_sizes($num, $pre);
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
     * @see Enygmata_chat::ec_get_num_msgs()
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_get_files_sizes ($num,$pre)
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
     * $ec->ec_bbcode($bbcode);
     *
     * </code>
     * @param string $pre  texto com bbcode
     * 
     * @return string  Retorna string em caso de sucesso e false em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::ec_smilies()
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_bbcode ( $bbcode)
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
     * $ec->ec_smilies($txt);
     *
     * </code>
     * @param string $txt  texto com cdigos ´de smilies
     * 
     * @return string  Retorna string em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::ec_bbcode()
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_smilies ($txt)
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
     * $ec->ec_msg($Nick,$Texto);
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
     * @see Enygmata_chat::ec_get_msg(),Enymata_chat::ec_msg2();
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_msg ($Nick,$Texto,$chk = 1)
	{
        if ($chk) {
            if (!$Nick)	{
                Return false;;
            }
        }
        
        $Nick  = $Nick ? $Nick : $this->anonimo;
        $Nick  = htmlentities($Nick);
        $Texto = $this->ec_bbcode(htmlentities($Texto));
        $Texto = $this->ec_smilies($Texto);

        $Texto = wordwrap( $Texto, 52, " ", 1);
        
        /*if ($this->ec_is_ban($Nick)) {
            Return false;;
        }*/

        $fr = $this->ec_ler($this->arq);
        
        $ip  = $_SERVER['REMOTE_ADDR'];	
        $tpl = $Nick . '<+>' . $ip .'<+>' . $Texto ."\n";

        $fr = $tpl .$fr;
        $fr = stripslashes($fr);

        $this->ec_escreve($this->arq,$fr);

        Return true;
    }
    
    /**
     * Cria mensagens(de aviso ou de erro)
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $ec->ec_msg2($texto,$notice);
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
     * @see Enygmata_chat::ec_get_msg(),Enymata_chat::ec_msg();
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_msg2 ($texto,$notice)
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
     * $ec->ec_get_msg($txt);
     *
     * </code>
     * @param string $txt texto não interpretado
     * 
     * @return array Returna array em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::ec_msg(),Enymata_chat::ec_msg2();
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_get_msg ($txt){
        $msg = explode("\n" , $txt);

        for ($i=0; $i<count($msg); $i++) {
            $msg[$i] = explode('<+>', $msg[$i]);
        }

        Return $msg;
    }

    /**
     * Lê arquivos
     *
     * Exemplo de uso:
     * <code>
     * require_once('ec.class.php');
     * 
     * $ec = new Enygmata_chat($arq, $anonimo, $admin, $entra, $sai);
     * $fr = $ec->ec_ler($file);
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
     * @see Enygmata_chat::ec_escreve(),Enygmata_chat::ec_limpa(),
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_ler ($file)
	{
        $fp = @fopen($file, 'rb');
        if (!$fp) {
            Return false;
        }
        $fr = @fread($fp, @filesize( $file) + 1);
        @fclose($fp);
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
     * $ec->ec_escreve($file, $oq);
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
     * @see Enygmata_chat::ec_ler(),Enygmata_chat::ec_limpa()
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_escreve ($file, $oq)
	{
        $fp = @fopen($file, 'wb');
        if (!$fp) {
            Return false;
        }
        @fwrite($fp, $oq);
        @fclose($fp);
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
     * $ec->ec_reg_nick($nick);
     *
     * </code>
     * @param string $nick nome do usuário à ser registrado
     * 
     * @return bool Returna TRUE em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::ec_unreg_nick();
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_reg_nick ($nick)
	{
        if (trim($nick))
        {
            if (!$_SESSION['nick'] == $nick)
            {
                $_COOKIE['nick']  = $nick;
                $_SESSION['nick'] = $nick;
                //$this->ec_add_nick ( $_SESSION['nick']);
                $this->ec_msg ($this->admin,$nick . $this->entra);
                Return true;
            }
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
     * $ec->ec_unreg_nick();
     *
     * </code>
     * @param void
     * 
     * @return bool Returna TRUE em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_reg_nick();
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_unreg_nick ()
    {
        if (isset($_SESSION['nick'])) {
            //$this->ec_rem_nick( $_SESSION['nick']);
            $this->ec_msg($this->admin,$_SESSION['nick'] . $this->sai);
            unset($_COOKIE['nick']);
            unset($_SESSION['nick']);
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
     * $ec->ec_limpa($arq);
     *
     * </code>
     * @param string $arq nome do arquivo á ser limpo
     * 
     * @return bool Returna TRUE em caso de sucesso e FALSE em caso de falha
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see Enygmata_chat::ec_escreve(),ec_ler();
     * @since Method available since Release 3.2
     * @deprecated Method deprecated in Release 3.2
     */
    function ec_limpa ($arq)
	{
        $fp = @fopen($arq, 'wb');
        if (!$fp) {
            Return false;
        }
        @fwrite($fp,'');
        @fclose($fp);
        Return true;
    }

    /** 
Funções não utilizadas nesta versão :

    function ec_banir ($ip_or_nick)
	{

        $file = 'texto/banidos.txt';
        $fr   = $this->ec_ler($file);

        if (!strstr( $fr , $ip_or_nick )) {
            $this->ec_escreve($file, $fr . $ip_or_nick . "\n");
        }
    }

    function ec_ativar ($ip_or_nick)
	{
        
        $file = 'texto/banidos.txt';
        $fr   = $this->ec_ler($file);
        
        if (strstr( $fr , $ip_or_nick )) {	
            $fr = str_replace($ip_or_nick . "\n", '', $fr);
            $this->ec_escreve($file, $fr);
        }
    }

    function ec_is_ban ($ip_or_nick)
	{
        
        $file = 'texto/banidos.txt';
        $fr   = $this->ec_ler($file);

        if (@strstr( $fr, $ip_or_nick)) {
            Return true;
        }
    }

    function ec_add_nick ($nick)
	{

        $file = 'texto/usuarios.txt';
        $fr   = $this->ec_ler($file);
        
        if (!strstr( $fr, $nick))
        {
            $fr .= $nick . '<+>';
        }
        
        $this->ec_escreve($file, $fr);

    }

    function ec_rem_nick ($nick)
	{
        
        $file =  'texto/usuarios.txt';
        $fr   = $this->ec_ler($file);
        $fr   = str_replace($nick . "<+>", '', $fr);

        $this->ec_escreve ( $file, $fr);
    }

    function ec_list_nick ()
	{
        $file = 'texto/usuarios.txt';
        $fr   = $this->ec_ler ($file);
        $fr   = explode('<+>', $fr);

        sort($fr);
        reset($fr);

        for ($i=0; $i<count($fr); $i++) {
            if ($fr[$i] == '' || $fr[$i] == ' ' || $fr[$i] == "\n") {
                unset($fr[$i]);
            }
        }

        sort($fr);
        reset($fr);

        Return $fr;
    }
 */

}


?>