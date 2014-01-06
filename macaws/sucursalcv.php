<?php
session_start(); 
define('CLAS', "clases/");
define('SMARTY_DIR', "../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
$smarty = new Smarty();

$title = ".:: Libro de Compras y Ventas ::.";
$title2 = ".:: Bienvenidos  ".$_GET['name']."::.";

$smarty->template_dir = 'templates';
$smarty->compile_dir = 'templates_c';
$smarty->assign('title', $title);

$smarty->assign('title_sucursal', $title2);

//agregando a sesion las variables para el manejo de sucursales
$_SESSION["sucursal_id"] = $_GET['cod'];
$_SESSION["sucursal_name"] =$_GET['name'];

if ($_GET['cod']!=1)
$smarty->display('headerAuxiliar.html');
else
$smarty->display('header.html');
?>