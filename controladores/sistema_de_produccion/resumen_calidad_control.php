<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../includes/fecha.php');
include_once('../../clases/validador.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

include_once('../../clases/sistema_de_produccion/calidad_control.php');
include_once('../../clases/sistema_de_produccion/ordenprod.php');

$validar=new Validador();
$calidad = new Calidad_control;
$ordenes = new OrdenProd;


if(!isset($_SESSION['logeo']))
{
	header("Location: ../index_logeo.php");
} 
else 
{
	$funcion = $_GET['funcion'];
	
	if($funcion==detalle)
	{
		$orden = $_GET['orden'];
		
		$cabecera = $ordenes->obtener_cabecera_orden($orden);
		$smarty->assign('cabecera', $cabecera);
		
		$detalle = $calidad->obtener_detalle_resumen($orden);
		$smarty->assign('detalle', $detalle);
		
		$smarty->display('sistema_de_produccion/control_calidad/detalle.html');
	}
	else
	{
		if($funcion==buscar)
		{
			$orden = $_GET['orden'];
			echo $orden;
			$lista = $calidad->lista_orden_produccion($orden);
			$smarty->assign('ordenes', $lista);
			$smarty->display('sistema_de_produccion/control_calidad/lista_ordenes.html');
			
		}
		else
		{
			$lista = $calidad->lista_ordenes_produccion();
			$smarty->assign('ordenes', $lista);
			$smarty->display('sistema_de_produccion/control_calidad/lista_ordenes.html');
		}
	}
}
?>