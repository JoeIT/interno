<?php
session_start();
define('CLAS', "clases/");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');

if(isset($_SESSION['usuario_id']))
{
	if (isset($_SESSION['davincirepcv']))
	{
		$result = $_SESSION['davincirepcv'];
		unset($_SESSION['davincirepcv']);
	}
	if (isset($_SESSION['davincirepper']))
	{
		$periodo = $_SESSION['davincirepper'];
		unset($_SESSION['davincirepper']);
	}
	if (isset($_SESSION['davincirepges']))
	{
		$gestion = $_SESSION['davincirepges'];
		unset($_SESSION['davincirepges']);
	}
	if (isset($_SESSION['davincireptipo']))
	{
		$tipo = $_SESSION['davincireptipo'];
		unset($_SESSION['davincireptipo']);
	}
	
	
	$smarty = new Smarty();
	if($tipo==2)
	$title = "LCV COMPRAS Da VINCI";
	if($tipo==3)
	$title = "LCV VENTAS Da VINCI";	
	
	
	//$hoy = date("d/m/Y   h:i:s a");
	$smarty->template_dir = 'templates';
	$smarty->compile_dir = 'templates_c';
	$smarty->assign('title', $title);
	$smarty->assign('result', $result);
	$smarty->assign('periodo', $periodo);
	$smarty->assign('gestion', $gestion);
	$smarty->assign('tipo', $tipo);
	
	$smarty->display('reporteLcvDaVinci.html');
}
else
header("Location:/sistema/macaws/lcv.php");
?>