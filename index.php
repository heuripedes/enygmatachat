<?
session_start(  );
include_once ( 'func.php' );
include_once ( 'config.php' );

//Configura o modo de manipulação de arquivos
if ( $MODO_ARQUIVO == 1 )
{
	$MODO = 'b';
}

//Configura o número da sala até em caso de erro
if ( $NUMERO_SALAS > 30 )
{
	$NUMERO_SALAS = 30;
}
unset( $sala );
$sala = $_GET['sala'];
if ( $sala == '' || $sala == 0 )
{
	$sala = 1;
	$_GET['sala'] = 1;
}
if ( $sala >= $NUMERO_SALAS )
{
	$sala = $NUMERO_SALAS;
}

//Configura o arquivo e a sala atual
$atual =  $PREFIXO . 'sala' . $sala;
$arq_atual = 'texto/' . $atual . '.txt';



//Faz logout se for pedido
if ( $_GET['logout'] == 'y' )
{
	echo  '<SCRIPT>location.href = \'' . $_SERVER['PHP_SELF'] . '?sala=' . $sala .'\';</SCRIPT>';
	gera_msg2 ($_SESSION['nick'] , $SAI_SALA);
	rem_nick( $_SESSION['nick']);
	unset( $_SESSION['nick'] );
	session_destroy(  );
}

//Se o arquivo da sala não existe o cria
if ( !file_exists( $arq_atual ) )
{	
	$fp = @fopen( $arq_atual, 'w' . $MODO );
	@fclose( $fp );
}

//Salva o nick do usuário em $_SESSION['nick']
salva_nick();

//Gera os posts
gera_msg();

//Calcula o nº de posts
$n_mensagens = n_mensagens();

//Estilos
$styles = array( 'Negrito','Itálico','Sublinhado', 'Link' );

//Smilies
$smilies = array(

	'Risada' => '1',
	'Choro' => '2',
	'!' => '3',
	'Ideia' => '4',
	'Sério' => '5',
	'?' => '6',
	'Feliz' => '7',	
	'Triste' => '8',
	'Raiva' => '9',
	'Legal' =>'10'
);

/*
Limpa as mensagens se o número delas for igual ou maior que o número máximo de ->
 mensagens
 */
