<?php
session_start();
define('CLAS', "clases/");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');

if(isset($_SESSION['usuario_id']))
{
	if (isset($_SESSION['sdidui']))
	{
		$result = $_SESSION['sdidui'];
		unset($_SESSION['sdidui']);
	}
	if (isset($_SESSION['sdirepper']))
	{
		$periodo = $_SESSION['sdirepper'];
		unset($_SESSION['sdirepper']);
	}
	if (isset($_SESSION['sdirepges']))
	{
		$gestion = $_SESSION['sdirepges'];
		unset($_SESSION['sdirepges']);
	}
	if (isset($_SESSION['sdireptipo']))
	{
		$tipo = $_SESSION['sdireptipo'];
		unset($_SESSION['sdireptipo']);
	}
	
	
	
	$smarty = new Smarty();
	if($tipo==4)
	$title = " SDI DUI";
	if($tipo==5)
	$title = "SDI FACT";
	
	if ($periodo<10)
		$periodo="0$periodo";
		
	//$hoy = date("d/m/Y   h:i:s a");
	$smarty->template_dir = 'templates';
	$smarty->compile_dir = 'templates_c';
	$smarty->assign('title', $title);
	$smarty->assign('result', $result);
	$smarty->assign('periodo', $periodo);
	$smarty->assign('gestion', $gestion);
	$smarty->assign('tipo', $tipo);
	
	$smarty->display('reporteSDI.html');
}
else
header("Location:/sistema/macaws/lcv.php");
?>