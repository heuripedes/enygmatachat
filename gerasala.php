<?php
/**
 * Enygmata Chat
 * --------------------
 * Arquivo..: gerasala.php 
 * Autor....: Higor Euripedes "Enygmata" (heuripedes@hotmail.com)
 * Editor...: Higor Euripedes "Enygmata" (heuirpedes@hotmail.com)
 * Vers�o...: 4
 * PHP......: 4.1+
 * 
 * +-Aviso:--------------------------------------------[_][ ][x]+
 * | Este programa � livre e voc� pode edit�-lo avontade desde  |
 * | desde que mantenha o nome do criador no campo Autor acima. |
 * +------------------------------------------------------------+
 */

// Constante de seguran�a do chat
define('EC_OK',TRUE);

// Inclus�es
require_once ('config.php');           // Arquivo de configura��es
require_once ('classes/ec.class.php'); // Classe Enygmata_Chat
require_once ('classes/bw.class.php'); // Classe BarWordFilter
require_once('functions.inc.php');     // Arquivo de Fun��es adicionais

// Verifica��o de bloqueio
if (EC_BLOQUEAR != 0) {
    die($lng['travado']);
}

// Inicio da sess�o
@session_start();

// Corrige o n�mero da sala em caso de erro
if(!$_GET['abre']) {
    $_GET['abre']=1;
}
if($_GET['abre'] >= EC_NUM_SALAS) {
	$_GET['abre'] = EC_NUM_SALAS;
}
if ($_GET['abre'] <= 1 ) {
    $_GET['abre'] = 1;
}

// Define a vari�vel $abre com o valorde $_GET['abre']
$abre = $_GET['abre'];

// Corrige erros com o nome da sala
if (!trim($abre)) {
	$abre = 1;
}

// Define qual arquivo abrir
$arq = 'texto/' . EC_PREFIX . 'sala' . $abre . '.txt';

// Cria uma inst�ncia da classe Enygmata_Chat
$ec = ec($arq,$lng['anonimo'],$lag['admin'],$lng['entrou'],$lng['saiu']);

// Verifica se o arquivo existe da sala existe
if (!file_exists($arq)) {
    die('<FONT COLOR="#FF0000"><B>' . $lng['erro'] . ':</B> ' . $lng['erro1'] .'</FONT>');
}

// Verifica se o usu�rio est� banido
 if($ec->isBan($_SERVER['REMOTE_ADDR'])) {
     die($lng['vc_banido']);
 }

// Abre e l� o arquivo da sala
$fr = $ec->ler($arq);

if($_POST['crc'] == 1) {
    die(md5($fr));
}

// Interpreta as mensagens
$m = $ec->getMsg($fr);
for ($i=0; $i<10; $i++) {

     // Siplifica o nome das vari�veis
	$muser = $m[$i][0];
	$mip   = $m[$i][1];
	$mtext = $m[$i][2];
    
     // Corre��o do erro que fazia aparecer um "A" antes do nick
    if($muser == 'A' . $_SESSION['nick'] || $muser == 'A' . $_COOKIE['nick']) {
        $muser = substr($muser,1);
    }
    
    if($muser && $mtext) {
        if(strstr($mtext,'[mp]')) {
            $mtext = explode('[mp]',$mtext);
            if($mtext[0] == $_SESSION['nick'] || $mtext[0] == $_COOKIE['nick']) {
                $MSG[0] = $muser;
                $MSG[1] = $mtext[1];
                $TAG = 2;
            }else{
                $MSG[0] = NULL;
                $MSG[1] = NULL;
                $TAG = 2;
            }
        }elseif($mtext && $muser)
        {
            $MSG[0] = $muser;
            $MSG[1] = $mtext;
            $TAG = 1;
        }
        if($MSG[0] != NULL ) {
            $mensagens .= "<div class='msg$TAG'><b>[{$MSG[0]}</b>{$lng['diz']}<B>] </B>{$MSG[1]}</div>";
        }        
    }
}
// Exibe as menssagens convertendo caracteres especiais
echo "<table width=\"100%\">\n".convertChars($mensagens)."\n</table>" ;
?>