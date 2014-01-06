<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/etiquetas.php');
include_once('../../clases/includes/validador.php');

$etiqueta=new Etiqueta;
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
		//consultamos los datos del cliente
		$consulta=$etiqueta->consultar_etiqueta($id);
		$smarty->assign('id',$consulta[0][id]);
		$smarty->assign('descripcion',$consulta[0][descripcion]);
		$smarty->assign('titulo', 'Modificar etiqueta');
		$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_etiqueta.html');
  	}
  	else
    {    
	    if($funcion=="eliminar")
	  	{
			$id=$_POST['elegido'];
			$consulta=$etiqueta->eliminar_etiqueta($id);
			$consulta=$etiqueta->consulta_lista_etiquetas();
		    $smarty->assign('etiqueta',$consulta);
			$smarty->display('sistema_de_produccion/orden_de_produccion/lista_etiqueta.html');
		}
		else
		{
			if($funcion=="modificar_datos")
			{

				$validar = new Validador();
				if ($validar->validarTodo($_POST['descripcion'], 1, 100))
						$error['err_descripcion'] = "Ingresa la descripcion de la etiqueta";
				

				if (isset($error))
				{
					$smarty->assign('error',$error);
					$smarty->assign('id',$_POST['elegido']);
					$smarty->assign('descripcion',$_POST['descripcion']);
					$smarty->assign('titulo', 'Modificar etiqueta');
					$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_etiqueta.html');
				}
				else
			   {

				//obtenemos toda la información del formulario de modificación
				$id=$_POST['elegido'];
				$descripcion = $_POST['descripcion'];
				//enviamos la información a la función de modificar chapa
				$resultado=$etiqueta->modificar_etiqueta($id,$descripcion);
				//obtenemos la lista de clientes y la enviamos a la plantilla
				$consulta= $etiqueta->consulta_lista_etiquetas();
				$smarty->assign('etiqueta',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_etiqueta.html');
			  }
			}
			else
			{
				$consulta= $etiqueta->consulta_lista_etiquetas();
				$smarty->assign('etiqueta',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_etiqueta.html');
			}
		}
	}

}
?>