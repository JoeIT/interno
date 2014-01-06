<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/ordenproductonuevo.php');
include_once('../../clases/sistema_de_produccion/estadoproductos.php');
include_once('../../clases/sistema_de_produccion/materiales.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);
$ordenes_producto_nuevo=new OrdenProductoNuevo;
$estadoproducto=new EstadoProducto;
$materiales=new Material;
$validar=new Validador();
$funcion=$_POST['funcion'];
 
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
	   $ordenproducto_id=$_POST['ordenproducto_id'];
	   $detalle_material["cantidad"]=$_POST['texto_cantidad'];
	   $detalle_material["material"]=$_POST['texto_material'];
	   $detalle_material["materialesordenprodnuevo_id"]=$_POST['elegido'];
	   $detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
	   $smarty->assign('ordenproducto_id',$ordenproducto_id);
	   $smarty->assign('detalle_orden',$detalle_orden);
	   $smarty->assign('detalle_material',$detalle_material);
	   $smarty->assign('errores',$error);
       $smarty->display('sistema_de_produccion/orden_de_producto_nuevo/modificar_material_orden_productos.html');
	}
	else
	{
	   
	   $ordenproducto_id=$_POST['ordenproducto_id'];
	   $id=$_POST['elegido'];
	   $cantidad=$_POST['texto_cantidad'];
	   $usuario=$_SESSION['usuario_id'];
	   $detalle_orden=$ordenes_producto_nuevo->modificar_material($id,$material_id,$cantidad,$usuario);
	  $detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
	  $materiales_orden=$ordenes_producto_nuevo->obtener_lista_materiales($ordenproducto_id);
	  $smarty->assign('ordenproducto_id',$ordenproducto_id);
	  $smarty->assign('detalle_orden',$detalle_orden);
  	  $smarty->assign('materiales_orden',$materiales_orden);
	  $smarty->display('sistema_de_produccion/orden_de_producto_nuevo/registrar_material_orden_productos.html');
	}   
?>