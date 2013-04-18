/**************************************************************
 * Ajax Básico v1.0                                           *
 **************************************************************
 * Criado por :: Higor Eurípedes Pimentel Fernandes de Araújo *
 * Email      :: heuripedes@hotmail.com                       *
 * Site       :: ens.6te.net                                  *
 **************************************************************
 **************************************************************/

var Ajax     = new Object();

function HttpRequest() {
    var xmlhttp = false;
	/*@cc_on
	@if (@_jscript_version >= 5)
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
		}
	}
	@else
	xmlhttp = false;
	@end @*/
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
	    try {
	    	xmlhttp = new XMLHttpRequest();
	    } catch (e) {
	    	xmlhttp = false;
	    }
	}
	return xmlhttp;
}



Ajax.getObj  = HttpRequest();
Ajax.postObj = HttpRequest();
Ajax.ftype   = 'txt';
Ajax.isOk    = 0;

if (Ajax.getObj !== false && Ajax.postObj !== false) {
	Ajax.isOk = 1;
}

Ajax.set = function(u,p,t,i,h){
	this.uri    = u;
	this.params = p;
	this.ftype  = t;
	this.toId   = i;
	this.handle = h;
	if (!h)	this.handle = this.noHandle;
};

Ajax.makeQuery = function (k,v){
	var str = '';
	for (i = 0;i<k.length;i++ )	{
		if (i == 0)	{
			str += k[i] + '=' + escape(v[i]);
		}else{
			str += '&' + k[i] + '=' + escape(v[i]);
		}
	}
	return str;
};

Ajax.noHandle = function(){};

Ajax.get = function (){
	if (!this.isOk)
		return;
	if (this.getObj !== null && (this.getObj.readyState === 4 || this.getObj.readyState === 0)) {
		this.last   = 0
		this.getObj = HttpRequest();
		this.getObj.onreadystatechange = this.handle;
		this.getObj.open('GET', this.uri + "?" + this.params, true);
		this.getObj.send(null);
	}else{
		setTimeout(function(){Ajax.get();},500);
	}
};

Ajax.post = function (){
	if (!this.isOk)
		return;
	if (this.postObj !== null && (this.postObj.readyState === 4 || this.postObj.readyState === 0)) {
		this.last    = 1;
		this.postObj = HttpRequest();
		this.postObj.onreadystatechange = this.handle;
		this.postObj.open('POST', this.uri, true);
		this.postObj.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=UTF-8');
		this.postObj.setRequestHeader('Content-length', this.params.length);
		this.postObj.setRequestHeader('Connection', "close");
		this.postObj.send(this.params);
	}else{
		setTimeout(function(){Ajax.post();},500);
	}
}

Ajax.writeId = function (){
	if (this.last == 1) {
		var ob = this.postObj;
	}else{
		var ob = this.getObj;
	}
	var ftype  = this.ftype;
	
	if (ob.readyState == 4 && ob != null){ 
		var id     = DOM(this.toId);
		if (ftype=="txt"){
			id.innerHTML = ob.responseText;
		}else{
			id.innerHTML = ob.responseXML;
		}
	}
};
Ajax.resp = function(){
	if (this.last == 1) {
		var ob = Ajax.postObj;
	}else{
		var ob = Ajax.getObj;
	}
	var ftype  = Ajax.ftype;
	if (ob.readyState == 4 && ob != null){ 
		if (ftype=="txt"){
		
			return(ob.responseText);
		}else{
		
			return(ob.responseXML);
		}
	}
};