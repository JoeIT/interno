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

$ges = new Gestion($link);
$title = ".:: Registrar Gestion ::.";

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

if (isset($_SESSION['macawsreggestion'])) 
{
	$reg_ok = $_SESSION['macawsreggestion'];
	unset($_SESSION['macawsreggestion']);		
}

$gest_abiertas = $ges->listarGestionesAbiertas();
$todas_ges = $ges->listarTodasGestiones();
$cantGesAb = $ges->totalGestionesAbiertas();
$smarty->template_dir = '../templates';
$smarty->compile_dir = '../templates_c';

$smarty->assign('title', $title);

$smarty->assign('val',$valores);
$smarty->assign('reg_ok',$reg_ok);
$smarty->assign('error',$errores);
$smarty->assign('gestiones',$todas_ges);
$smarty->assign('gesopen',$cantGesAb);
$smarty->assign('gestionesab',$gest_abiertas);

$smarty->display('abrirGestion.html');
?>