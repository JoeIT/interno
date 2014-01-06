<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../../clases/sistema_de_produccion/lugargrabado.php');
require_once('../includes/seguridad.php');
include_once('../../clases/includes/validador.php');

$lugar=new Lugar;
$funcion = $_POST['funcion'];

$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';

	//verificamos si se desea modificar la información de la chapa
    if($funcion=="modificar")
  	{
		$id=$_POST['elegido'];
		//consultamos los datos de la chapa
		$consulta=$lugar->consultar_lugar($id);
		$smarty->assign('id',$consulta[0][id]);
		$smarty->assign('descripcion',$consulta[0][descripcion]);
		$smarty->assign('titulo', 'Modificar Lugar de Grabado');
		$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_lugar.html');
  	}
  	else
    {    
	    if($funcion=="eliminar")
	  	{
	  	   $id=$_POST['elegido'];

			$consulta=$lugar->eliminar_lugar($id);
			$consulta=$lugar->consulta_lista_lugar();
			$smarty->assign('lugar',$consulta);
			$smarty->display('sistema_de_produccion/orden_de_produccion/lista_lugar.html');
		}
		else
		{
			if($funcion=="modificar_datos")
			{

				$validar = new Validador();
				if ($validar->validarTodo($_POST['descripcion'], 1, 100))
						$error['err_descripcion'] = "Ingresa la descripcion del lugar de grabado";
				

				if (isset($error))
				{
					$smarty->assign('error',$error);
					$smarty->assign('id',$_POST['elegido']);
					$smarty->assign('descripcion',$_POST['descripcion']);
					$smarty->assign('titulo', 'Modificar Lugar de Grabado');
					$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_lugar.html');
				}
				else
			   {

				//obtenemos toda la información del formulario de modificación
				$id=$_POST['elegido'];
				$descripcion = $_POST['descripcion'];
				//enviamos la información a la función de modificar chapa
				$resultado=$lugar->modificar_lugar($id,$descripcion);
				//obtenemos la lista de clientes y la enviamos a la plantilla
				$consulta= $lugar->consulta_lista_lugar();
				$smarty->assign('lugar',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_lugar.html');
			  }
			}
			else
			{
				$consulta= $lugar->consulta_lista_lugar();
				$smarty->assign('lugar',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_lugar.html');
			}
		}
	}
?>

