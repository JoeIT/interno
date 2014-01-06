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

$title = ".:: Seleccionar Gestion ::.";
$gesper = new Gestionperiodo($link);

$gestiones_abiertas= $gesper->obener_periodoGestionOpened3();

$smarty->template_dir = 'templates';
$smarty->compile_dir = 'templates_c';

$smarty->assign('title', $title);
$smarty->assign('gestabi',$gestiones_abiertas);

$smarty->display('seleccionarGestion.html');
?>