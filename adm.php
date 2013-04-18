<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> Administração </TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="">
<META NAME="Keywords" CONTENT="">
<META NAME="Description" CONTENT="">
</HEAD>

<BODY style="font-family:verdana,arial,helvetica,tahoma;">
<H2>Administração</H2><br>
<A HREF="index.php">Voltar ao chat</A>

<pre>Digite a senha para que as opções sejam exibidas

<?php
@session_start(  );
include_once( 'config.php' );
include_once( 'func.php' );

//Se banir não estiver vazio, o item selecionado é banido
if ( $_GET['banir'] )
{
	banir($_GET['banir']);
}

//Se ativado não estiver vazio, o item selecionado é ativado
if ( $_GET['ativar'] )
{
	ativar($_GET['ativar']);
}

//Se logout não estiver vazio o logout é efetuado
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

//Se não está logado exiba, casocontrário oculte o formulário
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

Usuários / IP ativos: 
<TABLE width=400>
<TR bgcolor="#F1F1F1"><TD>Nome</TD><TD>IP</TD><TD>Ações</TD>
</TR>
<?
//Obtem todos os arquivos de salas e os coloca numa variável
unset( $fr );
for ($i=1; $i<$NUMERO_SALAS; $i++)
{
	$arq = 'texto/' . $PREFIXO . 'sala' . $i . '.txt';
	//Abre arquivo , o lê e imprime na tela
	$fp = fopen( $arq , 'rb');
	$fr .= fread( $fp,filesize( $arq )+1 );
	
}

$m = obtem($fr);

//Exibe os usuários ativos
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

Usuários / IPs inativos: <?php echo $js;?>
<TABLE width=400 >
<TR bgcolor="#F1F1F1"><TD>Item</TD><TD>Ações</TD>
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
			$aux = 'Usuário: ';
		}
		echo '<TR><TD>' . $aux. $fr[$i] . '</TD><TD><A HREF="' . $_SERVER['PHP_SELF'] .
			'?ativar=' . $fr[$i] . '">Ativar</A></TD></TR>';
	}
}
fclose( $fp );
?>


