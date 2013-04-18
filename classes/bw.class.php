<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * Este é o arquivo da classe BadWords
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
 * Esta classe é responsável por filtrar palavras indesejadas
 *
 * 
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
 */
 class BadWords
 {
     // {{{ propriedades
     /**
      * String com o texto atual
      * @var string
      */
     var $texto = NULL;

      /**
       * Array das palavras
       * @var array
       */
      var $words = array();

      /**
       * String de substituição
       * @var string
       */
      var $subst = NULL;

      // {{{ Funções
    /**
     * Declara as palavras 
     *
     * Exemplo de uso:
     * <code>
     * require_once('bw.class.php');
     * 
     * $bw = new BadWords;
     * $bwc-> setWords($array_de_palavras_ruins,$string);
     *
     * </code>
     * @param array $arr      Array de palavras ruins
     * @param string $string  Palavra de substutição
     * @return 
     * @throws exceptionclass  [description]
     *
     * @access public
     * @static
     * @see 
     */
      function setWords($arr = array(),$string = '!@$5&*?') {
          if(is_array($arr)) {
              $this->words = $arr;
              $this->subst = $string;
          }
      }
     function goChanges($txt) {
         if(is_array($this->words)) {
             $arr = $this->filterArr($this->words);
             $str = $this->subst;
             for($i=0;$i<count($arr);$i++) {
                 unset($oth);
                 for($x=0;$x<strlen($arr[$i]);$x++) {
                     $oth .= '*';
                 }
                 $txt = eregi_replace($arr[$i],"[$str]",$txt);
             }
             return $txt;
         }
     }
     function filterArr($arr) {
         if(is_array($arr)) {
             for($i=0;$i<count($arr);$i++) {
                 
                 if(!$arr[$i]) {
                     unset($arr[$i]);
                 }else{
                     $arr[$i] = str_replace('*','(.*)',$arr[$i]);
                 }
             }
             sort($arr);
             reset($arr);
             return $arr;
         }
     }
 }

