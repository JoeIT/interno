<?php

session_start();
include_once('../../clases/usuarios.php');
include_once('../../clases/validador.php');
require_once('../includes/seguridad.php');


$usuarios=new Usuarios;
$funcion = $_POST['funcion'];


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {

	//verificamos si se desea modificar la información de un usuario
    if($funcion=="modificar")
  	{
  	   $id=$_POST['elegido'];
	   //consultamos los datos del usuario
       $consulta=$usuarios->ver_datos_usuario($id);
       $_SESSION['informacion'] = $consulta;
	   header("Location: ../src/modificar_usuarios.php" );
  	}
  	else
    {    //verificamos si la operacion a realizar es la de eliminar
	       if($funcion=="eliminar")
  		   {
  		      //recuperamos el id del usuario a eliminar
              $id=$_POST['elegido'];
              //enviamos el id a la funcion de eliminar usuario
              $resultado=$usuarios->eliminar_usuario($id);
              //verificamos si la operación se realizo con éxito
              if($resultado)
    		  {
    		    $consulta= $usuarios->consulta_lista_usuarios();
			       $_SESSION['lista'] = $consulta;
				    header("Location: " . $url_relativa);
     		  }
            }

            else
			{
			   //verificamos si se desea modificar los datos de un cliente
	       		if($funcion=="modificar_datos")
  		   		{

                    $validar = new Validador();
  					if ($validar->validarTodo($_POST['nombre'], 1, 100))
    						$error['err_nombre'] = "Ingresa el nombre del usuario";
  					if ($validar->validarTodo($_POST['email'], 1, 100))
    						$error['err_email'] = "Ingresa el email del usuario";
  					if ($validar->validarTodo($_POST['categoria'], 1, 100))
    						$error['err_categoria'] = "Ingresa la categoria del usuario";
					if ($validar->validarTodo($_POST['nick'], 1, 20))
    						$error['err_nick'] = "Ingresa el nick del usuario";		
  					   				
  					else
  					{
  							if($validar->validarEmail($_POST['email']))
         						$error['err_email'] = "Email no valido, vuelva a ingresarlo";
  					}

				    if (isset($error))
					{
					     $_SESSION['nombre'] = $_POST['nombre'];
		 				 $_SESSION['email'] = $_POST['email'];
		 				 $_SESSION['categoria']=  $_POST['categoria'];
		 				 $_SESSION['nick']= $_POST['nick'];
		 				 $_SESSION['error']= $error;
		 				 header("Location: ../src/modificar_usuarios.php");
                    }
     				else
  	               {

				   //obtenemos toda la información del formulario de modificación
                   $id = $_POST['elegido'];
  		   		   $nombre = $_POST['nombre'];
     			   $email = $_POST['email'];
     			   $categoria = $_POST['categoria'];
				   $nick = $_POST['nick'];
   
     			   //enviamos la información a la función de modificar usuario
				   $resultado=$usuarios->modificar_usuario($id,$nick,$nombre,$email,$categoria);
     			   //obtenemos la lista de usuarios y la enviamos a la plantilla
				   $consulta=$usuarios->consulta_lista_usuarios();
				   $_SESSION['lista'] = $consulta;
				    header("Location: " . $url_relativa);
	  		      }
	  		    }
		  		else
		  		{
					   if($funcion=="buscar")
						{
							$cadena=$_POST['parametro'];
							$opcion=$_POST['opcion'];
							$consulta= $usuarios->consultar_busqueda($cadena,$opcion);
							$_SESSION['lista'] = $consulta;
							header("Location: " . $url_relativa);
						}
						else
						{
							$consulta= $usuarios->consulta_lista_usuarios();
							$_SESSION['lista'] = $consulta;
							header("Location: " . $url_relativa);
						}
					 	
				}
			}
	}

}
?>

