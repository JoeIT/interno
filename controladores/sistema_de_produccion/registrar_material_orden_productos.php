<?php
session_start();
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once('../includes/seguridad.php');

include_once('../../clases/sistema_de_produccion/ordenproductonuevo.php');
include_once('../../clases/sistema_de_produccion/estadoproductos.php');
include_once('../../clases/sistema_de_produccion/materiales.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');
require('../../controladores/includes/fecha.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$ordenes_producto_nuevo=new OrdenProductoNuevo;
$estadoproducto=new EstadoProducto;
$materiales=new Material;
$validar=new Validador();
$funcion=$_POST['funcion'];
//echo $funcion;
if(isset($_GET["busqueda_ajax"]))
{    
      $valor = $_POST["value"];
	  $cadena=  utf8_decode(trim($valor));
	   header("Content-Type: text/html; charset=iso-8859-1");
	  echo "<ul>";
	  $lista=$materiales->busqueda_materiales($cadena);
	  if(count($lista)==0)
	  {
		   echo "<li>No hay resultados</li>";
	  }
	  else
	  {
		for($contador=0;$contador<count($lista);$contador++)
	     	{
		        echo "<li>".$lista[$contador]["nombre"]."</li>";
		 	}
	  }
	  echo "</ul>";
}
else
{
if(isset($_GET["imprimir"]))
{	
	$ordenproducto_id=$_SESSION['elegido'];
	$opcion=$_SESSION['opcion'];

	$detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
	$materiales_orden=$ordenes_producto_nuevo->obtener_lista_materiales($ordenproducto_id);

	$smarty->assign('ordenproducto_id',$ordenproducto_id);
	$smarty->assign('detalle_orden',$detalle_orden);
	$smarty->assign('materiales_orden',$materiales_orden);
	$smarty->assign('cantidad',$cantidad);
	$smarty->assign('material',$material);
	$smarty->assign('errores',$errores);
	$smarty->assign('fecha',$fecha);
	$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/hoja_de_impresion.html');				
		$materiales_orden=$ordenes_producto_nuevo-> fecha_de_entrega_material($ordenproducto_id);
}
else
{
	
if(isset($_GET["devolucion"]))
{	
	echo "entro";
	$ordenproducto_id=$_SESSION['elegido'];
	$opcion=$_SESSION['opcion'];

	$detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
	$materiales_orden=$ordenes_producto_nuevo->obtener_lista_materiales($ordenproducto_id);

	$smarty->assign('ordenproducto_id',$ordenproducto_id);
	$smarty->assign('detalle_orden',$detalle_orden);
	$smarty->assign('materiales_orden',$materiales_orden);
	$smarty->assign('cantidad',$cantidad);
	$smarty->assign('material',$material);
	$smarty->assign('errores',$errores);
	$smarty->assign('fecha',$fecha);
	$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/hoja_de_impresion.html');				
}	
else
{
 if(trim($funcion=="registrar"))
 {
	if ($validar->validarTodo($_POST['texto_material'], 1, 100)) {
   	   $error['err_texto_material'] = "Debe ingresar un material";}
	else
	{
	    $material_id=$materiales->verificar_material(trim($_POST['texto_material']));
        if($material_id==-1){ $error['err_texto_material'] = "Material no válido";}
	
	}
    if ($validar->validarTodo($_POST['texto_cantidad'], 1, 100)) {
    	$error['err_texto_cantidad'] = "Debe ingresar una cantidad";}							 
	else
    {
	     if ($validar->validarNumeros($_POST['texto_cantidad'], 1, 3)) {
        	$error['err_texto_cantidad'] = "Debe ingresar una cantidad valida";}							
	}
	 if (isset($error))
	{
	   $ordenproducto_id=$_SESSION['elegido'];
       $opcion=$_SESSION['opcion'];
	   $detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
       $materiales_orden=$ordenes_producto_nuevo->obtener_lista_materiales($ordenproducto_id);
			$smarty->assign('ordenproducto_id',$ordenproducto_id);
			$smarty->assign('detalle_orden',$detalle_orden);
			$smarty->assign('materiales_orden',$materiales_orden);
			$smarty->assign('cantidad',$_POST['texto_cantidad']);
			$smarty->assign('material',$_POST['texto_material']);
			$smarty->assign('errores',$error);
			$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/registrar_material_orden_productos.html');	
	}
	else
	{
	   $ordenproducto_id=$_SESSION['elegido'];
	   $cantidad=$_POST['texto_cantidad'];
	   ///////////  recuperar usuario            ////////////////////////
	   $usuario=$_SESSION['usuario_id'];
	   $detalle_orden=$ordenes_producto_nuevo->registrar_material($ordenproducto_id,$material_id,$cantidad,$usuario);
	   $detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
	   $materiales_orden=$ordenes_producto_nuevo->obtener_lista_materiales($ordenproducto_id);
	   
			$smarty->assign('ordenproducto_id',$ordenproducto_id);
			$smarty->assign('detalle_orden',$detalle_orden);
			$smarty->assign('materiales_orden',$materiales_orden);

			$smarty->assign('material',$material);
			$smarty->assign('errores',$errores);
			$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/registrar_material_orden_productos.html');
	}
	   
 }
 else
 {  
       if($funcion=="modificar_material")
	   {
	        $ordenproducto_id=$_POST['ordenproducto_id'];
			$materialesordenprodnuevo_id=$_POST['elegido'];
			$detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
  		    $detalle_material=$ordenes_producto_nuevo->obtener_material($materialesordenprodnuevo_id);
			
			$smarty->assign('ordenproducto_id',$ordenproducto_id);
			$smarty->assign('detalle_orden',$detalle_orden);
			$smarty->assign('detalle_material',$detalle_material);

			$smarty->assign('material',$material);
			$smarty->assign('errores',$errores);
			$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/modificar_material_orden_productos.html');
	   }
	   else
	   {
	     if($funcion=="eliminar_material")
		 {
		    $ordenproducto_id=$_POST['ordenproducto_id'];
			$materialesordenprodnuevo_id=$_POST['elegido'];
		    $ordenes_producto_nuevo->deshabilitar_material($materialesordenprodnuevo_id);
			$detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
			$materiales_orden=$ordenes_producto_nuevo->obtener_lista_materiales($ordenproducto_id);
			$smarty->assign('ordenproducto_id',$ordenproducto_id);
			$smarty->assign('detalle_orden',$detalle_orden);
			$smarty->assign('materiales_orden',$materiales_orden);
			$smarty->assign('cantidad',$cantidad);
			$smarty->assign('material',$material);
			$smarty->assign('errores',$errores);
			$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/registrar_material_orden_productos.html');			 
		 
		 }
	   	 else
	   	 {
		 	
				$ordenproducto_id=$_SESSION['elegido'];
				$opcion=$_SESSION['opcion'];
				$detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
				$materiales_orden=$ordenes_producto_nuevo->obtener_lista_materiales($ordenproducto_id);
	
				$smarty->assign('ordenproducto_id',$ordenproducto_id);
				$smarty->assign('detalle_orden',$detalle_orden);
				$smarty->assign('materiales_orden',$materiales_orden);
				$smarty->assign('cantidad',$cantidad);
				$smarty->assign('material',$material);
				$smarty->assign('errores',$errores);
				$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/registrar_material_orden_productos.html');	
			
		 }
	}
 
 }	 
}  

}}
?>