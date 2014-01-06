<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

include_once('../../clases/sistema_de_produccion/familias.php');
include_once('../../clases/sistema_de_produccion/clip.php');
include_once('../../clases/sistema_de_produccion/cuero.php');
include_once('../../clases/sistema_de_produccion/clientes.php');
include_once('../../clases/sistema_de_produccion/producto_status.php');
include_once('../../clases/validador.php');
require("../includes/fecha.php");

$validar = new Validador;
$familias = new Familia;
$clips = new Clip;
$cueros = new Cuero;
$clientes = new Cliente;
$ProductoStatus = new ProductoStatus;

//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);
//

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	//aqui hacer las acciones
	switch ($_GET['opcion']){
		case 1 :{
			$smarty->display('sistema_de_produccion/producto_status/formulario.html');
			break;
		}
	}
	
	if (!empty($_POST)){
		if ($_POST['buscar']){
			//buscamos el detalle
			$modelo = trim($_POST['modelo']);
			$clip = trim($_POST['clip']);
			$cuero = trim($_POST['cuero']);
			$cliente = trim($_POST['cliente']);
			
			if ($validar->validarTodo($modelo, 1, 100)){
				$error['err_modelo'] = "Debe ingresar la descripción del modelo";
			} else {
				$familia_id = $familias->verifica_familia_valida($modelo);
				if($familia_id==-1){
					$error['err_modelo'] = "Descripción del producto no válida";
				}
			}
			if($validar->validarTodo($clip, 1, 100)){
				$error['err_clip'] = "Debe ingresar un clip";
			} else {
				$clip_id = $clips->verificar_clip($clip);
				if($clip_id==-1){
					$error['err_clip'] = "Clip no valido";
				}
			}
			if($cuero != ''){
				if($validar->validarTodo($cuero, 1, 100)){
					$error['err_cuero'] = "Debe ingresar un tipo de cuero";
				} else {
					$cuero_id = $cueros->verificar_cuero($cuero);
					if($cuero_id == -1){
						$error['err_cuero'] = "Cuero no valido";
					}
				}
			} else {
				$cuero_id = -1;
			}
			
			if($validar->validarTodo($cliente, 1, 100)){
				$error['err_cliente'] = "Debe ingresar un cliente";
			} else {
				$cliente_id = $clientes->verificar_cliente($cliente);
				if($cliente_id == -1){
					$error['err_cliente'] = "Cliente no valido";
				}
			}
	

			if (isset($error)){
				//reenviar los errores
				$smarty->assign('errores', $error);
			} else {
				$resultado = $ProductoStatus->estado_productos($familia_id, $cuero_id, $clip_id, $cliente_id);
				if ($resultado != null){
					//si encontro algun registro
					$smarty->assign('resultado', $resultado);
				} else {
					$smarty->assign('mensaje', "No se encontr&oacute; producto con esas caracter&iacute;sticas");
				}
			}
			
			$smarty->assign('modelo', $modelo);
			$smarty->assign('clip', $clip);
			$smarty->assign('cuero', $cuero);
			$smarty->assign('cliente', $cliente);
			
			$smarty->display('sistema_de_produccion/producto_status/formulario.html');
		}//if ($_POST['funcion'])
	}
}
?>