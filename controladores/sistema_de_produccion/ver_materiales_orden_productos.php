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
$ordenproducto_id=$_SESSION['elegido'];

$detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
$materiales_orden=$ordenes_producto_nuevo->obtener_lista_materiales($ordenproducto_id);
$smarty->assign('ordenproducto_id',$ordenproducto_id);
$smarty->assign('detalle_orden',$detalle_orden);
$smarty->assign('materiales_orden',$materiales_orden);
$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/ver_materiales_orden_productos.html');	

?>