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

include_once('../../clases/sistema_de_produccion/exportaciones.php');

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
$validar = new Validador();
$exportaciones = new Exportaciones;
//

//******************************************************
function escribir_exportaciones_excel($nombre_archivo, $datos){
/*	echo "<pre>";
	print_r($datos);
	echo "</pre>";*/

	$excel=new ExcelWriter("../../reportes/exportaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Cantidad","Modelo","Tipo","Clip","Color","Pedido","Observaciones","Grabado","Lugar Grabado","Sello","Orden","Observaciones");
	$excel->writeCabecera($myArr);
	
	foreach($datos as $key => $value){
		$cantidad = $datos[$key]['cantidad'];
		$modelo = $datos[$key]['modelo'];
		$tipo = $datos[$key]['tipo'];
		$clip = $datos[$key]['clip'];		
		$color = $datos[$key]['color'];
		$pedido = $datos[$key]['pedido'];

		$observaciones = $datos[$key]['observaciones'];
		$grabado = $datos[$key]['grabado'];
		$lugargrabado = $datos[$key]['lugargrabado'];
		$sello = $datos[$key]['sello'];

		$orden = $datos[$key]['orden'];
		$observacion = $datos[$key]['observacion'];
		
		$myArr=array($cantidad,$modelo,$tipo,$clip,$color,$pedido,$observaciones,$grabado,$lugargrabado,$sello,$orden,$observacion);
		$excel->writeLine($myArr);
	}
	
	$excel->close();
}
//******************************************************
function escribir_exportaciones_excel_formato($nombre_archivo, $datos){
/*	echo "<pre>";
	print_r($datos);
	echo "</pre>";*/

	$excel=new ExcelWriter("../../reportes/exportaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;

	//Imprime la cabecera SPE		
	$excel->cabecera_SPE();

	foreach($datos as $key => $value){
		$cantidad = $datos[$key]['cantidad'];
		$modelo = $datos[$key]['modelo'];
		$tipo = $datos[$key]['tipo'];
		$clip = $datos[$key]['clip'];		
		$color = $datos[$key]['color'];
		$pedido = $datos[$key]['pedido'];

		$observaciones = $datos[$key]['observaciones'];
		$grabado = $datos[$key]['grabado'];
		$lugargrabado = $datos[$key]['lugargrabado'];
		$sello = $datos[$key]['sello'];

		$orden = $datos[$key]['orden'];
		$observacion = $datos[$key]['observacion'];
		
		$myArr=array("", $observacion, $cantidad, $pedido, $observaciones, $modelo, $tipo, $color, $clip);
		$excel->writeLine($myArr);
	}

	$excel->pie_SPE();
	$excel->close();
}
//******************************************************

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if ($_GET['tabu']){
		$_SESSION['tabu'] = $_GET['tabu'];
		$smarty->assign('tabu', $_SESSION['tabu']);
	}

	if(isset($_GET["busqueda_ajax"])){
		if ($_GET["busqueda_ajax"] == 'ordenes'){
			$num_orden = utf8_decode($_GET["value"]);
			
			echo "<ul>";
			$lista = $despacho->busqueda_ordenes($num_orden);
			if(count($lista) == 0){
				echo "<li>No hay resultados</li>";
			} else {
				for($contador = 0; $contador < count($lista); $contador ++){
					$detalles = $lista[$contador]['orden_id'];
					echo '<li id="'.$detalles.'">'.$lista[$contador]['num_orden'].'</li>';
				}
			}
			echo "</ul>";
		}
	} else {
	
		$opcion = $_GET['opcion'];
		switch ($opcion){
			case 1:{
				//liberar
				$ultimos_despachos = $exportaciones->ultimos_despachos();
				$smarty->assign('lista_despachos', $ultimos_despachos);
				$smarty->display('sistema_de_produccion/exportaciones/busqueda_exportaciones.html');
				break;
			}
			case 2:{
				//capturamos el despacho id
				$despacho_id = $_GET['deid'];
								
				$detalle_despacho = $exportaciones->detalle_despachos($despacho_id);
				$smarty->assign('detalle_despacho', $detalle_despacho);
				
				//escribimos los archivos
				$nombre_archivo = $detalle_despacho['nombre_despacho']."-".$detalle_despacho['fecha_despacho'];
				$datos = $exportaciones->reporte_despachos($despacho_id);
				escribir_exportaciones_excel($nombre_archivo, $datos);
				
				
				//********************************************************************
				//************************** Con formato *****************************
				$cadena_original = strtoupper($detalle_despacho['nombre_despacho']);
				$formato = 'ESPECIALES';				
				if(ereg($formato, $cadena_original)) {
						$mostrar_formato = false;
				} else {
					$formato = 'SPE';
					if(ereg($formato, $cadena_original)) {
						$smarty->assign('formato', $formato);
						$nombre_archivo = $formato."-".$detalle_despacho['nombre_despacho']."-".$detalle_despacho['fecha_despacho'];
						escribir_exportaciones_excel_formato($nombre_archivo, $datos);
						
						$mostrar_formato = true;
					} else {
						$mostrar_formato = false;
					}
				}
				
				//********************************************************************
				
				$smarty->assign('mostrar_formato', $mostrar_formato);
				$smarty->display('sistema_de_produccion/exportaciones/detalle_exportacion.html');
				break;
			}
		}
		
		if (!empty($_POST)){
			if ($_POST['buscar_despacho']){
				$nombre_despacho = trim($_POST["nombre_despacho"]);
				
				//validacion
				if ($validar->validarTodo($nombre_despacho, 1, 100)){
					$error['nombre_despacho'] = "Ingrese nombre del despacho";
				}
				
				if (isset($error)){
					//reenviar los errores
					$smarty->assign('errores', $error);
				} else {
					//echo "<br>Buscar despacho";
					$lista_despachos = $exportaciones->buscar_despacho($nombre_despacho);
					if ($lista_despachos != null){
						$smarty->assign('lista_despachos', $lista_despachos);
					} else {
						$smarty->assign('mensaje', "No se encontr&oacute; resultados");
					}
				}
				//desplegamos el listado de la busqueda
				$smarty->assign('nombre_despacho', $nombre_despacho);
				$smarty->display('sistema_de_produccion/exportaciones/busqueda_exportaciones.html');
			}// if ($_POST['buscar_despacho'])
		}
	}
}

?>