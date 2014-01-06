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

include_once('../../clases/sistema_de_produccion/asignacion.php');
include_once('../../clases/sistema_de_produccion/recepcion_calidad.php');
include_once('../../clases/sistema_de_produccion/calidad_control.php');

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
$validar=new Validador();
$asignacion = new Asignacion;
$recepcion_calidad = new Recepcion_calidad;
$calidad = new Calidad_control;
//

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	//Inicialmente bandera = false
	$bandera = false;
	
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
				//borramos las variables de sesion
				unset($_SESSION['num_asignacion']);
				
				break;
			}
			case 2:{
				//capturamos las variables
				$tabu = $_GET['tabu'];
				$num_asignacion = $_SESSION['num_asignacion'];
				//mostramos el detalle para 
				$detalle_asignacion = $calidad->buscar_asignacion($num_asignacion);
				
				//*************************************************
				//verificar que tiene que mostrar
				switch ($tabu){
					case 2:{
						//resumen para control de calidad
						$resumen_calidad = $calidad->cantidad_calidad_falta($num_asignacion);
						$smarty->assign('resumen_calidad', $resumen_calidad);
						
						//formulario para recepcion de calidad
						$detalle_control = $calidad->detalle_asignacion_control($num_asignacion);
						if ($detalle_control != null){
							//verificar si se asignaron todos los de limpieza
							if ($resumen_calidad['pendientes_calidad'] ==0){
								//no existen mas pendientes
								$mensaje_calidad = "No existen pendientes para asignar";
								$smarty->assign('mensaje_calidad', $mensaje_calidad);
							}
							
							$smarty->assign('detalle_control', $detalle_control);
						} else {
							$mensaje_calidad = "No se realiz&oacute; ninguna asignaci&oacute;n para limpieza";
							$smarty->assign('mensaje_calidad', $mensaje_calidad);
						}
						
						break;
					}
				}
				//*************************************************
				//deberia mostrar todo el detalle para la pestana
				$bandera = true;
				//*************************************************
				
				break;
			}
			case 3:{
				//quitar la id elegida
				//capturamos las variables
				$limpieza_id = $_GET['lid'];
				//echo "<br>lid: ".$limpieza_id;
				//mostramos el formulario de control de calidad
				$smarty->display('sistema_de_produccion/control_calidad/buscar_asignacion.html');
				break;
			}
		}
		
		if (!empty($_POST)){
			if ($_POST['buscar']){
				//********************
				//lo colocamos por defecto cada vez que se realice la busqueda
				$_SESSION['tabu'] = 1;
				//********************
				
				//recuperamos las variables
				$num_asignacion = trim($_POST["num_asignacion"]);
	
				//validacion del numero de asignacion
				if ($validar->validarTodo($num_asignacion, 1, 100)){
					$error['num_asignacion'] = "Ingrese N&uacute;mero de &Oacute;rden";
				} else {
					//validamos que sea solo numero
					if (!ereg("^[0-9]{1,}$", $num_asignacion)){
						$error['num_asignacion'] = "Ingresar solo n&uacute;meros";
					}
				}
				
				if (isset($error)){
					//reenviar los errores
					$smarty->assign('errores', $error);
				} else {
					//echo "<br>Buscar por: Asignacion";
					$detalle_asignacion = $calidad->buscar_asignacion($num_asignacion);
					if ($detalle_asignacion != null){
						//verificamos si esta cerrada o no
						//Sin cerrar = 0
						//Cerrada cualquier numero != 0
						if ($detalle_asignacion['cerrada'] == 0){
							//NO se cerro la asignacion
							//guardamos el numero de asignacion en sesion
							$_SESSION['num_asignacion'] = $num_asignacion;
							
							//variable para mostrar el detalle false = no muestra, true = muestra
							$bandera = true;
						} else {
							//la asignacion esta cerrada
							//mostrar la fecha
							$fecha_hora_cerrado = $detalle_asignacion['fecha_cerrado'];
							$fecha_hora_cerrado = split(" ", $fecha_hora_cerrado);
							//sacar fecha
							$fecha_cerrado = $fecha_hora_cerrado[0];
							//sacar hora
							$hora_cerrado = $fecha_hora_cerrado[1];
							//sacar usuario
							$nombre_usuario = $calidad->nombre_usuario($detalle_asignacion['usuario_cerrado']);
							
							$mensaje = "La asignaci&oacute;n se cerr&oacute;";
							$mensaje .= " el: '".$fecha_cerrado."'";
							$mensaje .= ", a horas: '".$hora_cerrado."'";
							$mensaje .= ", por el usuario: '".$nombre_usuario['usuario']."'";
							
							$smarty->assign('mensaje', $mensaje);
						}
					} else {
						$smarty->assign('mensaje', "No se encontr&oacute; el n&uacute;mero de asignaci&oacute;n");
					}
					//mostramos el detalle
					
				}
			}//end if ($_POST['buscar'])
			
			if ($_POST['asig_limpieza']){
				//*****************************************************************
				//recumperamos el numero de asignacion y calculamos el detalle
				$num_asignacion = $_SESSION['num_asignacion'];
				$detalle_asignacion = $calidad->buscar_asignacion($num_asignacion);
				//mostramos el detalle
				$bandera = true;
				//*****************************************************************
				
				//validar si si se selecciono personal
				$limpiador_id = $_POST['limpiador_id'];
				
				if ($limpiador_id != ""){
					//se escogio una persona
					//recogemos las variables
					$asignacion_id = $_POST['asignacion_id'];
					$cantidad = $_POST['cantidad'];
					
					//establecemos el tiempo = hora sistema
					$fecha_inicio = date ("Y-m-d H:i:s");
					$fecha_finalizacion = date ("Y-m-d H:i:s");
					
					//recuperamos el usuario del sistema
					$usuario_limpieza = $_SESSION['usuario_id'];
					
					//llamamos a la funcion que inserta los datos
					$calidad->insertar_asignacion_limpieza($asignacion_id, $limpiador_id, $fecha_inicio, $cantidad, $fecha_finalizacion, $usuario_limpieza);
				} else {
					//no se escogio a nadie
					//echo "<br>escoja persona";
					$error['nombre'] = "Seleccione personal";
					$smarty->assign('errores', $error);
					$smarty->assign('cantidad', $cantidad);
				}
								
			}//end if ($_POST['asig_limpieza'])
			
			
			if ($_POST['asignar_control']){
				//echo "<br>guardar los cambios";
				$num_asignacion = $_SESSION['num_asignacion'];
				
				
				$vacio = $post = array();
				
				foreach ($_POST as $nombre_var => $valor_var) {
					if (ereg("controlid", $nombre_var)){
						$control_calidad_id = trim($valor_var);
					}
					if (ereg("operarioid", $nombre_var)){
						$responsable_control = trim($valor_var);
						
						if ($responsable_control != 0){
							//echo "<br> control_id: ".$control_calidad_id ." operario_id: ".$responsable_control;
							//fechas
							$fecha_inicio_control = date("Y-m-d H:i:s");
							$fecha_finalizacion_control = date("Y-m-d H:i:s");
							$usuario_control = $_SESSION['usuario_id'];
							//sentencia para que actualice las asignciones de control
							$calidad->actualizar_asignacion_control($control_calidad_id, $fecha_inicio_control, $fecha_finalizacion_control, $responsable_control, $usuario_control);
						}
					}
				}
				
			}//if ($_POST['asignar_control'])
		
		}//end if (!empty($_POST))
		
		switch ($_SESSION['tabu']){
			case 1:{
				//limpieza
				if ($bandera){				
					//mostramos el resumen de la asignacion
					$resumen_limpieza = $calidad->cantidad_limpieza_falta($num_asignacion);						
					$smarty->assign('resumen_limpieza', $resumen_limpieza);
					
					//comprobar la cantidad recepcionada
					$cantidad_recepcionada = $recepcion_calidad->total_recepcionado($num_asignacion);
					if ($cantidad_recepcionada > 0){
						// si ya se realizo alguna recepcion
						
						//pasamos la cantidad recepcionada
						$smarty->assign('cantidad_recepcionada', $cantidad_recepcionada);
						
						//generamos el vector de valores para elegir
						for ($i = 1;$i <= $resumen_limpieza['pendientes']; $i ++){
							$vector_pendientes[$i] = $i;
						}
						$smarty->assign('vector_pendientes', $vector_pendientes);
						//valor por defecto
						$cantidad = 1;
						$smarty->assign('cantidad', $cantidad);
						
						//sacamos el detalle de limpieza
						$detalle_limpieza = $calidad->detalle_asignacion_limpieza($num_asignacion);
						$smarty->assign('detalle_limpieza', $detalle_limpieza);
					} else {
						//si No se realizo ninguna recepcion
						
						//Mostramos un mensaje
						$smarty->assign('mensaje', "No se realizo ninguna recepci&oacute;n para el control de calidad.");
					}
				}
				
				break;
			}
			case 2:{
				$detalle_control = $calidad->detalle_asignacion_control($num_asignacion);
				//resumen para control de calidad
				$resumen_calidad = $calidad->cantidad_calidad_falta($num_asignacion);
				$smarty->assign('resumen_calidad', $resumen_calidad);
				
				$smarty->assign('detalle_control', $detalle_control);
				$detalle_asignacion = $calidad->buscar_asignacion($num_asignacion);
				//echo "<br>detalle control";
				break;
			}

		}//end switch
		
		//en todo caso se desplega el numero de asignacion y el formulario
		//*******************************************************************************
		//mostramos el detalle de la asignacion
		$smarty->assign('tabu', $_SESSION['tabu']);
		$smarty->assign('detalle_asignacion', $detalle_asignacion);		
		$smarty->assign('num_asignacion', $num_asignacion);
		$smarty->display('sistema_de_produccion/control_calidad/buscar_asignacion.html');
		//*******************************************************************************
	}
}

?>