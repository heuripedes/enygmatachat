<?php
/**
 * Enygmata Chat
 * --------------------
 * Arquivo..: index.php 
 * Autor....: Higor Euripedes "Enygmata" (heuripedes@hotmail.com)
 * Editor...: Higor Euripedes "Enygmata" (heuirpedes@hotmail.com)
 * Versão...: 4
 * PHP......: 4.1+
 * 
 * +-Aviso:--------------------------------------------[_][ ][x]+
 * | Este programa é livre e vocÊ pode editá-lo avontade desde  |
 * | desde que mantenha o nome do criador no campo Autor acima. |
 * +------------------------------------------------------------+
 */

// Constante de segurança do chat
define('EC_OK',TRUE);

// Inclusões
require_once ('config.php');           // Arquivo de configurações
require_once ('classes/ec.class.php'); // Classe Enygmata_Chat
require_once ('classes/bw.class.php'); // Classe BarWordFilter
require_once('functions.inc.php');     // Arquivo de Funções adicionais

// Verificação de bloqueio
if (EC_BLOQUEAR != 0) {
    die($lng['travado']);
}

// Inicio da sessão
@session_start();
 
// Correção do nº máximo de salas
if (EC_NUM_SALAS > 25) {
	define('EC_NUM_SALAS', 30);
}

// Medida de correção do nº de sala em caso de erro
//INICIO
if(!$_GET['sala']) {
    $_GET['sala'] = 1;
}
if(!$_POST['sala']) {
    $_POST['sala'] = $_GET['sala'];
}
if($_POST['sala'] >= EC_NUM_SALAS) {
	$_POST['sala'] = EC_NUM_SALAS;
}
if ($_POST['sala'] <= 1 ) {
    $_POST['sala'] = 1;
}//FIM

// Configuração do arquivo de sala atual
$atual =  EC_PREFIX . 'sala' . $_POST['sala'];
$arq_atual = 'texto/' . $atual . '.txt';
define('EC_ARQ',$atual);
define('EC_ATUAL_ARQ', $arq_atual);

// Prevenção contra a inexistência do arquivo de sala
if (!file_exists(EC_ATUAL_ARQ)) {	
	$fp = @fopen(EC_ATUAL_ARQ, 'wb');
	@fclose($fp);
}

// Instanciação da classe EnygmataChat
$ec = ec($arq_atual, $lng['anonimo'], $lng['admin'], $lng['entrou'], $lng['saiu']);

// Instanciação da classe BadWords
$bw = new BadWords;

// Definição das palavras ruins
if(EC_BW_SEARCH) {
    $bw->setWords(explode(',',EC_BW_SEARCH));
}

// Salva o tema do usuário
if($_POST['thema']) {
    $_SESSION['style'] = $_POST['thema'];
}

// Rotina de gerênciamento de variáreis POST e SESSION
//INICION
if($_POST['nick'] && !$_POST['texto']) {

    // Registro de nick
    $_POST['nick'] = $bw->goChanges($_POST['nick']);
    $ec->regNick($_POST['nick']);
    unset($_POST['nick']);

}elseif($_POST['nick'] && $_POST['texto']){

    // Registro de nick e envio de mensagem
    $_POST['nick'] = $bw->goChanges($_POST['nick']);
    $ec->regNick($_POST['nick']);
    unset($_POST['nick']);
    $_POST['texto'] = $bw->goChanges($_POST['texto']);
    $ec->msg($_SESSION['nick'], $_POST['texto']);
    unset($_POST['texto']);
}//FIM

// Efetua o logoff se for solicitado
if ($_GET['logout'] == 'y') {
    $ec->unregNick();
    echo "<SCRIPT>location.href = '" . $_SERVER['PHP_SELF'] . "?sala=" . $_GET['sala'] . "';</SCRIPT>";
}

// Informações do usuário
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
define('EC_CURRENT_IP',$_SESSION['ip']);
$hsc = 'htmlspecialchars';
define('EC_CUR_NICK',stripslashes($hsc($_SESSION['nick'])));
unset($eb);
unset($hsc);




// Definições visuais
//INICIO
$styles = array(
    $stlng['negrito']    => '0',
    $stlng['italico']    => '1',
    $stlng['sublinhado'] => '2',
    $stlng['link']       => '3' 
);

$smilies = array(
    $slng['risada'] => '1',
    $slng['choro']  => '2',
    '!'             => '3',
    $slng['ideia']  => '4',
    $slng['serio']  => '5',
    '?'             => '6',
    $slng['feliz']  => '7',
    $slng['triste'] => '8',
    $slng['raiva']  => '9',
    $slng['legal']  =>'10'
);

