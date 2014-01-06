<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Gestionperiodo.php");

$gestion = null;
if(isset($_REQUEST['mcwgessel']))
$gestion = $_REQUEST['mcwgessel'];

if(isset($_SESSION['mcwgessel']))
{
	$gestion = $_SESSION['mcwgessel'];
	unset($_SESSION['mcwgessel']);
}

if(isset($gestion))
{
	$smarty = new Smarty();
	$link = new Coneccion();
	$link = $link->conectiondb();

	if(isset($_SESSION['macawsadmpr']))
	{
		$reg_ok=$_SESSION['macawsadmpr'];
		unset($_SESSION['macawsadmpr']);
	}

	if (isset($_SESSION['errores']))
	{
		$errores = $_SESSION['errores'];
		unset($_SESSION['errores']);
	}


	$title = ".:: Abrir - Cerrar Periodos ::.";

	$gesper = new Gestionperiodo($link);
	$periodos_gestion = $gesper->todosPeriodoGestion($gestion);

	$smarty->template_dir = '../templates';
	$smarty->compile_dir = '../templates_c';

	$smarty->assign('title', $title);
	
	$smarty->assign('val',$valores);
	$smarty->assign('reg_ok',$reg_ok);
	$smarty->assign('error',$errores);
	$smarty->assign('periodos',$periodos_gestion);
	$smarty->assign('gestion',$gestion);
	$smarty->display('adminPeriodo.html');
}
else
header("Location:/macaws/src/seleccionarGestion.php");
?>