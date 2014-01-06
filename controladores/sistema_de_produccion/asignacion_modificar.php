<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

include_once('../../clases/sistema_de_produccion/asignacion.php');
include_once('../../clases/validador.php');
require("../includes/fecha.php");

$validar = new Validador;
$asignacion = new Asignacion;

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);
//

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	$opcion = $_GET['opcion'];
	
	switch ($opcion){
		case 'buscar' :{
			if (isset($_POST['buscar'])){
				$num_asignacion = trim($_POST['num_asignacion']);
				$_SESSION['num_asignacion'] = $num_asignacion;
				
				if ($validar->validarTodo($num_asignacion, 1, 100)){
					$error['err_num_asignacion'] = "Ingrese N&uacute;mero de Asignaci&oacute;n";
				} else {
					if (!ereg("^[0-9]{1,}$", $num_asignacion)){
						$error['err_num_asignacion'] = "Ingrese solo N&uacute;meros";
					}
				}
				if (isset($error)){
					$smarty->assign('errores',$error);
				} else {
					$asignacion_modificar = $asignacion->buscar_asignacion_modificar($num_asignacion);
					if ($asignacion_modificar != false) {
						$smarty->assign('asignacion_modificar', $asignacion_modificar);
						//verificamos condicion del despacho
						$condicion_despacho = $asignacion->condicion_despacho($num_asignacion);
						$smarty->assign('condicion_despacho', $condicion_despacho);
						
						//si encontro la asignacion se mostrara el resumen de sus cantidades asignadas
						$cantidad_asignada = $asignacion->cantidad_asignada($num_asignacion);
						$total = $cantidad_asignada['cantidad'] + $cantidad_asignada['pendientes'];
						for ($h=1; $h<=$total; $h++)
							$vector[$h] = $h;
						//se asigna la cantidad y lo que puede modificarse
						$smarty->assign('vector', $vector);
						$smarty->assign('cantidad_asignada', $cantidad_asignada);
					} else
						$smarty->assign('mensaje', 'No se encontro la asignaci&oacute;n');
				}
			} else {
				unset($_SESSION['num_asignacion']);
			}
			break;
		}
		case 'modificar' :{
			$num_asignacion = $_SESSION['num_asignacion'];
			if (isset($_POST['modificar'])){
				$rb_modificar = $_POST['rb_modificar'];
				switch ($rb_modificar) {
					case 'maquinista': {
						$maquinista_id = $_POST['maquinista_id'];
						if ($maquinista_id != '')
							$asignacion->modificar_maquinista($num_asignacion, $maquinista_id);
						break;
					}
					case 'dado_baja': {
						$asignacion->modificar_dado_baja($num_asignacion);
						break;
					}
					case 'con_reprogramacion': {
						$asignacion->modificar_reprogramacion($num_asignacion);
						break;
					}
					case 'anular': {
						$asignacion->anular_hoja_ruta($num_asignacion);
						break;
					}
					case 'cantidad': {
						$cantidad_original = $_POST['cantidad_original'];
						$cantidad_nueva = $_POST['cantidad_nueva'];
						$asignacion->modificar_cantidad_asignada($num_asignacion, $cantidad_original, $cantidad_nueva);
						break;
					}
				}
			}
			break;
		}
	}//switch
	
	$smarty->assign('num_asignacion',$num_asignacion);
	$smarty->display('sistema_de_produccion/asignacion/asignacion_modificar.html');
	
}
?>