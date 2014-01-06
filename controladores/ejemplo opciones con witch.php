<?php
session_start();

//referencia a las clases
include_once('../clases/logeos.php');
$logeo=new Logeo;

if(!isset($_SESSION['logeo'])){
	header("Location: index_logeo.php");
} else {
	//aqui hacer las acciones
	switch ($_GET['op']){
		case 1 :{
			//nueva orden
			header("Location: ../src/ordenproduccion.php");
			break;
		}
		case 2 :{
			//buscar orden
			break;
		}
		case 3 :{
			//revisar orden
			break;
		}
	}
	echo "oden de produccion: ".$_GET['op'];
}
?>