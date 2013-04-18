///Variáveis
function DOM(i){
	if (document.getElementById)
		return (document.getElementById(i));
	if (document.all)
		return (document.all[i]);
}

//EXTENÇÃO DO AJAX
document.frm.onsubmit = send();
function send(){
	var fr = document.frm;
    var k = Array('nick','texto','sala');
    var v = Array(fr.nick.value, fr.texto.value, fr.sala.value);
    var q = Ajax.makeQuery(k,v);
    Ajax.set('index.php',q,'txt','','');
    Ajax.post();
	DOM('frm').texto.value = '';
	document.frm.texto.focus();
	//cht.location.reload();
	return false;
}

function changeTheme(){
	var a = Array(document.frm.thema.value);
	var q = Ajax.makeQuery(Array('thema'),a);
//	alert(q); 
	Ajax.set('index.php',q,'txt','','');
    Ajax.post();
	setTimeout('location.reload()',500);
}


// FIM DA EXTENÇÃO DO AJAX


