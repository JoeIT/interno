<?php

session_start();
include_once('../clases/familias.php');
include_once('../clases/validador.php');
require_once('../includes/seguridad.php');

$familia=new Familia;
$validar = new Validador();


if (!empty($_POST))
{
	if ($validar->validarTodo($_POST['descripcion'], 1, 100))
	    $error['err_descripcion'] = "Ingrese el nombre del modelo";
		
	if 	($_POST['nueva']==false )
	{
		if ($validar->validarTodo($_POST['familia'], 1, 100))
	    $error['err_familia'] = "Ingrese el nombre de la familia";		
	}
		
	$modelo_id=$familia->verificar_modelo(trim($_POST['descripcion']));
    if($modelo_id!=-1)
		{$error['err_modelo'] = " El modelo ya existe";}

  if (isset($error))
	{
	     $_SESSION['descripcion'] = $_POST['descripcion'];
		 $_SESSION['familia'] = $_POST['familia'];
  	 	 $_SESSION['error']= $error;
		 if ($_POST['popup']!= "true") 
		 { 
		    $_SESSION['modelo']= "true"; 
		 }
		 header("Location: ../src/registrar_modelo.php");
    }
   else
  	 {
    	 $descripcion = $_POST['descripcion'];
		 $flia = $_POST['familia'];

		 if($_POST['nueva']==false)
  		    $resultado=$familia->nuevo_modelo($descripcion,$flia);
		else
			$resultado=$familia->nuevo_modelo($descripcion," ");
			 
		   if($resultado)
		   {
			 $error['err_confirm'] = "El registro se realizo correctamente";
			 $_SESSION['error']= $error;
			 
			 if ($_POST['popup']!= "true")  
			 { 
				$_SESSION['modelo']= "true"; 
			 }
			 header("Location: ../src/registrar_modelo.php");
		  }
	 }
}
else
{
	$_SESSION['modelo']= "true";
	header("Location: ../src/registrar_modelo.php");
}	
?>
