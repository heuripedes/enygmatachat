<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> Administra��o </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
</HEAD>

<BODY style="font-family:verdana,arial,helvetica,tahoma;">
<H2>Administra��o</H2><br>
<A HREF="index.php">Voltar ao chat</A>

<pre>Digite a senha para que as op��es sejam exibidas

<?php
@session_start(  );
include_once( 'config.php' );
include_once( 'func.php' );

//Se banir n�o estiver vazio, o item selecionado � banido
if ( $_GET['banir'] )
{
	banir($_GET['banir']);
}

//Se ativado n�o estiver vazio, o item selecionado � ativado
if ( $_GET['ativar'] )
{
	ativar($_GET['ativar']);
}

//Se logout n�o estiver vazio o logout � efetuado
if ( $_GET['logout'] == 'y' )
{
	$_SESSION['senha'] = '';
}
if ( $_POST['senha'] == $SENHA  || $_SESSION['senha'])
{
	$_SESSION['senha'] = ($_POST['senha'])?$_POST['senha'] : $_SESSION['senha'];
}

if ( $_GET['limpar'] == 'y' )
{
	for ($i=0; $i<$NUMERO_SALAS; $i++)
	{
		limpa( 'texto/' . $PREFIXO . 'sala' . $i .'.txt' );
	}

	
}

//Se n�o est� logado exiba, casocontr�rio oculte o formul�rio
if ( !$_SESSION['senha'] )
{
?>
<FORM METHOD=POST ACTION="<?php echo $_SERVER['PHP_SELF']; ?>">
Senha:<INPUT TYPE="password" name="senha"><INPUT TYPE="submit" value="ok">
</FORM>
<?php
	exit;
}
else
{
	echo '<A HREF="' . $_SERVER['PHP_SELF'] . '?logout=y">Logout</A><br>';
}
?>

Limpar mensagens : <A HREF="<?php $_SERVER['PHP_SELF'] ?>?limpar=y">Sim</A>

Usu�rios / IP ativos: 
<TABLE width=400>
<TR bgcolor="#F1F1F1"><TD>Nome</TD><TD>IP</TD><TD>A��es</TD>
</TR>
<?
//Obtem todos os arquivos de salas e os coloca numa vari�vel
unset( $fr );
for ($i=1; $i<$NUMERO_SALAS; $i++)
{
	$arq = 'texto/' . $PREFIXO . 'sala' . $i . '.txt';
	//Abre arquivo , o l� e imprime na tela
	$fp = fopen( $arq , 'rb');
	$fr .= fread( $fp,filesize( $arq )+1 );
	
}

$m = obtem($fr);

//Exibe os usu�rios ativos
unset( $users );
for ($i=0; $i<count( $m ); $i++)
{
	if ( $m[$i][0] && $m[$i][1] && $m[$i][2] && $m[$i][3]   )
	{	
		unset( $ban );
		if ( is_banido($m[$i][1]) || is_banido($m[$i][0]) )
		{
			$ban = TRUE;
		}

		if ( !strstr( $users, $m[$i][0] ) && !$ban  )
		{
			$users .= '<TR><TD>' . $m[$i][0] . '</TD><TD>' . $m[$i][1] .'</TD><TD><A HREF="' .
				$_SERVER['PHP_SELF'] . '?banir=' . $m[$i][1] . '">Banir IP</A> - <A HREF="' .
				$_SERVER['PHP_SELF'] . '?banir=' . $m[$i][0] . '">Banir Nick</A></TD><tr>';
		}
		
	}
}
echo $users;
?>
</table>

Usu�rios / IPs inativos: <?php echo $js;?>
<TABLE width=400 >
<TR bgcolor="#F1F1F1"><TD>Item</TD><TD>A��es</TD>
</TR>
<?php
$arq = 'texto/' . $ARQ_BANIDOS;
$fp = @fopen( $arq, 'rb' );
$fr = fread( $fp, filesize( $arq ) + 1 );

$fr = explode( "\n",$fr );
for ($i=0; $i<count( $fr ); $i++)
{
	if ( $fr[$i] != '' && $fr[$i] != "\n" )
	{
		if ( @gethostbyaddr($fr[$i]) )
		{
			$aux = 'IP: ';
		}else
		{
			$aux = 'Usu�rio: ';
		}
		echo '<TR><TD>' . $aux. $fr[$i] . '</TD><TD><A HREF="' . $_SERVER['PHP_SELF'] .
			'?ativar=' . $fr[$i] . '">Ativar</A></TD></TR>';
	}
}
fclose( $fp );
?>


