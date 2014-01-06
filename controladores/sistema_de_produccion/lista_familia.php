<?php

session_start();
include_once('../clases/familias.php');
require_once('../includes/seguridad.php');
include_once('../clases/validador.php');

$familia=new Familia;
$validar = new Validador();
$funcion = $_POST['funcion'];

$id = $_POST['elegido'];

	//verificamos si se desea modificar la información de la familia
    if($funcion=="modificar")
  	{
  	   $family=$_POST['elegido'];
 	   //sacamos el id de la familia
  	   $consulta=$familia->verifica_familia_valida(trim($family));
	   //sacamos todos procuctos de ese id familia 
	   $info=$familia->obtener_lista_modelos($consulta);
	   $_SESSION['informacion'] = $info;
	   header("Location: ../src/modificar_familia.php");      	 		   
  	}
  	else
    {    
	    if($funcion=="modificar_datos")
			{
				$id=$_POST['elegido'];
	            $consulta=$familia->consultar_modelo($id);				
				$cadena=$familia->obtener_familia($id);
				$_SESSION['informacion'] = $consulta;
				$_SESSION['cadena'] = $cadena;
				header("Location: ../src/modificar_familia_producto.php");
			}
			else
			{
				if ($funcion=="eliminar_datos")
				{
					$id = $_POST['elegido'];	
					$resultado=$familia->eliminar_familia($id);
					$consulta= $familia->consulta_lista_familia();
		 		    $_SESSION['lista'] = $consulta;
					header("Location: ../src/lista_familia.php");
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
						if ($funcion=="nueva_familia")
						{
							$modelo = $_POST['fam_nueva'];
							$resultado=$familia->cambiar_familia($modelo);
							header("Location: ../src/modificar_familia_producto.php");
						}
						else
						{
							if ($funcion=="cambiar_familia")
							{
								$fam = $_POST['texto_modelo'];
								$modelo = $_POST['fam_nueva'];
								$nueva_fam=$familia->obtener_id_familia($fam);
								$resultado=$familia->cambiar_nueva_familia($modelo,$nueva_fam);
								header("Location: ../src/modificar_familia_producto.php");
							}
							else
							{
								$consulta= $familia->consulta_lista_familias();
								$_SESSION['lista'] = $consulta;
								header("Location: ../src/lista_familia.php");
							}
						}
					}
				}
			}
	}
?>

