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
 * Inicia a sessão atual
 */
@session_start();

/**
 * Inicia a classe Enygmata Chat
 */
$ec = ec('',$lng['anonimo'],$lng['admin'],$lng['entrou'],$lng['saiu']);

/**
 * Código randômico
 */
 $randCode = mt_rand(0,9) . mt_rand(0,9) . mt_rand(0,9) . mt_rand(0,9);
/*echo '<pre>';
print_r($_POST) ;
print_r($_GET) ;
print_r($_SESSION) ;
print_r($_COOKIE) ;
ksort($_EC);
print_r($_EC);
*/
/**
 * Verifica se a senha foi enviada e se é correta
 */
 if(EC_AUTENTICACAO_IMAGEM == 1) {
    if ($_POST['senha'] == EC_SENHA && $_POST['usuario'] == EC_ADMINISTRADOR && 
        $_POST['img_code'] == $_POST['hidden_code']) {
        $_SESSION['senha'] = $_POST['senha'];
        $_SESSION['usuario'] = $_POST['usuario'];
    }
 }else{
    if ($_POST['senha'] == EC_SENHA && $_POST['usuario'] == EC_ADMINISTRADOR) {
        $_SESSION['senha'] = $_POST['senha'];
        $_SESSION['usuario'] = $_POST['usuario'];
        
    }
 }

/**
 * Se solicitado gera uma imagem
 */
if($_GET['generate_image']) {
    imageID($_GET['generate_image']);
    exit;
}
/**
 * Ativa algumas rotinas se $_SESSION['senha'] não for nullo
 */
if (isset($_SESSION['senha'])) {
    /**
    * Se a limpeza de arquivos for solicitada a faça
    */
    if ($_GET['limpa_arq'] == 1) {
        $d = 'texto';
        $d = opendir($d);
        while($e = readdir($d) ) {
            if (is_file('texto/'.$e) && $e != 'banidos.txt') {
                $ec->limpa('texto/'.$e);
            }
         }
    }
    /**
    * Se o logout for solicitado o faça
    */
    if ($_GET['out'] == 1) {
        unset($_SESSION['usuario']);
        unset($_SESSION['senha']);
        @session_destroy();    
    }

    /**
     * Se desbloqueio de usuário for solicitado
     */
     if(base64_decode($_GET['ativar'])) {
         $ec->unBan(base64_decode($_GET['ativar']));
     }
    /**
     * Se bloqueio de usuário for solicitado
     */
     if(base64_decode($_GET['desativar'])) {
         $ec->ban(base64_decode($_GET['desativar']));
     }

    /**
     * Se modificção de configuração for solicitada
     */
     if($_POST['P']) {
        /* while(list($k,$v) = each($_POST['PEC'])) {
              IniSet($k,$v,1);
         }
         */
         foreach($_POST['P'] as $k => $v){
             IniSet($k,$v,1);
         }
         //echo '<SCRIPT LANGUAGE="JavaScript">location.href = \'adm.php\'</SCRIPT>';
     }
     if($_POST['msg_to_chat'] && $_POST['room_n']) {
         $p = ((int)$_POST['room_n'])?(int)$_POST['room_n']:1;
        $atual =  EC_PREFIX . 'sala' . $p;
        $arq_atual = 'texto/' . $atual . '.txt';
        $ec->arq = $arq_atual;
        $cr = $ec->admin;
        $ec->admin = '';        
         $ec->msg($lng['admin'],$_POST['msg_to_chat']);
         $ec->admin = $cr;
     }
}


$ip = $_SERVER['REMOTE_ADDR'];

 ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo EC_NOME_CHAT . '::' . $lng['administracao'];?> </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
