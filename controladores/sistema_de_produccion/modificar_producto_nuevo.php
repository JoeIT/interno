<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/ordenproductonuevo.php');
include_once('../../clases/sistema_de_produccion/propiedadesproductonuevo.php');
include_once('../../clases/sistema_de_produccion/propiedadesordenproductonuevo.php');
include_once('../../clases/sistema_de_produccion/estadoproductos.php');
include_once('../../clases/sistema_de_produccion/clip.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/sistema_de_produccion/modelos.php');
include_once('../../clases/sistema_de_produccion/clientes.php');
require_once('../includes/seguridad.php');
include_once('../../clases/includes/validador.php');
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);
$ordenes_producto_nuevo=new OrdenProductoNuevo;
$propiedadesproductonuevo=new PropiedadProductoNuevo;
$propiedadesordenproductonuevo=new PropiedadOrdenProductoNuevo;
$estadoproducto=new EstadoProducto;
$clientes=new Cliente;
$clip=new Clip;
$estilo=new Estilo;
$modelo=new Modelo;
$opcion=$_POST['opcion'];
$id=$_POST['elegido'];


if(isset($_GET["busqueda_ajax"]))
 {    header("Content-Type: text/html; charset=iso-8859-1");
      $valor = $_POST["value"];
	  $cadena=  utf8_decode(trim($valor));
	  echo "<ul>";
	  if($_GET['busqueda_ajax']=='estilos')
	  {
	       $lista1=$modelo->busqueda_estilos2($cadena,"1");
		   $lista2=$modelo->busqueda_estilos2($cadena,"2");
		   $lista3=$modelo->busqueda_estilos2($cadena,"3");
		   $lista = array_merge((array)$lista1,(array) $lista2,(array)$lista3);
      }
	  else
	  {
	       if($_GET['busqueda_ajax']=='clips')
		   	  $lista=$clip->busqueda_clips($cadena);
		   else
		   {
		      if($_GET['busqueda_ajax']=='clientes')
			     $lista=$clientes->busqueda_clientes($cadena);
		   
		   }
		  
	  }
	  if(count($lista)==0)
	  {
		   echo "<li>No hay resultados</li>";
	  }
	  else
	  {
		for($contador=0;$contador<count($lista);$contador++)
	     	{
		        echo "<li>".$lista[$contador]."</li>";
		  
		 	}
	  
	  }
	  echo "</ul>";
 }
 else
 {




if($_POST['funcion']=="validar")
{

 	 
         $validar = new Validador();
         if ($validar->validarTodo($_POST['num_orden'], 1, 100)) {
        	$error['err_num_orden'] = "	Número de orden invalido";}
 		 if ($validar->validarTodo($_POST['modelo'], 1, 100)) {
        	$error['err_modelo'] = "	Debe ingresar el modelo";}
  	     if ($validar->validarTodo($_POST['date1'], 1, 100)) {
		    $error['err_date1'] = " Debe ingresar una fecha de solicitud";}
         else{
			       if (!$validar->validarFecha($_POST['date1'])) {
							$error['err_date1'] = " Fecha no valida: ".$_POST['date1'];}
		 }
         if ($validar->validarTodo($_POST['clientes'], 1, 100)) {
    		$error['err_cliente'] = " Cliente no valido";}
		 if ($validar->validarTodo($_POST['date2'], 1, 100)) {
    	   	$error['err_date2'] = " Debe ingresar una fecha de culminación";}
    	 else{
		      if (!$validar->validarFecha($_POST['date2'])) {
    			$error['err_date2'] = " Fecha de culminación no valida: ".$_POST['date2'];}
		     }
		$cliente_id=$clientes->verificar_cliente(trim($_POST['clientes']));
        if($cliente_id==-1)
		{ $error['err_cliente'] = " Cliente no valido";}
		$estilo_id=$estilo->verificar_estilo(trim($_POST['texto_estilo']));
        if($estilo_id==-1)
		{ $error['err_estilo'] = " Estilo no valido";}
		$clip_id=$clip->verificar_clip(trim($_POST['texto_clip']));
        if($clip_id==-1)
		{ $error['err_clip'] = " Clip no valido";}
		if ($validar->validarTodo($_POST['cantidad'], 1, 100)) {
		    $error['err_cantidad'] = " Debe ingresar una cantidad";}
         else{
			        if ($validar->validarNumeros($_POST['cantidad'], 1, 3)) {
        	$error['err_cantidad'] = "Debe ingresar una cantidad valida";}
		 }
		 if ($validar->validarTodo($_POST['tarjeteros'], 1, 100)) {
		    $error['err_tarjeteros'] = " Debe ingresar la cantidad de tarjeteros";}
         else{
			       if ($validar->validarNumeros($_POST['tarjeteros'], 1, 3)) {
        	$error['err_tarjeteros'] = "Debe ingresar una cantidad valida";}
		 }
        if (isset($error))
		{
            $seleccionados=$_POST['seleccionados'];
            $lista_propiedades=$propiedadesproductonuevo->obtener_lista_propiedades();
			$smarty->assign('num_orden', $_POST['num_orden']);
		$smarty->assign('ordenproducto_id', $_POST['ordenproducto_id']);
		$smarty->assign('lista_propiedades',$lista_propiedades);
		$smarty->assign('fecha_solicitud',  $_POST['date1']);
		$smarty->assign('fecha_culminacion',$_POST['date2']);
		$smarty->assign('cliente',$_POST['clientes']);
		$smarty->assign('errores',$error);
		$smarty->assign('modelo',$_POST['modelo']);
		$smarty->assign('cantidad',$_POST['cantidad']);
		$smarty->assign('clip',$_POST['texto_clip']);
		$smarty->assign('estilo',$_POST['texto_estilo']);
		$smarty->assign('tarjeteros',$_POST['tarjeteros']);
		$smarty->assign('detalles',$_POST['detalles']);
		$smarty->assign('interior',$_POST['interior']);
		$smarty->assign('exterior',$_POST['exterior']);
		$smarty->assign('seleccionados',$seleccionados);
		$smarty->assign('material_varios',$_POST['material_varios']);
		$smarty->assign('observaciones', $_POST['observaciones']);
		$smarty->assign('titulo_pagina',"Modificar Orden de desarrollo de productos nuevos");
		$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/modificar_producto_nuevo.html');
		}
	   else
	   {
	   
	        
	   
			//INGRESA UNA NUEVA ORDEN DE PRODUCTO NUEVO			
			$mica=$_POST['pantalla']."-".$_POST['teclado']."-".$_POST['otros_mica'];
		    $ordenes_producto_nuevo->actualiza_orden($_POST['ordenproducto_id'],$cliente_id,$_POST['date1'],$_POST['date2'],$_POST['modelo'],$_POST['tarjeteros'],$_POST['detalles'],$_POST['cantidad'],$mica,$_POST['exterior'],$_POST['interior'],$_POST['material_varios'],$estilo_id,$clip_id);
		  
		 $seleccionados=$_POST['seleccionados'];
		  
		 $lista_propiedades=$propiedadesproductonuevo->obtener_lista_propiedades();
		 $propiedadesordenproductonuevo->eliminar_propiedades($_POST['ordenproducto_id']);
		 $propiedadesordenproductonuevo->insertar_propiedades($_POST['ordenproducto_id'],$seleccionados);
		 $usuario_id=$_SESSION['usuario_id'];
		 $observaciones="Modificación de las caracteristicas del producto";
		 $estadoproducto->registrar_estado($_POST['ordenproducto_id'],$observaciones,$usuario_id);
 		 header("Location: ../../controladores/sistema_de_produccion/lista_ordenes_productos.php");												
		}

	}
 	else
	{	
	    $ordenproducto_id = $_SESSION['elegido'];
		$opcion = $_SESSION['opcion'];
		$detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
		$propiedades_orden=$propiedadesordenproductonuevo->obtener_id_propiedades($ordenproducto_id);
   	    $lista_propiedades=$propiedadesproductonuevo->obtener_lista_propiedades();
        $smarty->assign('num_orden',$detalle_orden['num_ordenproducto']);
		$smarty->assign('ordenproducto_id', $detalle_orden['ordenproducto_id']);
		$smarty->assign('lista_propiedades',$lista_propiedades);
		$fecha_solicitud= split("-",$detalle_orden['fechasolicitud']);
		
		$smarty->assign('fecha_solicitud', $fecha_solicitud[2]."-".$fecha_solicitud[1]."-".$fecha_solicitud[0] );
		
		$fecha_culminacion= split("-",$detalle_orden['fechaculminacion']);
		
		$smarty->assign('fecha_culminacion',$fecha_culminacion[2]."-".$fecha_culminacion[1]."-".$fecha_culminacion[0]);
		$smarty->assign('cliente',$detalle_orden['clientenombre']);
		$smarty->assign('errores',$errores);
		$smarty->assign('modelo',$detalle_orden['modelo']);
		$smarty->assign('cantidad',$detalle_orden['cantidad']);
		$smarty->assign('clip',$detalle_orden['clip']);
		$smarty->assign('estilo',$detalle_orden['estilo']);
		$smarty->assign('tarjeteros',$detalle_orden['tarjetero']);
		$smarty->assign('detalles',$detalle_orden['detallesadicionales']);
		$smarty->assign('interior',$detalle_orden['caracteristicas_interior']);
		$smarty->assign('exterior',$detalle_orden['caracteristicas_exterior']);
		$smarty->assign('seleccionados',$propiedades_orden);
		$smarty->assign('material_varios',$detalle_orden['material_varios']);
		$smarty->assign('observaciones',$observaciones);
		$smarty->assign('titulo_pagina',"Modificar Orden de desarrollo de productos nuevos");
		$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/modificar_producto_nuevo.html');
	}
	
}
?>