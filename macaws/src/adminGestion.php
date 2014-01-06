<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Gestion.php");

$smarty = new Smarty();
$link = new Coneccion();
$link = $link->conectiondb();

if(isset($_SESSION['macawsadmgt']))
{
	$reg_ok=$_SESSION['macawsadmgt'];
	unset($_SESSION['macawsadmgt']);
}

if (isset($_SESSION['errores'])) 
{
	$errores = $_SESSION['errores'];
	unset($_SESSION['errores']);
}

$ges = new Gestion($link);
$title = ".:: Abrir - Cerrar Gestiones ::.";

$todas_ges = $ges->listarTodasGestiones();

$smarty->template_dir = '../templates';
$smarty->compile_dir = '../templates_c';

$smarty->assign('title', $title);

$smarty->assign('val',$valores);
$smarty->assign('reg_ok',$reg_ok);
$smarty->assign('error',$errores);
$smarty->assign('gestiones',$todas_ges);

$smarty->display('adminGestion.html');
?>