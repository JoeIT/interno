<?php
session_start();
define('CLAS', "clases/");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Gestion.php");
require_once(CLAS . "Reportes.php");

if(isset($_SESSION['usuario_id']))
{
	if (isset($_SESSION['errores']))
	{
		$errores = $_SESSION['errores'];
		unset($_SESSION['errores']);
	}

	if (isset($_SESSION['req']))
	{
		$valores = $_SESSION['req'];
		unset($_SESSION['req']);		
	}

	if (isset($_SESSION['pagreplcv']))
	{
		$paginacion = $_SESSION['pagreplcv'];
		unset($_SESSION['pagreplcv']);
	}
	
	if (isset($_SESSION['fechactual']))
	{
		$fechactual = $_SESSION['fechactual'];
		unset($_SESSION['fechactual']);
	}
	else 
	$fechactual = date("d/m/Y   h:i:s a");

	$smarty = new Smarty();
	$link = new Coneccion();
	$link = $link->conectiondb();
	$title = ".:: Reportes - LCV IVA. ::.";

	$ges = new Gestion($link);
	$gestiones = $ges->listarTodasGestiones();
	

	$smarty->template_dir = 'templates';
	$smarty->compile_dir = 'templates_c';
	$smarty->assign('title', $title);
	$smarty->assign('error',$errores);
	$smarty->assign('val',$valores);
	
	$smarty->assign('paginacion',$paginacion);	
	$smarty->assign('gestiones',$gestiones);
	$smarty->assign('fechactual',$fechactual);
	
	$smarty->display('reportes.html');
}
else
header("Location:/sistema/macaws/lcv.php");
?>