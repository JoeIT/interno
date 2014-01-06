<?php
session_start();
//************************************************
//require("includes/conf_dir_Smarty.php");

define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/avisos.php');
include_once('../../clases/grupos.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
//************************************************
$aviso = new Aviso;
$grupo = new Grupos;


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {

	$funcion = $_POST['funcion'];
	
	if ($funcion == 'publicar') {
		$receptor = $_POST['grupo'];
		$todos = $_POST['todos'];
		$anuncio = ($_POST['aviso']);
		
		if ($receptor == "" && $todos == false){
			$error['err_grupo'] = "Ingrese un grupo receptor";
		}
		if ($receptor != "" && $todos == true){
			$error['err_grupo'] = "Elegir solo una opcion como receptor";
		}
		if ($anuncio == "") {
			$error['err_anuncio'] = "Ingrese aviso";
		}
		
		if (isset($error)) {
			$smarty->assign('grupo',$receptor);
			$smarty->assign('aviso',$anuncio);
			$smarty->assign('todos',$todos);
			$smarty->assign('errores',$error);
			$smarty->display('sistema_de_produccion/avisos/enviar.html');
		} else {
			if (!get_magic_quotes_gpc()) {
				$anuncio = addslashes($anuncio);
			}
			if($todos == true) {
				$lista = $aviso->nuevo_aviso($anuncio,$_SESSION["usuario_id"],0);
			} else {
				$receptor = $grupo->obtener_id($receptor);
				$lista = $aviso->nuevo_aviso($anuncio,$_SESSION["usuario_id"],$receptor);
			}
			header("Location: ../contenido.php");
		}
	} else {
		if (isset($_GET['funcion'])) {
			$lista=$aviso->obtener_lista_total_avisos($_SESSION["usuario_id"]);
			$smarty->assign('avisos',$lista);
			$smarty->display('sistema_de_produccion/avisos/historial.html');
		} else {
			//enviar
			$smarty->assign('todos',true);
			$smarty->display('sistema_de_produccion/avisos/enviar.html');
		}
	}

}
?>