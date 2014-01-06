<?php
require_once('ajax/include/loader.php');
session_start();
define('CLAS', "clases/");

require_once(CLAS . "conexion.php");
require_once(CLAS . "Gestionperiodo.php");
require_once(CLAS . "sucursal.php");

function cargarRazonSocial()
{
	if(isset($_REQUEST['nit'])&&!empty($_REQUEST['nit']))
	{
		$nit = $_REQUEST['nit'];		
		$link = new Coneccion();
		$link = $link->conectiondb();
		$gesper = new Gestionperiodo($link);
		echo  $gesper->obtenerRazonSocialNit($nit);
	}
}

$link = new Coneccion();
$link = $link->conectiondb();

//cargar automatico
$suc = new Sucursal($link);
$sucursales = $suc->busqueda_sucursal();

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

ajax_register('cargarRazonSocial');
ajax_process_call();

$gp = $gesper->obener_periodoGestion($cod_pg);

$t->template_dir = 'templates';
$t->compile_dir = 'templates_c';

$t->assign_by_ref('sucursal_name',$_SESSION['sucursal_name']);
$t->assign_by_ref('title', $title);
$t->assign_by_ref('gestionperiodo',$gp);
$t->assign_by_ref('val',$valores);
$t->assign_by_ref('reg_ok',$reg_ok);
$t->assign_by_ref('error',$errores);
//cargando sucursales
$t->assign_by_ref('sucursales',$sucursales);
//cargar valor del id de sucursal para cargar la cabecera correspondiente a central o sucursal
$t->assign_by_ref('id',$_SESSION['sucursal_id']);
$t->display('venta.html');
?>