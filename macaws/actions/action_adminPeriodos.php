<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "validador.php");
require_once(CLAS . "Gestionperiodo.php");

$url_relativa = "/sistema/macaws/adminPeriodos.php";

$val = new Validador();
$error = null;
$link = new Coneccion();
$link = $link->conectiondb();
$ges = new Gestionperiodo($link);

if($_REQUEST['abrir'])
{
	$cant = $ges->CantidadPeriodosAbiertos();
	if($cant > 1)
	{		
		$error['abrir'] = "ERROR:<br/>Solo puede haber 2 periodos abiertos.";
		$_SESSION['errores'] = $error;
	}
	else
	{
		$ges->open_Periodo($_POST['cod_pg']);
		$_SESSION['macawsadmgt']="Operaci&oacute;n realizada con exito: Apertura periodo.";
	}
}
else
{
	$ges->close_Periodo($_POST['cod_pg']);
	$_SESSION['macawsadmgt']="Operaci&oacute;n realizada con exito: Cerrar periodo.";
}
$_SESSION['mcwgessel'] = $_POST['gestion'];
header("Location: " . $url_relativa);
?>