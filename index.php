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
 *Inclui o arquivo de funções adicionais
 */
require_once('functions.inc.php');

/**
 * Verifica se o chat está bloqueado
 */
if (EC_BLOQ != 0 || $cfg['EC_BLOQ'] != 0) {
    exit;    
}


/**
 * Inicia a sessão atual
 */
session_start();
 //echo '<pre>session<br>';print_r($_SESSION);echo '<br>cookie<br>';print_r($HTTP_COOKIE_VARS);exit;
/**
 * Corige o número máximo de salas em caso de erro
 */
if (EC_SALAS > 30) {
	define('EC_SALAS', 30);
}

/**
 * Corrige o número da sala em caso de erro
 */
if ($HTTP_GET_VARS['sala'] >= EC_SALAS) {
	$HTTP_GET_VARS['sala'] = EC_SALAS;
}
if ($HTTP_GET_VARS['sala'] < 1 ) {
    $HTTP_GET_VARS['sala'] = 1;
}
if ($HTTP_GET_VARS['sala'] == '' ) {
    $HTTP_GET_VARS['sala'] = 1;
}

/**
 * Configura o arquivo da sala atual, define suaconstante e apaga a variável
 */
$atual =  EC_PREFIX . 'sala' . $HTTP_GET_VARS['sala'];
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
 * Bloqueia o chat se configurado para isso
 */
if(EC_BLOQ == 1) {
    $ec->ec_msg2($lng['travado'],$lng['noticia']);
	exit;
}

/**
 * Se for solicitado, o logoff é efetuado e o usuário é redirecionado
 */
if ($HTTP_GET_VARS['logout'] == 'y') {
    $ec->ec_unreg_nick();
    echo "<SCRIPT>location.href = '" . $HTTP_SERVER_VARS['PHP_SELF'] . "?sala=" . $HTTP_GET_VARS['sala'] . "';</SCRIPT>";
}

/**
 * Salva o nick do usuário em $_SESSION['nick']/$HTTP_COOKIE_VARS['nick'], salva o ip do usuário em
 * $_SESSION['ip']/$HTTP_COOKIE_VARS['ip'] e define suas constantes.
 */
$ec->ec_reg_nick(stripslashes($HTTP_POST_VARS['nick']));
if(EC_AUTH != 2) {
    $_SESSION['ip'] = $HTTP_SERVER_VARS['REMOTE_ADDR'];
    define('EC_CUR_IP',$_SESSION['ip']);
    $eb = 'ec_bbcode';
    $hsc = 'htmlspecialchars';
    define('EC_CUR_NICK',$ec->$eb($hsc($_SESSION['nick'])));
    unset($eb);
    unset($hsc);
}else{
    $HTTP_COOKIE_VARS['ip'] = $HTTP_COOKIE_VARS['REMOTE_ADDR'];
    define('EC_CUR_IP',$HTTP_COOKIE_VARS['ip']);
    $eb = 'ec_bbcode';
    $hsc = 'htmlspecialchars';
    define('EC_CUR_NICK',$ec->$eb($hsc($HTTP_COOKIE_VARS['nick'])));
    unset($eb);
    unset($hsc);
}

/**
 * Se o arquivo da sala atual não existe é criaado
 */
if (!file_exists(EC_ATUAL_ARQ)) {	
	$fp = @fopen(EC_ATUAL_ARQ, 'wb');
	@fclose($fp);
}

/**
 * Gera as mensagens enviadas
 */
 if(EC_AUTH != 2) {
     $ec->ec_msg($_SESSION['nick'], $HTTP_POST_VARS['texto'],0);
 }else{
     $ec->ec_msg($HTTP_COOKIE_VARS['nick'], $HTTP_POST_VARS['texto'],0);
 }

/**
 * Calcula o número de mensagens enviadas
 */
$n_mensagens = $ec->ec_get_num_msgs();

/**
 * Limpa as mensagens se o número delas for igual ou maior que o número máximo de 
 * mensagens. Define a variável
 */
if ($n_mensagens >= EC_MAX_MSG) {	
	$ec->ec_limpa(EC_ATUAL_ARQ);
}
if ($n_mensagens < 0 || $n_mensagens > EC_MAX_MSG) {
    unset($n_mensagens);
    $n_mensagens = 0;	
}
define('EC_N_MSG',$n_mensagens);

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
for ($i = 1; $i < EC_SALAS + 1; $i++) {
    if ($i == $HTTP_GET_VARS['sala']) {
        $tpl['S_N_SALA'] .= "&nbsp;[$i]";
    }else {
        $tpl['S_N_SALA'] .= '&nbsp;<A HREF="' . $HTTP_SERVER_VARS['PHP_SELF'] . '?sala=' . $i . '" >' . $i .'</A>';
    }
}

/**
 * Definições de layout: Nick box
 */
$nick_box1     = '<INPUT TYPE="text" size="40" maxlength="' . EC_NICK . '" NAME="nick"  value="' . EC_CUR_NICK . '">';
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
    $s_logout = '[<A HREF="' . $HTTP_SERVER_VARS['PHP_SELF'] . '?logout=y">' . $lng['logout'] . '</A>]';
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
$tpl['EC_CHAT']         = EC_CHAT;
$tpl['EC_JSCRIPT']      = '';
$tpl['SELF']            = $HTTP_SERVER_VARS['PHP_SELF'];
$tpl['ATUAL_SALA']      = $HTTP_GET_VARS['sala'];
$tpl['S_SALA']          = $lng['sala'];
$tpl['S_TEXTO']         = $lng['texto'];
$tpl['S_ESTILO']        = $lng['estilo'];
$tpl['S_COLORS']        = $lng['cor'];
$tpl['OPT_SMILIES']     = '<OPTION ></OPTION>' . $tpl['OPT_SMILIES'];
$tpl['OPT_STYLE']       = "<OPTION ></OPTION>\n" . $tpl['OPT_STYLE'];
$tpl['OPT_COLORS']      = '<OPTION ></OPTION>' . $tpl['OPT_COLORS'];
$tpl['T_SIZE']          = ($cteT)?$cteT:52;
$tpl['S_LOGOUT']        = $s_logout;

$h = fopen(EC_TPL1,'rb');
$r = fread($h, filesize(EC_TPL1) + 1 );


while(list($k,$v) = each($tpl)) {
    $r = str_replace('{'.$k.'}',$v,$r);
}
echo $r;
?>