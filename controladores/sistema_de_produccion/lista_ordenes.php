<?php
session_start();
include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/sistema_de_produccion/clientes.php');
include_once('../../clases/sistema_de_produccion/usuarios.php');
require_once('../includes/seguridad.php');
include_once('../../clases/includes/validador.php');
include_once('../../clases/sistema_de_produccion/detalle_orden.php');
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');

//incluimos el archivo para generar EXCEL
include("excelwriter.inc.php");

$ordenes=new OrdenProd;
$detalle_orden=new Detalle_orden;
$cliente=new Cliente;
$usuario=new Usuarios;
$validar = new Validador;
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';


//******************************************************
function escribir_orden_excel($nombre_archivo, $datos){
	$excel=new ExcelWriter("../../reportes/ordenes/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Cantidad","Modelo","Tipo","Origen Cuero","Color","Clip","Sello","Pedido","Observaciones","Grabado", "Lugar grabado");
	$excel->writeCabecera($myArr);
	
	foreach($datos as $key => $value){
		$cantidad = $datos[$key]['cantidad'];
		$modelo = $datos[$key]['modelo'];
		$tipo = $datos[$key]['tipo'];
		$origencuero = $datos[$key]['origencuero'];
		$color = $datos[$key]['color'];
		$clip = $datos[$key]['clip'];
		$sello = $datos[$key]['sello'];
		$pedido = $datos[$key]['pedido'];
		$observaciones = $datos[$key]['observaciones'];
		$grabado = $datos[$key]['grabado'];
		$lugargrabado = $datos[$key]['lugargrabado'];
		
		$myArr=array($cantidad,$modelo,$tipo,$origencuero,$color,$clip,$sello,$pedido,$observaciones,$grabado,$lugargrabado);
		$excel->writeLine($myArr);
	}

	
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	$funcion = $_GET['funcion'];
	$url_relativa = "../../src/sistema_de_produccion/lista_ordenes.php";
	
	//verificamos si se desea modificar la información de una orden
	
	 if($funcion=="detalle")
	 {
		$id=$_GET['elegido'];
		//
		//sacamos la cabecera de la orden
		$cabecera=$ordenes->obtener_cabecera_orden($id);
		//generamos el archivo EXCEL
		$archivo_maquinistas = $cabecera['cup_num'];
		$archivo_orden = $detalle_orden->generar_excel_orden($id);
		if ($archivo_orden != null){
			escribir_orden_excel($archivo_maquinistas, $archivo_orden);
			$smarty->assign('excel', $archivo_maquinistas);
		}
		//
	
		$detalle=$detalle_orden->obtener_detalle_orden($id);
	
		$smarty->assign('orden_id',$id);
		$smarty->assign('cabecera',$cabecera);
		$smarty->assign('detalle',$detalle);	
		$smarty->assign('nombres', $_SESSION["nombres"]);
		$smarty->assign('apellidos', $_SESSION["apellidos"]);
		$smarty->display('sistema_de_produccion/orden_de_produccion/confirmar_orden.html');
						
	  }
	  else
	  {
		 if($funcion=="buscar")
		 {
			//buscamos el detalle
			$cadena = trim($_POST['orden']);
			$opcion = trim($_POST['tipo']);
			$_SESSION['tabu'] = $_POST['tabu'];//
			if ($validar->validarTodo($cadena, 1, 100)){
				if ($opcion == "num_orden"){
						$error['err_nombre'] = "Ingresar C&oacute;digo de &Oacute;rden";
				} else {
						$error['err_nombre'] = "Ingresar Nombre de Cliente";
				}
			}
			$smarty->assign('orden',$cadena);
			if (isset($error)){
					//reenviar los errores
					$lista= $ordenes->consulta_lista_ordenes_anual(date("Y"));
					$smarty->assign('ordenes',$lista);
					$smarty->assign('nombres', $_SESSION["nombres"]);
					$smarty->assign('apellidos', $_SESSION["apellidos"]);
					$smarty->assign('errores',$error);
					$smarty->assign('tabu',$_SESSION['tabu']);
					$smarty->display('sistema_de_produccion/orden_de_produccion/lista_ordenes.html');
			} else {
					$consulta = $ordenes->consultar_busqueda($cadena,$opcion);
					if ($consulta != null){
						$smarty->assign('ordenes',$consulta);
						$smarty->assign('tabu',$_SESSION['tabu']);
						$smarty->assign('nombres', $_SESSION["nombres"]);
						$smarty->assign('apellidos', $_SESSION["apellidos"]);
						$smarty->display('sistema_de_produccion/orden_de_produccion/lista_ordenes.html');
					} else {
						if ($opcion == "num_orden"){
								$smarty->assign('mensaje',"No se encontr&oacute; la &Oacute;rden de Producci&oacute;n");
						} else {
								$smarty->assign('mensaje',"No se encontr&oacute; el Nombre de Cliente");
						}
						$smarty->assign('orden',$cadena);
						//$lista = $ordenes->consulta_lista_ordenes_anual(date("Y"));
						$smarty->assign('ordenes',$lista);
							
						$smarty->assign('tabu',$_SESSION['tabu']);
						$smarty->assign('nombres', $_SESSION["nombres"]);
						$smarty->assign('apellidos', $_SESSION["apellidos"]);
						$smarty->display('sistema_de_produccion/orden_de_produccion/lista_ordenes.html');
							//$smarty->display('sistema_de_produccion/asignacion/detalle.html');
					}
						//$smarty->assign('tabu',$_SESSION['tabu']);
						//$smarty->display('sistema_de_produccion/asignacion/formulario.html');
						//$smarty->display('sistema_de_produccion/asignacion/detalle.html');
				}				
				//fin de la busqueda
			}
		else
	   {
		   if($funcion=='cambio_tab')
		   {
			  if ($_GET['tabu']){
			   $_SESSION['tabu'] = $_GET['tabu'];//
				$smarty->assign('tabu',$_GET['tabu']);
				$smarty->assign('nombres', $_SESSION["nombres"]);
				$smarty->assign('apellidos', $_SESSION["apellidos"]);
				$lista= $ordenes->consulta_lista_ordenes_anual(date("Y"));
				$smarty->assign('ordenes',$lista);
				$smarty->display('sistema_de_produccion/orden_de_produccion/lista_ordenes.html');
			}
		   
		   }
		   else
		   {
			$lista= $ordenes->consulta_lista_ordenes_anual(date("Y"));
			$smarty->assign('ordenes',$lista);
			$smarty->assign('tabu','1');
			$smarty->assign('nombres', $_SESSION["nombres"]);
			$smarty->assign('apellidos', $_SESSION["apellidos"]);
			$smarty->display('sistema_de_produccion/orden_de_produccion/lista_ordenes.html');
			}
		}
	 }
}
?>