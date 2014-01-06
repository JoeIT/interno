<?php
session_start();
define('CLAS', "../clases");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../clases/responsable.php');
include_once('../clases/area.php');
include_once('../clases/includes/validador.php');


$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';

$responsable= new Responsable();
$area= new Area();
if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	
	$ar=$area->listararea();
	$smarty->assign('area', $ar);
	
	if($_GET['funcion']== "validar")
	{    
		 $error="";
		 $validar = new Validador();
		 if ($validar->validarTodo($_GET['nombre'], 1, 100))
			 $error['err_nombre'] = "Ingresa el nombre del responsable";
		 if ($validar->validarTodo($_GET['apellido'], 1, 100))
			 $error['err_apell'] = "Ingresa el apellido del responsable";
		 if ($validar->validarTodo($_GET['cargo'], 1, 100))
			$error['err_cargo'] = "Ingresa el cargo";
			
		   if ($_GET['area']=='selc')	
		 	{
			$error['err_area'] = "Selecione area";
	     	}
	     
	     if ($validar->validarTodo($_GET['ci'], 1, 100))
			$error['err_ci'] = "Ingresa el ci ";
			
		 if ($validar->validarNumerosDecimales($_GET['ci'], 1, 100))
	     	{
	     	$error['err_ci'] = "Introduzca  numeros en  ci";
	        }
			
			if ($_GET['telefono']!='')
			{
				
				 if ($validar->validarNumerosDecimales($_GET['telefono'], 1, 100))
	     		{
	     		$error['err_telefono'] = "Introduzca  numeros en telefono";
	    		 }	
				
			}
			
			if ($_GET['celular']!='')
			{
				
				 if ($validar->validarNumerosDecimales($_GET['celular'], 1, 100))
	     		{
	     		$error['err_celular'] = "Introduzca  numeros en  celular";
	    		 }	
				
			}
	     
		if($error!="")
		{
			   $nombre = $_GET['nombre'];
			   $apellido = $_GET['apellido'];
			   $cargo = $_GET['cargo'];
			   $ci=$_GET['ci'];
		       $telefono=$_GET['telefono'];
		       $celular=$_GET['celular'];
		       $area=$area->listararea();
		       
			   
			   $smarty->assign('nombre',$nombre);
			   $smarty->assign('apellido',$apellido);
			   $smarty->assign('cargo',$cargo);
			   $smarty->assign('ci',$ci);
			   $smarty->assign('telefono',$telefono);
			   $smarty->assign('celular',$celular);
			   
			   $smarty->assign('area',$area);
			     
		  		if ($_GET['popup'] == "true") 
			  	 $smarty->assign('menu',"false");
				
			  	 $smarty->assign('errores',$error);
		 		 $smarty->display('registrar_responsable.html');
		}
		else
		{ 
		     $nombre = $_GET['nombre'];
		 	 $apellido = $_GET['apellido'];
		     $cargo = $_GET['cargo'];
		     $ci=$_GET['ci'];
		     $telefono=$_GET['telefono'];
		     $celular=$_GET['celular'];
		     $area=$_GET['area'];
		   
		     
		     
		  //echo "enviamos la informacion a la función de adicionar responsable";
		  $resultado=$responsable->insertar_responsable($nombre,$apellido,$cargo,$telefono,$celular,$area,$ci);
		   if($resultado!=0)
		   {
			  $error['err_confirm'] = "El registro se realizo correctamente";
			 //$_SESSION['error']= $error;
			 	if ($_GET['popup'] == "true")  
				 { 
			  	 $smarty->assign('menu',"true");
				
				 }
			 
				 $smarty->assign('errores',$error);
				 $smarty->display('registrar_responsable.html');
			
		   
		   
		}
		

		
		 }
	}
	else
		{ 
	   
		if($_GET['popup']=="true")
		   $smarty->assign('menu',"false");
		else
		
		$smarty->assign('menu',"true");
		
		
		$smarty->display('registrar_responsable.html');
		}

		
}


?>