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
$estado_productos=new EstadoProducto;
$ordenproducto_id = $_SESSION['elegido'];
$opcion = $_SESSION['opcion'];
$detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
$estados_orden=$estado_productos->obtener_estados($ordenproducto_id);
$smarty->assign('ordenproducto_id',$ordenproducto_id);
$smarty->assign('detalle_orden',$detalle_orden);
$smarty->assign('estados_orden',$estados_orden);
$smarty->assign('opcion',$opcion);
$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/ver_historial_orden_productos.html');
?>