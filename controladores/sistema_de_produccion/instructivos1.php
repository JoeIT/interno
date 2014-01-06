<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/instructivos.php');
include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/includes/validador.php');

$instructivo = new Instructivo;
$detalle = new Detalle_orden;
$validar = new Validador();
$funcion = $_GET['funcion'];

$smarty = new Smarty();
$smarty->template_dir = '../../templates/sistema_de_produccion/instructivos/';
$smarty->compile_dir = '../../templates_c';

if(!isset($_SESSION['logeo'])) {
	header("Location: ../index_logeo.php");
} else {
	if (isset($_POST["buscar"]))
	 {
	 	
	 	$consulta = $instructivo->listar_un_instructivos($_POST['numero']);

	 	$smarty->assign('orden', $consulta);
	 	$smarty->assign('ver', 'si');
	 	
										
		$smarty->display('lista_mostrar-un_instructivos.html');
	 }
		
}
?>