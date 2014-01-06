<?php

session_start();
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/clip.php');
include_once('../../clases/includes/validador.php');

$clip=new Clip;
$funcion = $_POST['funcion'];

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	//verificamos si se desea modificar la información del clip
    if($funcion=="modificar")
  	{
		$id=$_POST['elegido'];
		//consultamos los datos del cliente
		$consulta=$clip->consultar_clip($id);
		
		$smarty->assign('id',$consulta[0][id]);
		$smarty->assign('descripcion',$consulta[0][descripcion]);
		$smarty->assign('titulo', 'Modificar Clip');
		$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_clip.html');
		
  	}
  	else
    {    
	    if($funcion=="eliminar")
	  	{
			$id=$_POST['elegido'];

			$consulta=$clip->eliminar_clip($id);
			$consulta=$clip->consulta_lista_clip();
			$smarty->assign('clip',$consulta);
			$smarty->display('sistema_de_produccion/orden_de_produccion/lista_clip.html');
		}
		else
		{
			if($funcion=="modificar_datos")
			{

				$validar = new Validador();
				if ($validar->validarTodo($_POST['descripcion'], 1, 100))
						$error['err_descripcion'] = "Ingresa la descripcion del clip";
				

				if (isset($error))
				{
					$smarty->assign('error',$error);
   				    $smarty->assign('descripcion',$_POST['descripcion']);
					$smarty->assign('titulo', 'Modificar Clip');
					$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_clip.html');
				}
				else
			    {

					//obtenemos toda la información del formulario de modificación
					$id=$_POST['elegido'];
					$descripcion = $_POST['descripcion'];
					//enviamos la información a la función de modificar chapa
				    $resultado=$clip->modificar_clip($id,$descripcion);
					//obtenemos la lista de clientes y la enviamos a la plantilla
					$consulta= $clip->consulta_lista_clip();
					$smarty->assign('clip',$consulta);
					$smarty->display('sistema_de_produccion/orden_de_produccion/lista_clip.html');
				  }
			}
			else
			{
				$consulta= $clip->consulta_lista_clip();
				
				$smarty->assign('clip',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_clip.html');
			}
		}
	}
	
}
?>

