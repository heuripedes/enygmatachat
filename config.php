<?php
/**
 * Enygmata Chat
 * --------------------
 * Arquivo..: index.php 
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

// Verifica��o de seguran�a
if (EC_OK != TRUE) {
    die('Hacking attempt!');
}

// Array de configura��es
$ini_arq = 'ec_config.ini';
$cfg = parse_ini_file($ini_arq);

// Cria��o de constantes de configura��o
while(list($k,$v) = each($cfg)) {
    if (!defined($k)) {
        define($k,$v,TRUE); 
    }
    $GLOBALS['_EC'][$k] = $v;
    unset($cfg[$k]);
}

// Se solicitado, ativa o modo de depura��o
if(EC_MODO_DEPURACAO != 0) {
    if(@phpversion() >= '5.0.0' && EC_MODO_DEPURACAO == 2) {
        error_reporting(E_ALL | E_STRICT);
    }else{
        error_reporting(E_ALL);
    }
}

// Inclus�o do arquivo de linguagem
$Langs = 'ptbr,enus';
include('lang/' . EC_LINGUAGEM . '.lang.php'); 

@session_start();
?>