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
//*******grupo de especiales***********
$grupo_id = 15;
//*******grupo de especiales***********

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
				unset($_SESSION['mensaje']);
				unset($_SESSION['c']);
				unset($_SESSION['g']);
				unset($_SESSION['o']);
				
				$lista = $asignacion->consulta_lista_ordenes_anual(date("Y"));
				$smarty->assign('ordenes', $lista);
				$smarty->display('sistema_de_produccion/corte/orden_produccion_busqueda.html');
				break;
			}
			case 2:{
				unset($_SESSION['c']);
				unset($_SESSION['g']);
				unset($_SESSION['o']);
				
				$op_id = $_GET['oid'];
				$_SESSION['$op_id'] = $op_id;
				$smarty->assign('op_id', $op_id);
				
				$cabecera = $ordenes->obtener_cabecera_orden($op_id);
				$smarty->assign('cabecera', $cabecera);
				
				if(isset($_SESSION['mensaje'])){
					$smarty->assign('mensaje', $_SESSION['mensaje']);
					unset($_SESSION['mensaje']);
				}
				
				$detalle = $hoja_ruta_corte->detalle_orden_inicial($op_id, $_SESSION['tabu']);
				$smarty->assign('detalle', $detalle);
				//echo "imprimir la cabezera y el detalle de la orden";
				$smarty->assign('tabu', $_SESSION['tabu']);
				$smarty->display('sistema_de_produccion/corte/detalle_orden.html');
				break;
			}
			
			case 3:{
				//averiguar para que tipo de asignacion es
				$tipo_operacion = $_GET['op'];
				
				$hid = $_GET['hid'];
				//echo "<br>hid: ".$hid;
				
				//**************************generamos la cabacera del reporte
				//$cabecera = $hoja_ruta_corte->cabecera_reporte($hid);
				//$smarty->assign('cabecera', $cabecera);
				//**************************
				
				switch ($tipo_operacion){
					case 1:{
						$lista_corte = $hoja_ruta_corte->sacar_detalle_corte1($hid);
						$smarty->assign('lista_corte', $lista_corte);
						break;
					}
					case 2:{
						$lista_dividido = $hoja_ruta_corte->sacar_detalle_dividido1($hid, 'dividido');
						$smarty->assign('lista_dividido', $lista_dividido);
						break;
					}
					case 3:{
						$lista_desbaste = $hoja_ruta_corte->sacar_detalle_dividido1($hid, 'desbaste');
						$smarty->assign('lista_desbaste', $lista_desbaste);
						break;
					}
					case 4:{
						$lista_sellado = $hoja_ruta_corte->sacar_detalle_dividido1($hid, 'sellado');
						$smarty->assign('lista_sellado', $lista_sellado);
						break;
					}
					case 5:{
						$lista_planchado = $hoja_ruta_corte->sacar_detalle_dividido1($hid, 'planchado');
						$smarty->assign('lista_planchado', $lista_planchado);
						break;
					}
				}
				
				//mostramos el reporte
				$smarty->display('sistema_de_produccion/corte/hoja_ruta_reporte.html');
				break;
			}
			
			case 4:{
				$h_id = $_GET['hid'];
				
				$detalle_division = $hoja_ruta_corte->detalle_division($h_id);
				for ($i=$detalle_division[0]['cantidad']; $i >= 1 ; $i--){
					$vector_cantidad[$i] = $i;
				}
				
				$smarty->assign('consigna', "dividir_corte");
				
				$smarty->assign('vector_cantidad', $vector_cantidad);
				$smarty->assign('valor_seleccionado', $detalle_division[0]['cantidad']);
				
				$smarty->assign('detalle_division', $detalle_division);
				$smarty->display('sistema_de_produccion/corte/formulario_dividir.html');
				break;
			}
			case 5:{
				//echo "<br>Modificar las asignaciones";
				break;
			}
			case 6:{
				//echo "<br>opcion 6";
				

				//queremos saber si el cortador es del grupo "especiales"
				$especiales = $hoja_ruta_corte->verificar_especiales($grupo_id, $_SESSION['usuario_id']);
				if ($especiales){			
					$smarty->assign('especiales', $especiales);
				}
				//*******************************************************
				
				//para que aparezca el encabezado
				switch ($_SESSION['tabu']){
					case 1: {
						$titulo_asignacion = "Asignaciones para Corte";
						$nombre_tabla = 'corte';
						$smarty->assign('tabu', 1);
						break;
					}
					case 2:{
						$titulo_asignacion = "Asignaciones para Dividido";
						$nombre_tabla = 'dividido';
						$smarty->assign('tabu', 2);
						break;
					}
					case 3:{
						$titulo_asignacion = "Asignaciones para Desbaste";
						$nombre_tabla = 'desbaste';
						$smarty->assign('tabu', 3);
						break;
					}
					case 4:{
						$titulo_asignacion = "Asignaciones para Sellado";
						$nombre_tabla = 'sellado';
						$smarty->assign('tabu', 4);
						break;
					}
					case 5:{
						//echo "es 5";
						$titulo_asignacion = "Asignaciones para Planchado";
						$nombre_tabla = 'planchado';
						$smarty->assign('tabu', 5);
						break;
					}
					case 6:{
						$titulo_asignacion = "Asignaciones para Entrega";
						$smarty->assign('tabu', 6);
						break;
					}
				}
				$smarty->assign('titulo_asignacion', $titulo_asignacion);
				//fin para que aparezca el encabezado
				
				//se manda la orden para volver a la pagina anterior
				if(isset($_SESSION['$op_id'])){
					$smarty->assign('op_id', $_SESSION['$op_id']);
				}
				
				if(isset($_SESSION['comprobacion'])){
					$smarty->assign('comprobacion', $_SESSION['comprobacion']);
				}
				
				if(isset($_SESSION['avanzar_asignados'])){
					$smarty->assign('avanzar_asignados', $_SESSION['avanzar_asignados']);
					unset($_SESSION['avanzar_asignados']);
				}
				
				if(isset($_SESSION['mensaje'])){
					$smarty->assign('mensaje', $_SESSION['mensaje']);
				}
				
				$smarty->assign('cont_asignados', $_SESSION['cont_asignados']);
				$smarty->assign('long_hid', $_SESSION['long_hid']);
				$smarty->assign('lista_hid', $_SESSION['lista_hid']);
				
				$hid = $_SESSION['lista_hid'][$_SESSION['cont_asignados'] - 1];
				$smarty->assign('hid', $hid);
				
				//para que aparezca la cabecera
				$cabecera_hoja = $hoja_ruta_corte->cabecera_hoja($hid);
				$smarty->assign('cabecera_hoja', $cabecera_hoja);
				//
				
				//para el detalle del despiece
				$detalle_del_despiece = $hoja_ruta_corte->resumen_del_despiece($hid);
				$smarty->assign('detalle_del_despiece', $detalle_del_despiece);				
				//
				
				//operaciones de acuerdo al tipo de asignacion
				//echo "llega". $_SESSION['tabu'];
				switch ($_SESSION['tabu']){
					case 1: {
						//si se imprimio ya no se pueden modificar los datos
					
						//para generar el detalle, se averigua si existe
						$existe = $hoja_ruta_corte->averiguar_detalle_corte($hid, 'corte');
						if($existe == null){
							$insertar = $hoja_ruta_corte->insertar_tipo_detalle_corte($hid, 1);
						}
						
						$respuesta = $hoja_ruta_corte->mostrar_tipo_detalle_corte($hid, 'corte');
						
						$dcid = $respuesta['dcid'];
						$nombre = $respuesta['nombre'];
						$sis_cantidad = $respuesta['cantidad'];
						$sis_golpe = $respuesta['golpe'];
						$cantidad = $respuesta['cantidad'];
						$golpe = $respuesta['golpe'];
						$apodo = $respuesta['apodo'];
						$completo = $respuesta['completo'];
						
						$personal_id = $respuesta['personal_id'];
						$fhini = $respuesta['fhini'];
						$fhfin = $respuesta['fhfin'];
						
						if (isset($_SESSION['c']) && isset($_SESSION['g']) && isset($_SESSION['o']) && isset($_SESSION['ope'])){
							$cantidad = $_SESSION['c'];
							$golpe = $_SESSION['g'];
							$personal_id = $_SESSION['o'];
							$completo = $_SESSION['ope'];
							
							for ($i=0; $i < sizeof($nombre); $i++){
								$lugar_cantidad = $apodo[$i]."cantidad";
								$lugar_golpe = $apodo[$i]."golpe";
								$lugar_personal_id = $apodo[$i]."operario_id";
								$lugar_completo = $apodo[$i]."operario_nom";
							
								$lista_corte[$i] = array ('dcid' => $dcid[$i], 'nombre' => $nombre[$i], 'sis_cantidad' => $sis_cantidad[$i], 'sis_golpe' => $sis_golpe[$i], 'cantidad' => $cantidad[$lugar_cantidad], 'golpe' => $golpe[$lugar_golpe], 'apodo' => $apodo[$i], 'personal_id' => $personal_id[$lugar_personal_id], 'fhfin' => $fhfin[$i], 'completo' => $completo[$lugar_completo]);
							}
						
						} else {
		
							for ($i=0; $i < sizeof($nombre); $i++){
								$lista_corte[$i] = array ('dcid' => $dcid[$i], 'nombre' => $nombre[$i], 'sis_cantidad' => $sis_cantidad[$i], 'sis_golpe' => $sis_golpe[$i],'cantidad' => $cantidad[$i], 'golpe' => $golpe[$i], 'apodo' => $apodo[$i], 'personal_id' => $personal_id[$i], 'fhfin' => $fhfin[$i], 'completo' => $completo[$i]);
							}
						}
						
						$smarty->assign('lista_corte', $lista_corte);
						////////fin prueba
						break;
					}
					default:{//para las demas asignaciones 2,3,4,5
						//para generar el detalle, se averigua si existe
						//mandamos la hid y el numero de "paso" de asignacion
						
						switch ($_SESSION['tabu']){
							case 2:{
								$existe = $hoja_ruta_corte->averiguar_detalle_corte($hid, 'dividido');
								if($existe == null){
									$insertar = $hoja_ruta_corte->insertar_tipo_detalle_corte($hid, 2);
								}
								
								//sacamos el detalle del dividido
								$respuesta = $hoja_ruta_corte->mostrar_tipo_detalle_corte($hid, 'dividido');
								
								break;
							}
							case 3:{
								//para generar el detalle, se averigua si existe
								//mandamos la hid y el numero de "paso" de asignacion
								$existe = $hoja_ruta_corte->averiguar_detalle_corte($hid, 'desbaste');
								if($existe == null){
									$insertar = $hoja_ruta_corte->insertar_tipo_detalle_corte($hid, 3);
								}
								
								//sacamos el detalle del dividido
								$respuesta = $hoja_ruta_corte->mostrar_tipo_detalle_corte($hid, 'desbaste');
							
								break;
							}
							case 4:{
								//para generar el detalle, se averigua si existe
								//mandamos la hid y el numero de "paso" de asignacion
								$existe = $hoja_ruta_corte->averiguar_detalle_corte($hid, 'sellado');
								if($existe == null){
									$insertar = $hoja_ruta_corte->insertar_tipo_detalle_corte($hid, 4);
								}
								
								//sacamos el detalle del dividido
								$respuesta = $hoja_ruta_corte->mostrar_tipo_detalle_corte($hid, 'sellado');
							
								break;
							}
							case 5:{
								//para generar el detalle, se averigua si existe
								//mandamos la hid y el numero de "paso" de asignacion
								//echo "aqui veremos";
								$existe = $hoja_ruta_corte->averiguar_detalle_corte($hid, 'planchado');
								//echo "Exxite". $existe;
								if($existe == null){
									//echo "si";
									$insertar = $hoja_ruta_corte->insertar_tipo_detalle_corte($hid, 5);
								}
								
								////////////////////////////////////////////////////////////////
								// AQUIIIII ESTAMOS

								/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
								echo "no entre";
								//sacamos el detalle del dividido
								$respuesta = $hoja_ruta_corte->mostrar_tipo_detalle_corte($hid, 'planchado');
							
								break;
							}
						}
						
						//efectuamos las operaciones para que se despliege los datos
						$dcid = $respuesta['dcid'];
						$nombre = $respuesta['nombre'];
						$sis_cantidad = $respuesta['cantidad'];
						$cantidad = $respuesta['cantidad'];
						$apodo = $respuesta['apodo'];
						$completo = $respuesta['completo'];

						$personal_id = $respuesta['personal_id'];
						$fhini = $respuesta['fhini'];
						$fhfin = $respuesta['fhfin'];
						
						if (isset($_SESSION['c']) && isset($_SESSION['o']) && isset($_SESSION['ope'])){
							$cantidad = $_SESSION['c'];
							$personal_id = $_SESSION['o'];
							$completo = $_SESSION['ope'];
							
							for ($i = 0; $i < sizeof($nombre); $i ++){
								$lugar_cantidad = $apodo[$i]."cantidad";
								$lugar_personal_id = $apodo[$i]."operario_id";
								$lugar_completo = $apodo[$i]."operario_nom";
							
								$lista_corte[$i] = array ('dcid' => $dcid[$i], 'nombre' => $nombre[$i], 'sis_cantidad' => $sis_cantidad[$i], 'cantidad' => $cantidad[$lugar_cantidad], 'apodo' => $apodo[$i], 'personal_id' => $personal_id[$lugar_personal_id], 'fhfin' => $fhfin[$i], 'completo' => $completo[$lugar_completo]);
							}
						
						} else {
		
							for ($i=0; $i < sizeof($nombre); $i++){
								$lista_corte[$i] = array ('dcid' => $dcid[$i], 'nombre' => $nombre[$i], 'sis_cantidad' => $sis_cantidad[$i],'cantidad' => $cantidad[$i], 'apodo' => $apodo[$i], 'personal_id' => $personal_id[$i], 'fhfin' => $fhfin[$i], 'completo' => $completo[$i]);
							}
						}
						
						$smarty->assign('lista_corte', $lista_corte);
//
						break;
					}
				}
				//fin operaciones de acuerdo al tipo de asignacion
		
				//para mostrar el boton imprimir
				$clave = $hoja_ruta_corte->comprobar_detalle_corte($hid, $nombre_tabla);
				if ($clave){
					$smarty->assign('clave', $clave);
				}
				//fin mostrar el boton
		
				//verificamos si hoja completo = 1
				$imprimir = $hoja_ruta_corte->verificar_impresion_hoja($hid, $nombre_tabla);
				if ($imprimir != null){
					$smarty->assign('imprimir', 'imprimir');					
				}
				//
		
				//mostrar la pagina
				$smarty->display('sistema_de_produccion/corte/formulario_asignar_corte.html');
				//fin mostrar la pagina
				break;
			}
			case 7:{
				//eliminar un detalle de corte
				
				//recuperamos OP para ver cual es el tipo de operacion
				$tipo_operacion = $_GET['op'];
								
				//el codigo del detalle de la asignacion
				$dcid = $_GET['dcid'];
				//echo "<br>dcid: ".$dcid;
								
				switch ($tipo_operacion){
					case 1:{
						$hoja_ruta_corte->eliminar_detalle_corte($dcid, 'corte');
						break;
					}
					case 2:{
						$hoja_ruta_corte->eliminar_detalle_corte($dcid, 'dividido');
						break;
					}
					case 3:{
						$hoja_ruta_corte->eliminar_detalle_corte($dcid, 'desvaste');
						break;
					}
					case 4:{
						$hoja_ruta_corte->eliminar_detalle_corte($dcid, 'sellado');
						break;
					}
					case 5:{
						$hoja_ruta_corte->eliminar_detalle_corte($dcid, 'planchado');
						break;
					}
				}
				
				header("location: hoja_ruta_corte.php?opcion=6");
			}
			
			case 8:{
				//averiguar para que tipo de asignacion es
				//recuperamos OP para ver cual es el tipo de operacion
				$tipo_operacion = $_GET['op'];
				
				//codigo del detalle
				$dcid = $_GET['dcid'];
				//echo "<br>: Dividir: ".$dcid;
				
				switch ($tipo_operacion){
					case 1:{
						$detalle_division = $hoja_ruta_corte->detalle_detalle_corte($dcid);
						$smarty->assign('consigna', 'detalle_corte');
						break;
					}
					case 2:{
						$detalle_division = $hoja_ruta_corte->detalle_detalle_dividido($dcid, 'dividido');
						$smarty->assign('consigna', 'detalle_dividido');
						break;						
					}
					case 3:{
						$detalle_division = $hoja_ruta_corte->detalle_detalle_dividido($dcid, 'desbaste');
						$smarty->assign('consigna', 'detalle_desbaste');
						break;
					}
					case 4:{
						$detalle_division = $hoja_ruta_corte->detalle_detalle_dividido($dcid, 'sellado');
						$smarty->assign('consigna', 'detalle_sellado');
						break;
					}
					case 5:{
						$detalle_division = $hoja_ruta_corte->detalle_detalle_dividido($dcid, 'planchado');
						$smarty->assign('consigna', 'detalle_planchado');
						break;
					}
				}
				
				for ($i = $detalle_division[0]['cantidad']; $i >= 1; $i --){
					$vector_cantidad[$i] = $i;
				}
				
				//para mandar los datos de la cabecera
				$smarty->assign('detalle_division', $detalle_division);
				//datos para la division
				$smarty->assign('vector_cantidad', $vector_cantidad);
				$smarty->assign('valor_seleccionado', $detalle_division[0]['cantidad']);
				$smarty->assign('codigo_division', $dcid);
				//desplegamos la pagina
				$smarty->display('sistema_de_produccion/corte/formulario_dividir.html');
				break;
			}
			case 9:{
				//echo "eliminar y volver a asignar";
				
				$hid = $_GET['hid'];
				$tabu = $_GET['tabu'];
				switch ($_SESSION['tabu']){
					case 1:{
						$nombre_tabla = 'detalle_corte';
						break;
					}
					case 2:{
						$nombre_tabla = 'detalle_dividido';
						break;
					}
					case 3:{
						$nombre_tabla = 'detalle_desbaste';
						break;
					}
					case 4:{
						$nombre_tabla = 'detalle_sellado';
						break;
					}
					case 5:{
						$nombre_tabla = 'detalle_planchado';
						break;
					}
				}//switch
				$hoja_ruta_corte->vaciar_detalle_hoja ($hid, $nombre_tabla);
	
				header("location: hoja_ruta_corte.php?opcion=6");
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
				$smarty->display('sistema_de_produccion/corte/orden_produccion_busqueda.html');
			}
	
	
			if ($_POST['operar']){
				unset($_SESSION['mensaje']);
				unset($_SESSION['comprobacion']);
				
				$lista_hid = $_POST['hid'];
			
				if ($lista_hid == null){
					//echo "<br>Elija detalles de la orden";
					$_SESSION['mensaje'] = "Elija items para asignar";
					header("location: hoja_ruta_corte.php?opcion=2&tabu=".$_SESSION['tabu']."&oid=".$_SESSION['$op_id']);
				} else {
					//echo "<br>Asignar operarios y ver cuantas hojas se envio: ".$lista_did;
					
					$_SESSION['ddii'] = $_POST['did'];
					
					
					$_SESSION['cont_asignados'] = 1;
					$_SESSION['long_hid'] = sizeof($lista_hid);
					$_SESSION['lista_hid'] = $lista_hid;

					header("location: hoja_ruta_corte.php?opcion=6");
				}//else
			}//if operar
			
			
			//**********modificando para que funcionen todas las asignaciones
			if ($_POST['asignar_corte']){
				unset($_SESSION['comprobacion']);
				//capturar formulario
				$vacio = $post = array();
				
				foreach ($_POST as $nombre_var => $valor_var) {
					if (ereg("corteid", $nombre_var)){
						$corteid = trim($valor_var);
					}
					if (ereg("maximo", $nombre_var)){
						$maximo = trim($valor_var);
					}
					if (ereg("cantidad", $nombre_var)){
						$cantidad = trim($valor_var);
						$c[$nombre_var] = trim($valor_var);
					}
					if (ereg("golpe", $nombre_var)){
						$golpe = trim($valor_var);
						$g[$nombre_var] = trim($valor_var);
					}
					if (ereg("operario_id", $nombre_var)){
						$operario_id = trim($valor_var);
						$o[$nombre_var] = trim($valor_var);
					}
					//para conseguir el nombre del operario
					if (ereg("operario_nom", $nombre_var)){
						$lugar_nombre_operario = $nombre_var;
					}

					//verificamos si tiene un usuario asignado
					if (($operario_id != 0)&&($operario_id != '')){
						
						if ($_SESSION['tabu'] == 1){//si es para corte
							if ((ereg("^[0-9\.\,]{1,}$", $cantidad)) && (ereg("^[0-9\.\,]{1,}$", $golpe)) && ($cantidad <= $maximo + 1)){
								$fecha_hora_ini = date("Y-m-d H:i:s");
								$fecha_hora_fin = date("Y-m-d H:i:s");
								$hoja_ruta_corte->guardar_parcial_detalle_corte($corteid, $cantidad, $golpe, $operario_id, $fecha_hora_ini, $fecha_hora_fin, 'corte');
								//si todo esta bien generamos el vector de nombres
								$ope[$lugar_nombre_operario] = $hoja_ruta_corte->obtener_operario($operario_id);
								$_SESSION['avanzar_asignados'] = true;
							} else {
								$hoja_ruta_corte->guardar_parcial_solo_nombre_corte($corteid, $operario_id, 'corte');
								$_SESSION['comprobacion'][$corteid] = "Para guardar un registro, llenar todos sus campos apropiadamente";
								unset($_SESSION['avanzar_asignados']);
							}
						} else {//si es para las demas asignaciones
					
							switch ($_SESSION['tabu']){
								case 2:{
									$nombre_tabla = 'dividido';
									break;
								}
								case 3:{
									$nombre_tabla = 'desbaste';
									break;
								}
								case 4:{
									$nombre_tabla = 'sellado';
									break;
								}
								case 5:{
									$nombre_tabla = 'planchado';
									break;
								}
							}//switch

							if ($_SESSION['tabu'] == 4){//caso super especial para el sellado
								//
								if (ereg("^[0-9\.\,]{1,}$", $cantidad)){
									$fecha_hora_ini = date("Y-m-d H:i:s");
									$fecha_hora_fin = date("Y-m-d H:i:s");
									$hoja_ruta_corte->guardar_parcial_detalle_corte($corteid, $cantidad, $golpe, $operario_id, $fecha_hora_ini, $fecha_hora_fin, $nombre_tabla);
									//si todo esta bien generamos el vector de nombres
									$ope[$lugar_nombre_operario] = $hoja_ruta_corte->obtener_operario($operario_id);
									$_SESSION['avanzar_asignados'] = true;
								} else {
									$hoja_ruta_corte->guardar_parcial_solo_nombre_corte($corteid, $operario_id, $nombre_tabla);
									//especificar el mensaje de error
									$_SESSION['comprobacion'][$corteid] = "Llene todos sus campos apropiadamente, la cantidad esta limitada por el despiece";
									unset($_SESSION['avanzar_asignados']);
								}//fin if
								//							
							} else {
								//
								if (ereg("^[0-9\.\,]{1,}$", $cantidad) && ($cantidad <= $maximo)){
									$fecha_hora_ini = date("Y-m-d H:i:s");
									$fecha_hora_fin = date("Y-m-d H:i:s");
									$hoja_ruta_corte->guardar_parcial_detalle_corte($corteid, $cantidad, $golpe, $operario_id, $fecha_hora_ini, $fecha_hora_fin, $nombre_tabla);
									//si todo esta bien generamos el vector de nombres
									$ope[$lugar_nombre_operario] = $hoja_ruta_corte->obtener_operario($operario_id);
									$_SESSION['avanzar_asignados'] = true;
								} else {
									$hoja_ruta_corte->guardar_parcial_solo_nombre_corte($corteid, $operario_id, $nombre_tabla);
									//especificar el mensaje de error
									$_SESSION['comprobacion'][$corteid] = "Llene todos sus campos apropiadamente, la cantidad esta limitada por el despiece";
									unset($_SESSION['avanzar_asignados']);
								}//fin if
								//
							}

						}//fin if (session tabu)
						
						$operario_id = 0;
					}//fin if usuario asignado
				}
				//fin capturar
				
				
				
				if ($_SESSION['cont_asignados'] > $_SESSION['long_hid']){
					unset($_SESSION['c']);
					unset($_SESSION['g']);
					unset($_SESSION['o']);
					unset($_SESSION['ope']);

					$_SESSION['mensaje'] = "Cantidad de items asignados: ".$_SESSION['long_hid'];
					header("location: hoja_ruta_corte.php?opcion=2&tabu=".$_SESSION['tabu']."&oid=".$_SESSION['$op_id']);		
				} else {
					//si no hay error
					//mandar los 2 vectores para actualizar
					$_SESSION['c'] = $c;
					$_SESSION['g'] = $g;
					$_SESSION['o'] = $o;
					$_SESSION['ope'] = $ope;
					header("location: hoja_ruta_corte.php?opcion=6");
				}
			}//
			
			
			if ($_POST['asignar_corte_sin_modificar']){
				unset($_SESSION['comprobacion']);
			
				$_SESSION['cont_asignados'] += 1;
				if ($_SESSION['cont_asignados'] > $_SESSION['long_hid']){
					unset($_SESSION['c']);
					unset($_SESSION['g']);
					unset($_SESSION['o']);
					unset($_SESSION['ope']);

					$_SESSION['mensaje'] = "Cantidad de items asignados: ".$_SESSION['long_hid'];
					header("location: hoja_ruta_corte.php?opcion=2&tabu=".$_SESSION['tabu']."&oid=".$_SESSION['$op_id']);		
				} else {
					//mandar a la primera hoja
					header("location: hoja_ruta_corte.php?opcion=6");
				}
			}

			
			//**********modificado para que funcionen todas las asignaciones
			if ($_POST['dividir']){
				//echo "<br>Dividir";
				$cantidad1 = $_POST['cantidad1'];
				$cantidad2 = $_POST['cantidad2'];
				$consigna = $_POST['consigna'];

				if ($cantidad2 > 0){
					switch ($consigna){
						case 'dividir_corte':{
							$h_id = $_POST['h_id'];
							$did = $_POST['did'];
							//quitar la anterior hoja
							$hoja_ruta_corte->eliminar_hoja($h_id);
							//adicionar las nuevas cantidades con el detalle id
							$hoja_ruta_corte->insertar_hoja($did, $cantidad1, $cantidad2);
							break;
						}
						case 'detalle_corte':{
							$dcid = $_POST['dcid'];
							$detalle = $hoja_ruta_corte->detalle_detalle_corte($dcid);
							$hoja_ruta_corte->eliminar_detalle_detalle_corte($dcid, 'corte');
							$respuesta = $hoja_ruta_corte->insertar_detalle_detalle_corte($detalle[0], $cantidad1, $cantidad2);
							break;
						}
						case 'detalle_dividido':{
							$dcid = $_POST['dcid'];
							$detalle = $hoja_ruta_corte->detalle_detalle_dividido($dcid, 'dividido');
							$hoja_ruta_corte->eliminar_detalle_detalle_corte($dcid, 'dividido');
							$respuesta = $hoja_ruta_corte->insertar_detalle_detalle_dividido($detalle[0], $cantidad1, $cantidad2, 'dividido');
							break;
						}
						case 'detalle_desbaste':{
							$dcid = $_POST['dcid'];
							$detalle = $hoja_ruta_corte->detalle_detalle_dividido($dcid, 'desbaste');
							$hoja_ruta_corte->eliminar_detalle_detalle_corte($dcid, 'desbaste');
							$respuesta = $hoja_ruta_corte->insertar_detalle_detalle_dividido($detalle[0], $cantidad1, $cantidad2, 'desbaste');
							break;
						}
						case 'detalle_sellado':{
							$dcid = $_POST['dcid'];
							$detalle = $hoja_ruta_corte->detalle_detalle_dividido($dcid, 'sellado');
							$hoja_ruta_corte->eliminar_detalle_detalle_corte($dcid, 'sellado');
							$respuesta = $hoja_ruta_corte->insertar_detalle_detalle_dividido($detalle[0], $cantidad1, $cantidad2, 'sellado');
							break;
						}
						case 'detalle_planchado':{
							$dcid = $_POST['dcid'];
							$detalle = $hoja_ruta_corte->detalle_detalle_dividido($dcid, 'planchado');
							$hoja_ruta_corte->eliminar_detalle_detalle_corte($dcid, 'planchado');
							$respuesta = $hoja_ruta_corte->insertar_detalle_detalle_dividido($detalle[0], $cantidad1, $cantidad2, 'planchado');
							break;
						}
					}
				}
			}
			
			
			if ($_POST['imprimir_corte']){
				$hid = $_SESSION['lista_hid'][$_SESSION['cont_asignados'] - 1];
				//echo "<br>hoja: ".$hid;			
				
				///
				switch ($_SESSION['tabu']){
					case 1:{
						$nombre_tabla = 'corte';
						break;
					}
					case 2:{
						$nombre_tabla = 'dividido';
						break;
					}
					case 3:{
						$nombre_tabla = 'desbaste';
						break;
					}
					case 4:{
						$nombre_tabla = 'sellado';
						break;
					}
					case 5:{
						$nombre_tabla = 'planchado';
						break;
					}
				}//switch
				///
				
				$clave = $hoja_ruta_corte->comprobar_detalle_corte($hid, $nombre_tabla);
				if ($clave){
					$hoja_ruta_corte->actualizar_completo_hoja($hid, $nombre_tabla);
					header("location: hoja_ruta_corte.php?opcion=6");
				} else {
					//recargar la pagina y mostrar un mensaje
					$_SESSION['mensaje'] = "Debe completar todas las asignaciones";
					header("location: hoja_ruta_corte.php?opcion=6");
				}
			}
			
			
			if ($_POST['asignar_avanzar_corte']){
				unset($_SESSION['comprobacion']);
				//capturar formulario
				
				$vacio = $post = array();
				
				foreach ($_POST as $nombre_var => $valor_var) {
					if (ereg("corteid", $nombre_var)){
						$corteid = trim($valor_var);
					}
					if (ereg("maximo", $nombre_var)){
						$maximo = trim($valor_var);
					}
					if (ereg("cantidad", $nombre_var)){
						$cantidad = trim($valor_var);
						$c[$nombre_var] = trim($valor_var);
					}
					if (ereg("golpe", $nombre_var)){
						$golpe = trim($valor_var);
						$g[$nombre_var] = trim($valor_var);
					}
					if (ereg("operario_id", $nombre_var)){
						$operario_id = trim($valor_var);
						$o[$nombre_var] = trim($valor_var);
					}
					//para conseguir el nombre del operario
					if (ereg("operario_nom", $nombre_var)){
						$lugar_nombre_operario = $nombre_var;
					}

					//verificamos si tiene un usuario asignado
					if (($operario_id != 0)&&($operario_id != '')){
						//si todo esta bien generamos el vector de nombres
						$ope[$lugar_nombre_operario] = $hoja_ruta_corte->obtener_operario($operario_id);
						$operario_id = 0;
					}
					
				}
				//fin capturar
				
				if (!isset($_SESSION['comprobacion'])){
					//para que avance sumar + 1
					$_SESSION['cont_asignados'] += 1;
					//$_SESSION['avanzar_asignados'] = true;
				}
				
				if ($_SESSION['cont_asignados'] > $_SESSION['long_hid']){
					unset($_SESSION['c']);
					unset($_SESSION['g']);
					unset($_SESSION['o']);
					unset($_SESSION['ope']);

					$_SESSION['mensaje'] = "Cantidad de items asignados: ".$_SESSION['long_hid'];
					header("location: hoja_ruta_corte.php?opcion=2&tabu=".$_SESSION['tabu']."&oid=".$_SESSION['$op_id']);		
				} else {
					//si no hay error
					//mandar los 2 vectores para actualizar
					$_SESSION['c'] = $c;
					$_SESSION['g'] = $g;
					$_SESSION['o'] = $o;
					$_SESSION['ope'] = $ope;
					header("location: hoja_ruta_corte.php?opcion=6");
				}
			}
			
		}
	}
}

?>