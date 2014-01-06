<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/includes/validador.php');

$estilo=new Estilo;
$funcion = $_POST['funcion'];

$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	//verificamos si se desea modificar la información del clip
    if($funcion=="modificar")
  	{
		$id=$_POST['elegido'];
		
		$consulta=$estilo->consultar_estilo($id);
       	$smarty->assign('id',$consulta[0][id]);
		$smarty->assign('descripcion',$consulta[0][descripcion]);
		$smarty->assign('titulo', 'Modificar Estilo');
		$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_estilo.html');
  	}
  	else
    {    
	    if($funcion=="eliminar")
	  	{
			$id=$_POST['elegido'];

			$consulta=$estilo->eliminar_estilo($id);
			$consulta=$estilo->consulta_lista_estilos();
			$smarty->assign('estilo',$consulta);
			$smarty->display('sistema_de_produccion/orden_de_produccion/lista_estilo.html');
		}
		else
		{
			if($funcion=="modificar_datos")
			{

				$validar = new Validador();
				if ($validar->validarTodo($_POST['descripcion'], 1, 100))
						$error['err_descripcion'] = "Ingresa la descripcion del estilo";
				

				if (isset($error))
				{
					$smarty->assign('error',$error);
					$smarty->assign('id',$_POST['elegido']);
					$smarty->assign('descripcion',$_POST['descripcion']);
					$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_estilo.html');
					
				}
				else
			    { 
	
					//obtenemos toda la información del formulario de modificación
					$id=$_POST['elegido'];
					$descripcion = $_POST['descripcion'];
					//enviamos la información a la función de modificar chapa
					$resultado=$estilo->modificar_estilo($id,$descripcion);
					//obtenemos la lista de clientes y la enviamos a la plantilla
					$consulta= $estilo->consulta_lista_estilos();
					$smarty->assign('estilo',$consulta);
					$smarty->display('sistema_de_produccion/orden_de_produccion/lista_estilo.html');
	  		    }
			}
			else
			{
				$consulta= $estilo->consulta_lista_estilos();
				$smarty->assign('estilo',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_estilo.html');
			}
		}
	}

}
?>

