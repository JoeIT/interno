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

include_once('../../clases/sistema_de_produccion/entrega.php');
include_once('../../clases/sistema_de_produccion/instructivos.php');


//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
$validar = new Validador();
$entrega = new Entrega;
$ins = new Instructivo;
//



if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	//opcion que se ha escogido
	$opcion = $_GET['opcion'];
	
	switch ($opcion){
		case 1:{
			//formulario de numero de asignacion
			$smarty->display('sistema_de_produccion/entrega/formulario_entrega.html');
			break;
		}
	}
	
	if (!empty($_POST)){
		if ($_POST['buscar']){
			//capturamos los valores de la orden y el detalle
			if (isset($_POST['oid']) && isset($_POST['did'])) {
				$oid = $_POST['oid'];
				$did = $_POST['did'];
				$smarty->assign('oid', $oid);
				$smarty->assign('did', $did);
			}
		
			//capturar el numero de asignacion
			$num_asignacion = trim($_POST['num_asignacion']);
			$link=$ins->ultimo_instructivo_link($num_asignacion);
	
			$smarty->assign('link', $link[0]['url']);
			$smarty->assign('instructivo_id', $link[0]['instructivo_id']);
			//validar numero de asignacion
			if ($validar->validarTodo($num_asignacion, 1, 100)){
				$error['num_asignacion'] = "Ingresar n&uacute;mero de asignaci&oacute;n";
			} else {
				//validamos que sea solo numero
				if (!ereg("^[0-9]{0,}$", $num_asignacion)){
					$error['num_asignacion'] = "Ingresar solo n&uacute;meros";
				}
			}
			//si existe errores
			if (isset($error)){
				$smarty->assign('errores', $error);
			} else {
				//si no existe errores
				//efectuamos la busqueda
				$resultado_asignacion = $entrega->buscar_asignacion($num_asignacion);
				//verificar si devolvio algun resultado
				if ($resultado_asignacion != null){
					//SI existe asignacion
					//verificamos si no ha sido entregado en corte
					if ($resultado_asignacion["entregado"] == 0){
						$smarty->assign('resultado_asignacion', $resultado_asignacion);
						//tambien se mandan los datos del despice
						$cantidad = $resultado_asignacion["cantidad"];
						$resultado_despiece = $entrega->resumen_del_despiece($num_asignacion, $cantidad);
						$smarty->assign('resultado_despiece', $resultado_despiece);
					} else {
						//si ya fue entregaado los materiales se muestra mensaje
						//mostrar la fecha
						$fecha_hora_entrega = $resultado_asignacion["fecha_entrega_corte"];
						$fecha_hora_entrega = split(" ", $fecha_hora_entrega);
						//sacar fecha
						$fecha_entrega = $fecha_hora_entrega[0];
						//sacar hora
						$hora_entrega = $fecha_hora_entrega[1];
						//sacar usuario
						$nombre_usuario = $entrega->nombre_usuario($resultado_asignacion["usuario_entrega_corte"]);
						
						$mensaje = "La entrega se efectu&oacute;";
						$mensaje .= " el: '".$fecha_entrega."'";
						$mensaje .= ", a horas: '".$hora_entrega."'";
						$mensaje .= ", por el usuario: '".$nombre_usuario['usuario']."'";
						
						$smarty->assign('mensaje', $mensaje);
						
						//*******************se agregooooooo
						$smarty->assign('resultado_asignacion', $resultado_asignacion);
						//tambien se mandan los datos del despice
						$cantidad = $resultado_asignacion["cantidad"];
						$resultado_despiece = $entrega->resumen_del_despiece($num_asignacion, $cantidad);
						$smarty->assign('resultado_despiece', $resultado_despiece);
						
						$smarty->assign('ya_se_efectuo', 'ya_se_efectuo');
						//******************
					}
				} else {
					//NO existe asignacion
					$smarty->assign('mensaje', "No se encontr&oacute; la asignaci&oacute;n");
				}
			}
		}
		
		
		//para entregar
		if ($_POST['entregar']){
			//capturamos los valores de la orden y el detalle
			if (isset($_POST['oid']) && isset($_POST['did'])) {
				$oid = $_POST['oid'];
				$did = $_POST['did'];
				$smarty->assign('oid', $oid);
				$smarty->assign('did', $did);
			}
		
			// los que fueron checkeados
			$verificar = $_POST['verificar'];
		
			//capturar el pin del operario
			$pin = trim($_POST['pin']);
			$num_asignacion = trim($_POST['num_asignacion']);
			
			//validar pin
			if ($validar->validarTodo($pin, 1, 100)){
				$error['pin'] = "Debe ingresar PIN";
			} else {
				//validamos que sea solo numero
				if (!ereg("^[0-9]{3,}$", $pin)){
					$error['pin'] = "Ingrese n&uacute;mero de tres cifras";
				}
			}
			
			//si existe errores
			if (isset($error)){
				$smarty->assign('errores', $error);
			} else {
				//verificamos si el ping es correcto
				//capturar id del personal
				$personal_id = trim($_POST['personal_id']);
				$comprobar_pin = $entrega->comprobar_pin($personal_id, $pin);
//**cambiar este if //if ($comprobar_pin){
				if ($pin == 123) {
					//esta correcto
					$fecha_hoy = date('Y-m-d H:i:s');
					$usuario_entrega_corte = $_SESSION['usuario_id'];
					$entrega->modificar_asignacion($num_asignacion, $fecha_hoy, $usuario_entrega_corte);
					$smarty->assign('mensaje', "Se realiz&oacute; la comprobaci&oacute;n y entrega");
					//variable para que ya no aparezca el detalle
					$bandera = true;
				} else {
					//pin erroneo
					$smarty->assign('mensaje', "El PIN es incorrecto");
				}
			}
		
					
			//**********************
			$smarty->assign('verificar', $verificar);
			//print_r ($verificar);
			//**********************
			
			//mostramos el detalle si no existe 'bandera'
			if ($bandera != true){
				//recargamos la pagina para mostrar los datos
				$resultado_asignacion = $entrega->buscar_asignacion($num_asignacion);
				//
				$smarty->assign('resultado_asignacion', $resultado_asignacion);
				//tambien se mandan los datos del despice
				$cantidad = $resultado_asignacion["cantidad"];
				$resultado_despiece = $entrega->resumen_del_despiece($num_asignacion, $cantidad);
				$smarty->assign('resultado_despiece', $resultado_despiece);
				//
			}
			
			//mostramos el numero de asignacion
			$smarty->assign('pin', $pin);
		}
		
		//mostramos el numero de asignacion
		$smarty->assign('num_asignacion', $num_asignacion);
		//desplegamos el formulario y los resultados si existe
		$smarty->display('sistema_de_produccion/entrega/formulario_entrega.html');
			
	}
}

?>