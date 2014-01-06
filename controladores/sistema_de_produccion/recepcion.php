<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

include_once('../includes/fecha.php');
include_once('../../clases/validador.php');
include_once('../../clases/sistema_de_produccion/recepcion.php');

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
$validar = new Validador();
$recepcion = new Recepcion;
//

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	//opcion que se ha escogido
	$opcion = $_GET['opcion'];
	
	switch ($opcion){
		case 1:{
			//formulario de numero de asignacion
			$smarty->display('sistema_de_produccion/recepcion/formulario_recepcion.html');
			break;
		}
	}
	
	if (!empty($_POST)){
		if ($_POST['buscar']){
			//capturar el numero de asignacion
			$num_asignacion = trim($_POST['num_asignacion']);
			//validar numero de asignacion
			if ($validar->validarTodo($num_asignacion, 1, 100)){
				$error['num_asignacion'] = "Ingresar n&uacute;mero de asignaci&oacute;n";
			} else {
				//validamos que sea solo numero
				if (!ereg("^[0-9]{1,}$", $num_asignacion)){
					$error['num_asignacion'] = "Ingresar solo n&uacute;meros";
				}
			}
			//si existe errores
			if (isset($error)){
				$smarty->assign('errores', $error);
			} else {
				//si no existe errores
				//efectuamos la busqueda
				$resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
				//verificar si devolvio algun resultado
				if ($resultado_asignacion != null){
					//SI existe asignacion
					//verificamos si no ha sido entregado en corte
					if ($resultado_asignacion["usuario_recepcion"] == 0){
						$smarty->assign('resultado_asignacion', $resultado_asignacion);
					} else {
						//si ya fue entregado los materiales se muestra mensaje
						//mostrar la fecha
						$fecha_hora_entrega = $resultado_asignacion["fecha_recepcion"];
						$fecha_hora_entrega = split(" ", $fecha_hora_entrega);
						//sacar fecha
						$fecha_entrega = $fecha_hora_entrega[0];
						//sacar hora
						$hora_entrega = $fecha_hora_entrega[1];
						//sacar usuario
						$nombre_usuario = $recepcion->nombre_usuario($resultado_asignacion["usuario_recepcion"]);
						
						$mensaje = "La recepci&oacute;n se efectu&oacute;";
						$mensaje .= " el: '".$fecha_entrega."'";
						$mensaje .= ", a horas: '".$hora_entrega."'";
						$mensaje .= ", por el usuario: '".$nombre_usuario['usuario']."'";
						
						$smarty->assign('mensaje', $mensaje);
						
						//*******************se agregooooooo
						$smarty->assign('resultado_asignacion', $resultado_asignacion);						
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
		if ($_POST['recepcionar']){
			//capturar el numero de asignacion
			$num_asignacion = trim($_POST['num_asignacion']);
			$cantidad = trim($_POST['cantidad']);
			
			//esta correcto
			$fecha_hoy = date('Y-m-d H:i:s');
			$usuario_entrega_recepcion = $_SESSION['usuario_id'];
			$resultado = $recepcion->modificar_recepcion($num_asignacion, $fecha_hoy, $usuario_entrega_recepcion, $cantidad);
			
			if ($resultado){
				$smarty->assign('mensaje', "La recepci&oacute;n de producci&oacute;n se efectu&oacute; correctamente");
				$bandera = true;
			} else{
				$smarty->assign('mensaje', "No se puedo realizar la recepci&oacute;n");
			}
				
			//mostramos el detalle si no existe 'bandera'
			if ($bandera != true){
				//recargamos la pagina para mostrar los datos
				$resultado_asignacion = $recepcion->buscar_asignacion($num_asignacion);
				//
				$smarty->assign('resultado_asignacion', $resultado_asignacion);
				//
			}
		}
		
		//mostramos el numero de asignacion
		$smarty->assign('num_asignacion', $num_asignacion);
		//desplegamos el formulario y los resultados si existe
		$smarty->display('sistema_de_produccion/recepcion/formulario_recepcion.html');
	}
}

?>