<?php
session_start();
define('SMARTY_DIR', "../../smarty/");
include_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/sistema_de_produccion/clientes.php');
include_once('../../clases/includes/validador.php');
include_once('../../clases/sistema_de_produccion/modelos.php');

$ordenes = new OrdenProd;
$detalle_orden = new Detalle_orden;
$clientes = new Cliente;
$modelo = new Modelo;

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$url_relativa = "../../src/sistema_de_produccion/registrar_orden.php";

if (!isset($_SESSION['logeo'])) {
	header("Location: ../index_logeo.php");
} else {
	if (isset($_GET["busqueda_ajax"])) {
		$valor = $_POST["value"];
		$cadena =  utf8_decode(trim($valor));
		echo "<ul>";
		$lista = $clientes->busqueda_clientes($cadena);
		if (count($lista) == 0) {
			echo "<li>No hay resultados</li>";
		} else {
			for ($contador = 0; $contador < count($lista); $contador ++) {
				echo "<li>".$lista[$contador]."</li>";
			}
		}
		echo "</ul>";
	 } else {
	 	if ($_GET['funcion'] == "validar") {
			$validar = new Validador();
			if ($validar->validarTodo($_POST['num_orden'], 1, 100)) {
				$error['err_num_orden'] = "	Número de orden invalido";}
			
			if ($validar->validarTodo($_POST['num_cup'], 1, 100)) {
				$error['err_num_cup'] = " Cup. Num invalido";}
			
			if ($validar->validarTodo($_POST['date1'], 1, 100)) {
				$error['err_date1'] = " Debe ingresar una fecha";
			} else {
				if (!$validar->validarFecha($_POST['date1'])) {
					$error['err_date1'] = " Fecha no valida: ".$_POST['date1'];
				}
			}
			
			if ($validar->validarTodo($_POST['clientes'], 1, 100)) {
				$error['err_cliente'] = " Cliente no valido";
			}
			
			if ($validar->validarTodo($_POST['date2'], 1, 100)) {
				$error['err_date2'] = " Debe ingresar una fecha de entrega/despacho";
			} else {
				if (!$validar->validarFecha($_POST['date2'])) {
					$error['err_date2'] = " Fecha de entrega/despacho no valida: ".$_POST['date2'];
				}
			}
			
			$cliente_id = $clientes->verificar_cliente(trim($_POST['clientes']));
			
			if ($cliente_id == -1) {
				$error['err_cliente'] = " Cliente no valido";
			}
			if (isset($error)) {
				$smarty->assign('num_orden', $_POST['num_orden']);
				$smarty->assign('num_cup',$_POST['num_cup']);
				$smarty->assign('fecha',$_POST['date1']);
				$smarty->assign('fechaentrega',$_POST['date2']);
				$smarty->assign('cliente',$_POST['clientes']);
				$smarty->assign('errores',$error);
				$smarty->assign('observaciones',$_POST['observaciones']);
				$smarty->assign('nombres', $_SESSION["nombres"]);
				$smarty->assign('apellidos', $_SESSION["apellidos"]);
				$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_orden.html');
			} else {
				//
				$lista_indicadores_tipo = $modelo->listar_indicadores_tipo();
				$smarty->assign('lista_indicadores_tipo', $lista_indicadores_tipo);
				//
				
				$usuario_id = $_SESSION["usuario_id"];

                if($_POST['local']!=1)
				$orden_id = $ordenes->ingresa_nueva_orden($_POST['num_orden'],$_POST['num_cup'],$_POST['date1'],$_POST['date2'],$cliente_id,$_POST['observaciones'], $usuario_id);
                else
                $orden_id = $ordenes->ingresa_nueva_orden_local($_POST['num_orden'],$_POST['num_cup'],$_POST['date1'],$_POST['date2'],$cliente_id,$_POST['observaciones'], $usuario_id);
				
				//$detalle=$detalle_orden->obtener_detalle_orden($orden_id);
				$cabecera=$ordenes->obtener_cabecera_orden($orden_id);
				$smarty->assign('orden_id',$orden_id);
				$smarty->assign('cabecera',$cabecera);
				$smarty->assign('pagina_envio', "registrar");
				$smarty->assign('nombres', $_SESSION["nombres"]);
				$smarty->assign('apellidos', $_SESSION["apellidos"]);
				$smarty->display('sistema_de_produccion/orden_de_produccion/modificar_detalle_orden.html');												
			}
		} else {
            if ($_GET['funcion'] == 'radio') {
                switch ($_POST['radio']) {
				case 'exporta':{
					$num_orden= $ordenes->consulta_numero_orden();
                    $num_cup=$ordenes->obtener_num_cup($num_orden);
                    $smarty->assign('num_orden',$num_orden);
                    $smarty->assign('num_cup',$num_cup);
                    $smarty->assign('fecha',date("d-m-Y"));
                    $smarty->assign('fechaentrega',date("d-m-Y"));
                    $smarty->assign('nombres', $_SESSION["nombres"]);
                    $smarty->assign('apellidos', $_SESSION["apellidos"]);
                    $smarty->display('sistema_de_produccion/orden_de_produccion/registrar_orden.html');
					break;}
				case 'local':{
                    $num_orden= $ordenes->consulta_numero_orden_local();
                    $num_cup=$ordenes->obtener_num_cup_local($num_orden);
                    $smarty->assign('num_orden',$num_orden);
                    $smarty->assign('num_cup',$num_cup);
                    $smarty->assign('fecha',date("d-m-Y"));
                    $smarty->assign('fechaentrega',date("d-m-Y"));
                    $smarty->assign('nombres', $_SESSION["nombres"]);
                    $smarty->assign('apellidos', $_SESSION["apellidos"]);
                    $local=1;
                    $smarty->assign('local',$local);
                    $smarty->display('sistema_de_produccion/orden_de_produccion/registrar_orden.html');
                    break;}
                    }
                }
            else
                $smarty->display('sistema_de_produccion/orden_de_produccion/rg15.html');
		}
	}
}
?>