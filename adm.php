<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Este é o arquivo da Administração
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
 * Esta variável define que o EC pode funcionar comsegurança
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
 *Inclui o arquivo de funções adicionais
 */
require_once('functions.inc.php');

/**
 * Verifica se o chat está bloqueado
 */
if (EC_BLOC != 0 || $cfg['EC_BLOC'] != 0) {
    exit;    
}

/**
 * Inicia a sessão atual
 */
@session_start();

/**
 * Inicia a classe Enygmata Chat
 */
$ec = ec('',$lng['anonimo'],$lag['admin'],$lng['entrou'],$lng['saiu']);

/**
 * Verifica se a senha foi enviada e se é correta
 */
if (md5($HTTP_POST_VARS['senha']) == md5(EC_SENHA) && md5($HTTP_POST_VARS['usuario']) == md5(EC_NOME) ) {
    if(EC_AUTH != 2) {
        $_SESSION['senha'] = $HTTP_POST_VARS['senha'];
        $_SESSION['usuario'] = $HTTP_POST_VARS['usuario'];
    }else{
        $HTTP_COOKIE_VARS['senha'] = $HTTP_POST_VARS['senha'];
        $HTTP_COOKIE_VARS['usuario'] = $HTTP_POST_VARS['usuario'];

    }
}

/**
 * Ativa algumas rotinas se $_SESSION['senha'] não for nullo
 */
if (trim($_SESSION['senha']) || trim($HTTP_COOKIE_VARS['senha'])) {
    /**
    * Se a limpeza de arquivos for solicitada a faça
    */
    if ($HTTP_GET_VARS['limpa_arq'] == 1) {
        $d = 'texto';
        $d = opendir($d);
        while($e = readdir($d) ) {
            if (is_file('texto/'.$e) && $e != 'banidos.txt') {
                $ec->ec_limpa('texto/'.$e);
            }
         }
    }
    /**
    * Se o logout for solicitado o faça
    */
    if ($HTTP_GET_VARS['out'] == 1) {
        $_SESSION['usuario'] = NULL;
        $_SESSION['senha'] = NULL;
        $HTTP_COOKIE_VARS['usuario'] = NULL;
        $HTTP_COOKIE_VARS['senha'] = NULL;
    }

    /**
     * Se desbloqueio de usuário for solicitado
     */
     if(base64_decode($HTTP_GET_VARS['ativar'])) {
         $ec->ec_un_ban(base64_decode($HTTP_GET_VARS['ativar']));
     }
    /**
     * Se bloqueio de usuário for solicitado
     */
     if(base64_decode($HTTP_GET_VARS['desativar'])) {
         $ec->ec_ban(base64_decode($HTTP_GET_VARS['desativar']));
     }

}

$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];

 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo EC_CHAT . '::' . $lng['administracao'];?> </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<STYLE TYPE="text/css" TITLE="">
