<?php

	include_once('../clases/familias.php');
	require_once('../includes/seguridad.php');
	$familia=new Familia;
	
	session_start();
	
	if(!isset($_SESSION['nombre']))
	{
		header("Location: /macaws/sistema_de_produccion/controladores/index_menu.php");
	}
	else
	{
		$_SESSION['modelo']= "true";
		header("Location: /macaws/sistema_de_produccion/src/index_modelo.php");
	}
?>