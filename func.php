<?php
//Funções

include_once ( 'config.php' );

//Configura o modo de manipulação de arquivos
if ( $MODO_ARQUIVO == 1 )
{
	$MODO = 'b';
}

function n_mensagens ()
{
	global $arq_atual;

	$fp = @fopen( $arq_atual, 'rb' );
	$fr = @fread( $fp, @filesize( $arq_atual ) + 1 );
	@fclose( $fp );

	$fr = explode( "\n", $fr );

	if ( !@filesize( $arq_atual ) )
	{
		$n = 1;
	}
	else
	{
		$n = 0;
	}
	for ($i=0; $i<count( $fr ); $i++)
	{
		if ( $fr[$i] == '' || $fr[$i] == "\n" )
		{
			unset( $fr[$i] );
		}
	}

	$fr = @count( $fr ) - $n;
	Return $fr;
	
}

function bbcode ( $bbcode) 
{ 
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
		'<A HREF="\\1">\\1</A>'
	);
	
	$bbcode =  preg_replace( $pat,$rep,$bbcode );

	Return $bbcode;

}

function smilies ($txt)
{
	global $smilies;
	for ($i=1; $i<=10; $i++)
	{
		$txt = str_replace( "[$i]", "<img src=\"images/$i.gif\" border=\"0\" >",$txt );
	}
	Return $txt;
}

function gera_msg ()
{
	global $ANONIMO,$arq_atual,$PageScript;
	$Nick = ($_POST['nick'] != '') ? $_POST['nick'] : $ANONIMO;
	$Texto = $_POST['texto'];
	$Nick = htmlentities( $Nick );
	$Texto = bbcode(htmlentities( $Texto ));
	$Texto = smilies($Texto);
	
	if ( is_banido($Nick) )
	{
		return;
	}

	$fp = fopen( $arq_atual, 'rb' );
	$fr = fread( $fp, filesize( $arq_atual ) + 1 );
	fclose( $fp );

	$now =  getdate(  );
	$now = $now['hours'] . ':' . $now['minutes'] . ':' . $now['seconds'];
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$tpl = $Nick . '|+|' . $ip .'|+|' . $now .'|+|' .$Texto ."\n";

	$fr = $tpl .$fr;
	$fr = stripslashes( $fr );
	$fp = fopen( $arq_atual, 'wb' );
	fwrite( $fp, $fr );
	fclose( $fp );

}

function gera_msg2 ($Nick,$Texto)
{
	global $ANONIMO,$arq_atual;
	$Nick = ($Nick != '') ? $Nick : $ANONIMO;
	$Texto = $Texto;
	$Nick = htmlentities( $Nick );
	$Texto = bbcode(htmlentities( $Texto ));
	
	if ( $Texto )
	{
		
		$fp = fopen( $arq_atual, 'rb' );
		$fr = fread( $fp, filesize( $arq_atual ) + 1 );
		fclose( $fp );

		$now =  getdate(  );
		$now = $now['hours'] . ':' . $now['minutes'] . ':' . $now['seconds'];
		$ip = $_SERVER['REMOTE_ADDR'];
		
		$tpl = $Nick . '|+|' . $ip .'|+|' . $now .'|+|' .$Texto ."\n";

		$fr = $tpl .$fr;
		$fr = stripslashes( $fr );
		$fp = fopen( $arq_atual, 'wb' );
		fwrite( $fp, $fr );
		fclose( $fp );

	}

}

function obtem ($txt)
{
	$msg = explode( "\n" , $txt );

	for ($i=0; $i<count( $msg ); $i++)
	{
		$msg[$i] = explode( '|+|', $msg[$i] );
	}

	Return $msg;
}

function banir ($ip_or_nick)
{
	global $ARQ_BANIDOS;
	$file = 'texto/' . $ARQ_BANIDOS;

	$fr = ler( $file );

	if ( !strstr( $fr , $ip_or_nick  ) )
	{
		escreve( $file, $fr . $ip_or_nick . "\n" );
	}
}

function ativar ($ip_or_nick)
{
	global $ARQ_BANIDOS;
	$file = 'texto/' . $ARQ_BANIDOS;

	$fr = ler( $file );
	
	if ( strstr( $fr , $ip_or_nick  ) )
	{	
		$fr = str_replace( $ip_or_nick . "\n", '', $fr );
		escreve( $file, $fr );
	}
}

function is_banido ($ip_or_nick)
{
	global $ARQ_BANIDOS;
	
	$file = 'texto/' . $ARQ_BANIDOS;
	$fr = ler($file);

	if ( strstr( $fr, $ip_or_nick ) )
	{
		Return true;
	}
}

function add_nick ($nick)
{
	$file = 'texto/usuarios.txt';

	$fr = ler($file);
	
	if ( !strstr( $fr, $nick ) )
	{
		$fr .= $nick . '|+|';
	}
	
	escreve( $file, $fr );

}

function rem_nick ($nick)
{
	$file =  'texto/usuarios.txt';
	$fr = ler($file);

	$fr = str_replace( $nick . "|+|", '', $fr );

	escreve ( $file, $fr);
}

function list_nick ()
{
	$file = 'texto/usuarios.txt';

	$fr = ler ($file);
	$fr = explode( '|+|', $fr );

	sort( $fr );
	reset( $fr );

	for ($i=0; $i<count( $fr ); $i++)
	{
		if ( $fr[$i] == '' || $fr[$i] == ' ' || $fr[$i] == "\n" )
		{
			unset( $fr[$i] );
		}
	}

	sort( $fr );
	reset( $fr );

	Return $fr;
}

function ler ($file)
{
	$fp = @fopen( $file, 'rb' );
	$fr = @fread( $fp, @filesize( $file ) + 1 );
	@fclose( $fp );
	return $fr;
}

function escreve ($file, $data)
{
	$fp = @fopen( $file, 'wb' );
	@fwrite( $fp, $data );
	@fclose( $fp );
	
}

function salva_nick ()
{
	global $SERVIDOR,$MODO,$ENTRA_SALA;
	if ( $_POST['nick'] )
	{
		if ( !$_SESSION['nick'] == $_POST['nick'] )
		{
			$_SESSION['nick'] = htmlentities( $_POST['nick'] );
			add_nick ( $_SESSION['nick']);
			gera_msg2 ($_SESSION['nick'], $ENTRA_SALA);
		}
	}
}

function limpa ($arq)
{
	global $MODO;
	$fp = fopen( $arq, 'wb' );
	fwrite( $fp,'' );
	fclose( $fp );
}


?>