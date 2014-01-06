<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Gestionperiodo.php");


$gestion = null;
if(isset($_REQUEST['mcwgessel']))
{
	if(isset($_GET['mcwgessel']))
	{
	$gestion = $_GET['mcwgessel'];
	$gestion = base64_decode($gestion);
	}
	if(isset($_POST['mcwgessel']))
	{
	$gestion = $_POST['mcwgessel'];	
	}		
}
if(isset($_SESSION['mcwgessel']))
{
	$gestion = $_SESSION['mcwgessel'];
	unset($_SESSION['mcwgessel']);
}

if(isset($gestion))
{
	$smarty = new Smarty();	
	$title = ".:: Operaciones Periodo ::.";

	$smarty->template_dir = '../templates';
	$smarty->compile_dir = '../templates_c';
	$smarty->assign('title', $title);	
	$smarty->assign('gestion',$gestion);
	$smarty->display('operacionesPeriodo.html');
}
else
header("Location:/macaws/src/seleccionarGestion.php");
?>