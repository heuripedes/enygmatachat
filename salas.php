<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE><?php echo $NOME_CHAT; ?></TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<meta http-equiv="refresh" content="4; url=<?php echo $_SERVER['PHP_SELF'] . '?abre=' . $_GET['abre']?>">
<!-- <META http-equiv="Page-Enter" content="revealTrans(Duration=1,Transition=23)"> -->
<SCRIPT>
function r(){
         this.location.reload();
         setTimeout('r()',4000);
}
</SCRIPT>
<style>
body {font-family:Verdana, Tahoma; font-size:8pt;margin:2px;
     border: 0px solid;border-right-width:1px;border-color:#000000;
	SCROLLBAR-FACE-COLOR:#EEEEEE;SCROLLBAR-ARROW-COLOR:#808080;
	SCROLLBAR-3DLIGHT-COLOR:#F0F0F0;SCROLLBAR-DARKSHADOW-COLOR:#808080;
	SCROLLBAR-HIGHLIGHT-COLOR:#FFFFFF;SCROLLBAR-SHADOW-COLOR:#C0C0C0;
	SCROLLBAR-TRACK-COLOR:#FFFFFF;}

.table1 {border-style:solid; border-width:1px;border-color:#B0B0B0;
       background-color:#FDFDFD;font-family:Verdana, Tahoma;
       font-size:8pt;margin:0px;}
</style>
</HEAD>
<BODY bgcolor="#FFFFFF" onload="setTimeout('r()',4000);" scroll="auto">
<?php
include_once ( 'func.php' );
include_once ( 'config.php' );

$abre = $_GET['abre'];

$arq = 'texto/' . $abre . '.txt';
//Abre arquivo , o lê e imprime na tela
$fp = fopen( $arq , 'rb');
$fr = fread( $fp,filesize( $arq )+1 );

$m = obtem($fr);
//$Nick|+|$ip|+|$now|+|$Texto
for ($i=0; $i<count( $m ); $i++)
{
	if ( $m[$i][2] && $m[$i][0] && $m[$i][3] && !is_banido($m[$i][0])  )
	{
		$tpl = "<TABLE width=\"100%\" class=\"table1\"><TR><td>".
			"<B>[" . $m[$i][0] ."</b> diz:<b>]&nbsp;<!-- <mensagem> --></B>" . $m[$i][3] ."</TD>".
			"</TR></table><br>\n\n";
		echo $tpl;
	}
}
if ( !$fp )
{
	echo '<B>Erro:&nbsp;</B>O Arquivo correspondente ao nº da sala não existe!';
}
?>
<noframes>
</body></html>