<STYLE TYPE="text/css" TITLE="">
pre{font-family:Arial ,Trebuchet Ms,Helvetica,Arial;font-size:10pt;}
th{background-color:#F1F1F1;text-align:left;font-size:10pt;}
td{font-family:Arial ,Trebuchet Ms,Helvetica,Arial;text-align:left;font-size:10pt;line-height: 15px;}
.t{border-style:solid;border-color:#C0C0C0;border-width:1px;}
input{font-family:Arial,Trebuchet Ms,Helvetica,Arial;font-size:10pt;}
</STYLE>
<SCRIPT LANGUAGE="JavaScript">
<!--
var isDHTML  = 0;
var isLayers = 0;
var isAll    = 0;
var isID     = 0;

if (document.getElementByID)
{
	isID    = 1;
	isDHTML = 1;
}else{
	if (document.all)
	{
		isAll   = 1;
		isDHTML = 1;
	}else{
		browserVersion = parseInt(navigator.appVersion);
		if ((navigator.appName.indexOf('Netscape') != -1) && ( browserVersion == 4))
		{
			isLayers = 1;
			isDHTML  = 1;
		}
	}
}

function findDOM(objectID,withStyle){
	if (withStyle == 1)
	{
		if (isID)
		{
			return (document.getElementById(objectID).style);
		}else{
			if (isAll)
			{
				return (document.all[objectID].style);
			}else{
				if (isLayers)
				{
					return (document.layers[objectID]);
				}
			}
		}
	}else{
		if (isID)
		{
			return (document.getElementById(objectID));
		}else{
			if (isAll)
			{
				return (document.all[objectID]);
			}else{
				if (isLayers)
				{
					return (document.layers[objectID]);
				}
			}
		}
	}
}
function HideShow(ID) {
    var o = findDOM(ID,1);
    if(o.display == 'none') {
        o.display = '';
    }else{
        o.disaplay = 'none';
    }

}
//-->
</SCRIPT>
</HEAD>
<BODY style="font-family:verdana,arial,helvetica,tahoma;">
<H2><?php echo $lng['administracao'];?> </H2><br>
<pre><A HREF="index.php"><?php echo $lng['volta_chat'];?></A>

Digite a senha para que as opções sejam exibidas
Enter the password to see options
<?php
if (!trim($_SESSION['senha'])) {
?>
<FORM METHOD=POST ACTION="<?php echo $_SERVER['PHP_SELF'] ?>">
Usuário/User  : <INPUT TYPE="text" name="usuario">
Senha/Password: <INPUT TYPE="password" name="senha">
<?php 
    if(EC_AUTENTICACAO_IMAGEM == 1) {
    ?>
Digite o código/Type the code:
<IMG SRC="adm.php?generate_image=<?php echo $randCode; ?>"><INPUT TYPE="hidden" value="<?php echo $randCode; ?>" name="hidden_code"><INPUT TYPE="text" NAME="img_code">
  <?php  }
    ?>
<INPUT TYPE="submit" value="Entrar"><br>

</FORM>
<?
    exit;	
}
?><br><FORM METHOD=POST ACTION="<?php echo $PHP_SELF ?>">
<TABLE width="100%" class="t">
<TR>
	<Th>Enviar mensagem para o chat/Send message to chat</Th>
</TR>
<TR>
<TD>
Menssagem/Message:
<INPUT TYPE="text" NAME="msg_to_chat">
Sala/Room<SELECT NAME="room_n"><?php
for($i=1;$i<=EC_NUM_SALAS;$i++) {
    echo "<OPTION value=\"$i\">$i</OPTION>";
}?></SELECT><INPUT TYPE="submit" value="Enviar/Send">
</FORM>
</TD>
</TR>
</TABLE>

<TABLE width="100%" class="t">
<TR>
	<Th>Informações/Informations</Th>
</TR>
<TR>
	<TD>
        <TABLE width="100%"  id="info" name="info">
        <TR>
            <TD>Nome/Name:</TD>
            <TD><?php echo ucfirst(EC_ADMINISTRADOR)?>&nbsp;&nbsp; [<A HREF="<?php echo $_SERVER['PHP_SELF'];?>?out=1">Logout</A>]</TD>
        </TR>
        <TR>
            <TD>E-mail:</TD>
            <TD><?php echo EC_EMAIL?></TD>
        </TR>
        <TR>
            <TD>Nome do chat/Chat name:</TD>
            <TD><?php echo EC_NOME_CHAT;?></TD>
        </TR>
        <TR>
            <TD>Versão do chat/Chat version:</TD>
            <TD><?php echo EC_VERSAO_CHAT?></TD>
        </TR>
        <TR>
            <TD>Idioma do chat/Chat language:</TD>
            <TD><?php echo EC_LINGUAGEM?></TD>
        </TR>
        <TR>
            <TD>IP do servidor/Server IP:</TD>
            <TD><?php echo $_SERVER['SERVER_ADDR']; ?></TD>
        </TR>
         <TR>
            <TD>Template:</TD>
            <TD><?php echo EC_TEMPLATE;?></TD>
        </TR>

        <TR>
            <TD>Numero de salas/Number of rooms:</TD>
            <TD><?php echo EC_NUM_SALAS ?></TD>
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
<A HREF="<?php echo $_SERVER['PHP_SELF'] ;?>?limpa_arq=1">Limpar arquivos/Clear files</A>
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
$u = $ec->lsOnline();
$txt = NULL;
for($i=0;$i<count($u);$i++) {
    $u[$i] = explode('<>',$u[$i]);
    $un = $u[$i][0];
    $ui = $u[$i][1];

    if($un && $ui) {
        if(!strstr($txt,$ui)) {
            $sb = $ec->isBan($ui);
            if($sb) {
                $act = 'Ativar/Enable';
                $stt = '<FONT COLOR="#ff0000"><B>X</B></FONT>';
                $uri = 'ativar';
            }else{
                $act = 'Desativar/Disable';
                $stt = '<FONT COLOR="#0000ff"><B>OK</B></FONT>';
                $uri = 'desativar';
            }
            if($ui == $_SERVER['SERVER_ADDR']) {
                $act = '</a>Servidor/Server<a>';
            }
            $txt .=  '<TR><TD>' . $un . '</TD><TD>' . $ui . '</TD>'.
                '<TD>' . $stt .'</TD><TD><A HREF="' . $_SERVER['PHP_SELF'] .'?' . $uri . '=' . base64_encode($ui) .
                '">' . $act . "</A></TD></TR>\r\n";  
        }
   }
}
echo $txt;

?>
</TABLE>

<TABLE width="100%" class="t">
<TR>
	<Th>Configuração/Configuration</Th>
</TR>
<TR>
<TD>
<PRE>
<FORM METHOD=POST ACTION="<?php echo $_SERVER['PHP_SELF'];?>">
<TABLE>
<TR><TD><U>Item</U></TD><TD><U>Valor/Value</U></TD></TR>

<?php
$format  = '<TR><TD>%s</TD><TD><INPUT TYPE="text" NAME="P[%s]" value="%s">%s</TD></TR>'.chr('10');
$formatp = '<TR><TD>%s</TD><TD><INPUT TYPE="password" NAME="P[%s]" value="%s">%s</TD></TR>'.chr('10');
while(list($k,$v) = each($_EC)) {
    $k2 = $k;
    if($k == 'EC_TEMPLATE') {
        $d = dir("templates/");
        while (false !== ($y = $d->read())) {
            if($y != '.' && $y != '..' && @is_dir('templates/'.$y)) {
                $xt .= "$y, ";
            }
        }
        $d->close();
        $oth = '&nbsp;&nbsp;('.$xt.')';
    }elseif($k == 'EC_MODO_DEPURACAO' || $k == 'EC_AUTENTICACAO_IMAGEM' || $k == 'EC_BLOQUEAR' ){
        $oth = '&nbsp;&nbsp;(0 = On,1 = Off)';
    }elseif($k == 'EC_LINGUAGEM') {
        $oth = "&nbsp;&nbsp;($Langs)";
    }else{
        $oth = '';
    }
    if(stristr($k,'SENHA')) {
        $tfrm .= sprintf($formatp,$k,$k2,$v,$oth);
    }else{
        $tfrm .= sprintf($format,$k,$k2,$v,$oth);
    }
    
}
echo $tfrm;
?>
<TR><TD colspan="2"><INPUT TYPE="submit" value="Salvar/Save"></TD></TR>
</TABLE></FORM>
</TD>
</TR>
</TABLE>



</body></html>

