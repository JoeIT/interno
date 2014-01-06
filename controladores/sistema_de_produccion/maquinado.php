<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../includes/fecha.php');
include_once('../../clases/validador.php');

//incluimos el archivo para generar EXCEL
include("excelwriter.inc.php");

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

include_once('../../clases/sistema_de_produccion/maquinado.php');

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
$validar = new Validador();
$maquinado = new Maquinado;
//

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {

	if (isset($_GET['opcion'])){
		$opcion = $_GET['opcion'];
	}
	
	$fecha_inicio = date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d")-date("d")+1, date("Y"))); 
	$fecha_fin = date("Y-m-d", mktime(0, 0, 0, date("m") , date("d")-date("d"), date("Y"))); 
	
//	$fecha_inicio = $fecha_inicio." 00:00:00";
//	$fecha_fin = $fecha_fin." 24:00:00";
	
	switch ($opcion){
		case 1:{
			//echo "formulario especificar fechas reporte maquinado";
			$smarty->assign('fecha_inicio', $fecha_inicio);
			$smarty->assign('fecha_fin', $fecha_fin);
			
			$smarty->display('sistema_de_produccion/maquinado/formulario_maquinado.html');
			break;
		}
	}
		
	if (!empty($_POST)){
		if ($_POST['generar']){
			$fecha_inicio = trim($_POST["fecha_inicio"]);
			$fecha_fin = trim($_POST["fecha_fin"]);
			
			$fecha_inicio_rep = $fecha_inicio." 00:00:00";
			$fecha_fin_rep = $fecha_fin." 24:00:00";
			
			
			//validacion
			if ($fecha_fin < $fecha_inicio){
				$error['fecha'] = "La fecha de finalizaci&oacute;n debe ser mayor";
			}
			//fin validacion
				
			if (isset($error)){
				//reenviar los errores
				$smarty->assign('error', $error);
				//desplegamos el listado de la busqueda
				$smarty->assign('fecha_inicio', $fecha_inicio);
				$smarty->assign('fecha_fin', $fecha_fin);
				
				$smarty->display('sistema_de_produccion/maquinado/formulario_maquinado.html');
			} else {
				//echo "<br>mostrar reportes de maquinado de las fechas seleccionadas";
				//1 para corte
				$lista_maquinado = $maquinado->reporte_corte($fecha_inicio_rep, $fecha_fin_rep);
				if ($lista_maquinado != null){
					$nombre_archivo = 'corte-'.$fecha_inicio.'-'.$fecha_fin;
					$maquinado->reporte_corte_excel($nombre_archivo, $lista_maquinado);
					$smarty->assign('corte', $nombre_archivo);
				}
				//2 para dividido
				$clave = 2;
				$lista_dividido = $maquinado->reporte_varios($fecha_inicio_rep, $fecha_fin_rep, $clave);
				if ($lista_dividido != null){
					$nombre_archivo = 'dividido-'.$fecha_inicio.'-'.$fecha_fin;
					$maquinado->reporte_varios_excel($nombre_archivo, $lista_dividido);
					$smarty->assign('dividido', $nombre_archivo);
				}
				//3 para desbaste
				$clave = 3;
				$lista_desbaste = $maquinado->reporte_varios($fecha_inicio_rep, $fecha_fin_rep, $clave);
				if ($lista_desbaste != null){
					$nombre_archivo = 'desbaste-'.$fecha_inicio.'-'.$fecha_fin;
					$maquinado->reporte_varios_excel($nombre_archivo, $lista_desbaste);
					$smarty->assign('desbaste', $nombre_archivo);
				}
				//4 para sellado
				$clave = 4;
				$lista_sellado = $maquinado->reporte_varios($fecha_inicio_rep, $fecha_fin_rep, $clave);
				if ($lista_sellado != null){
					$nombre_archivo = 'sellado-'.$fecha_inicio.'-'.$fecha_fin;
					$maquinado->reporte_varios_excel($nombre_archivo, $lista_sellado);
					$smarty->assign('sellado', $nombre_archivo);
				}
				//5 para planchado
				$clave = 5;
				$lista_planchado = $maquinado->reporte_varios($fecha_inicio_rep, $fecha_fin_rep, $clave);
				if ($lista_planchado != null){
					$nombre_archivo = 'planchado-'.$fecha_inicio.'-'.$fecha_fin;
					$maquinado->reporte_varios_excel($nombre_archivo, $lista_planchado);
					$smarty->assign('planchado', $nombre_archivo);
				}
				//desplegamos los resultados
				$smarty->assign('fecha_inicio', $fecha_inicio);
				$smarty->assign('fecha_fin', $fecha_fin);
				$smarty->display('sistema_de_produccion/maquinado/formulario_maquinado.html');
			}// if (isset($error))
		}//if ($_POST['generar'])
	}
}

?>