<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/colores.php');
include_once('../../clases/includes/validador.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

$color=new Color;
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
		$consulta=$color->consultar_color($id);
		$smarty->assign('id',$consulta[0][id]);
		$smarty->assign('descripcion',$consulta[0][descripcion]);
		$smarty->assign('coleccion',$consulta[0][coleccion]);
		$smarty->assign('titulo', 'Modificar Color');
		$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_color.html');
  	}
  	else
    {    
	    if($funcion=="eliminar")
	  	{
			$id=$_POST['elegido'];

			$consulta=$color->eliminar_color($id);
			$consulta=$color->consulta_lista_colores();
			$smarty->assign('color',$consulta);
			$smarty->display('sistema_de_produccion/orden_de_produccion/lista_color.html');
		}
		else
		{
			if($funcion=="modificar_datos")
			{

				$validar = new Validador();
				if ($validar->validarTodo($_POST['descripcion'], 1, 100))
						$error['err_descripcion'] = "Ingresa la descripcion del color";
				if ($validar->validarTodo($_POST['coleccion'], 1, 100))
						$error['err_coleccion'] = "Ingresa la coleccion del color";
				

				if (isset($error))
				{
					$smarty->assign('id',$_POST['elegido']);
					$smarty->assign('descripcion',$_POST['descripcion']);
					$smarty->assign('coleccion',$_POST['coleccion']);
					$smarty->assign('error',$error);
					$smarty->assign('titulo', 'Modificar Color');
					$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_color.html'); 
					 
				}
				else
			    {

					//obtenemos toda la información del formulario de modificación
					$id=$_POST['elegido'];
					$descripcion = $_POST['descripcion'];
					$coleccion = $_POST['coleccion'];
					//enviamos la información a la función de modificar chapa
					$resultado=$color->modificar_color($id,$descripcion,$coleccion);
					//obtenemos la lista de clientes y la enviamos a la plantilla
					$consulta= $color->consulta_lista_colores();
					$smarty->assign('color',$consulta);
					$smarty->display('sistema_de_produccion/orden_de_produccion/lista_color.html');
 			    }
			}
			else
			{
				$consulta= $color->consulta_lista_colores();
				$smarty->assign('color',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_color.html');
			}
		}
	}
	
}
?>