$cores = array(
    $clng['azul1']     => '#0000ff',
    $clng['azul2']     => '#6600cc',
    $clng['amarelo1']  => '#ffff00',
    $clng['amarelo2']  => '#ffcc00',
    $clng['verde1']    => '#00ff00',
    $clng['verde2']    => '#339966',
    $clng['vermelho1'] => '#ff0000',
    $clng['vermelho2'] => '#cc0000',
    $clng['cinza1']    => '#c0c0c0',
    $clng['cinza2']    => '#808080',
    $clng['rosa']      => '#ff80c0',
    $clng['roxo']      => '#ff00ff',
    $clng['laranja']   => '#ff8000',
    $clng['marrom']    => '#804000'
    );


for ($i = 1; $i < EC_NUM_SALAS + 1; $i++) { // Exibição dos nºs de salas
    if ($i == $_GET['sala']) {
        $tpl['S_N_SALA'] .= "&nbsp;[$i]";
    }else {
        $tpl['S_N_SALA'] .= '&nbsp;<A HREF="' . $_SERVER['PHP_SELF'] . '?sala=' . $i . '" >' . $i .'</A>';
    }
}




// Lista de estilos
ksort($styles);
while(list($k,$n) = each($styles)) {
    $tpl['OPT_STYLE'] .= "<option value=\"$n\">$k</option>\n";
}

// Lista de smilies
while(list($name,$key) = each( $smilies))
{
    $tpl['OPT_SMILIES'] .= '<option value="' . $key . '">' . $name . '</option>';
}

// Link de logoff
if ($_SESSION['nick']) {
    $s_logout = '[<A HREF="' . $_SERVER['PHP_SELF'] . '?logout=y">' . $lng['logout'] . '</A>]';
}else{
    $s_logout = '';
}

// Lista de cores
ksort($cores);
while(list($k,$v) = each($cores)) {
    $tpl['OPT_COLORS'] .= '<option value="'.$v.'">'.$k.'</option>';
}

// Lista de temas
$d = dir("templates/");
while (false !== ($y = $d->read())) {
    if($y != '.' && $y != '..' && @is_dir('templates/'.$y)) {
      if($_SESSION['style'] == $y) {
            $oth = ' selected ';
        }else{
            $oth = '';
        }
        $tpl['OPT_THEMES'] .= '<option value="'.$y.'"' . $oth .'>'.$y.'</option>';
    }
}
$d->close();

$cteT = $_EC['EC_TEXT0'];

/**
 * DefiniÃ§Ãµes de layout: DefiniÃ§Ãµes
 */
 if (!EC_CUR_NICK)
{
    $tpl['NICK_BOX'] = 'usr_' .mt_rand(11111,99999);
}
else
{
    $tpl['NICK_BOX'] = EC_CUR_NICK;
}


$tpl['HASH']            = md5($ec->ler($arq_atual));
$tpl['S_MENSAGENS']     = $lng['mensagens'];
$tpl['S_N_MENSAGENS']   = EC_N_MSG;
$tpl['S_NICK']          = $lng['nick'];
$tpl['S_ENVIAR']        = $lng['enviar'];
$tpl['S_LIMPAR']        = $lng['limpar'];
$tpl['S_ADMINISTRACAO'] = $lng['administracao'];
$tpl['S_SMILIES']       = $lng['smilies'];
$tpl['EC_CHAT']         = EC_NOME_CHAT;
$tpl['EC_JSCRIPT']      = "window.name='chat';";
$tpl['SELF']            = $_SERVER['PHP_SELF'];
$tpl['ATUAL_SALA']      = $_GET['sala'];
$tpl['S_SALA']          = $lng['sala'];
$tpl['S_TEXTO']         = $lng['texto'];
$tpl['S_ESTILO']        = $lng['estilo'];
$tpl['S_THEME']         = $lng['tema'];
$tpl['S_COLORS']        = $lng['cor'];
$tpl['OPT_SMILIES']     = '<OPTION ></OPTION>' . $tpl['OPT_SMILIES'];
$tpl['OPT_STYLE']       = "<OPTION ></OPTION>" . $tpl['OPT_STYLE'];
$tpl['OPT_COLORS']      = '<OPTION ></OPTION>' . $tpl['OPT_COLORS'];
$tpl['OPT_THEMES']      = '<OPTION ></OPTION>' . $tpl['OPT_THEMES'];
$tpl['T_SIZE']          = ($cteT)?$cteT:52;
$tpl['S_LOGOUT']        = $s_logout;

$tfile = $_SESSION['style'] ? $_SESSION['style']: EC_TEMPLATE;
$template = strtolower('templates/' . $tfile . '/template1.html');
$h = fopen($template,'rb');
$r = fread($h, filesize($template) + 1 );


while(list($k,$v) = each($tpl)) {
    $r = @str_replace('{'.$k.'}',$v,$r);
}
echo $r;
echo mt_rand(0,99)%2?' ':'';;
?>
<PRE>

<A HREF="docs/help.html">Ajuda/Help</A>
</PRE>