<?php

session_start();
include_once('../clases/areas.php');
include_once('../clases/validador.php');

$area=new Areas;
$funcion = $_POST['funcion'];



	//verificamos si se desea modificar la información de la area
    if($funcion=="modificar")
  	{
  	   $id=$_POST['elegido'];
  	   //consultamos los datos de la area
       $consulta=$area->consultar_area($id);
       $_SESSION['informacion'] = $consulta;
	   header("Location: ../src/modificar_area.php" );      	 		   
  	}
  	else
    {    
	    if($funcion=="eliminar")
	  	{
	  	   $id=$_POST['elegido'];

           $consulta=$area->eliminar_area($id);
		   $consulta=$area->consulta_lista_area();
	       $_SESSION['lista'] = $consulta;
		   header("Location: ../src/lista_area.php" );      	 		   
		}
		else
		{
			if($funcion=="modificar_datos")
			{

				$validar = new Validador();
				if ($validar->validarTodo($_POST['descripcion'], 1, 100))

						$error['err_descripcion'] = "Ingresa la descripcion de la area";
					
				$area_id=$area->verificar_area(trim($_POST['descripcion']));
			    if($area_id!=-1)
					{$error['err_area'] = " La area ya existe";}

				if (isset($error))
				{
	  				 $_SESSION['id'] = $_POST['elelgido'];
					 $_SESSION['descrip'] = $_POST['descrip_aux'];
 					 $_SESSION['descrip_aux'] = $_POST['descrip_aux'];
					 $_SESSION['error'] = $error;
					 header("Location: ../src/modificar_area.php");
				}
				else
			   {

			   //obtenemos toda la información del formulario de modificación
		  	   $id=$_POST['elegido'];
			   $descripcion = $_POST['descripcion'];
			   //enviamos la información a la función de modificar area
			   $resultado=$area->modificar_area($id,$descripcion);
			   //obtenemos la lista de clientes y la enviamos a la plantilla
			   $consulta= $area->consulta_lista_area();
			   $_SESSION['lista'] = $consulta;
				header("Location: ../src/lista_area.php");
			  }
			}
			else
			{
				$consulta= $area->consulta_lista_areas();
				$_SESSION['lista'] = $consulta;
				header("Location: ../src/lista_area.php");
			}
		}
	}
?>

