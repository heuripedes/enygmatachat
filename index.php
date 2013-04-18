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
 * @since      File available since Release 3.2
 * @deprecated File deprecated in Release 3.3
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
 * Verifica se o chat está bloqueado
 */
if (EC_BLOC != 0 || $cfg['EC_BLOC'] != 0) {
    exit;    
}

/**
 * Inicia a sessão atual
 */
session_start();

/**
 * Corige o número máximo de salas em caso de erro
 */
if (EC_SALAS > 30) {
	define('EC_SALAS', 30);
}

/**
 * Corrige o número da sala em caso de erro
 */
if ($_GET['sala'] >= EC_SALAS) {
	$_GET['sala'] = EC_SALAS;
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
 * Bloqueia o chat se configurado para isso
 */
if(EC_BLOQ == 1) {
    $ec->ec_msg2($lng['travado'],$lng['noticia']);
	exit;
}

/**
 * Se for solicitado, o logoff é efetuado e o usuário é redirecionado
 */
if ($_GET['logout'] == 'y') {
    $ec->ec_unreg_nick();
    echo "<SCRIPT>location.href = '" . $_SERVER['PHP_SELF'] . "?sala=" . $_GET['sala'] . "';</SCRIPT>";
}

/**
 * Salva o nick do usuário em $_SESSION['nick'] e define sua constante
 */
$ec->ec_reg_nick($_POST['nick']);
define('EC_CUR_NICK',$_SESSION['nick']);

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
$ec->ec_msg($_POST['nick'], $_POST['texto']);

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
if ($n_mensagens < 0 || $n_mensagens > $MAX_MENSAGENS) {
    unset($n_mensagens);
    $n_mensagens = 0;	
}
define('EC_N_MSG',$n_mensagens);

/**
 * Define os estilos de texto
 */
$styles = array('Negrito','Itálico','Sublinhado', 'Link');

/**
 * Define os smilies
 */
$smilies = array(
	'Risada' => '1',
	'Choro' => '2',
	'!' => '3',
	'Ideia' => '4',
	'Sério' => '5',
	'?' => '6',
	'Feliz' => '7',	
	'Triste' => '8',
	'Raiva' => '9',
	'Legal' =>'10'
);

/**
 * Define as cores
 */
$cores = array(
    'Azul1' => '#0000FF',
    'Azul2' => '#6600CC',
    'Amarelo1' => '#FFFF00',
    'Amarelo2' => '#FFCC00',
    'Verde1' => '#00FF00',
    'Verde2' => '#339966',
    'Vermelho1' => '#FF0000',
    'Vermelho2' => '#CC0000',
    'Cinza1' => '#C0C0C0',
    'Cinza2' => '#808080',
    'Rosa' => '#FF80C0',
    'Roxo' => '#FF00FF',
    'Laranja' => '#FF8000',
    'Marrom' => '#804000'
    );

/**
 * Definições de layout
 */

/**
 * Definições de layout: Lista de salas
 */
for ($i = 1; $i < EC_SALAS + 1; $i++) {
    if ($i == $_GET['salas']) {
        $tpl['S_N_SALA'] .= "&nbsp;[$i]";
    }else {
        $tpl['S_N_SALA'] .= '&nbsp;<A HREF="' . $_SERVER['PHP_SELF'] . '?sala=' . $i . '" >' . $i .'</A>';
    }
}

/**
 * Definições de layout: Nick box
 */
$nick_box1     = '<INPUT TYPE="text" size="40" maxlength="' . EC_NICK . '" NAME="nick"  value="' . EC_CUR_NICK . '">';
$nick_box2     = '<INPUT TYPE="hidden" size="40" maxlength="'. EC_NICK . '" NAME="nick"" value="' . EC_CUR_NICK . '">';
if (EC_CUR_NICK)
{
    $tpl['NICK_BOX'] = '<FONT class="td">' . EC_CUR_NICK . '</FONT>' . $nick_box2;
}
else
{
    $tpl['NICK_BOX'] = $nick_box1;
}

/**
 * Definições de layout: Lista de estilos
 */
for ($i=0; $i<count($styles); $i++)
{
    $tpl['OPT_STYLE'] .= "<OPTION value=\"$i\">$styles[$i]</OPTION>\n";
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
}

/**
 * Definições de cores
 */
ksort($cores);
while(list($k,$v) = each($cores)) {
    $tpl['OPT_COLORS'] .= '<OPTION value="'.$v.'">'.$k.'</OPTION>';
}

/**
 * Definições de layout: Definições
 */
$tpl['S_MENSAGENS'] = $lng['mensagens'];
$tpl['S_N_MENSAGENS']   = EC_N_MSG;
$tpl['S_NICK']          = $lng['nick'];
$tpl['S_ENVIAR']        = $lng['enviar'];
$tpl['S_LIMPAR']        = $lng['limpar'];
$tpl['S_ADMINISTRACAO'] = $lng['administracao'];
$tpl['S_SMILIES']       = $lng['smilies'];
$tpl['EC_CHAT']         = EC_CHAT;
$tpl['EC_JSCRIPT']      = $pageScript;
$tpl['SELF']            = $_SERVER['PHP_SELF'];
$tpl['ATUAL_SALA']      = $atual;
$tpl['S_SALA']          = $lng['sala'];
$tpl['S_TEXTO']         = $lng['texto'];
$tpl['S_ESTILO']        = $lng['estilo'];
$tpl['S_COLORS']        = $lng['cor'];
$tpl['OPT_SMILIES']     = '<OPTION ></OPTION>' . $tpl['OPT_SMILIES'];
$tpl['OPT_STYLE']       = "<OPTION ></OPTION>\n" . $tpl['OPT_STYLE'];
$tpl['OPT_COLORS']      = '<OPTION ></OPTION>' . $tpl['OPT_COLORS'];
$tpl['T_SIZE']          = (EC_TEXT0)?EC_TEXT0:52;
$tpl['S_LOGOUT']        = $s_logout;

$h = fopen(EC_TPL1,'rb');
$r = fread($h, filesize(EC_TPL1) + 1 );


while(list($k,$v) = each($tpl)) {
    $p[] = '#\{'.$k.'\}#';
    $s[] = $v;
}
echo preg_replace($p,$s,$r);
?>