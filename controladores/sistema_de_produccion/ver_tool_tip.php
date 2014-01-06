<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/includes/validador.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/sistema_de_produccion/colores.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/sistema_de_produccion/clip.php');
include_once('../../clases/sistema_de_produccion/chapa.php');
include_once('../../clases/sistema_de_produccion/cuero.php');
include_once('../../clases/sistema_de_produccion/etiquetas.php');
include_once('../../clases/sistema_de_produccion/propiedades.php');
include_once('../../clases/sistema_de_produccion/familias.php');
include_once('../../clases/sistema_de_produccion/fuentes.php');
include_once('../../clases/sistema_de_produccion/lugargrabado.php');
include_once('../includes/fecha.php');

header("Content-Type: text/html; charset=iso-8859-1");
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$smarty->assign('fecha',$fecha);


$orden=new OrdenProd;
$detalle_orden=new Detalle_orden;
$funcion=$_GET["funcion"];

if($funcion=="ordenprod")
{
	$orden_id=$_GET["orden_id"];
	$detalle_id=$_GET["elegido"];
	$detalle_datos=$detalle_orden->obtener_datos_detalle($detalle_id,$orden_id);
	$smarty->assign('titulo', "Observaciones");
	$smarty->assign('mensaje', $detalle_datos['observaciones']);
	$smarty->assign('titulo2', "Obs. Internas");
	$smarty->assign('mensaje2', $detalle_datos['obs_interior']);
	$smarty->display('sistema_de_produccion/tool_tips/plantilla.html');
}
if($funcion=="detalle")
{
	$orden_id=$_GET["orden_id"];
	$detalle_id=$_GET["elegido"];
	$detalle_datos=$detalle_orden->obtener_observacion_detalle($detalle_id);
	$smarty->assign('titulo', "Observaciones");
	$smarty->assign('mensaje', $detalle_datos['observaciones']);
	$smarty->assign('titulo2', "Obs. Internas");
	$smarty->assign('mensaje2', $detalle_datos['obs_interior']);
	$smarty->display('sistema_de_produccion/tool_tips/plantilla.html');
}
?>