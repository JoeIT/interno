<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/cuero.php');
include_once('../../clases/includes/validador.php');

$cuero=new Cuero;
$funcion = $_POST['funcion'];

$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	//verificamos si se desea modificar la informaci�n del clip
    if($funcion=="modificar")
  	{
		$id=$_POST['elegido'];
		//consultamos los datos del cliente
		$consulta=$cuero->consultar_cuero($id);
		
		$smarty->assign('id',$consulta[0][id]);
		$smarty->assign('descripcion',$consulta[0][descripcion]);
		$smarty->assign('titulo', 'Modificar Cuero');
		$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_cuero.html');
  	}
  	else
    {    
	    if($funcion=="eliminar")
	  	{
			$id=$_POST['elegido'];

			$consulta=$cuero->eliminar_cuero($id);
			$consulta=$cuero->consulta_lista_cuero();
			$smarty->assign('cuero',$consulta);
			$smarty->display('sistema_de_produccion/orden_de_produccion/lista_cuero.html');
		}
		else
		{
			if($funcion=="modificar_datos")
			{

				$validar = new Validador();
				if ($validar->validarTodo($_POST['descripcion'], 1, 100))
						$error['err_descripcion'] = "Ingresa la descripcion del cuero";
				

				if (isset($error))
				{
					$smarty->assign('$error',$error);
					$smarty->assign('id',$_POST['elegido']);
					$smarty->assign('descripcion',$_POST['descripcion']);
					$smarty->assign('titulo', 'Modificar Cuero');
					$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_cuero.html');
				}
				else
			    {
					//obtenemos toda la informaci�n del formulario de modificaci�n
					$id=$_POST['elegido'];
					$descripcion = $_POST['descripcion'];
					//enviamos la informaci�n a la funci�n de modificar chapa
					$resultado=$cuero->modificar_cuero($id,$descripcion);
					//obtenemos la lista de clientes y la enviamos a la plantilla
					$consulta= $cuero->consulta_lista_cuero();
					$smarty->assign('cuero',$consulta);
					$smarty->display('sistema_de_produccion/orden_de_produccion/lista_cuero.html');
  			    }
			}
			else
			{
				$consulta= $cuero->consulta_lista_cuero();
				$smarty->assign('cuero',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_cuero.html');
			}
		}
	}

}
?>

