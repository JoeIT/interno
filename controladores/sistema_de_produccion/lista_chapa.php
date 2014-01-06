<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/chapa.php');
include_once('../../clases/includes/validador.php');

$chapa=new Chapa;
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
		$consulta=$chapa->consultar_chapa($id);
		$smarty->assign('id',$consulta[0][id]);
		$smarty->assign('descripcion',$consulta[0][descripcion]);
		$smarty->assign('titulo', 'Modificar Chapa');
		$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_chapa.html');
  	}
  	else
    {    
	    if($funcion=="eliminar")
	  	{
			$id=$_POST['elegido'];

			$consulta=$chapa->eliminar_chapa($id);
			$consulta=$chapa->consulta_lista_chapa();
			$smarty->assign('chapa',$consulta);
			$smarty->display('sistema_de_produccion/orden_de_produccion/lista_chapa.html');
		}
		else
		{
			if($funcion=="modificar_datos")
			{

				$validar = new Validador();
				if ($validar->validarTodo($_POST['descripcion'], 1, 100))

						$error['err_descripcion'] = "Ingresa la descripcion de la chapa";
					
				$chapa_id=$chapa->verificar_chapa(trim($_POST['descripcion']));
			    if($chapa_id!=-1)
					{$error['err_chapa'] = " La chapa ya existe";}

				if (isset($error))
				{
	  				$smarty->assign('id',$_POST['elegido']);
					if ($errores.err_chapa != null)
						{$smarty->assign('descripcion', $_POST['descrip_aux']);}
					else
						{$smarty->assign('descripcion', $_POST['descrip_aux']);}
						
					$smarty->assign('error',$error);
					
					$smarty->assign('titulo', 'Modificar Chapa');
					$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_chapa.html');
				}
				else
				{
					//obtenemos toda la información del formulario de modificación
					$id=$_POST['elegido'];
					$descripcion = $_POST['descripcion'];
					//enviamos la información a la función de modificar chapa
					$resultado=$chapa->modificar_chapa($id,$descripcion);
					//obtenemos la lista de clientes y la enviamos a la plantilla
					$consulta= $chapa->consulta_lista_chapa();
					$smarty->assign('chapa',$consulta);
					$smarty->display('sistema_de_produccion/orden_de_produccion/lista_chapa.html');
				}
			}
			else
			{
				$consulta= $chapa->consulta_lista_chapa();
				$smarty->assign('chapa',$consulta);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_chapa.html');
			}
		}
	}
	
}
?>

