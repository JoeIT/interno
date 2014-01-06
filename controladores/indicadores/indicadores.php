<?php
session_start();

header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

require_once ("../../clases/indicadores/class.graphic.php");

include_once('../includes/fecha.php');
include_once('../../clases/validador.php');
include_once('../../clases/indicadores/indicadores.php');
require_once('../../clases/sistema_de_produccion/modelos.php');

//include('excelwriter.inc.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

$validar = new Validador();
$indicadores = new Indicadores;
$modelo = new Modelo;



if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if ($_SESSION['usuario_id']==79||$_SESSION['usuario_id']==69||$_SESSION['usuario_id']==84)	
	{
		
		$smarty->assign('mostrarBoton', true);
		
	}
	
	
	function escribir_indicador6_excel($nombre_archivo, $datos){
	$excel=new ExcelWriter("../../reportes/indicadores/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("OP","cliente","cant_OP", "producto","estilo","cuero","total_cuero_despiece","tipo_nom");
	$excel->writeCabecera($myArr);
	
	foreach($datos as $key => $value){
		
		$OP = $datos[$key]['OP'];
		$cliente=$datos[$key]['cliente'];
		$cant_OP = $datos[$key]['cant_OP'];
		$producto = $datos[$key]['producto'];
		$estilo = $datos[$key]['estilo'];
		$cuero = $datos[$key]['cuero'];
		$total_cuero_despiece = $datos[$key]['total_cuero_despiece'];
		$tipo_nom = $datos[$key]['tipo_nom'];
		
		
		$myArr=array($OP,$cliente,$cant_OP,$producto,$estilo,$cuero,$total_cuero_despiece,$tipo_nom);
		$excel->writeLine($myArr);
	}

	
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}
	function escribir_indicador7_excel($nombre_archivo, $datos){
	$excel=new ExcelWriter("../../reportes/indicadores/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("OP","cliente","cant_OP", "producto","estilo","cuero","tipo_cueros","total_cuero_despiece","t_material");
	$excel->writeCabecera($myArr);
	
	foreach($datos as $key => $value){
		
		$OP = $datos[$key]['OP'];
		$cliente=$datos[$key]['cliente'];
		$cant_OP = $datos[$key]['cant_OP'];
		$producto = $datos[$key]['producto'];
		$estilo = $datos[$key]['estilo'];
		$cuero = $datos[$key]['cuero'];
		$tipo_cueros = $datos[$key]['tipo_cueros'];
		$total_cuero_despiece = $datos[$key]['total_cuero_despiece'];
		$t_material = $datos[$key]['t_material'];
		
		
		$myArr=array($OP,$cliente,$cant_OP,$producto,$estilo,$cuero,$tipo_cueros,$total_cuero_despiece,$t_material);
		$excel->writeLine($myArr);
	}

	
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}
function escribir_indicador8_excel($nombre_archivo, $datos){
	$excel=new ExcelWriter("../../reportes/indicadores/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("OP","detalle_id","Cantidad_OP", "Producto","Estilo","Num_asignacion","fecha_inicio","fecha_finalizacion","cantidad_asignada","fecha_entrega","clase","dias_trab","productividad");
	$excel->writeCabecera($myArr);
	
	foreach($datos as $key => $value){
		
		$OP = $datos[$key]['OP'];
		$detalle_id=$datos[$key]['detalle_id'];
		$Cantidad_OP = $datos[$key]['Cantidad_OP'];
		$Producto = $datos[$key]['Producto'];
		$Estilo = $datos[$key]['Estilo'];
		$Num_asignacion = $datos[$key]['Num_asignacion'];
		$fecha_inicio = $datos[$key]['fecha_inicio'];
		$fecha_finalizacion = $datos[$key]['fecha_finalizacion'];
		$cantidad_asignada = $datos[$key]['cantidad_asignada'];
		$fecha_entrega = $datos[$key]['fecha_entrega'];
		$clase = $datos[$key]['clase'];
		$dias_trab = $datos[$key]['dias_trab']; 
		$productividad = $datos[$key]['productividad'];
		
		
		$myArr=array($OP,$detalle_id,$Cantidad_OP,$Producto,$Estilo,$Num_asignacion,$fecha_inicio,$fecha_finalizacion,$cantidad_asignada,$fecha_entrega,$clase,$dias_trab,$productividad);
		$excel->writeLine($myArr);
	}

	
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}
	// Joe developing
	$monthsToShow = 6;
	
	// The month to show must be at least 1
	if($monthsToShow <= 0)
		return;
	
	// Cantidad de meses a mostrar en la vista principal de indicadores
	$limite = 11;
	$smarty->assign('limite', $limite);
	
	$lastMonthStartDate = null;
	
	$monthsDataArray = array();
	for($counter = $monthsToShow; $counter > 0; $counter--)
	{
		$auxMonth = date("m") - $counter;
		$auxDay = date("d");
		$auxYear = date("Y");
		
		$monthLabel = date("M", mktime(0, 0, 0, date("m") - $counter, date("d"), date("Y")));
		
		$lastMonthStartDate = $startDate = date("Y-m-d", mktime(0, 0, 0, $auxMonth, $auxDay - $auxDay + 1, $auxYear)); //$mes1, $fecha_ini_mes1
		$endDate = date("Y-m-d", mktime(0, 0, 0, $auxMonth + 1, $auxDay - $auxDay, $auxYear));//$fecha_fin_mes1
		array_push($monthsDataArray, array('startDate' => $startDate, 'endDate' => $endDate, 'monthLabel' => $monthLabel));
	}
	
	$smarty->assign('monthsToShow', $monthsToShow);
	$smarty->assign('monthsDataArray', $monthsDataArray);
	
	
	//$mes1 = date("Y-m-d", mktime(0, 0, 0, date("m")-3 , date("d"), date("Y"))); Marco
	$mes1 = date("Y-m-d", mktime(0, 0, 0, date("m")-3 , date("d")-date("d")+1, date("Y")));
	$fecha_ini_mes1 = date("Y-m-d", mktime(0, 0, 0, date("m")-3 , date("d")-date("d")+1, date("Y")));
	$fecha_fin_mes1 = date("Y-m-d", mktime(0, 0, 0, date("m")-2 , date("d")-date("d"), date("Y")));
	$smarty->assign('mes1', $mes1);
	$smarty->assign('fecha_ini_mes1', $fecha_ini_mes1);
	$smarty->assign('fecha_fin_mes1', $fecha_fin_mes1);
	
	//$mes2 = date("Y-m-d", mktime(0, 0, 0, date("m")-2 , date("d"), date("Y")));Marco
	$mes2 = date("Y-m-d", mktime(0, 0, 0, date("m")-2 , date("d")-date("d")+1, date("Y")));
	$fecha_ini_mes2 = date("Y-m-d", mktime(0, 0, 0, date("m")-2 , date("d")-date("d")+1, date("Y")));
	$fecha_fin_mes2 = date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d")-date("d"), date("Y")));
	$smarty->assign('mes2', $mes2);
	$smarty->assign('fecha_ini_mes2', $fecha_ini_mes2);
	$smarty->assign('fecha_fin_mes2', $fecha_fin_mes2);
	
	//$mes3=date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")));Marco
	$mes3=date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d")-date("d")+1, date("Y")));

	$fecha_ini_mes3 = date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d")-date("d")+1, date("Y")));
	$fecha_fin_mes3 = date("Y-m-d", mktime(0, 0, 0, date("m")-0 , date("d")-date("d"), date("Y")));
	$smarty->assign('mes3', $mes3);
	$smarty->assign('fecha_ini_mes3', $fecha_ini_mes3);
	$smarty->assign('fecha_fin_mes3', $fecha_fin_mes3);
	
    $opcion = $_GET['opcion'];
	switch ($opcion){
		case 'indicador_1':
			
			$gr2 = new PowerGraphic;
			$gr2->axis_x    = 'Months';
			$gr2->axis_y    = 'Percent';
			$gr2->skin      = 3;
			$gr2->type      = 1;
			$gr2->credits   = 0;
			
			$counter = 0;
			$trendThisMonth = 0;
			$trendLastMonth = 0;
			$indicator1LastPercent = 0;
			$indicator1Indicador_id = null;
			
			$indicator1Array = array();
			foreach($monthsDataArray as $monthData)
			{
				$indicator1List = $indicadores->reporte_indicador_1_general($monthData['startDate'], $monthData['endDate']);
				array_push($indicator1Array, $indicator1List);
				
				$trendLastMonth = $trendThisMonth;
				$indicator1LastPercent = $trendThisMonth = $indicator1List['porcentaje'];
				$indicator1Indicador_id = $indicator1List['indicador_id'];
								
				$gr2->x[$counter] = $monthData['monthLabel'];
				$gr2->y[$counter] = $indicator1List['porcentaje'];
				$counter++;
			}
			
			$smarty->assign('lastMonthStartDate', $lastMonthStartDate);
			$smarty->assign('indicator1Indicador_id', $indicator1Indicador_id);
			$smarty->assign('indicator1LastPercent', $indicator1LastPercent);
			$smarty->assign('indicator1Array', $indicator1Array);
			$smarty->assign('trend', ($trendThisMonth - $trendLastMonth) );
			
			$gr2->start();
			$smarty->assign('params',$gr2->create_query_string());
			
			// Para la actualiazacion automatica
			$smarty->display('indicadores/cumplimiento_despacho.html');
			$gr2->reset_values();
			
			break;
			
		case 'indicador_1_grafico':
			$fecha_inicio = $_GET['fi'];
			$fecha_fin = $_GET['ff'];
			
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Client';
			$gr->axis_y    = 'Percent';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			$gr->x[0] = 0;
			$gr->y[0] = 0;
						
			$lista_indicador_1_clientes = $indicadores->reporte_indicador_1_clientes($fecha_inicio, $fecha_fin);
			
			echo "<table width='60%' border='1' cellspacing='2' cellpadding='1' style='border-collapse:collapse;'>";
			echo "<tr><th colspan='3'>Porcentaje de cumplimiento por clientes</th></tr>";
			foreach($lista_indicador_1_clientes as $indice => $valor) {
				$cifra = number_format($valor['porcentaje'], 2, '.', ',');
				echo "<tr class='lista-normal'>";
				echo "<td>".($indice + 1)."</td>";
				echo "<td>".$valor['cliente']."</td>";
				echo "<td align='right'>".$cifra." % </td>";
				echo "</tr>";
				
				$gr->x[$indice + 1] = $indice + 1;
				$gr->y[$indice + 1] = $cifra;
			}
			echo "</table>";			
			echo "<br>";
			
			$gr->start();
			echo '<img src="../../clases/indicadores/class.graphic.php?' . $gr->create_query_string() . '" />';
			
			$gr->reset_values();

			break;
			
		case 'indicador_1_ordenes':
			$fecha_inicio = $_GET['fi'];
			$fecha_fin = $_GET['ff'];
			$lista_indicador_1_ordenes = $indicadores->reporte_indicador_1_ordenes($fecha_inicio, $fecha_fin);
			
			echo "<table width='90%' border='1' cellspacing='2' cellpadding='1' style='border-collapse:collapse;'>";
			echo "<tr><th colspan='7'>Porcentaje de cumplimiento por OP</th></tr>";
			echo "<tr class='enunciados' style='text-align:center;'><td>Orden</td><td>Cliente</td><td>Fecha Despacho</td><td>Cant</td><td>Cant asign</td><td>Cumplio</td><td> % </td></tr>";
			foreach($lista_indicador_1_ordenes as $indice => $valor) {
				echo "<tr class='lista-normal'>";
				echo "<td>".$valor['cup_num']."</td>";
				echo "<td>".$valor['cliente']."</td>";
				echo "<td>".$valor['fecha']."</td>";
				echo "<td align='right'>".$valor['cantidad']."</td>";
				echo "<td align='right'>".$valor['cant_asig']."</td>";
				echo "<td align='right'>".$valor['cumplio']."</td>";
				echo "<td align='right'>".number_format($valor['porcentaje'], 2, '.', ',')." % </td>";
				echo "</tr>";
			}
			echo "</table>";
			
			break;
		//-------------------------------------------------------------------------
		case 'indicador_2':
			$lista_indicadores_tipo = $modelo->listar_indicadores_tipo();
			$smarty->assign('lista_indicadores_tipo', $lista_indicadores_tipo);
			
			$indicator2Array = array();
			foreach($monthsDataArray as $monthData)
			{
				$indicator2List = $indicadores->reporte_indicador_2_general($monthData['startDate'], $monthData['endDate']);
				
				$gr = new PowerGraphic;
				$gr->axis_x		= 'Product';
				$gr->axis_y		= 'Percent';
				$gr->skin		= 3;
				$gr->type		= 1;
				$gr->credits	= 0;
				$gr->x[0]		= 0;
				$gr->y[0]		= 0;
			
				$total = $indicadores->total_indicador_2_general($monthData['startDate'], $monthData['endDate']);
				
				$indice = 1;
				foreach($indicator2List as $key => $valor) {
					$indicator2List[$key]["porcentaje"] = ($valor["cantidad"] / $total) * 100;
					
					$gr->x[$indice] = $valor["numero"];
					$gr->y[$indice] = ($valor["cantidad"] / $total) * 100;
					
					$indice ++;
				}
				
				$gr->start();
				
				$info = array();
				$info['params_mes'] = $gr->create_query_string();
				$info['startDate'] = $monthData['startDate'];
				$info['endDate'] = $monthData['endDate'];
				$info['lastMonth'] = false;
				
				if($monthData == end($monthsDataArray))
					$info['lastMonth'] = true;
				
				$dataArray = array();				
				$dataArray['info'] = $info;
				$dataArray['indicator2List'] = $indicator2List;
				
				array_push($indicator2Array, $dataArray);
			}
			
			$smarty->assign('indicator2Array', $indicator2Array);
			$smarty->display('indicadores/indicador_2.html');
			break;
			
		case 'indicador_2_ordenes':
			$fecha_inicio = $_GET['fi'];
			$fecha_fin = $_GET['ff'];
			$lista_indicador_2_ordenes = $indicadores->reporte_indicador_2_ordenes($fecha_inicio, $fecha_fin);
			
			echo "<table width='90%' border='1' cellspacing='2' cellpadding='1' style='border-collapse:collapse;'>";
			echo "<tr><th colspan='7'>Detalle OP</th></tr>";
			echo "<tr class='enunciados' style='text-align:center;'><td>Orden</td><td>Producto</td><td>Estilo</td><td>Material</td><td>C. OP</td><td>C. Asig.</td><td>Tipo</td></tr>";
			foreach($lista_indicador_2_ordenes as $indice => $valor) {
				echo "<tr class='lista-normal'>";
				echo "<td>".$valor['op']."</td>";
				echo "<td width='25%'>".$valor['producto']."</td>";
				echo "<td>".$valor['estilo']."</td>";
				echo "<td>".$valor['origen_cuero']."</td>";
				echo "<td align='right'>".$valor['cantidad_op']."</td>";
				echo "<td align='right'>".$valor['cantidad_asignada']."</td>";
				echo "<td>".$valor['tipo']."</td>";
				echo "</tr>";
			}
			echo "</table>";
			break;
		case 'productos_muestras':
			$lista_indicador_3_general = $indicadores->reporte_indicador_3_general($fecha_ini_mes1, $fecha_fin_mes1);			
			$valor_mes1_y = $lista_indicador_3_general[0]['cantidad'];
			$valor_mes1_z = $lista_indicador_3_general[1]['cantidad'];
			$smarty->assign('lista_indicador_3_general_mes1', $lista_indicador_3_general);
			$lista_indicador_3_general = $indicadores->reporte_indicador_3_general($fecha_ini_mes2, $fecha_fin_mes2);
			$valor_mes2_y = $lista_indicador_3_general[0]['cantidad'];
			$valor_mes2_z = $lista_indicador_3_general[1]['cantidad'];
			$smarty->assign('lista_indicador_3_general_mes2', $lista_indicador_3_general);
			$lista_indicador_3_general = $indicadores->reporte_indicador_3_general($fecha_ini_mes3, $fecha_fin_mes3);
			$valor_mes3_y = $lista_indicador_3_general[0]['cantidad'];
			$valor_mes3_z = $lista_indicador_3_general[1]['cantidad'];
			$smarty->assign('lista_indicador_3_general_mes3', $lista_indicador_3_general);
			
			/*********************************************************************************************************/
			$lista_general[0]["numero"] = date("M", strtotime($mes1)); $lista_general[0]["valorY"] = $valor_mes1_y; $lista_general[0]["valorZ"] = $valor_mes1_z;
			$lista_general[1]["numero"] = date("M", strtotime($mes2)); $lista_general[1]["valorY"] = $valor_mes2_y; $lista_general[1]["valorZ"] = $valor_mes2_z;
			$lista_general[2]["numero"] = date("M", strtotime($mes3)); $lista_general[2]["valorY"] = $valor_mes3_y; $lista_general[2]["valorZ"] = $valor_mes3_z;						
			
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Mounth';
			$gr->axis_y    = 'Percent';
			$gr->graphic_1 = 'P. Nuevo';
			$gr->graphic_2 = 'Muestras';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			$gr->x[0] = 0;
			$gr->y[0] = 0;
			$gr->z[0] = 0;
			
			foreach($lista_general as $indice => $valor) {
				$gr->x[$indice + 1] = $valor["numero"];
				$gr->y[$indice + 1] = $valor["valorY"];
				$gr->z[$indice + 1] = $valor["valorZ"];
			}
			
			$gr->start();
			$smarty->assign('params',$gr->create_query_string());
			/*********************************************************************************************************/
			
			$smarty->display('indicadores/productos_muestras.html');	
			break;
		case 'p_m_detalle':
			$fecha_inicio = $_GET['fi'];
			$fecha_fin = $_GET['ff'];
			$lista_detalle = $indicadores->reporte_indicador_3_detalle($fecha_inicio, $fecha_fin);
			/*
			echo "<pre>";
				print_r($lista_detalle);
			echo "</pre>";
			*/
			if ($lista_detalle != '') {
				echo "<table width='90%' border='1' cellspacing='2' cellpadding='1' style='border-collapse:collapse;'>";
				echo "<tr><th colspan='6'>Detalle orden: ".date("F, Y", strtotime($fecha_inicio))."</th></tr>";
				echo "<tr class='enunciados' style='text-align:center;'><td>Modelo</td><td>Estilo</td><td>Clip</td><td>Cantidad</td><td>F. solicitud</td><td>F. cierre</td></tr>";
				
				foreach($lista_detalle as $indice => $valor) {
					echo "<tr><td colspan='6' class='lista-seleccionada'>".$indice."</td></tr>";
					foreach($valor as $clave => $dato) {
						echo "<tr class='lista-normal'>";
						echo "<td>".$dato['modelo']."</td>";
						echo "<td>".$dato['estilo']."</td>";
						echo "<td>".$dato['clip']."</td>";
						echo "<td>".$dato['cantidad']."</td>";
						echo "<td>".$dato['fechasolicitud']."</td>";
						echo "<td>".$dato['fechafinalizacion']."</td>";
						echo "</tr>";
					}
				}
				echo "</table>";
			} else {
				echo "No existen datos";
			}
			break;
		case 'actualizar_indicadores':
			
			
			$lista_listar_indicadores = $indicadores->listar_indicadores();
			$smarty->assign('lista_listar_indicadores', $lista_listar_indicadores);
			//desde aqui aumnete
			if (isset($_GET['area'])) {
				$area = $_GET['area'];
				if ($area == 'Producción') {
					$links['1 Cumplimiento de despachos de productos'] = 'indicadores.php?opcion=indicador_1 ';
					$links['2 Composición de productos manufacturados'] = 'indicadores.php?opcion=indicador_2';
					$links['3 Diseños nuevos y muestras'] = 'indicadores.php?opcion=productos_muestras';
					$links['4 Cumplimiento de fechas de diseños'] = 'indicadores.php?opcion=cumplimiento_diseno';
					$links['5 Control de medio proceso y muestras iniciales'] = 'indicadores.php?opcion=medio_proceso';
					$links['9 Composición de los rechazos de producción'] = 'indicadores.php?opcion=composicon_rechazo';
					$links['10 Producto no conforme por tipo de rechazo'] = 'indicadores.php?opcion=tipo_rechazo';
					$links['11 Cumplimiento asignaciones a maquinistas'] = 'indicadores.php?opcion=cumplimiento_asignaciones';
					$links['12 Producción linea Vs. producción total (cantidad)'] = 'indicadores.php?opcion=produccion_linea';
					$smarty->assign('links', $links);
				}
			/*
				echo "<pre>";
					print_r($meta_act);
				echo "</pre>";
				*/
				
			}	
			//hasta aqui
			
			
			
			$mes_anterior = date("Y-m-d", mktime(0, 0, 0, date("m")-1 , date("d"), date("Y")));
			$smarty->assign('mes_anterior', $mes_anterior);
			
			if (isset($_GET['area'])) {
				$area = $_GET['area'];
				$lista_contenido_area = $indicadores->contenido_area($area, $limite);
				$observaciones = $lista_contenido_area['observaciones'];
				$lista_contenido_area = $lista_contenido_area['valores'];
				$smarty->assign('observaciones', $observaciones);
				$smarty->assign('lista_contenido_area', $lista_contenido_area);
				/*noooooo*/
			/*echo "<pre>";
					print_r($lista_contenido_area);
				echo "</pre>";*/
				
			}
			
			$smarty->display('indicadores/actualizar_indicadores.html');
			break;
		case 'valores':
			if (isset($_GET['iid'])) {
				$iid = $_GET['iid'];
				$smarty->assign('indicador_lista_id', $iid);
				//averigual cual fue el ultimo mes
				
				$lista_ultima_fecha_area = $indicadores->ultima_fecha_area($iid);
				$ultima_fecha = $lista_ultima_fecha_area['fecha'];
				$nombre = $lista_ultima_fecha_area['nombre'];
				if ($ultima_fecha == '')
					$ultima_fecha = $mes2;
				
				$d1 = date("F, Y", strtotime($ultima_fecha));
				$d2 = date("F, Y", strtotime($mes3));
				
				if ($d1 == $d2) {
					$iv = $lista_ultima_fecha_area['indicador_valor_id'];
					$fecha = $lista_ultima_fecha_area['fecha'];
					$valor = $lista_ultima_fecha_area['valor'];
					$observaciones = $lista_ultima_fecha_area['observaciones'];
					
					
					
					$smarty->assign('indicador_valor_id', $iv);
					$smarty->assign('fecha', $fecha);
					$smarty->assign('valor', $valor);
					$smarty->assign('observaciones', $observaciones);
					
					$_SESSION['titulo'] = $nombre;
					$smarty->assign('fecha', $ultima_fecha);
				} else {
					$fecha_time = strtotime($ultima_fecha);
					$proxima_fecha = date("Y-m-d", mktime(0, 0, 0, date("m", $fecha_time)+1 , date("d", $fecha_time), date("Y", $fecha_time)));	
								
					$_SESSION['titulo'] = $nombre;
					$smarty->assign('fecha', $proxima_fecha);
					//echo "no igual ultima fecha: ". $ultima_fecha." proxi fecha: ".$proxima_fecha;
				}
			}
			if (isset($_POST['grabar'])) {
				$indicador_valor_id = $_POST['indicador_valor_id'];
				$fecha = $_POST['fecha'];
				$valor = trim($_POST['valor']);
				$observaciones = trim($_POST['observaciones']);
				$indicador_lista_id = $_POST['indicador_lista_id'];
								
				if ($valor == '') {
					$error['err_valor'] = "Ingrese valor";
				}
				if (isset($error)) {
					$smarty->assign('indicador_valor_id', $indicador_valor_id);
					$smarty->assign('fecha', $fecha);
					$smarty->assign('valor', $valor);
					$smarty->assign('observaciones', $observaciones);
					$smarty->assign('indicador_lista_id', $indicador_lista_id);
					$smarty->assign('errores', $error);
				} else {
					$smarty->assign('cerrar', 'yes');
					unset($_SESSION['titulo']);
					$fecha_actualizacion = date("Y-m-d");
					if ($indicador_valor_id != '') {
						  $indicadores->actualizar_valor_indicador($indicador_valor_id, $valor, $observaciones, $fecha_actualizacion);
					} else {
						$indicadores->insertar_valor_indicador($fecha, $valor, $observaciones, $fecha_actualizacion, $indicador_lista_id);
					}
				}
			}
			
			$smarty->assign('titulo', $_SESSION['titulo']);
			$smarty->display('indicadores/valores.html');
			break;
			///////////////////////////////
			case 'valores1':
					if (isset($_POST['grabar']))
			
					{   $indi=$_POST['indi'];
						$fecha = $_POST['fecha'];
			   			$observaciones = trim($_POST['observaciones']);
				        $fecha_actualizacion = date("Y-m-d");
				 		$area=$_POST['area'];			      
 /*
				echo "<pre>";
				print_r($_POST);
				echo "</pre>";
*/
				  foreach( $indi as $key => $value)
					       	{ $verificar = $indicadores->verificar_fecha($fecha,$key);
					       
							   /*el ano cuanod cambia de ano cambiasr a date(Y)-1 para el ultimo mes diciembre despues cambiar a date(Y)*/
					      /*se supone que fecha_bd cuando hace actualizar tiene que ser igual ala fecha para insertar los nuevos datos pero 
					      si no es pues actualizara sobre el anterior entonces eso paso puesto que como jala la fecha actual y es otro ano entonces nunca 
					      actualizara diciembre pro eso a fin de ano cambiar */



					    list($ano, $mes, $dia) = split("-", $verificar["fecha"]);
					    /*controlar el mes de diciembre*/
					    				    
					    if ($mes==12)
					    {
					    	$fecha_bd = date("Y-m-d",mktime(0, 0, 0,date("m",strtotime($verificar["fecha"]))+1 ,date("d")-date("d")+1,date("Y")-1));     		       	
					    }
					    else
			            {
				           			            
				            $fecha_bd = date("Y-m-d",mktime(0, 0, 0,date("m",strtotime($verificar["fecha"]))+1 ,date("d")-date("d")+1,date("Y")));     		       	
				         }

  		       	

						if ($fecha==$fecha_bd)
			                          {//echo "Name: $key, Age: $value <br />";	
										 
			                          $indicadores->insertar_valor_indicador($fecha, $value, $observaciones, $fecha_actualizacion, $key);				   			
			                          
			                          header("Location: indicadores.php?opcion=actualizar_indicadores&area=Producción");                                  
				                        }
							          else{
											if ($fecha <=$fecha_bd )
											{
												$indicador_valor_id=$verificar['indicador_valor_id'];
											$indicadores->actualizar_valor_indicador($indicador_valor_id, $value, $observaciones, $fecha_actualizacion);			  
											
											header("Location: indicadores.php?opcion=actualizar_indicadores&area=Producción");
											
											}
											else{
												

												header("Location: indicadores.php?opcion=actualizar_indicadores&area=Producción");
													/*$error['fecha'] = "La fecha de actualizacion es".$fecha_bd;
			         								$smarty->assign('error',$error);
	                					            if (isset($error))
	                					            {
													$smarty->assign('error', $error);
													$smarty->display('indicadores/indicador_2.html');
												    }*/ 
												
					     				}	
							          
				   	                       
				                          }
								
			 		       
                           
			   	              }
				
					
					}
				
		    
        
		break;
                                
		case 'lista_indicadores':
			$lista_listar_indicadores = $indicadores->listar_indicadores();
			$smarty->assign('lista_listar_indicadores', $lista_listar_indicadores);
			
			if (isset($_GET['area'])) {
				$area = $_GET['area'];
				if ($area == 'Producción') {
					$links['1 Cumplimiento de despachos de productos'] = 'indicadores.php?opcion=indicador_1';
					$links['2 Composición de productos manufacturados'] = 'indicadores.php?opcion=indicador_2';
					$links['3 Diseños nuevos y muestras'] = 'indicadores.php?opcion=productos_muestras';
					$links['4 Cumplimiento de fechas de diseños'] = 'indicadores.php?opcion=cumplimiento_diseno';
					$links['5 Control de medio proceso y muestras iniciales'] = 'indicadores.php?opcion=medio_proceso';
					$links['9 Composición de los rechazos de producción'] = 'indicadores.php?opcion=composicon_rechazo';
					$links['10 Producto no conforme por tipo de rechazo'] = 'indicadores.php?opcion=tipo_rechazo';
					$links['11 Cumplimiento asignaciones a maquinistas'] = 'indicadores.php?opcion=cumplimiento_asignaciones';
					$links['12 Producción linea Vs. producción total (cantidad)'] = 'indicadores.php?opcion=produccion_linea';
					$smarty->assign('links', $links);
				}
				$lista_contenido_area = $indicadores->contenido_area($area, $limite);
				$observaciones = $lista_contenido_area['observaciones'];
				$fecha_act = $lista_contenido_area['fecha_act'];
				$meta_act = $lista_contenido_area['meta_act'];
				$lista_contenido_area = $lista_contenido_area['valores'];
				/*
				echo "<pre>";
					print_r($lista_contenido_area);
				echo "</pre>";
				*/
				$smarty->assign('observaciones', $observaciones);
				$smarty->assign('fecha_act', $fecha_act);
				$smarty->assign('meta_act', $meta_act);
				$smarty->assign('lista_contenido_area', $lista_contenido_area);
			}
			
			$smarty->display('indicadores/lista_indicadores.html');
			break;
		case 'cumplimiento_diseno':
				$valor_mes1 = $indicadores->cumplimiento_fechas_general($fecha_ini_mes1, $fecha_fin_mes1);
				$valor_mes2 = $indicadores->cumplimiento_fechas_general($fecha_ini_mes2, $fecha_fin_mes2);
				$valor_mes3 = $indicadores->cumplimiento_fechas_general($fecha_ini_mes3, $fecha_fin_mes3);
				
				$smarty->assign('valor_mes1', $valor_mes1);
				$smarty->assign('valor_mes2', $valor_mes2);
				$smarty->assign('valor_mes3', $valor_mes3);
				
				/*********************************************************************************************************/
				$lista_general[0]["numero"] = date("M", strtotime($mes1)); $lista_general[0]["valor"] = $valor_mes1;
				$lista_general[1]["numero"] = date("M", strtotime($mes2)); $lista_general[1]["valor"] = $valor_mes2;
				$lista_general[2]["numero"] = date("M", strtotime($mes3)); $lista_general[2]["valor"] = $valor_mes3;
				
				$gr = new PowerGraphic;
				$gr->axis_x    = 'Mounth';
				$gr->axis_y    = 'Percent';
				$gr->skin      = 3;
				$gr->type      = 1;
				$gr->credits   = 0;
				$gr->x[0] = 0;
				$gr->y[0] = 0;
				
				foreach($lista_general as $indice => $valor) {
					$gr->x[$indice + 1] = $valor["numero"];
					$gr->y[$indice + 1] = $valor["valor"];
				}
				
				$gr->start();
				$smarty->assign('params',$gr->create_query_string());
				/*********************************************************************************************************/
				
				$smarty->display('indicadores/cumplimiento_diseno.html');
			break;
		case 'cumplimiento_diseno_detalle':
			$fecha_inicio = $_GET['fi'];
			$fecha_fin = $_GET['ff'];
			$lista_detalle = $indicadores->cumplimiento_fechas_detalle($fecha_inicio, $fecha_fin);
			/*
			echo "<pre>";
				print_r($lista_detalle);
			echo "</pre>";
			*/
			if ($lista_detalle != '') {
				echo "<table width='90%' border='1' cellspacing='2' cellpadding='1' style='border-collapse:collapse;'>";
				echo "<tr><th colspan='6'>Detalle orden: ".date("F, Y", strtotime($fecha_inicio))."</th></tr>";
				echo "<tr class='enunciados' style='text-align:center;'><td>Modelo</td><td>Cantidad</td><td>F. Solicitud</td><td>F. Conclusi&oacute;n</td><td>F. Real Conclusi&oacute;n</td><td>Observaciones</td></tr>";
				
				foreach($lista_detalle as $indice => $valor) {
					echo "<tr class='lista-normal'>";
					echo "<td>".$valor['modelo']."</td>";
					echo "<td>".$valor['cantidad']."</td>";
					echo "<td>".$valor['fechasolicitud']."</td>";
					echo "<td>".$valor['fechaconclusion']."</td>";
					echo "<td>".$valor['fecharealconclusion']."</td>";
					echo "<td>".$valor['cumplimiento']."</td>";
					echo "</tr>";
				}
				echo "</table>";
			}
			break;
		case 'medio_proceso':
			
			$generalArray = array();
			foreach($monthsDataArray as $monthData)
			{
				$generalList = $indicadores->medio_proceso_general($monthData['startDate'], $monthData['endDate']);
				
				$gr = new PowerGraphic;
				$gr->axis_x    = 'Control';
				$gr->axis_y    = 'Cantidad';
				$gr->skin      = 3;
				$gr->type      = 1;
				$gr->credits   = 0;
				$gr->x[0] = 0;
				$gr->y[0] = 0;
				
				foreach($generalList as $key => $value)
				{
					$gr->x[$key + 1] = $value["numero"];
					$gr->y[$key + 1] = $value["cantidad"];
				}
				
				$gr->start();
				
				$info = array();
				$info['params_mes'] = $gr->create_query_string();
				$info['startDate'] = $monthData['startDate'];
				$info['endDate'] = $monthData['endDate'];
				$info['lastMonth'] = false;
				
				if($monthData == end($monthsDataArray))
					$info['lastMonth'] = true;
				
				$dataArray = array();
				
				$dataArray['info'] = $info;
				$dataArray['generalList'] = $generalList;
				
				array_push($generalArray, $dataArray);
			}
			$smarty->assign('generalArray', $generalArray);
			
			//----------------------------------------------------------------------
			//----------------------------------------------------------------------
			/*
			$lista_general = $indicadores->medio_proceso_general($fecha_ini_mes2, $fecha_fin_mes2);
			$smarty->assign('lista_indicador_2_general_mes2', $lista_general);			
			
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Control';
			$gr->axis_y    = 'Cantidad';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			$gr->x[0] = 0;
			$gr->y[0] = 0;
			
			foreach($lista_general as $indice => $valor) {
				$gr->x[$indice + 1] = $valor["numero"];
				$gr->y[$indice + 1] = $valor["cantidad"];
			}
			
			$gr->start();
			$smarty->assign('params_mes2',$gr->create_query_string());
			
			//----------------------------------------------------------------------
			$lista_general = $indicadores->medio_proceso_general($fecha_ini_mes3, $fecha_fin_mes3);
			$smarty->assign('lista_indicador_2_general_mes3', $lista_general);			
			
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Control';
			$gr->axis_y    = 'Cantidad';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			$gr->x[0] = 0;
			$gr->y[0] = 0;
			
			foreach($lista_general as $indice => $valor) {
				$gr->x[$indice + 1] = $valor["numero"];
				$gr->y[$indice + 1] = $valor["cantidad"];
			}
			
			$gr->start();
			$smarty->assign('params_mes3',$gr->create_query_string());
			//----------------------------------------------------------------------
			*/
			
			
			$smarty->display('indicadores/medio_proceso.html');
			break;
		case 'produccion_linea':
			/*********************************************************************************************************/			
			$lista_general = $indicadores->produccion_linea_general($fecha_ini_mes1, $fecha_fin_mes1);
			$smarty->assign('prog_mes1', $lista_general['cantidad']);
			$smarty->assign('cump_mes1', $lista_general['cumplio']);
			$smarty->assign('porcentaje_mes1', $lista_general['porcentaje']);
			$smarty->assign('indicador_id', $lista_general['indicador_id']);
			$vector_grafico['mes1'] = $lista_general['porcentaje'];
			/*********************************************************************************************************/			
			$lista_general = $indicadores->produccion_linea_general($fecha_ini_mes2, $fecha_fin_mes2);
			$smarty->assign('prog_mes2', $lista_general['cantidad']);
			$smarty->assign('cump_mes2', $lista_general['cumplio']);
			$smarty->assign('porcentaje_mes2', $lista_general['porcentaje']);
			$smarty->assign('indicador_id', $lista_general['indicador_id']);
			$vector_grafico['mes2'] = $lista_general['porcentaje'];
			/*********************************************************************************************************/			
			$lista_general = $indicadores->produccion_linea_general($fecha_ini_mes3, $fecha_fin_mes3);
			$smarty->assign('prog_mes3', $lista_general['cantidad']);
			$smarty->assign('cump_mes3', $lista_general['cumplio']);
			$smarty->assign('porcentaje_mes3', $lista_general['porcentaje']);
			$smarty->assign('indicador_id', $lista_general['indicador_id']);
			$vector_grafico['mes3'] = $lista_general['porcentaje'];
			/*********************************************************************************************************/
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Linea';
			$gr->axis_y    = 'Percent';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			
			// Set values Y= especial Z= stock
			$gr->x[0] = date("M",mktime(0, 0, 0, date("m")-3, date("d"), date("Y")));
			$gr->y[0] = $vector_grafico['mes1'];
			
			$gr->x[1] = date("M",mktime(0, 0, 0, date("m")-2, date("d"), date("Y")));
			$gr->y[1] = $vector_grafico['mes2'];
			
			$gr->x[2] = date("M",mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
			$gr->y[2] = $vector_grafico['mes3'];
			
			$gr->start();
			$smarty->assign('params',$gr->create_query_string());
			/*********************************************************************************************************/
			
			$smarty->display('indicadores/produccion_linea.html');
			$gr->reset_values();
			break;

		case 'cumplimiento_asignaciones':
			/*********************************************************************************************************/			
			$lista_general = $indicadores->cumplimiento_asignaciones_general($fecha_ini_mes1, $fecha_fin_mes1);
			$smarty->assign('prog_mes1', $lista_general['cantidad']);
			$smarty->assign('cump_mes1', $lista_general['cumplio']);
			$smarty->assign('porcentaje_mes1', $lista_general['porcentaje']);
			$smarty->assign('indicador_id', $lista_general['indicador_id']);
			$vector_grafico['mes1'] = $lista_general['porcentaje'];
			/*********************************************************************************************************/			
			$lista_general = $indicadores->cumplimiento_asignaciones_general($fecha_ini_mes2, $fecha_fin_mes2);
			$smarty->assign('prog_mes2', $lista_general['cantidad']);
			$smarty->assign('cump_mes2', $lista_general['cumplio']);
			$smarty->assign('porcentaje_mes2', $lista_general['porcentaje']);
			$smarty->assign('indicador_id', $lista_general['indicador_id']);
			$vector_grafico['mes2'] = $lista_general['porcentaje'];
			/*********************************************************************************************************/			
			$lista_general = $indicadores->cumplimiento_asignaciones_general($fecha_ini_mes3, $fecha_fin_mes3);
			$smarty->assign('prog_mes3', $lista_general['cantidad']);
			$smarty->assign('cump_mes3', $lista_general['cumplio']);
			$smarty->assign('porcentaje_mes3', $lista_general['porcentaje']);
			$smarty->assign('indicador_id', $lista_general['indicador_id']);
			$vector_grafico['mes3'] = $lista_general['porcentaje'];
			/*********************************************************************************************************/
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Asig.';
			$gr->axis_y    = 'Percent';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			
			// Set values Y= especial Z= stock
			$gr->x[0] = date("M",mktime(0, 0, 0, date("m")-3, date("d"), date("Y")));
			$gr->y[0] = $vector_grafico['mes1'];
			
			$gr->x[1] = date("M",mktime(0, 0, 0, date("m")-2, date("d"), date("Y")));
			$gr->y[1] = $vector_grafico['mes2'];
			
			$gr->x[2] = date("M",mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
			$gr->y[2] = $vector_grafico['mes3'];
			
			$gr->start();
			$smarty->assign('params',$gr->create_query_string());
			/*********************************************************************************************************/
			
			$smarty->display('indicadores/cumplimiento_asignaciones.html');
			$gr->reset_values();
			break;
		case 'asignaciones_detalle':
			$fecha_inicio = $_GET['fi'];
			$fecha_fin = $_GET['ff'];
			$lista_asignaciones = $indicadores->cumplimiento_asignaciones_detalle($fecha_inicio, $fecha_fin);
			
			echo "<table width='60%' border='1' cellspacing='2' cellpadding='1' style='border-collapse:collapse;'>";
			echo "<tr><th colspan='4'>Detalle cumplimiento de asignaciones ".date("F, Y", strtotime($fecha_inicio))."</th></tr>";
			echo "<tr class='enunciados' style='text-align:center;'><td>N&deg;</td><td>Asignaciones</td><td>Cant.</td><td>%</td></tr>";
			foreach($lista_asignaciones as $indice => $valor) {
				echo "<tr class='lista-normal'>";
				echo "<td align='center'>".$valor['numero']."</td>";
				echo "<td>".$valor['tipo']."</td>";
				echo "<td align='right'>".$valor['cantidad']."</td>";
				echo "<td align='right'>".number_format($valor['porcentaje'], 2, '.', ',')." % </td>";
				echo "</tr>";
				$cant = $cant + $valor['cantidad'];
				$perc = $perc + $valor['porcentaje'];
			}
			echo "<tr class='lista-seleccionada'>";
				echo "<td align='right' colspan='2'>Total asignaciones: </td>";
				echo "<td align='right'>".$cant."</td>";
				echo "<td align='right'>".number_format($perc, 2, '.', ',')." %</td>";
			echo "</tr>";
			echo "</table>";
			
			break;
		case 'tipo_rechazo':
			/*********************************************************************************************************/
			$lista_general = $indicadores->tipo_rechazo_general($fecha_ini_mes1, $fecha_fin_mes1);
			$smarty->assign('lista_indicador_2_general_mes1', $lista_general);
			/*********************************************************************************************************/
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Fallo';
			$gr->axis_y    = 'Percent';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			$gr->x[0] = 0;
			$gr->y[0] = 0;
			
			$cont = 1;
			foreach($lista_general as $indice => $valor) {
				$gr->x[$cont] = $valor["numero"];
				$gr->y[$cont] = $valor["porcentaje"];
				$cont ++;
			}
			
			$gr->start();
			$smarty->assign('params_mes1',$gr->create_query_string());
			/*********************************************************************************************************/
			$lista_general = $indicadores->tipo_rechazo_general($fecha_ini_mes2, $fecha_fin_mes2);
			$smarty->assign('lista_indicador_2_general_mes2', $lista_general);			
			/*********************************************************************************************************/
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Fallo';
			$gr->axis_y    = 'Percent';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			$gr->x[0] = 0;
			$gr->y[0] = 0;
			
			$cont = 1;
			foreach($lista_general as $indice => $valor) {
				$gr->x[$cont] = $valor["numero"];
				$gr->y[$cont] = $valor["porcentaje"];
				$cont ++;
			}
			
			$gr->start();
			$smarty->assign('params_mes2',$gr->create_query_string());
			/*********************************************************************************************************/
			$lista_general = $indicadores->tipo_rechazo_general($fecha_ini_mes3, $fecha_fin_mes3);
			$smarty->assign('lista_indicador_2_general_mes3', $lista_general);
			/*********************************************************************************************************/
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Fallo';
			$gr->axis_y    = 'Percent';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			$gr->x[0] = 0;
			$gr->y[0] = 0;
			
			$cont = 1;
			foreach($lista_general as $indice => $valor) {
				$gr->x[$cont] = $valor["numero"];
				$gr->y[$cont] = $valor["porcentaje"];
				$cont ++;
			}
			
			$gr->start();
			$smarty->assign('params_mes3',$gr->create_query_string());
			/*********************************************************************************************************/
		
		
			$smarty->display('indicadores/tipo_rechazo.html');
			break;
		case 'composicon_rechazo':
			/*********************************************************************************************************/
			$lista_general = $indicadores->composicion_rechazo_general($fecha_ini_mes1, $fecha_fin_mes1);
			$smarty->assign('lista_indicador_2_general_mes1', $lista_general);
			/*********************************************************************************************************/
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Fallo';
			$gr->axis_y    = 'Rech / Total';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			$gr->x[0] = 0;
			$gr->y[0] = 0;
			
			$cont = 1;
			foreach($lista_general as $indice => $valor) {
				$gr->x[$cont] = $valor["numero"];
				$gr->y[$cont] = $valor["porcentaje"];
				$cont ++;
			}
			
			$gr->start();
			$smarty->assign('params_mes1',$gr->create_query_string());
			/*********************************************************************************************************/
			$lista_general = $indicadores->composicion_rechazo_general($fecha_ini_mes2, $fecha_fin_mes2);
			$smarty->assign('lista_indicador_2_general_mes2', $lista_general);			
			/*********************************************************************************************************/
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Fallo';
			$gr->axis_y    = 'Rech / Total';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			$gr->x[0] = 0;
			$gr->y[0] = 0;
			
			$cont = 1;
			foreach($lista_general as $indice => $valor) {
				$gr->x[$cont] = $valor["numero"];
				$gr->y[$cont] = $valor["porcentaje"];
				$cont ++;
			}
			
			$gr->start();
			$smarty->assign('params_mes2',$gr->create_query_string());
			/*********************************************************************************************************/
			$lista_general = $indicadores->composicion_rechazo_general($fecha_ini_mes3, $fecha_fin_mes3);
			$smarty->assign('lista_indicador_2_general_mes3', $lista_general);
			/*********************************************************************************************************/
			$gr = new PowerGraphic;
			$gr->axis_x    = 'Fallo';
			$gr->axis_y    = 'Rech / Total';
			$gr->skin      = 3;
			$gr->type      = 1;
			$gr->credits   = 0;
			$gr->x[0] = 0;
			$gr->y[0] = 0;
			
			$cont = 1;
			foreach($lista_general as $indice => $valor) {
				$gr->x[$cont] = $valor["numero"];
				$gr->y[$cont] = $valor["porcentaje"];
				$cont ++;
			}
			
			$gr->start();
			$smarty->assign('params_mes3',$gr->create_query_string());
			/*********************************************************************************************************/
			
			$smarty->display('indicadores/composicon_rechazo.html');
			break;
		case 'metas':

			
			if (isset($_GET['area'])) {
				$area = utf8_decode(trim($_GET['area']));
				$listado_areas = $indicadores->listado_metas_areas($area, 1);
				$smarty->assign('lista_indicadores', $listado_areas);
				header("Content-Type: text/html; charset=iso-8859-1");
				$smarty->display('indicadores/actualizar_metas_contenido.html');
				exit();
			} else {
				$lista_listar_indicadores = $indicadores->listar_indicadores();
				$smarty->assign('lista_listar_indicadores', $lista_listar_indicadores);
				$smarty->display('indicadores/actualizar_metas.html');
			}		
			
			break;

		case 'reporte_indicadores':
			 
			 	   
				
				 $reporte_indicador= $indicadores->reporte_indicador();									      	
			     $smarty->assign('indicadores',$reporte_indicador);
			   			
		         $fecha_inicio = date("Y-m-d"); 
		         $fecha_fin = date("Y-m-d"); 
			     
				 $smarty->assign('fecha_inicio',$fecha_inicio);
				 $smarty->assign('fecha_fin',$fecha_fin);
				
				$smarty->display('indicadores/rep_indicadores.html');
				
			
					
				
				
			   
			break;
			
		case 'actualizar_metas':
			$mid = $_GET['mid'];
			$valor = $_GET['valor'];
			$obs = $_GET['obs'];
			$mfec = date("Y-m-d");
			$usuario = $_SESSION['usuario_id'];
			
			$indicadores->insertar_valor_meta($valor, $obs, $mfec, $usuario, $mid);
			
			//echo $mid ." - " .$valor." - ".$obs;
			echo '<span style="float:left;">'.$valor.'</span>';
			echo '<span style="float:right;" title="'.$mfec.'"><img src="../../templates/indicadores/pics/date.jpg"></span>';
			if ($obs != null)
				echo '<span style="float:right;" title="'.$obs.'"><img src="../../templates/indicadores/pics/obs.jpg"></span>';			
			break;
		
			default :
			
			break;	
			
			
	}
	
	if ($_POST['repasignacion']){
				//buscamos el detalle
				$fecha_inicio = trim($_POST["fecha_inicio"]);
				$fecha_fin = trim($_POST["fecha_fin"]);
				
				if ($fecha_fin < $fecha_inicio){
					
					 $error['fecha'] = "La fecha de finalizacióon debe ser mayor";
			         $smarty->assign('error',$error);
			         $smarty->assign('fecha_inicio',$fecha_inicio);
			         $smarty->assign('fecha_fin',$fecha_fin);
					}
				
					
								
				
		        //No olvidar la instancia de clase
				if (isset($error)){
					$smarty->assign('error', $error);
					$reporte_indicador= $indicadores->reporte_indicador();									      	
			        $smarty->assign('indicadores',$reporte_indicador);
			   			
					$smarty->display('indicadores/rep_indicadores.html');
				} 
				else {
					
				    	 if($_POST['indicadores'])
				     	{     
				     	  $lista=$_POST['indicadores'];
				     	  /*echo "<pre>";
							print_r($_POST);
							echo "</pre>";*/
				     	  
				    		 if ($lista=='6 Desperdicio real en cuero y otros')
				     				{   $indi='Desperdicio-'.$fecha_inicio.'-'.$fecha_fin;
				     		 	       // $reporte_ind6=$indicadores->reporte_ind6($fecha_inicio,$fecha_fin);
				           				$excel_ind6=$indicadores->reporte_excelind6($fecha_inicio,$fecha_fin);
				          			 
				           				$smarty->assign('ver6','si');
                                       
				       					 if ($excel_ind6 != null)
				           					 {
				          				     escribir_indicador6_excel($indi, $excel_ind6);
				             				  $smarty->assign('excel', $indi);
				             				  
				            				 }

				            					//$smarty->assign('reporte_ind',$reporte_ind6);
				            					$smarty->assign('lista',$lista);
				            					$smarty->assign('fecha_inicio',$fecha_inicio);
												$smarty->assign('fecha_fin',$fecha_fin);
												$reporte_indicador= $indicadores->reporte_indicador();									      	
			   									$smarty->assign('indicadores',$reporte_indicador);
												$smarty->display('indicadores/rep_indicadores.html');
				           						
				             
				     				}
					               if ($lista=='7 Materia prima procesada')
				    	             {      
				     	  				 $indi='Materia Prima-'.$fecha_inicio.'-'.$fecha_fin;
				          				// $reporte_ind7=$indicadores->reporte_ind7($fecha_inicio,$fecha_fin);
				         				 $excel_ind7=$indicadores->reporte_excelind7($fecha_inicio,$fecha_fin);
                           				 $smarty->assign('ver7','si');
				       					 if ($excel_ind7 != null)
				         				   {
				         				      escribir_indicador7_excel($indi,$excel_ind7);
				          				     $smarty->assign('excel',$indi);
				          				   }
												$smarty->assign('lista',$lista);
				          						 $smarty->assign('fecha_inicio',$fecha_inicio);
												$smarty->assign('fecha_fin',$fecha_fin);
												$reporte_indicador= $indicadores->reporte_indicador();									      	
			   									$smarty->assign('indicadores',$reporte_indicador);
												$smarty->display('indicadores/rep_indicadores.html');
				           						
				             
				    				 }
				    				   if ($lista=='8 Productos elaborados comparado con la cantidad de personal')
				    	             {      
				     	  				 $indi='Productividad-'.$fecha_inicio.'-'.$fecha_fin;
				          				 //$reporte_ind8=$indicadores->reporte_ind8($fecha_inicio,$fecha_fin);
				         				 $excel_ind8=$indicadores->reporte_excelind8($fecha_inicio,$fecha_fin);
                           				 $smarty->assign('ver8','si');
				       					 if ($excel_ind8 != null)
				         				   {
				         				      escribir_indicador8_excel($indi,$excel_ind8);
				          				     $smarty->assign('excel',$indi);
				          				   }
												$smarty->assign('lista',$lista);
				          				        $smarty->assign('fecha_inicio',$fecha_inicio);
												$smarty->assign('fecha_fin',$fecha_fin);
												$reporte_indicador= $indicadores->reporte_indicador();									      	
			   									$smarty->assign('indicadores',$reporte_indicador);
												$smarty->display('indicadores/rep_indicadores.html');
				           						
				    				 }
				    				 
				    				  if ($lista=='5 Control de medio proceso y muestras iniciales')
				    	             {      
				     	  				 $indi='Medio Proceso-'.$fecha_inicio.'-'.$fecha_fin;
				          				 //$reporte_ind8=$indicadores->reporte_ind8($fecha_inicio,$fecha_fin);
				         				
                           				 $smarty->assign('ver9','si');
				       					
												$smarty->assign('lista',$lista);
				          				        $smarty->assign('fecha_inicio',$fecha_inicio);
												$smarty->assign('fecha_fin',$fecha_fin);
												$smarty->assign('indi',$indi);
												$reporte_indicador= $indicadores->reporte_indicador();
												$indi5=$indicadores->reporte_muestra($fecha_inicio,$fecha_fin);									      	
			   									$smarty->assign('indicadores',$reporte_indicador);
			   									$smarty->assign('indi5',$indi5);
			   									$smarty->display('indicadores/rep_indicadores.html');
				           						
				    				 }
				     
				//print_r($reporte_asignaciones) ;
				
				
				}	
				//fin de la busqueda
			}
	
	}
}

?>