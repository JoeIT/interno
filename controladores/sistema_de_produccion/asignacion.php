<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/sistema_de_produccion/asignacion.php');
include_once('../../clases/sistema_de_produccion/clientes.php');
include_once('../../clases/sistema_de_produccion/usuarios.php');
include_once('../../clases/sistema_de_produccion/recepcion.php');
include_once('../../clases/validador.php');
require("../includes/fecha.php");

include("excelwriter.inc.php");

$ordenes = new OrdenProd;
$cliente = new Cliente;
$usuario = new Usuarios;
$validar = new Validador;
$asignacion = new Asignacion;
$recepcion = new Recepcion;

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);
//

function escribir_asignacion_excel($nombre_archivo, $datos){
	$excel=new ExcelWriter("../../reportes/asignaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Cup","Asig","Cant", "Descripcion","Estilo_es","Color_es","Clip_es","Nombres","Apellidos","Clase", "Fecha_f","fecha_r","obs");
	$excel->writeCabecera($myArr);
	
	foreach($datos as $key => $value){
		
		$cup = $datos[$key]['cup'];
		$id = $datos[$key]['id'];
		$asignacion = $datos[$key]['asignacion'];
		$descripcion = $datos[$key]['descripcion'];
		$estilo_es = $datos[$key]['estilo_es'];
		$color_es = $datos[$key]['color_es'];
		$clip_es = $datos[$key]['clip_es'];
		$nombres = $datos[$key]['nombres'];
		$apellidos = $datos[$key]['apellidos'];
		$clase = $datos[$key]['clase'];
		$fecha_f = $datos[$key]['fecha_f'];
		$fecha_r = $datos[$key]['fecha_r'];
		$obs = $datos[$key]['obs'];
		
		$myArr=array($cup,$id,$asignacion,$descripcion,$estilo_es,$color_es,$clip_es,$nombres,$apellidos,$clase,$fecha_f,$fecha_r,$obs);
		$excel->writeLine($myArr);
	}

	
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}

