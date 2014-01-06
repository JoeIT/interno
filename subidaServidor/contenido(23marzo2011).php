<?php
session_start();
require("includes/fecha.php");
require_once('../clases/avisos.php');

//************************************************
//require("includes/conf_dir_Smarty.php");

define('CLAS', "../clases/");
define('SMARTY_DIR', "../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');


$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';
//************************************************
require_once('../clases/noconformidad_apertura.php');
$aviso = new Aviso;
//para cargar no conformidades
$noconformidad = new NoConformidad_apertura();
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

if(!isset($_SESSION['logeo'])){
	header("Location: index_logeo.php");
} else {

	if (isset($_SESSION["usuario_id"])){
		$lista=$aviso->obtener_lista_avisos($_SESSION["usuario_id"]);
		$smarty->assign('avisos',$lista);
		
		//para los cumpleañeros
		$dia = date("d");
		$mes = date("m");
		$lista_cumpleanios = $aviso->busqueda_cumpleanios($mes, $dia);
		$lista_cumpleanios_mes = $aviso->cumpleanios_mes($mes, $dia);
		$smarty->assign('lista_cumpleanios', $lista_cumpleanios);
		$smarty->assign('lista_cumpleanios_mes', $lista_cumpleanios_mes);
		//
	}
	/////////
    $totalRG=$noconformidad->consulta_noconformidad($_SESSION["grupo_id"],1);
    $smarty->assign('totalRG', count($totalRG));
    /////////////
	$smarty->display('contenido/contenido.html');
}
?>