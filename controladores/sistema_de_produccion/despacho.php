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

include_once('../../clases/sistema_de_produccion/despacho.php');

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
$validar = new Validador();
$despacho = new Despacho;
//

//******************************************************
function escribir_maquinistas_excel($nombre_archivo, $datos){
	$excel=new ExcelWriter("../../reportes/administracion/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","OP","Maquinista","Producto","Tipo","Color","Obs","Entregada","f_prevista","Reprog","f_real","Diferencia","Aceptada","Rechazada","Porcentaje","fallas_a","fallas_b","fallas_c","fallas_d","d_retraso","d_rechazo","incremento","observaciones");
	$excel->writeCabecera($myArr);
	
	foreach($datos as $key => $value){
		$codigo = $datos[$key]['codigo'];
		$op = $datos[$key]['op'];
		$maquinista = $datos[$key]['maquinista'];
		$producto = $datos[$key]['producto'];
		$tipo = $datos[$key]['tipo'];
		$color = $datos[$key]['color'];
		$obs = $datos[$key]['obs'];
		$entregada = $datos[$key]['entregada'];
		$f_prevista = $datos[$key]['f_prevista'];
		$reprog = $datos[$key]['reprog'];
		$f_real = $datos[$key]['f_real'];
		$diferencia = $datos[$key]['diferencia'];
		$aceptada = $datos[$key]['aceptada'];
		$rechazados = $datos[$key]['rechazados'];
		$porcentaje = $datos[$key]['porcentaje'];
		$fallas_a = $datos[$key]['A'];
		$fallas_b = $datos[$key]['B'];
		$fallas_c = $datos[$key]['C'];
		$fallas_d = $datos[$key]['D'];
		$d_retraso = $datos[$key]['d_retraso'];
		$d_rechazo = $datos[$key]['d_rechazo'];
		$incremento = $datos[$key]['incremento'];
		$observaciones = $datos[$key]['observaciones'];
		
		$myArr=array($codigo,$op,$maquinista,$producto,$tipo,$color,$obs,$entregada,$f_prevista,$reprog,$f_real,$diferencia,$aceptada,$rechazados,$porcentaje,$fallas_a,$fallas_b,$fallas_c,$fallas_d,$d_retraso,$d_rechazo,$incremento,$observaciones);
		$excel->writeLine($myArr);
	}

	
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}
function escribir_limpiezas_excel($nombre_archivo, $datos){
	$excel=new ExcelWriter("../../reportes/administracion/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","OP","Cantidad","Maquinista","Producto","Tipo","Color","Observaciones","Limpiadora","Fecha_fin","Total");
	$excel->writeCabecera($myArr);
	
	foreach($datos as $key => $value){
		$codigo = $datos[$key]['codigo'];
		$op = $datos[$key]['op'];
		$cantidad = $datos[$key]['cantidad'];
		$maquinista = $datos[$key]['maquinista'];
		$producto = $datos[$key]['producto'];
		$tipo = $datos[$key]['tipo'];
		$color = $datos[$key]['color'];
		$observaciones = $datos[$key]['observaciones'];
		$limpiadora = $datos[$key]['limpiadora'];
		$fecha_fin = $datos[$key]['fecha_fin'];
		$total = $datos[$key]['total'];
		
		$myArr=array($codigo,$op,$cantidad,$maquinista,$producto,$tipo,$color,$observaciones,$limpiadora,$fecha_fin,$total);
		$excel->writeLine($myArr);
	}

	
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}
function escribir_comedidos_excel($nombre_archivo, $datos){
	$excel=new ExcelWriter("../../reportes/administracion/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","OP","Maquinista","Producto","Tipo","Color","Rechazos","Arreglador","Clasificacion","Culpable","Detalle","Observaciones");
	$excel->writeCabecera($myArr);
	
	foreach($datos as $key => $value){
		$codigo = $datos[$key]['codigo'];
		$op = $datos[$key]['op'];
		$maquinista = $datos[$key]['maquinista'];
		$producto = $datos[$key]['producto'];
		$tipo = $datos[$key]['tipo'];
		$color = $datos[$key]['color'];
		$rechazos = $datos[$key]['rechazos'];
		$arreglador = $datos[$key]['arreglador'];
		$clasificacion = $datos[$key]['clasificacion'];
		$culpable = $datos[$key]['culpable'];
		$detalle = $datos[$key]['detalle'];
		$observaciones = $datos[$key]['observaciones'];
		
		$myArr=array($codigo,$op,$maquinista,$producto,$tipo,$color,$rechazos,$arreglador,$clasificacion,$culpable,$detalle,$observaciones);
		$excel->writeLine($myArr);
	}

	
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
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
			$num_orden =  utf8_decode(trim($_GET["value"]));
			
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
				unset($_SESSION['num_orden']);
				unset($_SESSION['deid']);
				unset($_SESSION['orden_id']);

				$smarty->display('sistema_de_produccion/despachos/nuevo_despacho.html');
				break;
			}
			case 3:{
				//insertar las asignaciones del detalle en la tabla despacho_detalle
				$did = $_GET['did'];
				//echo "<br>codigo del detalle:". $did;
				
				//mostrar los elementos
				$despacho_id = $_SESSION['deid'];
				$elementos_detalle = $despacho->mostrar_elementos($despacho_id);
				$smarty->assign('elementos_detalle', $elementos_detalle);
				//
				
				//mostrar el resumen de las asignaciones
				$asignaciones = $despacho->mostrar_asignaciones($did);
				$smarty->assign('asignaciones', $asignaciones);
				$smarty->display('sistema_de_produccion/despachos/mostrar_asignaciones.html');
				break;
			}
			case 5:{
				//buscar despachos existentes
				unset($_SESSION['num_orden']);
				unset($_SESSION['deid']);
				unset($_SESSION['orden_id']);
				
				$ultimos_despachos = $despacho->ultimos_despachos();
				
				$smarty->assign('lista_despachos', $ultimos_despachos);
				$smarty->display('sistema_de_produccion/despachos/despacho_busqueda.html');
				break;
			}
			case 6:{			
				//buscar despachos
				//unset($_SESSION['num_orden']);
				//unset($_SESSION['deid']);
				//unset($_SESSION['orden_id']);
				
				//para eliminar un detalle del despacho
				if (isset($_GET['dedeid'])){
					$dedeid = $_GET['dedeid'];
					//echo "<br>codigo del detalle de despacho:". $dedeid;
					$despacho->eliminar_detalle_despacho($dedeid);
					//comprobar cuantos registros tiene
					//si no tiene, cantidad = 0 eliminar
					
				}
				
				//para mostrar el detalle del despacho
				if (isset($_GET['deid'])){
					$deid = $_GET['deid'];
				
					$_SESSION['deid'] = $deid;
					
					$deshabilitar=$despacho->buscar_link($deid);
					
					if ($deshabilitar['migrar_administracion']==0&&$deshabilitar['migrar_link']==0)
					{   
						$smarty->assign('ver', 'si');
						
					}
			
				}
				
				if (isset($_SESSION['deid'])){
					$deid = $_SESSION['deid'];
					$descripcion_despacho = $despacho->descripcion_despacho($deid);
					$smarty->assign('descripcion_despacho', $descripcion_despacho);
					
					$elementos_detalle = $despacho->mostrar_elementos($deid);
					$smarty->assign('elementos_detalle', $elementos_detalle);
				}

				if (isset($_SESSION['num_orden'])){
					$num_orden = $_SESSION['num_orden'];
					$orden_id = $despacho->busqueda_id_orden($num_orden);
					
					$smarty->assign('num_orden', $num_orden);
					$resumen_detalle = $despacho->resumen_detalle($orden_id);
					
					
					$smarty->assign('resumen_detalle', $resumen_detalle);
				}
				
				
				
				
				$smarty->display('sistema_de_produccion/despachos/detalle_despacho.html');

				break;
			}
			case 7:{
				//imprimir
				//echo "<br>Imprimir";
				$deid = $_GET['deid'];
				//echo "<br>codigo del despacho:". $deid;
				
				$descripcion_despacho = $despacho->descripcion_despacho($deid);
				$smarty->assign('descripcion_despacho', $descripcion_despacho);
				
				$elementos_detalle = $despacho->mostrar_elementos_imprimir($deid);
				$smarty->assign('elementos_detalle', $elementos_detalle);
				//mostrar la pagina para imprimir
				$smarty->display('sistema_de_produccion/despachos/despacho_reporte.html');
				break;
			}
			case 8:{
				//echo "<br>generar reportes george";
				if (isset($_GET['deid'])){
					//si existe un despacho seleccionado
					$despacho_id = $_GET['deid'];
					
					//para que no aparezca con link
					$smarty->assign('deid', $despacho_id);
										
					//averiguar cual es el nombre del despacho
					$descripcion_despacho = $despacho->descripcion_despacho($despacho_id);
					
					//1 generamos sus archivos
					//maquinistas
					$reporte_maquinistas = $despacho->reporte_maquinistas($despacho_id);
					if ($reporte_maquinistas){
						//si existen maquinistas generamos el archivo
						$archivo_maquinistas = $descripcion_despacho['nombre_despacho'].'-maquinistas-'.$descripcion_despacho['fecha_despacho'];
						escribir_maquinistas_excel($archivo_maquinistas, $reporte_maquinistas);
						
						$smarty->assign('reporte_maquinistas', $archivo_maquinistas);
					}
					//limpiezas
					$reporte_limpiezas = $despacho->reporte_limpiezas($despacho_id);
					if ($reporte_limpiezas){
						//si existen limpiadores generamos el archivo
						$archivo_limpiezas = $descripcion_despacho['nombre_despacho'].'-limpiezas-'.$descripcion_despacho['fecha_despacho'];
						escribir_limpiezas_excel($archivo_limpiezas, $reporte_limpiezas);
						
						$smarty->assign('reporte_limpiezas', $archivo_limpiezas);
					}
					//comedidos
					$reporte_comedidos = $despacho->reporte_arreglos($despacho_id);
					if ($reporte_comedidos){
						//si existen limpiadores generamos el archivo
						$archivo_comedidos = $descripcion_despacho['nombre_despacho'].'-comedidos-'.$descripcion_despacho['fecha_despacho'];
						escribir_comedidos_excel($archivo_comedidos, $reporte_comedidos);
						
						$smarty->assign('reporte_comedidos', $archivo_comedidos);
					}
					
					//cambiamos el estado para que no muestre el ultimo despacho
					$despacho->actualizar_despacho_impreso($despacho_id);
				}
				
				$despacho_reporte = $despacho->despachos_administracion();
				$smarty->assign('despacho_reporte', $despacho_reporte);
				$smarty->display('sistema_de_produccion/despachos/generar_reportes.html');
				break;
			}
			case 9:{
				//editar las caracteristicas del despacho
				if (isset($_GET['deid'])){
					$deid = $_GET['deid'];
					//sacamos la descripcion del despacho
					$despachodes = $despacho->descripcion_despacho($deid);
					
					$smarty->assign('deid', $despachodes['despacho_id']);
					$smarty->assign('nombre_despacho', $despachodes['nombre_despacho']);
					$smarty->assign('fecha_despacho', $despachodes['fecha_despacho']);
					$smarty->assign('modificar', 'modificar');
					$smarty->display('sistema_de_produccion/despachos/nuevo_despacho.html');
				}
				break;
			}
		}
		
		if (!empty($_POST)){
			if ($_POST['nuevo']){
				$nombre_despacho = trim($_POST["nombre_despacho"]);
				$fecha_despacho = trim($_POST["fecha_despacho"]);
				
				if (isset($_POST['deid'])){
					$smarty->assign('deid', $_POST['deid']);
					$smarty->assign('modificar', 'modificar');
				}
				
				//validacion
				if ($validar->validarTodo($nombre_despacho, 1, 100)){
					$error['nombre_despacho'] = "Ingrese nombre de despacho";
				}
	
				if ($validar->validarTodo($fecha_despacho, 1, 100)){
					$error['fecha_despacho'] = "Ingresar fecha de despacho";
				} else {
					if (!$validar->validarFecha2($fecha_despacho)){
						$error['fecha_despacho'] = "Fecha de despacho no valida";
					}
				}
				//fin validacion
					
				if (isset($error)){
					//reenviar los errores
					$smarty->assign('error', $error);
					//desplegamos el listado de la busqueda
					$smarty->assign('nombre_despacho', $nombre_despacho);
					$smarty->assign('fecha_despacho', $fecha_despacho);
					$smarty->display('sistema_de_produccion/despachos/nuevo_despacho.html');
				} else {
					//echo "<br>Insertar nuevo despacho";
					$fecha_creacion = date("Y-m-d");
					$usuario_creador = $_SESSION["usuario_id"];
					
					if (isset($_POST['deid'])){
						//modificar
						$despacho_id = $_POST['deid'];
						$despacho->modificar_descripcion_despacho($despacho_id, $nombre_despacho, $fecha_despacho, $fecha_creacion, $usuario_creador);
					} else {
						//insertar
						$despacho_id = $despacho->insertar_nuevo_despacho($nombre_despacho, $fecha_despacho, $fecha_creacion, $usuario_creador);
					}

					//echo "<br>se creo despacho con ID: ".$despacho_id;
					//redireccionamos a la pagina para que muestre sus detalles
					
					//header ("Location : despacho.php?opcion=2&deid=".$despacho_id);
					header ("Location: despacho.php?opcion=6&deid=".$despacho_id);
				}// if (isset($error))
			}//if ($_POST['nuevo'])
			
			if ($_POST['ver_detalle']){
				$num_orden = trim($_POST["num_orden"]);
				
				//validacion
				//si esta vacio
				if ($validar->validarTodo($num_orden, 1, 100)){
					$error['num_orden'] = "Ingrese numero de orden";
				} else {
					//si existe realmente
					$orden_id = $despacho->busqueda_id_orden($num_orden);
					if ($orden_id == false){
						$error['num_orden'] = "Ingrese numero de orden valido";
					} else {
						$_SESSION['num_orden'] = $num_orden;
					}
				}
				//fin validacion
				
				if (isset($error)){
					//reenviar los errores
					$smarty->assign('error', $error);
				} else {
					//echo "<br>Orden: ".$orden_id;
					$_SESSION['orden_id'] = $orden_id;

					//***************************************************
					//redireccionamos haber que pasa
					$deid = $_SESSION['deid'];
					header ("Location: despacho.php?opcion=6&deid=".$deid);
					//***************************************************
				}
				
				//desplegamos el listado de la busqueda
				$smarty->assign('num_orden', $num_orden);
				
				//mostramos la descripcion del despacho
				$deid = $_SESSION['deid'];
				$descripcion_despacho = $despacho->descripcion_despacho($deid);
				$smarty->assign('descripcion_despacho', $descripcion_despacho);
				
				//*****************************************
				$elementos_detalle = $despacho->mostrar_elementos($deid);
				
				
				
				
					
				
				
				
				
				$smarty->assign('elementos_detalle', $elementos_detalle);
				//*****************************************
			
				$smarty->display('sistema_de_produccion/despachos/detalle_despacho.html');
			}//if ($_POST['ver_detalle'])
			
			if ($_POST['despachar']){
				$asignaciones = $_POST['asignaciones'];
				//recorremos todos los campos que ha seleccionado
				
					
				if ($asignaciones != null){
					foreach($asignaciones as $indice => $valor){
						$asignacion_id = $valor;
						
											
						$cantidad = $_POST['cantidad'.$valor];
						$despacho_id = $_SESSION['deid'];
						//echo "<br>n asig: ".$valor." - ".$cantidad;
						//
						//insertar elementos SOLO INSERTAR LA CANTIDAD QUE FALTA
						$despacho->insertar_elementos($despacho_id, $asignacion_id, $cantidad);
					}
				}
			}// if ($_POST['ver_detalle'])
			if ($_POST['despachar1']){
				$asignaciones = $_POST['asignaciones'];
				//recorremos todos los campos que ha seleccionado
				//$cantidad=$_POST['cantidad'];
				$despacho_id = $_SESSION['deid'];
				/*echo "<pre>";
				print_r($asignaciones);
				echo "</pre>";
								
					*/	
				if ($asignaciones != null)
				{
					foreach($asignaciones as $indice => $valor){
						$asignacion_id = $indice;
						
						 $cantidad=$valor;					

						$despacho_id = $_SESSION['deid'];
						
						//
						//insertar elementos SOLO INSERTAR LA CANTIDAD QUE FALTA
						$despacho->insertar_elementos($despacho_id, $asignacion_id, $cantidad);
						
						
					}
			header ("Location: despacho.php?opcion=6&deid=".$despacho_id);
							
				}
				else {
				
				header ("Location: despacho.php?opcion=6&deid=".$despacho_id);	
						
				}
				
			}
			
			
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
					$lista_despachos = $despacho->buscar_despacho($nombre_despacho);
					if ($lista_despachos != null){
						$smarty->assign('lista_despachos', $lista_despachos);
					} else {
						$smarty->assign('mensaje', "No se encontr&oacute; resultados");
					}
				}
				//desplegamos el listado de la busqueda
				$smarty->assign('nombre_despacho', $nombre_despacho);
				$smarty->display('sistema_de_produccion/despachos/despacho_busqueda.html');
			}// if ($_POST['buscar_despacho'])
		
		
		
		
		}
	}
}

?>