if ( $n_mensagens >= $MAX_MENSAGENS )
{	
	$n_messagens = 0;
	limpa($arq_atual);
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo $NOME_CHAT; ?></TITLE>

<STYLE TYPE="text/css" TITLE="">
.table1 {background-color:#F1F1F1;background-image:url('images/tab.gif');
		border:0px solid ;border-color:#808080;
		border-bottom-width:1px;border-right-width:1px;}
.tab1 { text-indent: 3pt;}
.tab1_ { text-indent: 3pt;border:0px solid ;border-color:#C0C0C0;
	border-bottom-width:1px;}

.titulo { font-family:Trebuchet Ms,Verdana,Helvetica,Tahoma,Arial,Serif;
		font-size: 16pt; font-weight:bold;}
.texto{font-family:Verdana, Tahoma; font-size:8pt;background-color:#F1F1F1;}

body {font-family:Verdana, Tahoma; font-size:8pt;margin:3px;
	SCROLLBAR-FACE-COLOR:#EEEEEE;SCROLLBAR-ARROW-COLOR:#808080;
	SCROLLBAR-3DLIGHT-COLOR:#F0F0F0;SCROLLBAR-DARKSHADOW-COLOR:#808080;
	SCROLLBAR-HIGHLIGHT-COLOR:#FFFFFF;SCROLLBAR-SHADOW-COLOR:#C0C0C0;
	SCROLLBAR-TRACK-COLOR:#FFFFFF;}

hr {color:#C0C0C0;height:1px;}
input {font-family:Verdana, Tahoma; font-size:8pt;background-color:transparent;border-width:1px; }
select {font-family:Verdana, Tahoma; font-size:8pt;background-color:transparent;border-width:1px; }
.td {font-family:Verdana, Tahoma; font-size:8pt;}
</STYLE>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript" src="script.js">
</SCRIPT>
<SCRIPT LANGUAGE="JavaScript">
<!--
<?php echo $PageScript; ?>
//-->
</SCRIPT>
</HEAD>

<BODY oncload="frm.text.focus();">
<FORM name="frm" METHOD=POST ACTION="<?php echo $_SERVER['PHP_SELF'] . '?sala=' . $sala; ?>">
<TABLE cellpadding="0" border=0 cellspacing="0" width="600" height="85"class="table1"  >
<TR>
	<TD class="tab1_" valign="middle" align="left" height="20">
   <FONT class="titulo"><?php echo $NOME_CHAT; ?></FONT>
	</TD>
</TR>
<TR>
	<TD class="tab1" valign="middle" align="left" ><FONT class="td">
	<?php
	echo $SALA . ':';

	//Gera os links para as salas do chat
	for ($i = 1; $i < $NUMERO_SALAS + 1; $i++)
	{
		if ( $i == $sala )
		{
			echo "&nbsp;[$i]";
		}else
		{
			echo '&nbsp;<A HREF="' . $_SERVER['PHP_SELF'] . '?sala=' . $i . '" >' . $i .'</A>';
		}
	}
	?></FONT>
	</TD>
</TR>
<TR>
	<TD class="tab1" valign="middle" align="left"  height="20">
	<TABLE width='100%'>
	<TR>
		<TD width='34%'><FONT class="td"><?php echo $MENSAGENS . ':' . $n_mensagens; ?></FONT></TD>
		<TD width='33%'>&nbsp;</TD>
		<?php
			$ls = list_nick();
			foreach ($ls as $val)
			{
				$u_opt .= '<OPTION>'.$val.'</OPTION>';
		
			}
		?>
		<TD width='33%'><FONT class="td"><?=$U_ONLINE;?>:</FONT><SELECT NAME=""><?=$u_opt;?></SELECT></TD>
	</TR>
	</TABLE>
	</TD>
</TR>
</TABLE>

<IFRAME NAME="cht" SRC="salas.php?abre=<?php echo $atual?>" WIDTH="600" height="200" style="bACKGROUND-color:#F1F1F1;">Seu navegador não suporta frames. <br>
Para visualizar corretamente esta página atualize seu navegador ou instale um que suporte frames.</IFRAME>	

<TABLE class="table1" width=600>
<TR>
	<TD><FONT class="td"><?php echo $NICK; ?>:</FONT></TD>
	<TD>

	<?php
	
	$nick_box1 = '<INPUT TYPE="text" size="40" NAME="nick" class="texto" value="' . $_SESSION['nick'] .
		'">';
	$nick_box2 = '<INPUT TYPE="hidden" size="40" NAME="nick" class="texto" value="' . $_SESSION['nick'] .
		'">';
	//Oculta/Mostra a caixa de texto do nick
	if ( $_SESSION['nick'] )
	{
		echo '<FONT class="td">' . $_SESSION['nick'] . '&nbsp;&nbsp;&nbsp;&nbsp; [<A HREF="' .
			$_SERVER['PHP_SELF'] . '?logout=y">' . $LOGOUT . '</A>]</FONT>' . $nick_box2;
	}
	else
	{
		echo $nick_box1;
	}
	?>
	</TD>
</TR>
<tr><td>
</td></tr> 

<TR>
	<TD><FONT class="td"><?php echo $TEXTO; ?>:</FONT></TD>
	<TD><INPUT TYPE="text" size="40" class="texto" NAME="texto">&nbsp;&nbsp;
	<?php
	for ($i=0; $i<count( $styles ); $i++)
	{
		$opt_style .= "<OPTION value=\"$i\">$styles[$i]</OPTION>\n";
	}
		echo '<FONT class="td">' . $ESTILO . ':</FONT><SELECT NAME="bb" size="1" onchange="bbfontstyle(\'\')">' . $opt_style . '</select>';
	
	ksort( $smilies );
	while( list( $name,$key ) = each( $smilies ) )
	{
		$opt_smilies .= '<OPTION value="' . $key . '">' . $name . '</OPTION>';
	}
		echo '&nbsp;<FONT class="td">Smilies:</FONT><SELECT NAME="smy" onchange="smilies();">' . $opt_smilies . '</SELECT>';
	?>
	
	</TD>
</TR>
<TR>
	<TD colspan=2 align="center" valign="middle">
	<INPUT TYPE="submit" value="<?php echo $ENVIAR; ?>">&nbsp;&nbsp;&nbsp;
	<INPUT TYPE="reset"value="<?php echo $LIMPAR; ?>">
	</TD>
	</tr>
</TABLE>
</FORM>
<CENTER><A HREF="adm.php">Administração</A></CENTER>

