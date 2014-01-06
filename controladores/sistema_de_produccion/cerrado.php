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

include_once('../../clases/sistema_de_produccion/asignacion.php');
include_once('../../clases/sistema_de_produccion/recepcion_calidad.php');
include_once('../../clases/sistema_de_produccion/calidad_control.php');
include_once('../../clases/sistema_de_produccion/cerrado.php');
include_once('../../clases/sistema_de_produccion/rechazos.php');

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
$validar=new Validador();
$asignacion = new Asignacion;
$recepcion_calidad = new Recepcion_calidad;
$calidad = new Calidad_control;
$rechazos = new Rechazo;
//

$funcion=$_GET['funcion'];

if(!isset($_SESSION['logeo']))
{
	header("Location: ../index_logeo.php");
} 
else 
{
	if ($_GET['tabu']){
		$_SESSION['tabu'] = $_GET['tabu'];
		$smarty->assign('tabu', $_SESSION['tabu']);
	}
	if ($funcion=='cerrar')
	{
		$num_asignacion = $_SESSION['num_asignacion'];
		$cerrar=$calidad->cerrar_asignacion($num_asignacion,date("Y-m-d H:i:s"),$_SESSION["usuario_id"]);
		$smarty->assign('num_asignacion', $num_asignacion);
		$smarty->assign('mensaje', 'La asignación se cerró, no esta disponible para modificaciones. ');
		$smarty->display('sistema_de_produccion/control_calidad/buscar_asignacion.html');
	}
	else
	{
		//capturamos las variables
		$tabu = $_GET['tabu'];
		$num_asignacion = $_SESSION['num_asignacion'];
		//mostramos el detalle para 
		$detalle_asignacion = $calidad->buscar_asignacion($num_asignacion);
		$recepcionado= $recepcion_calidad->total_recepcionado($num_asignacion);
		$limpieza=$calidad->cantidad_limpieza_falta($num_asignacion);
		$cal=$calidad->cantidad_calidad_falta($num_asignacion);
		$rechazo=$rechazos->total_rechazado($num_asignacion);
		$arreglo=$rechazos->total_arreglado($num_asignacion);
		//*************************************************
		//verificar que tiene que mostrar
		
		
		//en todo caso se desplega el numero de asignacion y el formulario
		//*******************************************************************************
		//mostramos el detalle de la asignacion
		$smarty->assign('tabu', $_SESSION['tabu']);
		$smarty->assign('detalle_asignacion', $detalle_asignacion);		
		$smarty->assign('num_asignacion', $num_asignacion);
		$smarty->assign('recepcion', $recepcionado);
		$smarty->assign('limpieza', $limpieza);
		$smarty->assign('cal', $cal);
		$smarty->assign('rechazo', $rechazo);
		$smarty->assign('arreglo', $arreglo);
		$smarty->assign('msg', "No se puede cerrar todavia");
		$smarty->display('sistema_de_produccion/control_calidad/buscar_asignacion.html');
		//*******************************************************************************
	}
		
	
}

?>