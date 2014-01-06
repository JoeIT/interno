<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/usuarios.php');
include_once('../../clases/validador.php');

$usuarios=new Usuarios;
$funcion = $_POST['funcion'];

$smarty = new Smarty();
$smarty->template_dir = '../../templates/usuarios/';
$smarty->compile_dir = '../../templates_c';


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {

	//verificamos si se desea modificar la información de un usuario
    if($funcion=="modificar")
  	{
  	   	$id=$_POST['elegido'];
	   	//consultamos los datos del usuario
    	   $informacion=$usuarios->ver_datos_usuario($id);
	      $smarty->assign('id',$informacion[0][codigo]);
           $smarty->assign('nombre',$informacion[0][nombre]);
		   $smarty->assign('apellido',$informacion[0][apellido]);
       	   $smarty->assign('email',$informacion[0][email]);
       	   $smarty->assign('nick',$informacion[0][nick]);
       	   $smarty->assign('grupo',$informacion[0][grupo]);
       	   
       	   $smarty->assign('titulo', 'Modificar Usuario');
           $smarty->display('modificar_usuarios.html');
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
				    $smarty->assign('usuarios',$consulta);
					$smarty->display('lista_usuarios.html');
     		  }
            }

            else
			{
			   //verificamos si se desea modificar los datos de un cliente
	       		if($funcion=="modificar_datos")
  		   		{
				
                    $validar = new Validador();
  					
					if ($validar->validarTodo($_POST['nick'], 1, 20))
    						$error['err_nick'] = "Ingresa el nick del usuario";		
  					   				
  			
				    if (isset($error))
					{	
					   $id=$_POST['elegido'];
					   
					   
					//consultamos los datos del usuario
					   $informacion=$usuarios->ver_datos_usuario($id);
						  $smarty->assign('id',$informacion[0][codigo]);
					   $smarty->assign('nombre',$informacion[0][nombre]);
					   $smarty->assign('apellido',$informacion[0][apellido]);
					   $smarty->assign('email',$informacion[0][email]);
					   $smarty->assign('nick',$informacion[0][nick]);
					   $smarty->assign('grupo',$informacion[0][grupo]);
					   
					   $smarty->assign('titulo', 'Modificar Usuario');
					   $smarty->display('modificar_usuarios.html');
                    }
     				else
  	               {

				   //obtenemos toda la información del formulario de modificación
                   $id = $_POST['elegido'];
  		   		 
     			   $password = $_POST['hash'];
				   $nick = $_POST['nick'];
				   $p = $_POST['password'];
				   $p2 = $_POST['passworddos'];
				   
				 
   
     			   //enviamos la información a la función de modificar usuario
				   $resultado=$usuarios->modificar_usuario($id,$nick,$password);
     			   //obtenemos la lista de usuarios y la enviamos a la plantilla
				   $consulta=$usuarios->consulta_lista_usuarios();
				    $smarty->assign('usuarios',$consulta);
					$smarty->display('lista_usuarios.html');
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
								$smarty->assign('usuarios',$consulta);
								$smarty->display('lista_usuarios.html');
						}
					 	
				}
			}
	}

}
?>