<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../includes/fecha.php');
include_once('../../clases/validador.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/sistema_de_produccion/asignacion.php');
include_once('../../clases/sistema_de_produccion/hoja_ruta_corte.php');

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
$validar=new Validador();
$ordenes = new OrdenProd;
$asignacion = new Asignacion;
$hoja_ruta_corte = new Hoja_ruta_corte;
//

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if ($_GET['tabu']){
		$_SESSION['tabu'] = $_GET['tabu'];
		$smarty->assign('tabu', $_SESSION['tabu']);
	}

	if(isset($_GET["busqueda_ajax"])){    
		if ($_GET["busqueda_ajax"] == 'nombres'){
			//recuperando las variables
			$nombre =  utf8_decode(trim($_POST["nombre"]));
			$puesto = trim($_GET["puesto"]);
			
			echo "<ul>";
			$lista = $asignacion->busqueda_personal($nombre, $puesto);
		
			if(count($lista) == 0){
				echo "<li>No hay resultados</li>";
			} else {
				for($contador = 0; $contador < count($lista); $contador ++){
					$detalles = $lista[$contador]["personal_id"]."-".$lista[$contador]["clase"];
					echo '<li id="'.$detalles.'">&nbsp;'.$lista[$contador]["clase"].' - '.$lista[$contador]["completo"].'</li>';
				}
			}
			echo "</ul>";
		}
	} else {
	
		$opcion = $_GET['opcion'];
		switch ($opcion){
			case 1:{
				//
				$lista = $asignacion->consulta_lista_ordenes_anual(date("Y"));
				$smarty->assign('ordenes', $lista);
				$smarty->display('sistema_de_produccion/corte/orden_imprimir_cabecera.html');
				break;
			}
			case 2:{
				$op_id = $_GET['oid'];
				$smarty->assign('op_id', $op_id);
				//$_SESSION['$op_id'] = $op_id;
				
				//detalle de la orden
				$cabecera = $ordenes->obtener_cabecera_orden($op_id);
				$smarty->assign('cabecera', $cabecera);
				
				if(isset($_SESSION['mensaje'])){
					$smarty->assign('mensaje', $_SESSION['mensaje']);
				}
				
				$detalle = $hoja_ruta_corte->detalle_orden_inicial($op_id,1);
				$smarty->assign('detalle', $detalle);
				$smarty->display('sistema_de_produccion/corte/detalle_imprimir_cabecera.html');
				break;
			}
			case 3:{
				//**************************generamos la cabacera del reporte
				$hid = $_GET['hid'];
				$cabecera = $hoja_ruta_corte->cabecera_reporte($hid);
				$smarty->assign('cabecera', $cabecera);
				//**************************
				$smarty->display('sistema_de_produccion/corte/hoja_ruta_reporte.html');
				break;
			}
			case 4:{
				$hid = $_GET['hid'];
				$hoja_ruta_corte->modificar_impresion($hid);
				header("Location: hoja_ruta_corte_cabecera.php?opcion=2&oid=".$_GET['oid']);
				break;
			}
		}
		
		if (!empty($_POST)){
			if ($_POST['buscar']){
				$norden = trim($_POST["norden"]);
				
				//validacion
				if ($validar->validarTodo($norden, 1, 100)){
					$error['err_norden'] = "Ingrese N&uacute;mero de &Oacute;rden";
				}
				
				if (isset($error)){
					//reenviar los errores
					$smarty->assign('errores', $error);
				} else {
					//echo "<br>Buscar por: Asignacion";
					$consulta = $asignacion->consultar_busqueda($norden, "num_orden");
					if ($consulta != null){
						$smarty->assign('ordenes', $consulta);
					} else {
						$smarty->assign('mensaje', "No se encontr&oacute; la &Oacute;rden de Producci&oacute;n");
					}
				}
				//desplegamos el listado de la busqueda
				$smarty->assign('norden', $norden);
				$smarty->display('sistema_de_produccion/corte/orden_imprimir_cabecera.html');
			}
			
		}
	}
}

?>