<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Este é o arquivo de exibição principal
 *
 * 
 *
 * PHP versions 4.1.1 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Chat
 * @package    Enygmata Chat
 * @author     Higor Euripedes <heuripedes@hotmail.com>
 * @copyright  2006 The EC Group
 * @license    http://php.net/license/3_0.txt       PHP License 3.0
 * @license    http://gnu.org/copyleft/lesser.html  LGPL License 2.1
 * @version    CVS: $Id:$
 * @link       http://enygmata.orgfree.com/diretorio/enygmatachat/enygmatachat_3.2.rar
 * @see        
 * @access     public
 */

/**
 * Esta variável define que o EC pode funcionar comsegurança
 */
define('EC_OK',TRUE);

/**
 * Inclui o arquivo de configuração
 */
require_once ('config.php');

/**
 * Inclui o arquivo com a classe principal: Enygmata_Chat
 */
require_once ('classes/ec.class.php');

/**
 * Inclui o arquivo com a classe BadWords
 */
require_once ('classes/bw.class.php');


/**
 *Inclui o arquivo de funções adicionais
 */
require_once('functions.inc.php');

/**
 * Verifica se o chat está bloqueado
 */
if (EC_BLOQUEAR != 0) {
    exit;    
}


/**
 * Inicia a sessão atual
 */
session_start();
 //echo '<pre>session<br>';print_r($_SESSION);echo '<br>cookie<br>';print_r($_COOKIE);exit;
/**
 * Corige o número máximo de salas em caso de erro
 */
if (EC_NUM_SALAS > 25) {
	define('EC_NUM_SALAS', 30);
}

/**
 * Corrige o número da sala em caso de erro
 */
if ($_GET['sala'] >= EC_NUM_SALAS) {
	$_GET['sala'] = EC_NUM_SALAS;
}
if ($_GET['sala'] < 1 ) {
    $_GET['sala'] = 1;
}
if ($_GET['sala'] == '' ) {
    $_GET['sala'] = 1;
}

/**
 * Configura o arquivo da sala atual, define suaconstante e apaga a variável
 */
$atual =  EC_PREFIX . 'sala' . $_GET['sala'];
$arq_atual = 'texto/' . $atual . '.txt';
define('EC_ARQ',$atual);
define('EC_ATUAL_ARQ', $arq_atual);
unset($atual);
unset($arq_atual);

/**
 * Inicia a classe Enygmata Chat
 */
$ec = ec(EC_ATUAL_ARQ,$lng['anonimo'],$lng['admin'],$lng['entrou'],$lng['saiu']);

/**
 * Inicia a classe Enygmata Chat
 */
$bw = new BadWords;

/**
 * Bloqueia o chat se configurado para isso
 */
if(EC_BLOQUEAR == 1) {
    $ec->msg2($lng['travado'],$lng['noticia']);
	exit;
}

/**
 * Salva o tema do usuário
 */
if($_POST['thema']) {
    $_SESSION['style'] = $_POST['thema'];
}


/**
 * Seta as palavras ruins
 */
if(EC_BW_SEARCH) {
    $bw->setWords(explode(',',EC_BW_SEARCH));
}

/**
 * Gerencia as variáveis de post e session para que não oorram erros
 */
if($_POST['nick'] && !$_POST['texto']) {
    /**
     * Registrar nick
     */
     $_POST['nick'] = $bw->goChanges($_POST['nick']);
    $ec->regNick($_POST['nick']);
    unset($_POST['nick']);
}elseif($_POST['nick'] && $_POST['texto']){
    /**
     * Registrar Nick ezvaziar o buffer e cria mensagem
     */
    $_POST['nick'] = $bw->goChanges($_POST['nick']);
    $ec->regNick($_POST['nick']);
    unset($_POST['nick']);
    $_POST['texto'] = $bw->goChanges($_POST['texto']);
    $ec->msg($_SESSION['nick'], $_POST['texto']);
    unset($_POST['texto']);
}elseif($_SESSION['nick'] && $_POST['texto']){
    /**
     * Criar mensagem
     */
    $_POST['texto'] = $bw->goChanges($_POST['texto']);
    $ec->msg($_SESSION['nick'], $_POST['texto']);
}

/**
 * Se for solicitado, o logoff é efetuado e o usuário é redirecionado
 */
if ($_GET['logout'] == 'y') {
    $ec->unregNick();
    echo "<SCRIPT>location.href = '" . $_SERVER['PHP_SELF'] . "?sala=" . $_GET['sala'] . "';</SCRIPT>";
}

/**
 * Define o ip do usuário
 * Cria as costantes EC_CUR_IP e EC_CUR_NICK
 */
