<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "validador.php");
require_once(CLAS . "Gestion.php");

$url_relativa = "/sistema/macaws/abrirGestion.php";
$url_relativa2 = "/sistema/macaws/seleccionarGestion.php";
$url_relativa3 = "/sistema/macaws/lvc.php";

if(isset($_SESSION['usuario_id']))
{
$val = new Validador();
$error = null;

	if(	$val->validarNumeros($_POST['gestion'],4,4) )
	$error['gestion'] = "Gesti&oacute;n invalida.";
	if(	empty($_POST['gestion']))
	$error['gestion'] = "Gesti&oacute;n invalida.";
	if ((isset($error)))
	{
		$_SESSION['errores'] = $error;
	}
	else
	{
		$link = new Coneccion();
		$link = $link->conectiondb();
		$ges = new Gestion($link);
		$exist = $ges->exiteGestionEstado($_POST['gestion']);
		if(isset($exist))
		{
			$error['closedgestion'] = "ERROR:<br/>La gesti&oacute;n que intenta abrir ya esta registrada.";
			$_SESSION['errores'] = $error;
		}
		else
		{
			$ges->insertGestion($_POST['gestion'],$_POST['obs'],$_SESSION['usuario_id']);
			$_SESSION['macawsreggestion']="regOkGestion";
		}
	}

	$_SESSION['req'] = $_POST;
	header("Location: " . $url_relativa);
}
else
header("Location: " . $url_relativa3);
?>