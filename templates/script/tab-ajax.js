var peticion = false; 
if (window.XMLHttpRequest) {
      peticion = new XMLHttpRequest();
      } else if (window.ActiveXObject) {
            peticion = new ActiveXObject("Microsoft.XMLHTTP");
}


function ObtenerDatos(datos,divID) { 
if(peticion) {
     var obj = document.getElementById(divID); 
     peticion.open("GET", datos); 
     peticion.onreadystatechange = function()  { 
          if (peticion.readyState == 4) { 
               obj.innerHTML = peticion.responseText; 
          } 
     } 
peticion.send(null); 
}
}

function CambiarEstilo(id) {
	var elementosMenu = getElementsByClassName(document, "li", "activo");
	for (k = 0; k< elementosMenu.length; k++) {
	elementosMenu[k].className = "inactivo";
	}
	var identity=document.getElementById(id);
	identity.className="activo";
}

/*
    function getElementsByClassName
    Written by Jonathan Snook, http://www.snook.ca/jonathan
    Add-ons by Robert Nyman, http://www.robertnyman.com
*/

function getElementsByClassName(oElm, strTagName, strClassName){
    var arrElements = (strTagName == "*" && document.all)? document.all : oElm.getElementsByTagName(strTagName);
    var arrReturnElements = new Array();
    strClassName = strClassName.replace(/\-/g, "\\-");
    var oRegExp = new RegExp("(^|\\s)" + strClassName + "(\\s|$)");
    var oElement;
    for(var i=0; i<arrElements.length; i++){
        oElement = arrElements[i];      
        if(oRegExp.test(oElement.className)){
            arrReturnElements.push(oElement);
        }   
    }
    return (arrReturnElements)
}
