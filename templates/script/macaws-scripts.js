//nueva pantalla completa
function popup(URL, h, w) {
	var newwindow = '';
	if (navigator.appName != "Explorer") {
		derecha = screen.width - w - 30;
		newwindow = window.open(URL, '', "height="+h+", width="+w+", top=0, left="+derecha+", toolbar=no, status=no, scrollbars=auto, location=no, menubar=no, directories=no, resizable=no");
		//abrir.self.moveTo(derecha,0)
	} else
		window.open(URL, '', 'fullscreen=yes, scrollbars=yes');
}

//Para divivir funciones de pantalla completa
function PantallaCompleta1(URL) {
	if (navigator.appName!="Explorer") {
		abrir=window.open(URL, '', "height="+window.screen.availHeight+", width="+(window.screen.availWidth-10)+", top=0, left=0, toolbar=yes, status=yes, scrollbars=auto, location=no, menubar=yes, directories=no, resizable=yes");
		abrir.window.innerWidth=window.screen.width-5
		abrir.window.innerHeight=window.screen.height-50
		abrir.self.moveTo(0,0)
	} else
		window.open(URL, '', 'fullscreen=yes, scrollbars=yes');
}

//formulario dividir
var llave = 0;

function pregunta(){
    if (document.dividir.cantidad2.value == 0){
		alert("La 'Cantidad 2' debe ser mayor a 0")
		return false;
    } else {
		if (confirm('¿Esta seguro de realizar la division?')){
			llave = 1;
			return true;
		} else {
			return false;
		}
	}
}

function cerrar(){
	if (llave == 1){
		opener.location.reload();
		this.close();
	}
}

//pantalla completa
function PantallaCompleta(URL) {
	if (navigator.appName!="Explorer") {
		abrir=window.open(URL, '', "height=600, width=500, top=0, left=0, toolbar=no, status=no, scrollbars=yes, location=no, menubar=no, directories=no, resizable=yes");
		//abrir.self.moveTo(400,200)
	} else
		window.open(URL, '', 'fullscreen=yes, scrollbars=yes');
}

//consultar si se envia el formulario
function comprobar_envio(mensaje) {
	if (confirm(mensaje))
		return true;
	else
		return false;
}

//para colorear las filas de la entrega de corte
function colorear(obj, b, rowObj) {
	rowObj.className = (b) ? 'lista_terrible' : obj.lang;
}

function enter_handle (field, event, salto, tipo) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode == 13) {
		var i;
		for (i = 0; i < field.form.elements.length; i++)
			if (field == field.form.elements[i])
				break;
		
		i = (i + salto) % field.form.elements.length;
		if (field.form.elements[i] != null) {
			field.form.elements[i].focus();
			field.form.elements[i].select();
			return false;
		}
	} else
		if (tipo == 0)//validar numeros
			if ((keyCode >= 48 && keyCode <= 57) || (keyCode == 8))
				return true;
			else
				return false;
		else
			return true;
}

function ventanaSecundaria (URL) {
	window.open(URL,"ventana1","width=500, height=400, scrollbars=no, menubar=no, location=no, resizable=no")
}

function ventanaMateriales (URL) {
	window.open(URL,"ventana1","width=500, height=400, scrollbars=yes, menubar=no, location=no, resizable=no")
}

function handleEnter (field, event) {
	var keyCode = event.keyCode ? event.keyCode : event.which ? event.which : event.charCode;
	if (keyCode == 13) {
		var i;
		for (i = 0; i < field.form.elements.length; i++)
			if (field == field.form.elements[i])
				break;
		i = (i + 1) % field.form.elements.length;
		field.form.elements[i].focus();
		return false;
	} else
		return true;
}

function cambiar_casilla(campo) {
	if(campo.id=="modelo")
		document.agregar_producto_detalle_orden.texto_cantidad.focus();
	else {
		if(campo.id=="estilo")
			document.agregar_producto_detalle_orden.cuero.focus();
		else {
			if(campo.id=="cuero")
				document.agregar_producto_detalle_orden.texto_color.focus();
			else {
				if(campo.id=="color")
					document.agregar_producto_detalle_orden.texto_clip.focus();
				else {
					if(campo.id=="clip")
						document.agregar_producto_detalle_orden.texto_sello.focus();
					else {
						if(campo.id=="sello")
							document.agregar_producto_detalle_orden.texto_etiqueta.focus();
						else {
							if(campo.id=="etiqueta")
								document.agregar_producto_detalle_orden.texto_grabado.focus();
							else {
								if(campo.id=="texto_lugar_grabado")
									document.agregar_producto_detalle_orden.texto_fuente.focus();
								else {
									if(campo.id=="texto_fuente")
										document.agregar_producto_detalle_orden.texto_pedido.focus();
								}
							}
						}
					}
				}
			}
		}
	}
}

function cambiar_casilla_pieza(campo) {
		document.agregar_pieza_material.texto_largo.focus();
}

function registrar_fecha(){
	document.getElementById('texto_fecha').style.display= 'none';
	document.getElementById('campo_fecha_entrega').style.display = 'block';
	document.getElementById('fecha_entrega').style.display = 'block';
	document.getElementById('boton_modificar').style.display = 'block';
	document.getElementById('link_modificar').style.display = 'none';
	document.getElementById('link_registrar').style.display = 'none';
}

function consultar(nombre,opcion){
	var formulario=document.getElementById(nombre).form.name;
	document.getElementById(formulario).elegido.value = nombre;    
    document.getElementById(formulario).funcion.value = opcion;  
	document.getElementById(formulario).submit();
}

function consultar_links(nombre,opcion,formulario){
	document.getElementById(formulario).elegido.value = nombre;    
    document.getElementById(formulario).funcion.value = opcion;  
	document.getElementById(formulario).submit();		
}

function habilitar_grafico(){
	if(document.orden_producto_nuevo.checkgrafico.checked){
		document.orden_producto_nuevo.grafico.enabled=true;
		document.orden_producto_nuevo.grafico.disabled=false;
	} else {
		document.orden_producto_nuevo.grafico.enabled=false;	
		document.orden_producto_nuevo.grafico.disabled=true;	
	}
}

function imprime(){
	document.getElementById('menu').style.visibility = 'hidden';
    document.getElementById('pie').style.visibility = 'hidden';
    document.getElementById('cabecera').style.visibility = 'hidden';
    document.getElementById('hoja_imprimir').style.visibility = 'visible';
    window.print();
    //document.getElementById('menu').style.visibility = 'visible';
    //document.getElementById('pie').style.visibility = 'visible';
    //document.getElementById('cabecera').style.visibility = 'visible';
}

function cambiar_filtro(valor){
	//alert (valor);
	if(valor=='num_orden'){
		document.getElementById('busqueda_cliente').style.display = 'none';  
		document.getElementById('busqueda_usuario').style.display = 'none';  
	   	document.getElementById('busqueda_numero_orden').style.display = 'block';
	} else {
		if(valor=='clientes'){
			document.getElementById('busqueda_cliente').style.display = 'block';  
			document.getElementById('busqueda_usuario').style.display = 'none';  
	   	    document.getElementById('busqueda_numero_orden').style.display = 'none';
		} else {
			if(valor=='usuarios'){
				document.getElementById('busqueda_cliente').style.display = 'none';  
			 	document.getElementById('busqueda_usuario').style.display = 'block';  
	   	     	document.getElementById('busqueda_numero_orden').style.display = 'none';
		 	}
		}
	}
}
//anadidopor samuel zubieta
function presionado()
{
	alert('El pedido fue Cancelado, No se puede Asignar\n Consulte con el Administrador del Sistema');
}