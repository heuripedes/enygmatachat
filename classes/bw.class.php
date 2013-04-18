<?php
/**
 * Enygmata Chat
 * --------------------
 * Arquivo..: bw.class.php 
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

 class BadWords
 {
	var $texto = NULL;
	var $words = array();
	var $subst = NULL;

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