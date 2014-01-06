<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../includes/fecha.php');
include_once('../../clases/sistema_de_produccion/administracion_reportes.php');
include('../../clases/class.phpMysqlConnection.php');
//
//include_once('../../clases/validador.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$administracion = new Administracion_reportes;

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if (isset($_GET['opcion']))
		$opcion = $_GET['opcion'];
	else
		$opcion = 'por_defecto';
		
	switch ($opcion) {
		case 'por_defecto' : {
			$fecha_inicio = date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d")-date("d")+1, date("Y")));
			$fecha_fin = date("Y-m-d", mktime(0, 0, 0, date("m") , date("d")-date("d"), date("Y")));
			
			if ($_POST['listado_maquinistas']){
				$fecha_inicio = trim($_POST["fecha_inicio"]);
				$fecha_fin = trim($_POST["fecha_fin"]);				
				
				//validacion
				if ($fecha_fin < $fecha_inicio){
					$error['fecha'] = "La fecha de finalizaci&oacute;n debe ser mayor";
				}
				//fin validacion
					
				if (isset($error)){
					//reenviar los errores
					$smarty->assign('error', $error);
				} else {
					//echo "<br>mostrar reportes de maquinado de las fechas seleccionadas";
					$resumen_maquinistas = $administracion->resumen_maquinistas($fecha_inicio, $fecha_fin);
					$smarty->assign('resumen_maquinistas', $resumen_maquinistas);
				}// if (isset($error))
			}//if ($_POST['generar'])
			
			
			$smarty->assign('fecha_inicio', $fecha_inicio);
			$smarty->assign('fecha_fin', $fecha_fin);
			$smarty->display('sistema_de_produccion/administracion/reportes_varios.html');
			break;
		}
		case 'asignaciones' : {
			$personal_id = $_GET['pid'];
			$fecha_inicio = $_GET['fini'];
			$fecha_fin = $_GET['ffin'];
			$resumen_asignaciones = $administracion->resumen_asignaciones($fecha_inicio, $fecha_fin, $personal_id);
			$smarty->assign('resumen_asignaciones', $resumen_asignaciones);
			
			$smarty->assign('fecha_inicio', $fecha_inicio);
			$smarty->assign('fecha_fin', $fecha_fin);
			$smarty->display('sistema_de_produccion/administracion/reportes_varios.html');
			break;
		}
	}
}

?>