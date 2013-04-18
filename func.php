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

	$fr = explode( '<mensagem>', $fr );

	if ( !@filesize( $arq_atual ) )
	{
		$n = 1;
	}
	else
	{
		$n = 0;
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

function gera_msg ()
{
	global $ANONIMO,$arq_atual;

	if ( $_POST['texto'] )
	{
		$Nick = ($_POST['nick'] != '') ? $_POST['nick'] : $ANONIMO;
		$Texto = $_POST['texto'];
		$Nick = htmlentities( $Nick );
		$Texto = htmlentities( $Texto );
		$Nick = bbcode( $Nick );
		$Texto = bbcode( $Texto );
		
		$fp = fopen( $arq_atual, 'rb' );
		$fr = fread( $fp, filesize( $arq_atual ) + 1 );
		fclose( $fp );

		$tpl = "<TABLE width=\"100%\" class=\"table1\"><TR><td>".
			"<B>[$Nick</b> diz:<b>]&nbsp;<!-- <mensagem> --></B>$Texto</TD>".
			"</TR></table><br>\n\n";

		$fr = $tpl .$fr;

		$fr = stripslashes( $fr );

		$fp = fopen( $arq_atual, 'wb' );
		fwrite( $fp, $fr );
		fclose( $fp );
		
		Return true;
		
	}

}

function gera_msg2 ($Nick,$Texto)
{
	global $ANONIMO,$arq_atual,$MODO;

	if ( $Texto )
	{
		$Nick =  $Nick ;
		$Texto =  $Texto ;
		$Nick = bbcode( $Nick );
		$Texto = bbcode( $Texto );
		
		$fp = fopen( $arq_atual, 'rb' );
		$fr = fread( $fp, filesize( $arq_atual ) + 1 );
		fclose( $fp );

		$tpl = "<TABLE width=\"100%\" class=\"table1\"><TR><td>".
			"<B>[$Nick</b> diz:<b>]&nbsp;<!-- <mensagem> --></B>$Texto</TD>".
			"</TR></table><br>\n\n";

		$fr = $tpl .$fr;

		$fr = stripslashes( $fr );

		$fp = fopen( $arq_atual, 'wb' );
		fwrite( $fp, $fr );
		fclose( $fp );
		
		Return true;
		
	}

}

function salva_nick ()
{
	global $SERVIDOR,$MODO;
	if ( $_POST['nick'] )
	{
		if ( !$_SESSION['nick'] == $_POST['nick'] )
		{
			$_SESSION['nick'] = htmlentities( $_POST['nick'] );
			gera_msg2 ('[color=#FFCC33]' . $SERVIDOR . '[/color]',
				'[color=#FFCC33]<B>' . bbcode( $_POST['nick'] ) . ' entrou na sala</B>[/color]');
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