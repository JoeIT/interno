<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/fuentes.php');
include_once('../../clases/includes/validador.php');

$fuente=new Fuente;
$funcion = $_POST['funcion'];

$smarty = new Smarty();
$smarty->template_dir = '../../templates';
$smarty->compile_dir = '../../templates_c';


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	//verificamos si se desea modificar la información de la chapa
    if($funcion=="modificar")
 	{
  	   $id=$_POST['elegido'];
  	   //consultamos los datos de la chapa
       	$consulta=$fuente->consultar_fuente($id);
       	$smarty->assign('id',$consulta[0][id]);
		$smarty->assign('descripcion',$consulta[0][descripcion]);
		$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_fuente.html');
  	}
  	else
	{    
	    if($funcion=="eliminar")
	  	{
			$id=$_POST['elegido'];

			$consulta=$fuente->eliminar_fuente($id);
			$consulta=$fuente->consulta_lista_fuente();
			$smarty->assign('fuente',$consulta);
			$smarty->display('sistema_de_produccion/orden_de_produccion/lista_fuente.html');
		}
		else
		{
			if($funcion=="modificar_datos")
			{

				$validar = new Validador();
				if ($validar->validarTodo($_POST['descripcion'], 1, 100))
						$error['err_descripcion'] = "Ingresa la descripcion del tipo de fuente";
						
				$fuente_id=$fuente->verificar_fuente(trim($_POST['descripcion']));
			    if($fuente_id!=-1)
					{$error['err_fuente'] = " El tipo de fuente ya existe";}
				
				if (isset($error))
				{
					$smarty->assign('error',$_POST['error']);
					$smarty->assign('id',$_POST['elegido']);
					$smarty->assign('descripcion',$_POST['descripcion']);
					$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_fuente.html');
				}
				else
			   	{

					//obtenemos toda la información del formulario de modificación
					$id=$_POST['elegido'];
					$descripcion = $_POST['descripcion'];
					//enviamos la información a la función de modificar chapa
					$resultado=$fuente->modificar_fuente($id,$descripcion);
					//obtenemos la lista de clientes y la enviamos a la plantilla
					$consulta= $fuente->consulta_lista_fuente();
					$smarty->assign('fuente',$consulta);
					$smarty->display('sistema_de_produccion/orden_de_produccion/lista_fuente.html');
		 		 }
			}
			else
			{
				$consulta= $fuente->consulta_lista_fuente();
				$smarty->assign('fuente',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_fuente.html');
			}
		}
	}

}
?>

