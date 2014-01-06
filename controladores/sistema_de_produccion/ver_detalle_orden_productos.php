<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/ordenproductonuevo.php');
include_once('../../clases/sistema_de_produccion/propiedadesordenproductonuevo.php');
include_once('../../clases/sistema_de_produccion/estadoproductos.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);
$ordenes_producto_nuevo=new OrdenProductoNuevo;
$propiedades_orden_producto_nuevo=new PropiedadOrdenProductoNuevo;
//$estado_productos=new EstadoProducto;
$funcion = $_POST['funcion'];
if($funcion!="")
{

    $ordenes_producto_nuevo=new OrdenProductoNuevo;
	$estadoproducto=new EstadoProducto;
	$propiedades_orden_producto_nuevo=new PropiedadOrdenProductoNuevo;
	$ordenproducto_id=$_POST['elegido'];
	$opcion = $_POST['opcion'];
	$estado = $_POST['estado'];
	$observaciones = $_POST['observaciones'];
	if($_POST['codigo']!="")
	{
	   $ordenes_producto_nuevo->actualizar_codigo($ordenproducto_id,$_POST['codigo']);
	  
	}
	$ordenes_producto_nuevo->actualizar_estado($ordenproducto_id,$estado);
	$usuario_id=$_SESSION["usuario_id"];
	$estadoproducto->registrar_estado($ordenproducto_id,trim($observaciones),$usuario_id);
	$detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
	$propiedades_orden=$propiedades_orden_producto_nuevo->obtener_propiedades($ordenproducto_id);
	//$estados_orden=$estado_productos->obtener_estados($ordenproducto_id);
	$smarty->assign('ordenproducto_id',$ordenproducto_id);
	$smarty->assign('detalle_orden',$detalle_orden);
	$smarty->assign('propiedades_orden',$propiedades_orden);
	$smarty->assign('opcion',"lista_busqueda");
	$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/ver_detalle_orden_productos.html');
	
}
else
{
		 $ordenproducto_id = $_SESSION['elegido'];
		 $opcion = $_SESSION['opcion'];
		 $detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
		 $propiedades_orden=$propiedades_orden_producto_nuevo->obtener_propiedades($ordenproducto_id);
		 $smarty->assign('ordenproducto_id',$ordenproducto_id);
		 $smarty->assign('detalle_orden',$detalle_orden);
		 $smarty->assign('propiedades_orden',$propiedades_orden);
		 $smarty->assign('opcion',$opcion);
	     $smarty->display('sistema_de_produccion/orden_de_producto_nuevo/ver_detalle_orden_productos.html');
}   
?>