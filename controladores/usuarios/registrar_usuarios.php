<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/usuarios.php');
include_once('../../clases/grupos.php');
include_once('../../clases/paginas.php');

include_once('../../clases/validador.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$usuarios=new Usuarios;
$pagina=new Paginas;
$grupo=new Grupos;

if(isset($_GET["busqueda_ajax"]))
 {    
      $valor = $_POST["value"];
	  $cadena= trim($valor);
	  header("content-type: text/html; charset=iso-8859-1");
	  echo "<ul>";
	  $lista=$grupo->busqueda_grupos($cadena);
	  if(count($lista)==0)
	  {
		   echo "<li>No hay resultados</li>";
	  }
	  else
	  {
		for($contador=0;$contador<count($lista);$contador++)
	     	{
		        echo "<li>".$lista[$contador]."</li>";
		  
		 	}
	  }
	  echo "</ul>";
 }
else
{


if($_POST['funcion']== "validar")
{
	echo "entro a validar";
  $validar = new Validador();
  if ($validar->validarTodo($_POST['nombre'], 1, 100))
    $error['err_nombre'] = "Ingresa el nombre del usuario";
	if ($validar->validarTodo($_POST['apellido'], 1, 100))
    $error['err_apellido'] = "Ingresa el apellidos del usuario";
  if ($validar->validarTodo($_POST['nick'], 1, 100))
    $error['err_nick'] = "Ingresa el nick del usuario";
  if ($validar->validarTodo($_POST['email'],1,100))
    $error['err_email'] = "Ingresa el correo electronico del usuario";
  else
  {
  	if($validar->validarEmail($_POST['email']))
         $error['err_email'] = "Email no valido, vuelva a ingresarlo";
  }

    if (isset($error))
	{
		echo "error";

		 $smarty->assign('nombre',$nombre);
		 $smarty->assign('apellido',$apellido);
		 $smarty->assign('nick',$nick);
		 $smarty->assign('categoria',$categoria);
		 $smarty->assign('email',$email);
		 $smarty->assign('errores',$error);
 		 $lista=$grupo->obtener_lista_total();  	 	 

		$smarty->assign('grupos',$lista);
		$smarty->display('usuarios/registrar_usuarios.html');
    }
     else
  	 {
       $nombre = $_POST['nombre'];
       $apellido = $_POST['apellido'];
       $nick = $_POST['nick'];
       $categoria = $_POST['categoria'];
       $password = $_POST['hash'];
       $email = $_POST['email'];
	   $grupos = $_POST['grupo'];
	   
	   $grupo_id=$grupo->obtener_id($grupos);

       $resultado=$usuarios->nuevo_usuario($nick,$nombre,$apellido,$password,$email,$grupo_id);	   
	   
	   //enviamos la informacion a la función de adicionar cliente

       if($resultado)
  	   {
  	 	 $error['err_confirm'] = "El registro se realizo correctamente";
  	 	  $smarty->assign('errores',$error);
		 
   		$lista=$grupo->obtener_lista_total();  	 	 

		$smarty->assign('grupos',$lista);
		$smarty->display('usuarios/registrar_usuarios.html');
       }

	 }
}
else
{ 
 header("content-type: text/html; charset=iso-8859-1");
	$lista=$grupo->obtener_lista_total();  	
	$smarty->assign('grupos',$lista); 
	$smarty->display('usuarios/registrar_usuarios.html');
	
}
}
?>