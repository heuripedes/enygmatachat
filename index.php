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
	gera_msg2 ('[color=#FFCC33]' . $SERVIDOR . '[/color]', 
		'[color=#FFCC33]<B>' . bbcode( $_SESSION['nick'] ) . ' saiu da sala</B>[/color]');
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

/*
Limpa as mensagens se o número delas for igual ou maior que o número máximo de ->
 mensagens
 */
if ( $n_mensagens >= $MAX_MENSAGENS )
{	
	$n_messagens = 0;
	limpa($arq_atual);
}

//Seta a variavel $nick com $_SESSION['nick']
$nick = $_SESSION['nick'];


//Cores
$colors = array(
	'#00FF00' => 'Verde',
	'#008080' => 'Verde 2',
	'#0000FF' => 'Azul',
	'#0000A0' => 'Azul 2',
	'#FF0000' => 'Vermelho',
	'#B70000' => 'Vermelho 2',
	'#000000' => 'Preto',
	'#FFFF00' => 'Amarelo',
	'#E8E800' => 'Amarelo 2',
	'#FF80C0' => 'Rosa',
	'#FF0080' => 'Rosa 2',
	'#C0C0C0' => 'Cinza',
	'#808080' => 'Cinza 2',
	'#FF8040' => 'Laranja',
	'#DD6F00' => 'Laranja 2',
	'#FF80FF' => 'Lilás',
	'#930093' => 'Roxo',
	'#804000' => 'Marrom',
);

//Estilos
$styles = array( 'Negrito','Itálico','Sublinhado', 'Link' );

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo $NOME_CHAT; ?></TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">


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
</STYLE>
<SCRIPT LANGUAGE="JavaScript" type="text/javascript" src="script.js">
</SCRIPT>
</HEAD>

<BODY>
<FORM name="frm" METHOD=POST ACTION="<?php echo $_SERVER['PHP_SELF'] . '?sala=' . $sala; ?>">
<TABLE cellpadding="0" border=0 cellspacing="0" width="600" height="85"class="table1"  >
<TR>
	<TD class="tab1_" valign="middle" align="left" height="20">
   <FONT class="titulo"><?php echo $NOME_CHAT; ?></FONT>
	</TD>
</TR>
<TR>
	<TD class="tab1" valign="middle" align="left" >
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
	?>
	</TD>
</TR>
<TR>
	<TD class="tab1" valign="middle" align="left"  height="20"><?php echo $MENSAGENS . ':' . $n_mensagens; ?></TD>
</TR>
</TABLE>

<IFRAME NAME="cht" SRC="salas.php?abre=<?php echo $atual?>" WIDTH="600" height="200" style="bACKGROUND-color:#F1F1F1;">Seu navegador não suporta frames. <br>
Para visualizar corretamente esta página atualize seu navegador ou instale um que suporte frames.</IFRAME>	

<TABLE class="table1" width=600>
<TR>
	<TD><?php echo $NICK; ?>:</TD>
	<TD>
	<?php
	$nick_box1 = '<INPUT TYPE="text" size="40" NAME="nick" class="texto" value="' . $nick . '">&nbsp;&nbsp;';
	$nick_box2 = '<INPUT TYPE="hidden" size="40" NAME="nick" class="texto" value="' . $nick . '">';

	//Oculta/Mostra a caixa de texto do nick
	if ( $nick )
	{
		echo '<B>' . $nick . '</B>&nbsp;&nbsp;[<A HREF="' . $_SERVER['PHP_SELF'] . '?sala=' . $sala .
			'&logout=y">' . $LOGOUT . '</A>]' . $nick_box2;
	}
	else
	{
		echo $nick_box1;
		
		while( list( $hex, $name ) = each( $colors ) )
		{
			$color_options .= "<OPTION value=\"$hex\">$name</OPTION>\n";
		}

		echo $COR . ':<SELECT  NAME="cor1" size="1" sort="true" onchange="color1()">' . $color_options . '</SELECT>';
	}
	?>
	</TD>
</TR>
<TR>
	<TD><?php echo $TEXTO; ?>:</TD>
	<TD><INPUT TYPE="text" size="40" class="texto" NAME="texto">&nbsp;&nbsp;
	<?php
	for ($i=0; $i<count( $styles ); $i++)
	{
		$opt_style .= "<OPTION value=\"$i\">$styles[$i]</OPTION>\n";
	}
		echo $ESTILO . ':<SELECT NAME="bb" size="1" onchange="bbfontstyle(\'\')">' . $opt_style . '</select>';
	
	?>
	</TD>
</TR>
<TR>
	<TD colspan=2 align="center" valign="middle">
	<INPUT TYPE="submit" value="<?php echo $ENVIAR; ?>">&nbsp;&nbsp;&nbsp;
	<INPUT TYPE="reset"value="<?php echo $LIMPAR; ?>">
	</TD>
</TR>
</TABLE>
</FORM>
<CENTER><A HREF="adm.php">Administração</A></CENTER>
