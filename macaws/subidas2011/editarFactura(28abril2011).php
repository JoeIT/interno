<?php
require_once('ajax/include/loader.php');
session_start();
define('CLAS', "clases/");
require_once(CLAS . "conexion.php");
require_once(CLAS . "Gestionperiodo.php");
require_once(CLAS . "Reportes.php");
require_once(CLAS . "Compra.php");
require_once(CLAS . "Venta.php");

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

$cod_pg = null;
$codfac = null;
$tipo = null;

if(isset($_REQUEST['mcwtipofac']))
{
	$tipo = $_REQUEST['mcwtipofac'];
}
/*if(isset($_SESSION['mcwtipofac']))
{
$tipo = $_SESSION['mcwtipofac'];
unset($_SESSION['mcwtipofac']);
}*/

if(isset($_REQUEST['mcwcodigofac']))
{
	$codfac = $_REQUEST['mcwcodigofac'];
}
/*if(isset($_SESSION['mcwcodigofac']))
{
$codfac = $_SESSION['mcwcodigofac'];
unset($_SESSION['mcwcodigofac']);
}*/

if(isset($_REQUEST['macawscodpg']))
{
	$cod_pg = $_REQUEST['macawscodpg'];
}

/*if(isset($_SESSION['macawscodpg']))
{
$cod_pg = $_SESSION['macawscodpg'];
unset($_SESSION['macawscodpg']);
}*/

if (isset($_SESSION['req']))
{
	$valores = $_SESSION['req'];
	unset($_SESSION['req']);
	$cod_pg = $valores['cod_pg'];
	$codfac = $valores['codfac'];
	$tipo = $valores['tipo'];
}


if(	isset($tipo)&&isset($codfac)&&isset($cod_pg)	)
{
	//echo "entra---tipo:$tipo - pg: $cod_pg - fact: $codfac";
	$link = new Coneccion();
	$link = $link->conectiondb();
	$rep = new Reportes($link);
	$gesper = new Gestionperiodo($link);
	$var = $rep->validarGestionPeriodoCodigo($cod_pg);

	if (isset($_SESSION['errores']))
	{
		$errores = $_SESSION['errores'];
		unset($_SESSION['errores']);
	}

	if(isset($var))
	{
		if(strcasecmp($tipo,"Compra")==0)
		{
			$compra = new Compra($link);
			$valores = $compra->buscarcompra($codfac);
			//echo "<br>COMPRA";
		}
		if(strcasecmp ($tipo,"Venta")==0)
		{
			$venta = new Venta($link);
			$valores = $venta->buscarventa($codfac);
			//echo "<br>Venta";
		}
		list( $valores['gestion'], $valores['periodo'], $valores['fecha'] ) = split( '[/.-]', $valores['fecha']);
		$title = ".:: Editar Factura ::.";
	}
	else
	{	
		$errorperiodo = "la factura se encuentra en un periodo cerrado.<br/>
		 				 No puede modificarla.";
	}
	ajax_register('cargarRazonSocial');
	ajax_process_call();
	
	$gesper = new Gestionperiodo($link);
	$gp = $gesper->obener_periodoGestion($cod_pg);

	$t->template_dir = 'templates';
	$t->compile_dir = 'templates_c';

	$t->assign_by_ref('title', $title);
	$t->assign_by_ref('gestionperiodo',$gp);
	$t->assign_by_ref('val',$valores);
	$t->assign_by_ref('reg_ok',$reg_ok);
	$t->assign_by_ref('error',$errores);
	$t->assign_by_ref('tipo',$tipo);
	$t->assign_by_ref('cod_pg',$cod_pg);
	$t->assign_by_ref('codfac',$codfac);
	$t->assign_by_ref('errorperiodo',$errorperiodo);
	$t->display('editarFactura.html');
}
else
header("Location:/sistema/macaws/lcv.php");
?>