var xmlHttp

function GetXmlHttpObject() {
	var xmlHttp=null;
	try {
		//Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	} catch (e) {
		// Internet Explorer
		try {
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}

function stateChanged() {
	if (xmlHttp.readyState==4) {
		document.getElementById("contenido").innerHTML=xmlHttp.responseText;
		desbloquear_contenido();
	}
}

//para bloquear el contenido
function bloquear_contenido() {
	document.getElementById("ingresar").style.height = document.body.scrollHeight;
	document.getElementById("ingresar").style.display = "block";
}
//para desbloquear el contenido
function desbloquear_contenido() {
	document.getElementById("ingresar").style.display = "none";
}

//****************************************************************************
function generar_contenido(fi, ff, opcion) {
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null) {
		alert ("Your browser does not support AJAX!");
		return;
	}
	
	bloquear_contenido();
	
	var url="indicadores.php?opcion="+opcion;
	url=url+"&fi="+fi;
	url=url+"&ff="+ff;
	url=url+"&sid="+Math.random();
	xmlHttp.onreadystatechange=stateChanged;
	xmlHttp.open("GET",url,true);
	xmlHttp.send(null);
}
