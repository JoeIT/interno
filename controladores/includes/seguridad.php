<?php
session_start();

include_once('../../clases/permisos.php');

if($_SESSION["logeo"]!=true){
	header("Location: index.php");
} else {
	$url = $_SERVER['PHP_SELF'];
	$grupo = $_SESSION['grupo_id'];
	$seguridad = new Permiso;
	$seguridad = $seguridad->listado($url,$grupo);
/*
	if (!$seguridad){
		echo "<br><h1>usted no tiene permiso y sera redireccionado</h1>";
		//header("Location: ../src/index.php");
		exit;
	}*/
}
?>