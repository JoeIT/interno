<?php
session_start();
define('CLAS', "clases/");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');

if(isset($_SESSION['usuario_id']))
{
	if (isset($_SESSION['resultreplcv']))
	{
		$result = $_SESSION['resultreplcv'];
		unset($_SESSION['resultreplcv']);
	}
	if (isset($_SESSION['tiporeplcv']))
	{
		$tipo = $_SESSION['tiporeplcv'];
		unset($_SESSION['tiporeplcv']);
	}
	
	if (isset($_SESSION['lcvcontribuyente']))
	{
		$contribuyente = $_SESSION['lcvcontribuyente'];		 
		unset($_SESSION['lcvcontribuyente']);
	}
	
	if (isset($_SESSION['totalesreplcv']))
	{
		$totales = $_SESSION['totalesreplcv'];		 
		unset($_SESSION['totalesreplcv']);
	}
	
	if (isset($_SESSION['reppersonalcv']))
	{
		$persona = $_SESSION['reppersonalcv'];		 
		unset($_SESSION['reppersonalcv']);
	}
	
	if (isset($_SESSION['reptipventlcv']))
	{
		$tipoventa = $_SESSION['reptipventlcv'];		 
		unset($_SESSION['reptipventlcv']);
	}
	
	if (isset($_SESSION['repcomalfalcv']))
	{
		$alfanumerico = $_SESSION['repcomalfalcv'];		 
		unset($_SESSION['repcomalfalcv']);
	}
	
	if (isset($_SESSION['fechactual']))
	{
		$diahora = $_SESSION['fechactual'];		 
		unset($_SESSION['fechactual']);
	}
	else 
	$diahora=date("d/m/Y   h:i:s a");
	
	
	$smarty = new Smarty();
	if($tipo==0)
	$title = "LIBRO DE COMPRAS IVA";
	if($tipo==1)
	$title = "LIBRO DE VENTAS IVA";	
	
	
	//$hoy = date("d/m/Y   h:i:s a");
	$smarty->template_dir = 'templates';
	$smarty->compile_dir = 'templates_c';
	$smarty->assign('title', $title);
	$smarty->assign('result', $result);
	$smarty->assign('diahora', $diahora);
	$smarty->assign('contribuyente', $contribuyente);
	$smarty->assign('totales', $totales);
	$smarty->assign('persona', $persona);	
	
	$smarty->assign('tipovent', $tipoventa);
	$smarty->assign('alfanum',$alfanumerico);	
	
	$smarty->display('reporteSalida.html');
}
else
header("Location:/sistema/macaws/lcv.php");
?>