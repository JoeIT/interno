<?php

	include_once('../clases/logeos.php');
	include_once('../clases/avisos.php');
	$logeo=new Logeo;
	$aviso=new Aviso;
	
	session_start();

	if(!isset($_SESSION['logeo']))
	{
		header("Location: /macaws/sistema_de_produccion/controladores/index_logeo.php" );
	}
	else
	{
		$funcion = $_POST['funcion'];
		
		if($funcion=="salir")
		{
			$logeo->salir_session();
			header("Location: /macaws/sistema_de_produccion/controladores/index_logeo.php" );
		}
		else
		{
			$id=$_SESSION['usuario_id'];
            $consulta=$aviso->obtener_lista_avisos($id);
	        $_SESSION['avisos'] = $consulta;
		    
			header("Location: /macaws/sistema_de_produccion/src/index_menu.php");
		}
	}
?>