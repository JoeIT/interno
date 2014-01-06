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

$smarty->template_dir = '../templates';
$smarty->compile_dir = '../templates_c';

$smarty->assign('title', $title);
$smarty->assign('tipo',$tipo);
$smarty->assign('periodos',$gp);

$smarty->display('seleccionarPeriodo.html');

?>