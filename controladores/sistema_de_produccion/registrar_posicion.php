<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/despiece.php');
include_once('../../clases/validador.php');
require_once('../includes/seguridad.php');
$smarty = new Smarty();
$smarty->template_dir = '../../templates/sistema_de_produccion/despiece/';
$smarty->compile_dir = '../../templates_c';

$despiece=new Despiece;

if($_POST['funcion']== "validar")
{
  $validar = new Validador();
  if ($validar->validarTodo($_POST['descripcion'], 1, 100))
    $error['err_nombre'] = "Ingresa la descripcion de la posición";
 

    if (isset($error))
	{
		 $smarty->assign('descripcion',$descripion);
		 $smarty->assign('errores',$error);
		 
		$smarty->display('registrar_posicion.html');
    }
     else
  	 {

       $descripcion = $_POST['descripcion'];

	   $contador=0;
       $resultado=$despiece->nueva_posicion($descripcion);	   
	 
	   //enviamos la informacion a la función de adicionar cliente

       if($resultado)
  	   {
  	 		$error['err_confirm'] = "El registro se realizo correctamente";
  			$smarty->assign('errores',$error);
		 
			$smarty->display('registrar_posicion.html');
			exit;
       }

	 }
}
else
{
	$smarty->display('registrar_posicion.html');
}
?>