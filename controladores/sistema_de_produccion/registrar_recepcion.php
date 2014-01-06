<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/recepcion_calidad.php');
include_once('../../clases/includes/validador.php');
require('../../controladores/includes/fecha.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$recepcion=new Recepcion_Calidad;
$validar = new Validador();

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	$funcion = $_GET['funcion'];
	if ($funcion == "buscar") {
		$num_asignacion = trim($_GET['num_asignacion']);
		//validar numero de asignacion
		if ($validar->validarTodo($num_asignacion, 1, 100)) {
			$error['num_asignacion'] = "Ingresar n&uacute;mero de asignaci&oacute;n";
		} else {
			//validamos que sea solo numero
			if (!ereg("^[0-9]{1,}$", $num_asignacion)){
				$error['num_asignacion'] = "Ingresar solo n&uacute;meros";
			}
		}
		
		//si existe errores
		if (isset($error)) {
			$smarty->assign('errores', $error);
			$smarty->assign('num_asignacion', $num_asignacion);
			$smarty->display('sistema_de_produccion/control_calidad/formulario_recepcion.html');
		} else {
			//si no existe errores
			//efectuamos la busqueda
			$resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
			$resumen_recepciones = $recepcion->resumen_recepcion($num_asignacion);
			$total_recepcionado = $recepcion->total_recepcionado($num_asignacion);
			//verificar si devolvio algun resultado
			if ($resultado_asignacion != null) {
				if ($resultado_asignacion['usuario_entrega'] == 0) {
					$smarty->assign('num_asignacion', $num_asignacion);
					$smarty->assign('mensaje', "Comunicar la finalización de produccion al encargado");
					$smarty->display('sistema_de_produccion/control_calidad/formulario_recepcion.html');
				} else {
					$fecha_actual = date('Y-m-d');
					$smarty->assign('fecha_actual', $fecha_actual);
					
					if ($resultado_asignacion['frep'] != null && $resultado_asignacion['frep'] != '0000-00-00' )
						$fecha_finalizacion = $resultado_asignacion['frep'];
					else
						$fecha_finalizacion = $resultado_asignacion['ffin'];
					
					//calcular dias de retraso
					if ($fecha_finalizacion <= $fecha_actual) {
						list($ano1, $mes1, $dia1) = split("-", $fecha_finalizacion);
						$nueva_fecha1 = $dia1."-".$mes1."-".$ano1;
						list($ano2, $mes2, $dia2) = split("-", $fecha_actual);
						$nueva_fecha2 = $dia2."-".$mes2."-".$ano2;
						
						$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
						$timestamp2 = mktime(0,0,0,$mes2,$dia2,$ano2);
						
						$segundos_diferencia = $timestamp2 - $timestamp1;
						$dias_retraso = $segundos_diferencia / (60 * 60 * 24);
						
						//para darle permisos de anular dias de retraso
						if ($_SESSION['usuario_id'] == 69) {
							$smarty->assign('permiso', 'yes');
						}
					}
					
					if ($dias_retraso < 0)
						$dias_retraso = 0;
					//obtengo el valor absoulto de los días (quito el posible signo negativo)
					$dias_retraso = abs($dias_retraso);
					$smarty->assign('dias_retraso', $dias_retraso);
					//
					
					$smarty->assign('resultado_asignacion', $resultado_asignacion);
					$smarty->assign('resumen_recepciones', $resumen_recepciones);
					$smarty->assign('total_recepcionado', $total_recepcionado);
					$smarty->assign('fecha', $fecha);
					$smarty->assign('num_asignacion', $num_asignacion);
					$smarty->display('sistema_de_produccion/control_calidad/formulario_recepcion.html');
				}
			} else {
				$smarty->assign('num_asignacion', $num_asignacion);
				$smarty->assign('mensaje', "No se encontr&oacute; la asignaci&oacute;n");
				$smarty->display('sistema_de_produccion/control_calidad/formulario_recepcion.html');
			}
		}
	} else {
		if ($funcion == "registrar_recepcion") {
			//para darle permisos de anular dias de retraso
			if ($_SESSION['usuario_id'] == 69) {
				$smarty->assign('permiso', 'yes');
			}
			
			$cantidad = $_GET['cantidad'];
			$fret = $_GET['fret'];
			$num_asignacion = trim($_GET['num_asignacion']);
			if ($validar->validarTodo($cantidad, 1, 100)) {
				$error['cantidad'] = "Ingresar una cantidad";
			} else {
				//validamos que sea solo numero
				if (!ereg("^[0-9]{1,}$", $cantidad)) {
					$error['cantidad'] = "Ingresar solo n&uacute;meros";
				} else {
					$total_recepcionado = $recepcion->total_recepcionado($num_asignacion);
					$resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
					$faltante = $resultado_asignacion['cantidad'] - $total_recepcionado;
					if ($faltante < $cantidad) {
						$error['cantidad'] = "Cantidad no válida";
					}
				}
			}
			
			//si existe errores
			if (isset($error)) {
				$fecha_actual = date('Y-m-d');
				$smarty->assign('fecha_actual', $fecha_actual);
				$smarty->assign('dias_retraso', $fret);	
				
				$smarty->assign('errores', $error);
				$resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
				$resumen_recepciones = $recepcion->resumen_recepcion($num_asignacion);
				$total_recepcionado = $recepcion->total_recepcionado($num_asignacion);
				$smarty->assign('resultado_asignacion', $resultado_asignacion);
				$smarty->assign('resumen_recepciones', $resumen_recepciones);
				$smarty->assign('total_recepcionado', $total_recepcionado);
				$smarty->assign('fecha', $fecha);
				$smarty->assign('num_asignacion', $num_asignacion);
				$smarty->display('sistema_de_produccion/control_calidad/formulario_recepcion.html');				
			} else {
				//averiguando si tiene o no reprogramacion
				$resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
				if ($resultado_asignacion['frep'] != null && $resultado_asignacion['frep'] != '0000-00-00' ) {
					$fecha_final_obtenida = $resultado_asignacion['frep'];
				} else {
					$fecha_final_obtenida = $resultado_asignacion['ffin'];
				}
				
				//averiguar si se tiene que anular los dias de retraso
				if (isset($_GET['anular_retraso'])) {
					$resultado_registro = $recepcion->registrar_recepcion_sin_retraso($num_asignacion,$cantidad,$_SESSION['usuario_id'],$fecha_final_obtenida);
				} else {
					$resultado_registro = $recepcion->registrar_recepcion($num_asignacion,$cantidad,$_SESSION['usuario_id'], $fecha_final_obtenida);
				}
				
				$fecha_actual = date('Y-m-d');
				$smarty->assign('fecha_actual', $fecha_actual);
				$smarty->assign('dias_retraso', $fret);
				
				$confirmacion="El registro se realizo con éxito";
				$smarty->assign('confirmacion', $confirmacion);
				$resumen_recepciones = $recepcion->resumen_recepcion($num_asignacion);
				$total_recepcionado = $recepcion->total_recepcionado($num_asignacion);
				if($total_recepcionado==$resultado_asignacion['cantidad'])
					  $smarty->assign('mensaje', "Se completo la recepción de todos los productos asignados");
				$smarty->assign('resultado_asignacion', $resultado_asignacion);
				$smarty->assign('resumen_recepciones', $resumen_recepciones);
				$smarty->assign('total_recepcionado', $total_recepcionado);
				$smarty->assign('fecha', $fecha);
					$smarty->assign('num_asignacion', $num_asignacion);
				$smarty->display('sistema_de_produccion/control_calidad/formulario_recepcion.html');
			}
		} else {
			$smarty->display('sistema_de_produccion/control_calidad/formulario_recepcion.html');
		}
	}
}
?>