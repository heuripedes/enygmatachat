///Variáveis
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


function sf(){
	cht.location.reload();
	 setTimeout('sf()',4000);
}
function foc()
{
	document.frm.texto.focus();
}
function sf2(){
	cht.location.reload();
}


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

/*##############
Script retirado de dynamicdrive.com
################*/
//Basic Ajax Routine- Author: Dynamic Drive (http://www.dynamicdrive.com)
//Last updated: Jan 15th, 06'

function createAjaxObj(){
var httprequest=false
if (window.XMLHttpRequest){ // if Mozilla, Safari etc
httprequest=new XMLHttpRequest()
if (httprequest.overrideMimeType)
httprequest.overrideMimeType('text/xml')
}
else if (window.ActiveXObject){ // if IE
try {
httprequest=new ActiveXObject("Msxml2.XMLHTTP");
} 
catch (e){
try{
httprequest=new ActiveXObject("Microsoft.XMLHTTP");
}
catch (e){}
}
}
return httprequest
}

var ajaxpack=new Object()
ajaxpack.basedomain="http://"+window.location.hostname
ajaxpack.ajaxobj=createAjaxObj()
ajaxpack.filetype="txt"
ajaxpack.addrandomnumber=0 //Set to 1 or 0. See documentation.

ajaxpack.getAjaxRequest=function(url, parameters, callbackfunc, filetype){
ajaxpack.ajaxobj=createAjaxObj() //recreate ajax object to defeat cache problem in IE
if (ajaxpack.addrandomnumber==1) //Further defeat caching problem in IE?
var parameters=parameters+"&ajaxcachebust="+new Date().getTime()
if (this.ajaxobj){
this.filetype=filetype
this.ajaxobj.onreadystatechange=callbackfunc
this.ajaxobj.open('GET', url+"?"+parameters, true)
this.ajaxobj.send(null)
}
}

ajaxpack.postAjaxRequest=function(url, parameters, callbackfunc, filetype){
ajaxpack.ajaxobj=createAjaxObj() //recreate ajax object to defeat cache problem in IE
if (this.ajaxobj){
this.filetype=filetype
this.ajaxobj.onreadystatechange = callbackfunc;
this.ajaxobj.open('POST', url, true);
this.ajaxobj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
this.ajaxobj.setRequestHeader("Content-length", parameters.length);
this.ajaxobj.setRequestHeader("Connection", "close");
this.ajaxobj.send(parameters);
}
}

//ACCESSIBLE VARIABLES (for use within your callback functions):
//1) ajaxpack.ajaxobj //points to the current ajax object
//2) ajaxpack.filetype //The expected file type of the external file ("txt" or "xml")
//3) ajaxpack.basedomain //The root domain executing this ajax script, taking into account the possible "www" prefix.
//4) ajaxpack.addrandomnumber //Set to 0 or 1. When set to 1, a random number will be added to the end of the query string of GET requests to bust file caching of the external file in IE. See docs for more info.

//ACCESSIBLE FUNCTIONS:
//1) ajaxpack.getAjaxRequest(url, parameters, callbackfunc, filetype)
//2) ajaxpack.postAjaxRequest(url, parameters, callbackfunc, filetype)

///////////END OF ROUTINE HERE////////////////////////


//////EXAMPLE USAGE ////////////////////////////////////////////
/* Comment begins here

//Define call back function to process returned data
function processGetPost(){
var myajax=ajaxpack.ajaxobj
var myfiletype=ajaxpack.filetype
if (myajax.readyState == 4){ //if request of file completed
if (myajax.status==200 || window.location.href.indexOf("http")==-1){ if request was successful or running script locally
if (myfiletype=="txt")
alert(myajax.responseText)
else
alert(myajax.responseXML)
}
}
}

/////1) GET Example- alert contents of any file (regular text or xml file):

ajaxpack.getAjaxRequest("example.php", "", processGetPost, "txt")
ajaxpack.getAjaxRequest("example.php", "name=George&age=27", processGetPost, "txt")
ajaxpack.getAjaxRequest("examplexml.php", "name=George&age=27", processGetPost, "xml")
ajaxpack.getAjaxRequest(ajaxpack.basedomain+"/mydir/mylist.txt", "", processGetPost, "txt")

/////2) Post Example- Post some data to a PHP script for processing, then alert posted data:

//Define function to construct the desired parameters and their values to post via Ajax
function getPostParameters(){
var namevalue=document.getElementById("namediv").innerHTML //get name value from a DIV
var agevalue=document.getElementById("myform").agefield.value //get age value from a form field
var poststr = "name=" + encodeURI(namevalue) + "&age=" + encodeURI(agevalue)
return poststr
}

var poststr=getPostParameters()

ajaxpack.postAjaxRequest("example.php", poststr, processGetPost, "txt")
ajaxpack.postAjaxRequest("examplexml.php", poststr, processGetPost, "xml")

Comment Ends here */



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
