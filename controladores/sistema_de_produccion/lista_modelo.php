<?php

session_start();
include_once('../clases/familias.php');
include_once('../clases/validador.php');

$familia=new Familia;
$funcion = $_POST['funcion'];
$id = $_POST['elegido'];

	//verificamos si se desea modificar la información de la chapa
    if($funcion=="modificar")
  	{
  	   $id=$_POST['elegido'];
  	   //consultamos los datos de la chapa
  	   $consulta=$familia->consultar_modelo($id);  	
	   $_SESSION['informacion'] = $consulta;
	   header("Location: ../src/modificar_modelo.php" );      	 		   
  	}
  	else
    {    
	    if($funcion=="eliminar")
	  	{
	  	   $id=$_POST['elegido'];
           $consulta=$familia->consultar_modelo($id);  	
		   $cadena=$familia->obtener_familia($id);
		   $_SESSION['informacion'] = $consulta;
 	       $_SESSION['cadena'] = $cadena;
           header("Location: ../src/eliminar_modelo.php");      	 		   
		}
		else
		{
			if($funcion=="modificar_datos")
			{

				$validar = new Validador();
				if ($validar->validarTodo($_POST['descripcion'], 1, 100))
					$error['err_descripcion'] = "Ingresa la descripcion del modelo";
					
				$modelo_id=$familia->verificar_modelo(trim($_POST['descripcion']));
			    if($modelo_id!=-1)
					{$error['err_modelo'] = "El modelo ya existe";}

				if (isset($error))
				{
	  				 $_SESSION['id'] = $_POST['elegido'];
					 $_SESSION['descrip'] = $_POST['descrip_aux'];
 					 $_SESSION['descrip_aux'] = $_POST['descrip_aux'];
					 $_SESSION['error'] = $error;
					 header("Location: ../src/modificar_modelo.php");
				}
				else
			   {

			   //obtenemos toda la información del formulario de modificación
			   $descripcion = $_POST['descripcion'];
			   //enviamos la información a la función de modificar chapa
			   $resultado=$familia->modificar_modelo($id,$descripcion);
			   //obtenemos la lista de clientes y la enviamos a la plantilla
			   $consulta= $familia->consulta_lista_modelo();
			   $_SESSION['lista'] = $consulta;
				header("Location: ../src/lista_modelo.php");
			  }
			}
			else
			{
				if ($funcion=="eliminar_datos")
				{
					$id = $_POST['elegido'];	
					$resultado=$familia->eliminar_modelo($id);
					$consulta= $familia->consulta_lista_modelo();
		 		    $_SESSION['lista'] = $consulta;
					header("Location: ../src/lista_modelo.php");
				}
				else
				{
					if ($funcion=="buscar")
					{

						$id = $_POST['parametro'];
						if ($_POST['opcion']=="modelo")
							$resultado=$familia->buscar("descripcion",$id);
						else
							$resultado=$familia->buscar("familia",$id);
							
						$_SESSION['lista'] = $resultado;
						header("Location: ../src/lista_modelo.php");
					}
					else
					{
						$consulta= $familia->consulta_lista_modelo();
						$_SESSION['lista'] = $consulta;
						header("Location: ../src/lista_modelo.php");
					}
				}
				
			}
		}
	}
?>

