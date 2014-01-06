<?php
session_start(); 
define('CLAS', "clases/");
define('SMARTY_DIR', "../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "sucursal.php");

$link = new Coneccion();
$link = $link->conectiondb();
//cargar automatico
$suc = new Sucursal($link);
$sucursales = $suc->busqueda_sucursal();

$smarty = new Smarty();
$title = ".:: Libro de Compras y Ventas ::.";
$smarty->template_dir = 'templates';
$smarty->compile_dir = 'templates_c';
$smarty->assign('title', $title);
//cargando el grupo id para identificar al administrador
$smarty->assign('grupo',$_SESSION['grupo_id']);

//cargando sucursales
$smarty->assign('sucursales',$sucursales);
$smarty->display('gestionarLCV.html');
?>