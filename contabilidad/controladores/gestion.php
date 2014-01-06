<?php
session_start();
define('CLAS', "../clases");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../clases/responsable.php');
include_once('../clases/gestion.php');
include_once('../clases/includes/validador.php');


$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';

$responsable= new Responsable();
$gestion= new Gestion();
if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
		
	if($_GET['funcion']== "validar")
	{    
		 $error="";
		 $validar = new Validador();
		
		 if ($validar->validarTodo($_GET['gestion'], 1, 100))
			 $error['err_gestion'] = "Ingresa el nombre de la gestion";
		
			
		 
	     
		if($error!="")
		{
			   $gest = $_GET['gestion'];
			   $fecha_inicio = date("Y-m-d"); 
			   $fecha_fin = date("Y-m-d"); 
			   $smarty->assign('gestion',$gestion);
			   $smarty->assign('fecha_inicio',$fecha_inicio);
			   $smarty->assign('fecha_fin',$fecha_fin);
			   
			     
		  		if ($_GET['popup'] == "true") 
			  	 $smarty->assign('menu',"false");
				
			  	 $smarty->assign('errores',$error);
		 		 $smarty->display('registrar_gestion.html');
		}
		else
		{ 
		     $gest= $_GET['gestion'];
		 	 $fecha_inicio = $_GET['fecha_inicio'];
		     $fecha_fin = $_GET['fecha_fin'];
		     
		  //echo "enviamos la informacion a la función de adicionar responsable";
		  $resultado=$gestion->insertar_gestion($gest,$fecha_inicio,$fecha_fin);
		   if($resultado!=0)
		   {
			  $error['err_confirm'] = "El registro se realizo correctamente";
			 //$_SESSION['error']= $error;
			 	if ($_GET['popup'] == "true")  
				 { 
			  	 $smarty->assign('menu',"true");
				
				 }
			 
				 $smarty->assign('errores',$error);
				 $smarty->display('registrar_gestion.html');
			
		   
		   
		}
		

		
		 }
	}
	else
		{ 
	   
		if($_GET['popup']=="true")
		   $smarty->assign('menu',"false");
		else
		
		$smarty->assign('menu',"true");
		
		
		$smarty->display('registrar_gestion.html');
		}

		
}


?>