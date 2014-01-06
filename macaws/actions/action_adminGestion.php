<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "validador.php");
require_once(CLAS . "Gestion.php");

$url_relativa = "/sistema/macaws/adminGestion.php";

$val = new Validador();
$error = null;
$link = new Coneccion();
$link = $link->conectiondb();
$ges = new Gestion($link);

if($_REQUEST['abrir'])
{
	$cant = $ges->totalGestionesAbiertas();
	if($cant > 1)
	{		
		$error['abrir'] = "ERROR:<br/>Solo puede haber 2 gestiones abiertas.";
		$_SESSION['errores'] = $error;
	}
	else
	{
		$ges->open_gestion($_POST['gestion']);
		$_SESSION['macawsadmgt']="Operaci&oacute;n Realizada Satisfactoriamente: Apertura gesti&oacute;n ".$_POST['gestion'];
	}
}
else
{
	$ges->close_gestion($_POST['gestion']);
	$_SESSION['macawsadmgt']="Operaci&oacute;n Realizada Satisfactoriamente: Cierre gesti&oacute;n ".$_POST['gestion'];
}
header("Location: " . $url_relativa);
?>