$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
define('EC_CUR_IP',$_SESSION['ip']);
$eb  = 'bbCode';
$hsc = 'htmlspecialchars';
define('EC_CUR_NICK',stripslashes($ec->$eb($hsc($_SESSION['nick']))));
unset($eb);
unset($hsc);

/**
 * Se o arquivo da sala atual não existe é criaado
 */
if (!file_exists(EC_ATUAL_ARQ)) {	
	$fp = @fopen(EC_ATUAL_ARQ, 'wb');
	@fclose($fp);
}


/**
 * Calcula o número de mensagens enviadas
 */
$n_mensagens = $ec->getNumMsgs();

/**
 * Limpa as mensagens se o número delas for igual ou maior que o número máximo de 
 * mensagens. Define a variável
 */
if ($n_mensagens >= EC_MAX_MSG) {	
	$ec->limpa(EC_ATUAL_ARQ);
}
if ($n_mensagens < 0 || $n_mensagens > EC_MAX_MSG) {
    unset($n_mensagens);
    $n_mensagens = 0;	
    define('EC_N_MSG',0,TRUE);
}else{
    define('EC_N_MSG',$n_mensagens,TRUE);
}

/**
 * Define os estilos de texto
 */
$styles = array(
    $stlng['negrito']    => '0',
    $stlng['italico']    => '1',
    $stlng['sublinhado'] => '2',
    $stlng['link']       => '3' 
);

/**
 * Define os smilies
 */
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

/**
 * Define as cores
 */
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

/**
 * Definições de layout
 */

/**
 * Definições de layout: Lista de salas
 */
for ($i = 1; $i < EC_NUM_SALAS + 1; $i++) {
    if ($i == $_GET['sala']) {
        $tpl['S_N_SALA'] .= "&nbsp;[$i]";
    }else {
        $tpl['S_N_SALA'] .= '&nbsp;<A HREF="' . $_SERVER['PHP_SELF'] . '?sala=' . $i . '" >' . $i .'</A>';
    }
}

/**
 * Definições de layout: Nick box
 */
$nick_box1     = '<INPUT TYPE="text" size="40" maxlength="' . EC_TAM_NICK . '" NAME="nick"  value="Usr_' .mt_rand(11111,99999) . '">';
$nick_box2     = '';
if (EC_CUR_NICK)
{
    $tpl['NICK_BOX'] = '<FONT class="td">' . EC_CUR_NICK . '</FONT>';
}
else
{
    $tpl['NICK_BOX'] = $nick_box1;
}

/**
 * Definições de layout: Lista de estilos
 */
ksort($styles);
while(list($k,$n) = each($styles)) {
    $tpl['OPT_STYLE'] .= "<OPTION value=\"$n\">$k</OPTION>\n";
}

/**
 * Definições de layout: Lista de similies
 */
ksort($smilies);
while(list($name,$key) = each( $smilies))
{
    $tpl['OPT_SMILIES'] .= '<OPTION value="' . $key . '">' . $name . '</OPTION>';
}

if ($_SESSION['nick']) {
    $s_logout = '[<A HREF="' . $_SERVER['PHP_SELF'] . '?logout=y">' . $lng['logout'] . '</A>]';
}else{
    $s_logout = '';
}

/**
 * Definições de cores
 */
ksort($cores);
while(list($k,$v) = each($cores)) {
    $tpl['OPT_COLORS'] .= '<OPTION value="'.$v.'">'.$k.'</OPTION>';
}

/**
 * Definições de temas
 */
$d = dir("templates/");
while (false !== ($y = $d->read())) {
    if($y != '.' && $y != '..' && @is_dir('templates/'.$y)) {
        if($_SESSION['style'] == $y) {
            $oth = ' selected ';
        }else{
            $oth = '';
        }
        $tpl['OPT_THEMES'] .= '<OPTION value="'.$y.'"' . $oth .'>'.$y.'</OPTION>';
    }
}
$d->close();

$cteT = $_EC['EC_TEXT0'];
/**
 * Definições de layout: Definições
 */
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

//$_SESSION['style'] = 'default';
$tfile = $_SESSION['style'] ? $_SESSION['style']: EC_TEMPLATE;
$template = strtolower('templates/' . $tfile . '/template1.html');
$h = fopen($template,'rb');
$r = fread($h, filesize($template) + 1 );


while(list($k,$v) = each($tpl)) {
    $r = @str_replace('{'.$k.'}',$v,$r);
}
echo $r;
?>
<PRE>

<A HREF="docs/help.html">Ajuda/Help</A>
</PRE>