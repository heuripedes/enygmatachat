//// Javascript de funções básicas do chat
var imageTag = false;
var theSelection = false;

// Check for Browser & Platform for PC & IE specific bits
// More details from: http://www.mozilla.org/docs/web-developer/sniffer/browser_type.html
var clientPC = navigator.userAgent.toLowerCase(); // Get client info
var clientVer = parseInt(navigator.appVersion); // Get browser version

var is_ie = ((clientPC.indexOf("msie") != -1) && (clientPC.indexOf("opera") == -1));
var is_nav = ((clientPC.indexOf('mozilla')!=-1) && (clientPC.indexOf('spoofer')==-1)
                && (clientPC.indexOf('compatible') == -1) && (clientPC.indexOf('opera')==-1)
                && (clientPC.indexOf('webtv')==-1) && (clientPC.indexOf('hotjava')==-1));
var is_moz = 0;

var is_win = ((clientPC.indexOf("win")!=-1) || (clientPC.indexOf("16bit") != -1));
var is_mac = (clientPC.indexOf("mac")!=-1);


function addcolor(s)
{
	ft = document.frm.texto;
	fc = document.frm.cor1.value;
	
	str = '[color=' + fc + ']';
	end = '[/color]';
	if (!fc)
	{
		return;
	}
		if ((clientVer >= 4) && is_ie && is_win) {
		theSelection = document.selection.createRange().text;
		if (!theSelection) {
			ft.value += str + end;
			ft.focus();
			return;
		}
		document.selection.createRange().text = str + theSelection + end;
		ft.focus();
		return;

	}
	else
	{
		ft.value += str + end;
		ft.focus();
	}
}


function open_page(page,width,height){
	window.open(page,'',width,height);
}
function open_page2(page){
	window.open(page);
}
function smilies(){
	fn = document.frm.texto;
	fs = document.frm.smy;
	document.frm.texto.value =  document.frm.texto.value + '[' + document.frm.smy.value + ']';
}

function MM_preloadImages() { //v3.0
  var d=document;
  if(d.images){ 
	  if(!d.MM_p) {
		  d.MM_p=new Array();
	  }
  }
  var i,j=d.MM_p.length,a=MM_preloadImages.arguments;
  for(i=0; i<a.length; i++){
	  if (a[i].indexOf("#")!=0){ 
		  d.MM_p[j]=new Image;
		  d.MM_p[j++].src=a[i];
	  }
  }
}




/*###############
Apartir daqui o javascript foi tirado do sitema de
forum phpBB e modificado.
#################*/



function bbfontstyle(num) {
	var txtarea = document.frm.texto;

	var bbopen = new Array('[b]','[i]','[u]','[url]');
	var bbclose = new Array('[/b]','[/i]','[/u]','[/url]');

	if ( num == '' )
	{
		num = document.frm.bb.value;
	}

	if ((clientVer >= 4) && is_ie && is_win) {
		theSelection = document.selection.createRange().text;
		if (!theSelection) {
			txtarea.value += bbopen[num] + bbclose[num];
			txtarea.focus();
			return;
		}
		document.selection.createRange().text = bbopen[num] + theSelection + bbclose[num];
		txtarea.focus();
		return;
	}
	else
	{
		txtarea.value += bbopen[num] + bbclose[num];
		txtarea.focus();
	}
	storeCaret(txtarea);
}

function mozWrap(txtarea, open, close)
{
	var selLength = txtarea.textLength;
	var selStart = txtarea.selectionStart;
	var selEnd = txtarea.selectionEnd;
	if (selEnd == 1 || selEnd == 2) 
		selEnd = selLength;

	var s1 = (txtarea.value).substring(0,selStart);
	var s2 = (txtarea.value).substring(selStart, selEnd)
	var s3 = (txtarea.value).substring(selEnd, selLength);
	txtarea.value = s1 + open + s2 + close + s3;
	return;
}

function storeCaret(textEl) {
	if (textEl.createTextRange) textEl.caretPos = document.selection.createRange().duplicate();
}
