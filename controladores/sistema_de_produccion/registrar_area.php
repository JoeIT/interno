<?php

session_start();
include_once('../clases/areas.php');
include_once('../clases/validador.php');

$area=new Areas;
$validar = new Validador();


if (!empty($_POST))
{
	if ($validar->validarTodo($_POST['descripcion'], 1, 100))
	    $error['err_descripcion'] = "Ingresa la descripcion de la area";
		
	$area_id=$area->verificar_area(trim($_POST['descripcion']));

    if($area_id!=-1)
		{$error['err_area'] = " La area ya existe";}

  if (isset($error))
	{
	     $_SESSION['descripcion'] = $_POST['descripcion'];
  	 	 $_SESSION['error']= $error;
		 
		 header("Location: ../src/registrar_area.php");
    }
   else
  	 {
    	 $descripcion = $_POST['descripcion'];
		 $resultado=$area->nuevo_area($descripcion);
			 
		   if($resultado)
		   {

			 $error['err_confirm'] = "El registro se realizo correctamente";
			 $_SESSION['error']= $error;
					
			 header("Location: ../src/registrar_area.php");
		   }

	 }
    
  	 
}
else
{
	$_SESSION['area']= "true";
	header("Location: ../src/registrar_area.php");
}	
?>