<?php
session_start();
define('CLAS', "clases/");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Gestion.php");
require_once(CLAS . "Reportes.php");

if(isset($_SESSION['usuario_id']))
{
	$smarty = new Smarty();
	$link = new Coneccion();
	$link = $link->conectiondb();
	$title = ".:: Reportes - LCV IVA. ::.";

	$ges = new Gestion($link);
	$gestiones = $ges->listarTodasGestiones();

	if(isset($_REQUEST['search'])||!empty($_REQUEST['search']))
	{
		$rep = new Reportes($link);
		$result=null;
		if(	strcmp($_POST['tipo'],"1")==0	)
		{
			$result = $rep->buscar_Compras($_POST['tsearch'],$_POST['criterio'],$_POST['gestion'],$_POST['periodo'],$_POST['fecha']);
		}
		elseif (	strcmp($_POST['tipo'],"2")==0	)
		{
			$result = $rep->buscar_Ventas($_POST['tsearch'],$_POST['criterio'],$_POST['gestion'],$_POST['periodo'],$_POST['fecha']);
		}
		else
		{
			$result = $rep->buscar_Compras($_POST['tsearch'],$_POST['criterio'],$_POST['gestion'],$_POST['periodo'],$_POST['fecha']);
			$result2 = $rep->buscar_Ventas($_POST['tsearch'],$_POST['criterio'],$_POST['gestion'],$_POST['periodo'],$_POST['fecha']);
			if(isset($result))
			{
				if(isset($result2))
				$result = array_merge($result,$result2);
			}
			else
			if (isset($result2))
			$result=$result2;
		}
	}

	$smarty->template_dir = 'templates';
	$smarty->compile_dir = 'templates_c';
	$smarty->assign('title', $title);
	$smarty->assign('gestiones',$gestiones);
	$smarty->assign('result',$result);
	$smarty->display('reporteLcv.html');
}
else
header("Location:/sistema/macaws/seleccionarGestion.php");
?>