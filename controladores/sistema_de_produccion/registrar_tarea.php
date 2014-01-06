<?php

session_start();
include_once('../clases/tareas.php');
include_once('../clases/validador.php');
require_once('../includes/seguridad.php');

$tarea=new Tareas;
$validar = new Validador();


if (!empty($_POST))
{
	if ($validar->validarTodo($_POST['descripcion'], 1, 100))
	    $error['err_descripcion'] = "Ingresa la descripcion de la tarea";
		
	$tarea_id=$tarea->verificar_tarea(trim($_POST['descripcion']));

    if($tarea_id!=-1)
		{$error['err_tarea'] = " La tarea ya existe";}

  if (isset($error))
	{
	     $_SESSION['descripcion'] = $_POST['descripcion'];
  	 	 $_SESSION['error']= $error;
		 
		 header("Location: ../src/registrar_tarea.php");
    }
   else
  	 {
    	 $descripcion = $_POST['descripcion'];
		 $area = $_POST['areas'];
		 $resultado=$tarea->nuevo_tarea($descripcion,$area);
			 
		   if($resultado)
		   {

			 $error['err_confirm'] = "El registro se realizo correctamente";
			 $_SESSION['error']= $error;
					
			 header("Location: ../src/registrar_tarea.php");
		   }

	 }
    
  	 
}
else
{
	$_SESSION['tarea']= "true";
	header("Location: ../src/registrar_tarea.php");
}	
?>