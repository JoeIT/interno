<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/asignacion.php');
include_once('../includes/fecha.php');
include_once('../../clases/validador.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
$validar=new Validador();
$asignacion = new Asignacion;
//

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if ($_GET['tabu']){
		$smarty->assign('tabu',$_GET['tabu']);
	}

	$opcion = $_GET['opcion'];
	switch ($opcion){
		case 1:{
			//1ro actuzlizar la tdetalle asignacion: imprimir_num=1
			$daid = $_GET['daid'];

			//volver a habilitarrrrrrrr
			$asignacion->modificar_impresion($daid);
			//cargamos los datos
			$lista_mdr = $asignacion->reporte_modificar_detalle_resultados($daid);
			$smarty->assign('lista',$lista_mdr);
			$smarty->display('sistema_de_produccion/asignacion/hoja_ruta.html');
			break;
		}
		case 2:{
			//echo "<br>Reprogramacion";
			$smarty->display('sistema_de_produccion/asignacion/formulario_reprogramacion.html');
			break;
		}
		case 3:{
			$daid = $_GET['daid'];
			$puesto = 3; // Maquinista = 3
			$lista = $asignacion->busqueda_personal_reprogramacion($daid, $puesto);
			$_SESSION['lista'] = $lista;
			$smarty->assign('lista',$_SESSION['lista']);
			$smarty->display('sistema_de_produccion/asignacion/reprogramacion_fecha.html');
			break;
		}
		case 4:{
			//1ro actuzlizar la tdetalle asignacion: imprimir_num=1
			$daid = $_GET['daid'];

			//****************************************Modificar si se quiere deshabilitar
			//$asignacion->modificar_impresion($daid);
			//****************************************
		
			//cargamos los datos
			$lista_mdr = $asignacion->reporte_modificar_detalle_resultados($daid);
			
			$reprogramacion = "R";
			$fecha_reprogramacion = $lista_mdr['fecha_reprogramacion'];
			$smarty->assign('r',$reprogramacion);
			$smarty->assign('frep',$fecha_reprogramacion);
			$smarty->display('sistema_de_produccion/asignacion/hoja_ruta.html');
			break;
		}
	}
	
	if (!empty($_POST)){
		if (($_POST['funcion']) || ($_POST["funcion_actualizar_rep_x"])){
			$numero = trim($_POST["maquinista"]);
			$puesto = 3; // Maquinista = 3
			
			//validacion
			if ($validar->validarTodo($numero, 1, 100)){
				$error['err_numero'] = "Ingrese N&uacute;mero de Asignaci&oacute;n";
			} else {
				if (!ereg("^[0-9]{1,}$", $numero)){
					$error['err_numero'] = "Ingrese solo N&uacute;meros";
				}
			}
			
			if (isset($error)){
				//reenviar los errores
				$smarty->assign('errores',$error);
			} else {
				//echo "<br>Buscar por: Asignacion";
				$lista = $asignacion->busqueda_personal_reprogramacion($numero, $puesto);
				$smarty->assign('lista',$lista);
			}
			//desplegamos el listado de la busqueda
			$smarty->assign('maquinista',$numero);
			$smarty->display('sistema_de_produccion/asignacion/formulario_reprogramacion.html');
		}
		
		if ($_POST['reprogramar_fecha_asignada']){
			//echo "<br>Almacenar nueva fecha";
			$nffin = trim($_POST["nffin"]);
			$motivo = $_POST['motivo'];
			
			if ($motivo == "Varios"){
				$motivodes = trim($_POST['motivodes']);
				if ($validar->validarTodo($motivodes, 1, 100)){
					$error['err_motivodes'] = "Ingrese el motivo";
				}
			}
			
			if ($validar->validarTodo($nffin, 1, 100)){
				$error['err_nffin'] = "Ingrese fecha de reprogramaci&oacute;n";
			}
			
			if (isset($error)){
				//reenviar los errores
				$smarty->assign('errores',$error);
			} else {
				//colocar nueva fecha
				$lista = $_SESSION['lista'];
				$puesto = 3; // Maquinista = 3
				
				$responsable_reprog = $_SESSION["usuario_id"];
				
				if ($motivo == "Varios"){
					$descripcion = $motivodes;
				} else {
					$descripcion = $motivo;
				}
				
				$asignacion->modificar_fecha_reprogramacion($lista['asignacion_detalle_id'], $nffin, $responsable_reprog, $descripcion);
				$_SESSION['lista'] = $asignacion->busqueda_personal_reprogramacion($lista['asignacion_detalle_id'], $puesto);
			}
		
			$smarty->assign('motivo',$motivo);
			if ($motivo == "Varios"){
				$smarty->assign('motivodes',$motivodes);
			}
		
			$smarty->assign('nffin',$nffin);
			$smarty->assign('lista',$_SESSION['lista']);
			$smarty->display('sistema_de_produccion/asignacion/reprogramacion_fecha.html');
		}
	}
}
?>