pre{font-family:Verdana,Trebuchet Ms,Helvetica,Arial;font-size:8pt;}
th{background-color:#F1F1F1;text-align:left;font-size:8pt;}
td{font-family:Verdana,Trebuchet Ms,Helvetica,Arial;text-align:left;font-size:8pt;line-height: 15px;}
.t{border-style:solid;border-color:#C0C0C0;border-width:1px;}
</STYLE>
</HEAD>
<BODY style="font-family:verdana,arial,helvetica,tahoma;">
<H2><?php echo $lng['administracao'];?> </H2><br>
<pre><A HREF="index.php"><?php echo $lng['volta_chat'];?></A>

Digite a senha para que as opções sejam exibidas
Enter the password to see options
<?php
if (!trim($_SESSION['senha'])) {
?>
<FORM METHOD=POST ACTION="<?php echo $HTTP_SERVER_VARS['PHP_SELF'] ?>">
Usuário/User  : <INPUT TYPE="text" name="usuario"><br>
Senha/Password: <INPUT TYPE="password" name="senha"> <INPUT TYPE="submit" value="Entrar">
</FORM>
<?
    exit;	
}
?><br>
<TABLE width="100%" class="t">
<TR>
	<Th>Informações/Informations</Th>
</TR>
<TR>
	<TD>
    <TABLE width="100%">
    <TR>
    	<TD>Nome/Name:</TD>
    	<TD><?php echo ucfirst(EC_NOME)?>&nbsp;&nbsp; [<A HREF="<?php echo $HTTP_SERVER_VARS['PHP_SELF'];?>?out=1">Logout</A>]</TD>
    </TR>
    <TR>
    	<TD>E-mail:</TD>
    	<TD><?php echo EC_EMAIL?></TD>
    </TR>
    <TR>
    	<TD>Nome do chat/Chat name:</TD>
    	<TD><?php echo EC_CHAT;?></TD>
    </TR>
    <TR>
    	<TD>Versão do chat/Chat version:</TD>
    	<TD><?php echo EC_VERSAO?></TD>
    </TR>
    <TR>
    	<TD>Idioma do chat/Chat language:</TD>
    	<TD><?php echo EC_LANG?></TD>
    </TR>
    <TR>
    	<TD>Autenticação/Authentication:</TD>
    	<TD><?php echo (EC_LANG != 2)?'SESSION':'COOKIE'?></TD>
    </TR>
    <TR>
    	<TD>IP do servidor/Server IP:</TD>
    	<TD><?php echo $HTTP_SERVER_VARS['SERVER_ADDR']; ?></TD>
    </TR>
     <TR>
    	<TD>Template:</TD>
    	<TD><A HREF="<?php echo EC_TPL1?>"><?php echo EC_TPL1?></A></TD>
    </TR>

    <TR>
    	<TD>Numero de salas/Number of rooms:</TD>
    	<TD><?php echo EC_SALAS ?></TD>
    </TR>
    <TR>
    	<TD >Numero max de msgs/Max mensage number:</TD>
    	<TD><?php echo EC_MAX_MSG;?></TD>
    </TR>
    </TABLE>
   </TD>
</TR>
</TABLE>

<TABLE width="100%" class="t">
<TR>
	<Th>Serviços/Services</Th>
</TR>
<TR>
<TD>
<A HREF="<?php echo $HTTP_SERVER_VARS['PHP_SELF'] ;?>?limpa_arq=1">Limpar arquivos/Clear files</A>
</TD>
</TR>
</TABLE>

<TABLE width="100%" class="t">
<TR>
	<Th colspan="4">Usuários/Users</Th>
</TR>
<TR>
<TD><U>Nome/Name</U></TD>
<TD><U>IP</U></TD>
<TD><U>Status</U></TD>
<TD><U>Ação/Action</U></TD>
</TR>

<?php
/**
 * Administração de usuários
 */
unset($u);
$u = $ec->ec_ls_online();
$us = array();
$ui = array();
for($i=0;$i<count($u[1]);$i++) {
    if($u[0][$i] && $u[1][$i]) {
        if(in_array($u[1][$i],$ui)) {
        }else{
            $us[$i] = $u[0][$i];
            $ui[$i] = $u[1][$i];
            
            $sb = $ec->ec_is_ban($ui[$i]);
            if($sb) {
                $act = 'Ativar/Enable';
                $stt = '<FONT COLOR="#ff0000"><B>X</B></FONT>';
                $uri = 'ativar';
            }else{
                $act = 'Desativar/Disable';
                $stt = '<FONT COLOR="#0000ff"><B>OK</B></FONT>';
                $uri = 'desativar';
            }
            if($ui[$i] == $HTTP_SERVER_VARS['REMOTE_ADDR']) {
                $act = '</a>Servidor/Server<a>';
            }
            echo  '<TR><TD>' . $us[$i] . '</TD><TD>' . $ui[$i] . '</TD>'.
                '<TD>' . $stt .'</TD><TD><A HREF="' . $PHP_SELF .'?' . $uri . '=' . base64_encode($ui[$i]) .
                '">' . $act . "</A></TD></TR>\r\n";  
        }
        
   }
}

?>
</TABLE>


</body></html>

