<?php
session_start();
include_once('../clases/usuarios.php');

include_once('../clases/areas.php');
include_once('../clases/validador.php');
require_once('../includes/seguridad.php');
$usuarios=new Usuarios;
$areas=new Areas;

if($_POST['funcion']== "validar")
{
  $validar = new Validador();
  if ($validar->validarTodo($_POST['nombre'], 1, 100))
    $error['err_nombre'] = "Ingresa el nombre del usuario";
  if ($validar->validarTodo($_POST['nick'], 1, 100))
    $error['err_nick'] = "Ingresa el nick del usuario";
  if ($validar->validarTodo($_POST['categoria'], 1, 100))
    $error['err_categoria'] = "Ingresa la categoria del usuario";
  if ($validar->validarTodo($_POST['email'],1,100))
    $error['err_email'] = "Ingresa el correo electronico del usuario";
  else
  {
  	if($validar->validarEmail($_POST['email']))
         $error['err_email'] = "Email no valido, vuelva a ingresarlo";
  }

    if (isset($error))
	{
	     $_SESSION['nombre'] = $_POST['nombre'];
		 $_SESSION['nick'] = $_POST['nick'];
		 $_SESSION['categoria']=  $_POST['categoria'];
		 $_SESSION['email']= $_POST['email'];
  	 	 $_SESSION['error']= $error;
		 $lista=$areas->obtener_lista_total();  	 	 
		 $_SESSION['areas']= $lista;
		 header("Location: ../src/registrar_usuario.php");
    }
     else
  	 {
       $nombre = $_POST['nombre'];
       $nick = $_POST['nick'];
       $categoria = $_POST['categoria'];
       $password = $_POST['hash'];
       $email = $_POST['email'];
	   $contador=0;
       $resultado=$usuarios->nuevo_usuario($nick,$nombre,$password,$email,$categoria);	   
	   if($resultado)
  	   {
		   $lista=$areas->obtener_lista_total();
		   foreach ($lista as $value) 
		   {
				foreach ($value as $val) 
				{
				  $cad = str_replace(" ","",$val[tarea_id]);
	
				  //$array_tareas[$contador]=$_POST[$cad];
					$us_id=$usuarios->sacar_ultimo();
				  $res=$usuarios->dar_permiso($us_id,$cad);	   
	
				  $contador=$contador+1;
				}
				
				
		   }
 		}
	   //enviamos la informacion a la función de adicionar cliente

       if($resultado)
  	   {
  	 	 $error['err_confirm'] = "El registro se realizo correctamente";
  	 	 $_SESSION['error']= $error;
		 
		 $lista=$areas->obtener_lista_total(); 
		 $_SESSION['areas']= $lista;
		 header("Location: ../src/registrar_usuario.php");
       }

	 }
}
else
{
	$lista=$areas->obtener_lista_total();  	 	 
    $_SESSION['areas']=$lista;
    header("Location: ../src/registrar_usuario.php");
}
?>