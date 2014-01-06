<?php
session_start(); 
define('CLAS', "clases/");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Gestionperiodo.php");

$smarty = new Smarty();
$link = new Coneccion();
$link = $link->conectiondb();
$tipo = null;
$gesper = new Gestionperiodo($link);
$title = null;
if(	isset($_REQUEST['tactMacReg'])	)
{
	$tipo = $_REQUEST['tactMacReg'];
	if( strcmp($tipo,"1")!=0 && strcmp($tipo,"0")!=0)
	{
		$url_relativa = "/macaws/src/lcv.php";
		header("Location: " . $url_relativa);
	}
	else 
	{
		if(strcmp($tipo,"1")==0)
		$title = ".:: Registrar Compra ::.";
		else
		$title = ".:: Registrar Venta ::.";
	}
}

$gp = $gesper->obener_periodoGestionOpened();
//permite enviar nombre de sucursal en variable smarty para la interfaz
$smarty->assign('sucursal_name',$_SESSION['sucursal_name']);

$smarty->template_dir = 'templates';
$smarty->compile_dir = 'templates_c';

$smarty->assign('title', $title);
$smarty->assign('tipo',$tipo);
$smarty->assign('periodos',$gp);

//cargar valor del id de sucursal para cargar la cabecera correspondiente a central o sucursal
$smarty->assign('id',$_SESSION['sucursal_id']);
$smarty->display('seleccionarPeriodo.html');

?>