<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Gestionperiodo.php");

$smarty = new Smarty();
$link = new Coneccion();
$link = $link->conectiondb();

$gesper = new Gestionperiodo($link);
$title = ".:: Registrar venta ::.";
$cod_pg = null;

if(isset($_REQUEST['cod_pg']))
{
	$cod_pg = $_REQUEST['cod_pg'];
}
if(isset($_SESSION['MacawsCodpg']))
{
	$cod_pg = $_SESSION['MacawsCodpg'];
	unset($_SESSION['MacawsCodpg']);
}

if (isset($_SESSION['errores'])) 
{
	$errores = $_SESSION['errores'];
	unset($_SESSION['errores']);
}

if (isset($_SESSION['req'])) 
{
	$valores = $_SESSION['req'];
	unset($_SESSION['req']);	
	$cod_pg = $valores['cod_pg'];
}

if (isset($_SESSION['macawsregventa'])) 
{
	$reg_ok = $_SESSION['macawsregventa'];
	unset($_SESSION['macawsregventa']);		
}


$gp = $gesper->obener_periodoGestion($cod_pg);

$smarty->template_dir = '../templates';
$smarty->compile_dir = '../templates_c';

$smarty->assign('title', $title);
$smarty->assign('gestionperiodo',$gp);
$smarty->assign('val',$valores);
$smarty->assign('reg_ok',$reg_ok);
$smarty->assign('error',$errores);
$smarty->display('venta.html');
?>