//funcion para sacar la fecha media
function fecha_media($fecha1, $fecha2){
	list($ano1,$mes1,$dia1) = split("-",$fecha1);
	$nueva_fecha1 = $dia1."-".$mes1."-".$ano1;

	list($ano2,$mes2,$dia2) = split("-",$fecha2);
	$nueva_fecha2 = $dia2."-".$mes2."-".$ano2;
	
	$timestamp1 = mktime(0,0,0,$mes1,$dia1,$ano1);
	$timestamp2 = mktime(4,12,0,$mes2,$dia2,$ano2);
	
	$segundos_diferencia = $timestamp1 - $timestamp2;
	$dias_diferencia = $segundos_diferencia / (60 * 60 * 24);
	
	$dias_diferencia = abs($dias_diferencia);
	$ndias = floor($dias_diferencia / 2);

	$timeStamp = strtotime($fecha1);
	$timeStamp += 24 * 60 * 60 * $ndias;
	$newDate = date("Y-m-d", $timeStamp);
	
	return $newDate;
}
//

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	//fecha de hoy
	$fecha_hoy = date("Y-m-d");

	if(isset($_GET["busqueda_ajax"])){
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
	} else {
		//aqui hacer las acciones
		if ($_GET['tabu']){
			$_SESSION['tabu'] = $_GET['tabu'];//
			$smarty->assign('tabu', $_GET['tabu']);
		}
	
		switch ($_GET['opcion']){
			case 1 :{
				$_SESSION['clave_asig'] = 0;
				unset($_SESSION['bandera']);

				$lista = $asignacion->consulta_lista_ordenes_anual(date("Y"));
				$smarty->assign('ordenes', $lista);
				//echo "imprimir lista inicial";
				$smarty->display('sistema_de_produccion/asignacion/formulario.html');
				$smarty->display('sistema_de_produccion/asignacion/detalle.html');
				break;
			}
	
			case 2 :{
				$_SESSION['clave_asig'] = 0;
				unset($_SESSION['bandera']);

				//ver detalle de la orden con su respectiva cabecera
				$op_id = $_GET['oid'];
				$cabecera = $ordenes->obtener_cabecera_orden($op_id);
				$smarty->assign('cabecera', $cabecera);
				
				$detalle = $asignacion->resumen_inicial($op_id);
				$smarty->assign('detalle', $detalle);
				
				//mostramos el resumen numerico
				$resumen_numerico = $asignacion->resumen_numerico_asignaciones($op_id);
				/*echo "<pre>";
				print_r($detalle);
				echo "</pre>";*/
				
				$smarty->assign('resumen_numerico', $resumen_numerico);
				
				//echo "imprimir la cabezera y el detalle de la orden";
				$smarty->display('sistema_de_produccion/asignacion/cabecera_orden.html');
				break;
			}
			case 3 :{
				unset($_SESSION['bandera']);
		
				if (isset($_GET['flag'])){
					$lista_mdr = $_SESSION['lista_mdr'];
					$smarty->assign('nombre', $lista_mdr['completo']);
					$smarty->assign('categoria', $lista_mdr['clase']);
					$smarty->assign('cantidad', $lista_mdr['cantidad_asignada']);
					$smarty->assign('muestra', $lista_mdr['cantidad_muestra']);
					$smarty->assign('finicio', $lista_mdr['fecha_inicio']);
					$smarty->assign('ffin', $lista_mdr['fecha_finalizacion']);				
					$smarty->assign('observaciones', $lista_mdr['observaciones']);
					$smarty->assign('personal_id', $lista_mdr['personal_id']);
				}
				
				$op_id = $_GET['oid'];
				$_SESSION['oid'] = $op_id;
				$de_id = $_GET['did'];
				$_SESSION['did'] = $de_id;
				$smarty->assign('did',$de_id);
				
				//ver detalle de la orden con su respectiva cabecera
				$cabecera = $ordenes->obtener_cabecera_orden($op_id);
				$smarty->assign('cabecera', $cabecera);

				//verificar si existe pendientes
				$lista_pendientes = $asignacion->sacar_pendientes($de_id);
				$valor_pendientes = $lista_pendientes["asignados"];
				
				//para generar las cabeceras
				$detalle_asig = $asignacion->obtener_detalle_orden($op_id, $de_id);
				if ($valor_pendientes < 1){
					$smarty->assign('mensaje', "No existe pendientes para asignar");
				} else {
					//generamos
					$detalle_asig = $asignacion->obtener_detalle_orden($op_id, $de_id);
					//for ($i = 1; $i <= $detalle_asig["Pendientes"]; $i ++){
					//	$vector_pendientes[$i] = $i;
					//}
					
					for ($i = $detalle_asig["Pendientes"]; $i >=1 ; $i --){
						$vector_pendientes[$i] = $i;
					}
					
					
					$smarty->assign('vector_pendientes', $vector_pendientes);
					//fin generamos
					if ($valor_pendientes < 5){
						for ($i = 1; $i <= $valor_pendientes; $i ++){
							$vector_muestras[$i] = $i;
						}
					} else {
						for ($i = 1; $i <= 5; $i ++){
							$vector_muestras[$i] = $i;
						}
					}
					$smarty->assign('vector_muestras', $vector_muestras);
				}
				//fin verificar
				
				
				if ((!isset($error)) && ($_SESSION['clave_asig'] != 1)){
					$smarty->assign('cantidad',$detalle_asig["Pendientes"]);
					$smarty->assign('muestra',1);
				}
				

				$smarty->assign('detalle_asig', $detalle_asig);
				
				$lista_detalle_resultados = $asignacion->ver_detalle_resultados($de_id);
				$smarty->assign('lista_detalle_resultados', $lista_detalle_resultados);
			
				//echo "mostrar formulario de asignacion";
				$smarty->display('sistema_de_produccion/asignacion/registrar_asignacion.html');
				break;
			}
			case 4 :{
				//mostramos los datos existentes para editar
				$_SESSION['clave_asig'] = 1;
								
				$_SESSION['did'] = $_GET['did'];
				$_SESSION['daid'] = $_GET['daid'];
				$_SESSION['bandera'] = "bandera";
				
				$lista_mdr = $asignacion->ver_modificar_detalle_resultados($_GET['daid']);
				$_SESSION['lista_mdr'] = $lista_mdr;
				
				//1ro sacamos la cantidad al original
					$cantidad_asignada = $asignacion->sacar_cantidad($_SESSION['daid']);
					$actual_cantidad = $cantidad_asignada["cantidad_asignada"];
					//echo "<br>tiene cantidad:".$actual_cantidad;
					//echo "<br>1ro colocar la cantidad a su numero original";
					$asignacion->reestablecer_asignar_detalle_resultados($_SESSION['did'], $actual_cantidad);
					
					$detalle_asig = $asignacion->obtener_detalle_orden($_SESSION['oid'], $_SESSION['did']);
					for ($i = 1; $i <= $detalle_asig["Pendientes"]; $i ++){
						$vector_pendientes[$i] = $i;
					}
					$_SESSION['vector_pendientes'] = $vector_pendientes;
				//fin 1ro
				
				//2do borramos de asignacion_detalle 
					$asignacion->eliminar_asignar_detalle($_SESSION['daid']);
				//3ro redireccionamos a la pagina
				header("Location: asignacion.php?opcion=3&flag=1&oid=".$_SESSION['oid']."&did=".$_SESSION['did']);
				break;
			}
			case 5 :{
				//imprimir
				$_SESSION['clave_asig'] = 0;
				header("Location: asignacion.php?opcion=3&oid=".$_SESSION['oid']."&did=".$_SESSION['did']);
				break;
			}
			case 6 :{
				//reporte maquinistas
				$reporte_maquinistas=$asignacion->reporte_maquinistas();
				$smarty->assign('reporte_maquinistas',$reporte_maquinistas);
				$smarty->display('sistema_de_produccion/asignacion/reporte_maquinistas.html');
				break;
			}
			case 7 :{
				//listado de asignaciones
				$lista = $asignacion->consulta_lista_ordenes_anual(date("Y"));
				$smarty->assign('ordenes', $lista);
				//echo "imprimir lista inicial";
				$smarty->display('sistema_de_produccion/asignacion/listado_asignaciones.html');
				break;
			}
			case 8 :{
				//ver detalle para las asignaciones
				$op_id = $_GET['oid'];
				$cabecera = $ordenes->obtener_cabecera_orden($op_id);
				$smarty->assign('cabecera', $cabecera);
				
				$detalle = $asignacion->resumen_inicial($op_id);
				$smarty->assign('detalle', $detalle);
				//echo "imprimir la cabezera y el detalle de la orden";
				$smarty->display('sistema_de_produccion/asignacion/listado_asignaciones_detalle.html');
				break;
			}
			case 9 :{
				$op_id = $_GET['oid'];
				$_SESSION['oid'] = $op_id;
				$smarty->assign('oid',$op_id);

				$de_id = $_GET['did'];
				$_SESSION['did'] = $de_id;
				$smarty->assign('did',$de_id);
				
				//ver detalle de la orden con su respectiva cabecera
				$cabecera = $ordenes->obtener_cabecera_orden($op_id);
				$smarty->assign('cabecera', $cabecera);

				//para generar las cabeceras
				$detalle_asig = $asignacion->obtener_detalle_orden($op_id, $de_id);
				$smarty->assign('detalle_asig', $detalle_asig);
				
				$lista_detalle_resultados = $asignacion->ver_detalle_resultados($de_id);
				if ($lista_detalle_resultados){
					$smarty->assign('lista_detalle_resultados', $lista_detalle_resultados);
				} else {
					$smarty->assign('mensaje', "No existen maquinistas asignados");
				}
			
				//echo "mostrar formulario de asignacion";
				$smarty->display('sistema_de_produccion/asignacion/listado_asignaciones_maquinistas.html');
				break;
			}
			case 10 :{
				if ($_POST['buscar']){
					$num_asignacion = trim($_POST['num_asignacion']);
					if ($num_asignacion == '')
						$error['num_asignacion'] = "Ingresar N&uacute;mero de asignaci&oacute;n";
					
					if (isset($error)) {
						$smarty->assign('errores', $error);
					} else {
						$resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
						if ($resultado_asignacion != null){
							$smarty->assign('mpf', $resultado_asignacion["medio_proceso_fecha"]);
							$smarty->assign('mif', $resultado_asignacion["muestra_inicial_fecha"]);
							if ($resultado_asignacion["usuario_recepcion"] == 0){
								$smarty->assign('cerrado', "no");
								$smarty->assign('tipo_mp', $resultado_asignacion["medio_proceso_tipo"]);
								//
								$smarty->assign('resultado_asignacion', $resultado_asignacion);
								//
								//echo "<br>ra: ".$resultado_asignacion["medio_proceso_tipo"];
								if ($resultado_asignacion["medio_proceso_fecha"] != null || $resultado_asignacion["muestra_inicial_fecha"] != null){
									$smarty->assign('mensaje', "Asignaci&oacute;n con registro del tipo: '".$resultado_asignacion["medio_proceso_literal"]."'");
								}
							} else {
								$smarty->assign('mensaje', "Asignaci&oacute;n dada de baja, con tipo: '".$resultado_asignacion["medio_proceso_literal"]."'");
							}
						} else {
							$smarty->assign('mensaje', "No se encontr&oacute; la asignaci&oacute;n");
						}
					}
				}
				
				if ($_POST['registrar']){
					$num_asignacion = trim($_POST['num_asignacion']);
					$tipo = $_POST['tipo'];
					$fecha = date("Y-m-d");
					$consulta = $asignacion->medio_proceso($num_asignacion, $tipo, $fecha);
					/*
					echo "<pre>";
						print_r($_POST);
					echo "</pre>";
					*/
				}
				
				$smarty->assign('num_asignacion', $num_asignacion);
				$smarty->display('sistema_de_produccion/asignacion/medio_proceso.html');
				break;
			}
				case 11 :{
				//reporte asignaciones
				$fecha_inicio = date("Y-m-d"); 
			    $fecha_fin = date("Y-m-d"); 
				//$reporte_asignacione=$asignacion->reporte_asignacion($mes,$año);
				//$smarty->assign('reporte_asignaciones',$reporte_asignaciones);
				$smarty->assign('fecha_inicio',$fecha_inicio);
				$smarty->assign('fecha_fin',$fecha_fin);
				$smarty->display('sistema_de_produccion/asignacion/reporte_asignacion.html');
				break;
			}
			case 12 :{
				
				/*$_SESSION['clave_asig'] = 0;
				unset($_SESSION['bandera']);*/
				$asig = new Asignacion;
				$lista1 = $asig->consulta_lista_pendiente(date("Y"));
				
				foreach($lista1 as $indice => $valor) 
					{
						$orden_id[$valor['orden_id']]=$valor['orden_id'];
					}
				
				$smarty->assign('ordenes', $lista1);
				$smarty->assign('orden_id', $orden_id);
				
				//echo "imprimir lista inicial";
				//$smarty->display('sistema_de_produccion/asignacion/formulario.html');
			
				$smarty->display('sistema_de_produccion/asignacion/detalle_pendientes.html');
				break;
			}
		}
		
		if (!empty($_POST)){
			if ($_POST['funcion']){
				//buscamos el detalle
				$cadena = trim($_POST['orden']);
				$opcion = trim($_POST['tipo']);
	
				if ($validar->validarTodo($cadena, 1, 100)){
					if ($opcion == "num_orden"){
						$error['err_nombre'] = "Ingresar C&oacute;digo de &Oacute;rden";
					} else {
						$error['err_nombre'] = "Ingresar Nombre de Cliente";
					}
				}
	
				$smarty->assign('orden', $cadena);

				if (isset($error)){
					//reenviar los errores
					$smarty->assign('errores', $error);
					
					$smarty->assign('tabu', $_SESSION['tabu']);
					$smarty->display('sistema_de_produccion/asignacion/formulario.html');
				} else {
					$consulta = $asignacion->consultar_busqueda($cadena, $opcion);
					if ($consulta != null){
						$smarty->assign('ordenes', $consulta);
						$smarty->assign('tabu', $_SESSION['tabu']);
						$smarty->display('sistema_de_produccion/asignacion/formulario.html');
						$smarty->display('sistema_de_produccion/asignacion/detalle.html');
					} else {
						if ($opcion == "num_orden"){
							$smarty->assign('mensaje', "No se encontr&oacute; la &Oacute;rden de Producci&oacute;n");
						} else {
							$smarty->assign('mensaje', "No se encontr&oacute; el Nombre de Cliente");
						}
						$smarty->assign('orden', $cadena);
						$lista = $asignacion->consulta_lista_ordenes_anual(date("Y"));
						$smarty->assign('ordenes', $lista);
						
						$smarty->assign('tabu', $_SESSION['tabu']);
						$smarty->display('sistema_de_produccion/asignacion/formulario.html');
					}
				}				
				//fin de la busqueda
			}
			
			if ($_POST['Asignar']){
				//validacion de los datos
				$nombre = $_POST['nombre'];
				if ($validar->validarTodo($nombre, 1, 100)){
					$error['err_nombre'] = "Ingrese nombre";
				}
				
				$categoria = $_POST['categoria'];
				if ($validar->validarTodo($categoria, 1, 100)){
					$error['err_categoria'] = "Especificar categoria";
				}
				
				$cantidad = $_POST['cantidad'];
				$muestra = $_POST['muestra'];
				
				$finicio = $_POST['finicio'];
				if ($validar->validarTodo($finicio, 1, 100)){
					$error['err_finicio'] = "Ingresar fecha de inicio";
				} else {
					if (!$validar->validarFecha2($finicio)){
						$error['err_finicio'] = "Fecha de inicio no valida";
					}
				}
				
				$ffin = $_POST['ffin'];
				if ($validar->validarTodo($ffin, 1, 100)){
					$error['err_ffin'] = "Ingresar fecha de finalizci&oacute;n";
				} else {
					if (!$validar->validarFecha2($ffin)){
						$error['err_ffin'] = "Fecha de finalizaci&oacute;n no valida";
					} else {
						if ($ffin < $finicio){
							$error['err_ffin'] = "Debe ser mayor a la recha de inicio";
						}
					}
				}
				
				$observaciones = $_POST['observaciones'];
			//fin validacion
			
			//verificamos si existe errores
				if (isset($error)){
					$smarty->assign('nombre', $nombre);
					$smarty->assign('categoria', $categoria);
					$smarty->assign('cantidad', $cantidad);
					$smarty->assign('muestra', $muestra);
					$smarty->assign('finicio', $finicio);
					$smarty->assign('ffin', $ffin);				
					$smarty->assign('observaciones', $observaciones);
					$smarty->assign('personal_id', $_POST['maquinista_id']);
					
					$smarty->assign('errores', $error);
					
					//generamos				
					$detalle_asig = $asignacion->obtener_detalle_orden($_SESSION['oid'], $_SESSION['did']);
					for ($i = $detalle_asig["Pendientes"]; $i >= 1; $i --){
						$vector_pendientes[$i] = $i;
					}
					$smarty->assign('vector_pendientes', $vector_pendientes);

					//***********************
					if ($detalle_asig["Pendientes"] < 5){
						for ($i = 1; $i <= $detalle_asig["Pendientes"]; $i ++){
							$vector_muestras[$i] = $i;
						}
					} else {
						for ($i = 1; $i <= 5; $i ++){
							$vector_muestras[$i] = $i;
						}
					}
					$smarty->assign('vector_muestras', $vector_muestras);
					//***********************
					//fin generamos
				} else {
			//si no existen errores
					$maquinista_id = $_POST['maquinista_id'];
					$detalle_id = $_POST['detalle_id'];
					//datos generales: responsable y fechas
					$responsable_asignacion = $_SESSION["usuario_id"];
					$fecha_asignacion = $fecha_hoy;
					$fecha_medio = fecha_media($finicio, $ffin);
					//fin datos
					
					if (isset($_SESSION['bandera'])){
						//echo "existe";
						$_SESSION['daid'] = $encontrar['asignacion_detalle_id'];
						$asignacion->modificar_detalle_resultados($detalle_id, $_SESSION['daid'], $maquinista_id, $cantidad, $muestra, $fecha_asignacion, $responsable_asignacion, $finicio, $fecha_medio, $ffin, $observaciones);
					} else {
						//echo "<br>no existe: ".$_POST['pendientes_nu'];
						//verificamos si los pendientes son >= 1
						$pendientes_nu = $_POST['pendientes_nu'];
						if ($pendientes_nu >= 1){
							$asignacion->asignar_detalle_resultados($detalle_id, $maquinista_id, $cantidad, $muestra,$fecha_asignacion, $responsable_asignacion, $finicio, $fecha_medio, $ffin, $observaciones);
							//mandamos un mensaje
							$smarty->assign('aviso_asignacion', "La asignaci&oacute;n se realiz&oacute; satisfactoriamente.");
						} else {
							$smarty->assign('mensaje', "No existe pendientes para asignar");
						}
						//fin verificar
					}
					
					//ver detalle de la orden con su respectiva cabecera
					$cabecera = $ordenes->obtener_cabecera_orden($_SESSION['oid']);
					$smarty->assign('cabecera', $cabecera);
					
					//verificar si existe pendientes
					$lista_pendientes = $asignacion->sacar_pendientes($detalle_id);
					$valor_pendientes = $lista_pendientes["asignados"];
					if ($valor_pendientes < 1){
						$smarty->assign('mensaje', "No existe pendientes para asignar");
					}
					//fin verificar
				}
				//
				unset($_SESSION['bandera']);
				if ((!isset($error))){
					$smarty->assign('cantidad', 1);
					$smarty->assign('muestra', 1);
				}
				
				$op_id = $_SESSION['oid'];
				$_SESSION['oid'] = $op_id;
				$de_id = $_SESSION['did'];
				$_SESSION['did'] = $de_id;
				$smarty->assign('did',$de_id);
				
				//verificar si existe pendientes
				$lista_pendientes = $asignacion->sacar_pendientes($de_id);
				$valor_pendientes = $lista_pendientes["asignados"];
				
				if ($valor_pendientes < 1){
					$smarty->assign('mensaje', "No existe pendientes para asignar");
				}
				//fin verificar
				
				//generamos				
				$detalle_asig = $asignacion->obtener_detalle_orden($op_id, $de_id);
				for ($i = $detalle_asig["Pendientes"]; $i >= 1; $i --){
					$vector_pendientes[$i] = $i;
				}
				$smarty->assign('vector_pendientes', $vector_pendientes);
				//fin generamos
				if ($valor_pendientes < 5){
					for ($i = 1; $i <= $valor_pendientes; $i ++){
						$vector_muestras[$i] = $i;
					}
				} else {
					for ($i = 1; $i <= 5; $i ++){
						$vector_muestras[$i] = $i;
					}
				}
				$smarty->assign('vector_muestras', $vector_muestras);
				
				$smarty->assign('detalle_asig', $detalle_asig);
				
				$lista_detalle_resultados = $asignacion->ver_detalle_resultados($de_id);
				$smarty->assign('lista_detalle_resultados', $lista_detalle_resultados);
			
				//echo "mostrar formulario de asignacion";
				$smarty->display('sistema_de_produccion/asignacion/registrar_asignacion.html');
				//
			}
			
			
			if ($_POST['funcion_listado']){
				//buscamos el detalle
				$cadena = trim($_POST['orden']);
				$opcion = trim($_POST['tipo']);
	
				if ($validar->validarTodo($cadena, 1, 100)){
					if ($opcion == "num_orden"){
						$error['err_nombre'] = "Ingresar C&oacute;digo de &Oacute;rden";
					} else {
						$error['err_nombre'] = "Ingresar Nombre de Cliente";
					}
				}
	
				$smarty->assign('orden', $cadena);

				if (isset($error)){
					//reenviar los errores
					$smarty->assign('errores', $error);
					
					$smarty->assign('tabu', $_SESSION['tabu']);
					$smarty->display('sistema_de_produccion/asignacion/listado_asignaciones.html');
				} else {
					$consulta = $asignacion->consultar_busqueda($cadena, $opcion);
					if ($consulta != null){
						$smarty->assign('ordenes', $consulta);
						$smarty->assign('tabu', $_SESSION['tabu']);
						$smarty->display('sistema_de_produccion/asignacion/listado_asignaciones.html');
					} else {
						if ($opcion == "num_orden"){
							$smarty->assign('mensaje', "No se encontr&oacute; la &Oacute;rden de Producci&oacute;n");
						} else {
							$smarty->assign('mensaje', "No se encontr&oacute; el Nombre de Cliente");
						}
						$smarty->assign('orden', $cadena);
						$lista = $asignacion->consulta_lista_ordenes_anual(date("Y"));
						$smarty->assign('ordenes', $lista);
						
						$smarty->assign('tabu', $_SESSION['tabu']);
						$smarty->display('sistema_de_produccion/asignacion/listado_asignaciones.html');
					}
				}				
				//fin de la busqueda
			}
			/////////////////
			if ($_POST['repasignacion']){
				//buscamos el detalle
				$fecha_inicio = trim($_POST["fecha_inicio"]);
				$fecha_fin = trim($_POST["fecha_fin"]);
				
				if ($fecha_fin < $fecha_inicio){
					
					 $error['fecha'] = "La fecha de finalizaci&oacute;n debe ser mayor";
			         $smarty->assign('error',$error);
			         $smarty->assign('fecha_inicio',$fecha_inicio);
			         $smarty->assign('fecha_fin',$fecha_fin);
					}
				
				
				
								
				
		        //No olvidar la instancia de clase
				if (isset($error)){
					$smarty->assign('error', $error);
					$smarty->display('sistema_de_produccion/asignacion/reporte_asignacion.html');
				} 
				else {
					$asig='Asignaciones-'.$fecha_inicio.'-'.$fecha_fin;
				    $reporte_asignaciones=$asignacion->reporte_asignacion($fecha_inicio,$fecha_fin);
				    $excel_asignacion=$asignacion->reporte_excel($fecha_inicio,$fecha_fin);
				     if ($excel_asignacion != null)
				            {
				               escribir_asignacion_excel($asig, $excel_asignacion);
				               $smarty->assign('excel', $asig);
				             }

					
					
				
				//print_r($reporte_asignaciones) ;
				
				$smarty->assign('reporte_asignaciones',$reporte_asignaciones);
				$smarty->display('sistema_de_produccion/asignacion/lista_asignacion.html');
				}		
				//fin de la busqueda
			}
			
			
			
			
			
			
			//////
			
		}
	}
}
?>