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

//Configura o modo de manipulação de arquivos
if ( $MODO_ARQUIVO == 1 )
{
	$MODO = 'b';
}

$abre = $_GET['abre'];

$arq = 'texto/' . $abre . '.txt';
//Abre arquivo , o lê e imprime na tela
$fp = @fopen( $arq , 'r' . $MODO );
@fpassthru( $fp );
if ( !$fp )
{
	echo '<B>Erro:&nbsp;</B>O Arquivo correspondente ao nº da sala não existe!';
}
?>
</body></html>