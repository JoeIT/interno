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
	    $error['err_descripcion'] = "Ingrese el nombre de la familia";
		
	$modelo_id=$familia->verificar_modelo(trim($_POST['descripcion']));
    if($modelo_id!=-1)
		{$error['err_familia'] = "La familia ya existe";}

  if (isset($error))
	{
	     $_SESSION['descripcion'] = $_POST['descripcion'];
 	 	 $_SESSION['error']= $error;
		 if ($_POST['popup']!= "true") 
		 { 
		    $_SESSION['familia']= "true"; 
		 }
		 header("Location: ../src/registrar_familia.php");
    }
   else
  	 {
    	 $descripcion = $_POST['descripcion'];
		 $flia = $_POST['familia'];

		   $resultado=$familia->nueva_familia($descripcion);
			 
		   if($resultado)
		   {
			 $error['err_confirm'] = "El registro se realizo correctamente";
			 $_SESSION['error']= $error;
			 
			 if ($_POST['popup']!= "true")  
			 { 
				$_SESSION['familia']= "true"; 
			 }
			 header("Location: ../src/registrar_familia.php");
		  }
	 }
}
else
{
	$_SESSION['familia']= "true";
	header("Location: ../src/registrar_familia.php");
}	
?>
