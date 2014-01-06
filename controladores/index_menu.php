<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';

include_once('../clases/logeos.php');

$logeo=new Logeo;
	
if(!isset($_SESSION['logeo'])){
	header("Location: index_logeo.php");
} else {
	$funcion = $_GET['funcion'];
	
	if($funcion=="salir"){
		$logeo->salir_session();
		header("Location: index_logeo.php" );
	} else {
		$smarty->assign('errorlogeo', $errores);
		$smarty->display('index.html');
	}
}
?>