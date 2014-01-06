<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Gestionperiodo.php");

$smarty = new Smarty();
$link = new Coneccion();
$link = $link->conectiondb();

$title = ".:: Registrar Gestion - Periodo ::.";
$gesper = new Gestionperiodo($link);
$estado=1;

if(isset($_REQUEST['mcwgessel']))
{
	$gestion_selec=$_REQUEST['mcwgessel'];
}
if (isset($_SESSION['mcwgessel'])) {
	$gestion_selec=$_SESSION['mcwgessel'];
	unset($_SESSION['mcwgessel']);
}
if(isset($gestion_selec))
{
	$periodos_abiertos = $gesper->CantidadPeriodosAbiertos();
	
	$pg_abiertos= $gesper->obenerPeriodosGestion($gestion_selec);	
	$pg = $gesper->obtenerUltimoPeriodoGestion($gestion_selec);
	$allperiodos = $gesper->todosPeriodoGestion($gestion_selec);
	$periodos_disponibles = null;
	$indice = 0;
	for($i=($pg['periodo']+1);$i<13;$i++)
	{
		if($i<10)
		$periodos_disponibles[$indice]="0".$i;
		else
		$periodos_disponibles[$indice]="".$i;
		$indice++;
	}

	if (isset($_SESSION['errores']))
	{	$errores = $_SESSION['errores'];
		unset($_SESSION['errores']);
	}

	if (isset($_SESSION['req']))
	{	$valores = $_SESSION['req'];
		unset($_SESSION['req']);
		$cod_pg = $valores['cod_pg'];
	}

	if (isset($_SESSION['macawsregperi']))
	{	$reg_ok = $_SESSION['macawsregperi'];
		unset($_SESSION['macawsregperi']);
	}

	$smarty->template_dir = '../templates';
	$smarty->compile_dir = '../templates_c';

	$smarty->assign('title', $title);
	$smarty->assign('val',$valores);
	$smarty->assign('reg_ok',$reg_ok);
	$smarty->assign('error',$errores);
	$smarty->assign('perdis',$periodos_disponibles);	
	$smarty->assign('abiertos',$pg_abiertos);	
	$smarty->assign('periodos',$allperiodos);
	$smarty->assign('gestion',$gestion_selec);
	$smarty->assign('peropen',$periodos_abiertos);
	$smarty->display('periodoGestion.html');
}
else
header("Location:/macaws/src/seleccionarGestion.php");
?>