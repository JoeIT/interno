<?php
session_start();
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/ordenproductonuevo.php');
include_once('../../clases/sistema_de_produccion/propiedadesproductonuevo.php');
include_once('../../clases/sistema_de_produccion/propiedadesordenproductonuevo.php');
include_once('../../clases/sistema_de_produccion/estadoproductos.php');
include_once('../../clases/sistema_de_produccion/clip.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/sistema_de_produccion/modelos.php');
include_once('../../clases/sistema_de_produccion/clientes.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

$ordenes_producto_nuevo = new OrdenProductoNuevo;
$propiedadesproductonuevo = new PropiedadProductoNuevo;
$propiedadordenproductonuevo = new PropiedadOrdenProductoNuevo;
$estadoproducto = new EstadoProducto;
$clientes = new Cliente;
$clip = new Clip;
$estilo = new Estilo;
$modelo = new Modelo;
$url_relativa = "../../src/sistema_de_produccion/registrar_producto_nuevo.php";

if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if (isset($_GET["busqueda_ajax"])) {
		header("Content-Type: text/html; charset=iso-8859-1");
		$valor = $_POST["value"];
		$cadena=  utf8_decode(trim($valor));
		
		echo "<ul>";
		if ($_GET['busqueda_ajax'] == 'estilos') {
			$lista1 = $modelo->busqueda_estilos2($cadena, "1");
			$lista2 = $modelo->busqueda_estilos2($cadena, "2");
			$lista3 = $modelo->busqueda_estilos2($cadena, "3");
			$lista = array_merge((array)$lista1, (array) $lista2 ,(array)$lista3);
		} else {
			if ($_GET['busqueda_ajax'] == 'clips')
				$lista = $clip->busqueda_clips($cadena);
			else {
				if ($_GET['busqueda_ajax'] == 'clientes')
					$lista = $clientes->busqueda_clientes($cadena);
			}
		}
		
		if (count($lista) == 0) {
			echo "<li>No hay resultados</li>";
		} else {
			for ($contador = 0; $contador < count($lista); $contador++) {
				echo "<li>".$lista[$contador]."</li>";
			}
		}
		echo "</ul>";
	} else {
		if ($_POST['funcion'] == "validar") {
			$tipo_registro = $_POST['tipo_registro'];
			
			$mica = $_POST['pantalla']."-".$_POST['teclado']."-".$_POST['otros_mica'];
			$validar = new Validador();
			if ($validar->validarTodo($_POST['num_orden'], 1, 100)) {
				$error['err_num_orden'] = "Número de orden no válido";
			}
			if ($validar->validarTodo($_POST['modelo'], 1, 100)) {
				$error['err_modelo'] = "Ingrese modelo";
			}
			if ($validar->validarTodo($_POST['date1'], 1, 100)) {
				$error['err_date1'] = "Ingrese fecha de solicitud";
			} else	{
				if (!$validar->validarFecha($_POST['date1'])) {
					$error['err_date1'] = "Fecha no válida: ".$_POST['date1'];
				}
			}
			if ($validar->validarTodo($_POST['clientes'], 1, 100)) {
				$error['err_cliente'] = " Cliente no válido";
			}
			if ($validar->validarTodo($_POST['date2'], 1, 100)) {
				$error['err_date2'] = "Ingrese fecha de culminación";
			} else {
				if (!$validar->validarFecha($_POST['date2'])) {
					$error['err_date2'] = "Fecha de culminación no válida: ".$_POST['date2'];
				}
			}
			$cliente_id = $clientes->verificar_cliente(trim($_POST['clientes']));
			if ($cliente_id == -1) {
				$error['err_cliente'] = "Cliente no válido";
			}
			$estilo_id = $estilo->verificar_estilo(trim($_POST['texto_estilo']));
			if ($estilo_id == -1) {
				$error['err_estilo'] = "Estilo no válido";
			}
			$clip_id = $clip->verificar_clip(trim($_POST['texto_clip']));
			if ($clip_id == -1) {
				$error['err_clip'] = "Clip no válido";
			}
			if ($validar->validarTodo($_POST['cantidad'], 1, 100)) {
				$error['err_cantidad'] = "Ingrese cantidad";
			} else {
				if ($validar->validarNumeros($_POST['cantidad'], 1, 3)) {
					$error['err_cantidad'] = "Ingrese cantidad válida";
				}
			}
			if ($validar->validarTodo($_POST['tarjeteros'], 1, 100)) {
				$error['err_tarjeteros'] = "Ingrese cantidad de tarjeteros";
			} else {
				if ($validar->validarNumeros($_POST['tarjeteros'], 1, 3)) {
					$error['err_tarjeteros'] = "Ingrese cantidad válida";
				}
			}
			
			if (isset($error)) {
				$smarty->assign('tr', $tipo_registro);
				
				$seleccionados = $_POST['seleccionados'];
				$lista_propiedades = $propiedadesproductonuevo->obtener_lista_propiedades();
				$smarty->assign('num_orden', $_POST['num_orden']);
				$smarty->assign('lista_propiedades', $lista_propiedades);
				$smarty->assign('fecha_solicitud', $_POST['date1']);
				$smarty->assign('fecha_culminacion', $_POST['date2']);
				$smarty->assign('cliente', $_POST['clientes']);
				$smarty->assign('errores', $error);
				$smarty->assign('modelo', $_POST['modelo']);
				$smarty->assign('cantidad', $_POST['cantidad']);
				$smarty->assign('estilo', $_POST['texto_estilo']);
				$smarty->assign('clip', $_POST['texto_clip']);
				$smarty->assign('pantalla', $_POST['pantalla']);
				$smarty->assign('teclado', $_POST['teclado']);
				$smarty->assign('otros_mica', $_POST['otros_mica']);
				$smarty->assign('tarjeteros', $_POST['tarjeteros']);
				$smarty->assign('detalles', $_POST['detalles']);
				$smarty->assign('interior', $_POST['interior']);
				$smarty->assign('exterior', $_POST['exterior']);
				$smarty->assign('seleccionados', $seleccionados);
				$smarty->assign('material_varios', $_POST['material_varios']);
				$smarty->assign('observaciones', $_POST['observaciones']);
				$smarty->assign('titulo_pagina', "Registro de Orden de desarrollo de productos nuevos");
				$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/registrar_producto_nuevo.html');
			} else {
				//INGRESA UNA NUEVA ORDEN DE PRODUCTO NUEVO
				$orden_producto_id = $ordenes_producto_nuevo->ingresa_nueva_orden($cliente_id, $_POST['num_orden'], $_POST['date1'], $_POST['date2'], $_POST['modelo'], $_POST['tarjeteros'], "normas", $_POST['detalles'], $_POST['cantidad'], $mica, $_POST['exterior'], $_POST['interior'], $_POST['material_varios'], $estilo_id, $clip_id, $tipo_registro);
				$seleccionados = $_POST['seleccionados'];
				$lista_propiedades = $propiedadesproductonuevo->obtener_lista_propiedades();
				$propiedadordenproductonuevo->insertar_propiedades($orden_producto_id,$seleccionados);
				$usuario_id = $_SESSION["usuario_id"];
				$observaciones = $_POST['observaciones'];
				$estadoproducto->registrar_estado($orden_producto_id, $observaciones, $usuario_id);
				header("Location: ../../controladores/sistema_de_produccion/lista_ordenes_productos.php?opcion=modificar&estado=normas");
			}
		} else {
			$num_orden = $ordenes_producto_nuevo->consulta_numero_orden();
			$lista_propiedades = $propiedadesproductonuevo->obtener_lista_propiedades();
			$seleccionados = "";
			$smarty->assign('num_orden', $num_orden);
			$smarty->assign('lista_propiedades', $lista_propiedades);
			$smarty->assign('fecha_solicitud', date("d-m-Y"));
			$smarty->assign('fecha_culminacion', date("d-m-Y"));
			$smarty->assign('seleccionados', $seleccionados);
			$smarty->assign('lista_propiedades', $lista_propiedades);
			$smarty->assign('titulo_pagina', "Registro de Orden de desarrollo de productos nuevos");
			$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/registrar_producto_nuevo.html');
		}
	}
}
?>