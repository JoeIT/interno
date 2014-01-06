<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "validador.php");
require_once(CLAS . "Gestionperiodo.php");

$url_relativa = "/sistema/macaws/periodoGestion.php";
$url_relativa2 = "/sistema/macaws/seleccionarGestion.php";
$url_relativa3 = "/sistema/macaws/lvc.php";

if(isset($_SESSION['usuario_id']))
{
$val = new Validador();
$error = null;
if(isset($_REQUEST['gestion']))
{
	$gestion_selec = $_REQUEST['gestion'];
	$_SESSION['mcwgessel']=$gestion_selec;
	if(	$val->validarNumerosDecimales($_POST['iva'],1,10)	)
	$error['iva'] = "Cantidad invalida.";
	if(	empty($_POST['iva']))
	$error['iva'] = "Cantidad invalida.";
	if ((isset($error)))
	{
		$_SESSION['errores'] = $error;
	}
	else
	{
		$link = new Coneccion();
		$link = $link->conectiondb();
		$gesper = new Gestionperiodo($link);
		$gesper->insert_PeriodoGestion($gestion_selec,$_POST['periodo'],$_POST['obs'],$_POST['iva'],$_SESSION['usuario_id']);
		$_SESSION['macawsregperi']="regOkPeriodo";	
	}	
	header("Location: " . $url_relativa);
}
else
header("Location: " . $url_relativa2);
}
else
header("Location: " . $url_relativa3);
?>