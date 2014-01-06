<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../includes/fecha.php');
include_once('../../clases/validador.php');
include_once('../../clases/sistema_de_produccion/despacho.php');
include_once('../../clases/sistema_de_produccion/asignacion.php');
include_once('../../clases/sistema_de_produccion/recepcion_calidad.php');
include_once('../../clases/sistema_de_produccion/calidad_control.php');
include_once('../../clases/sistema_de_produccion/cerrado.php');
include_once('../../clases/sistema_de_produccion/rechazos.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';



//Variables para cabecera
$smarty->assign('fecha', $fecha);
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

//
$validar = new Validador();
$despacho = new Despacho;
$asignacion = new Asignacion;
$recepcion_calidad = new Recepcion_calidad;
$calidad = new Calidad_control;
$rechazos = new Rechazo;


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {

	$funcion=$_GET['funcion'];
	//echo $funcion;
	if($funcion=="buscar")
	{
		 $nombre_despacho = trim($_POST["nombre_despacho"]);
		 $fecha_despacho = trim($_POST["fecha_despacho"]);
		 //validacion
		  if ($validar->validarTodo($nombre_despacho, 1, 100)){
				$error['nombre_despacho'] = "Ingrese nombre de despacho";
		  }
		  if ($validar->validarTodo($fecha_despacho, 1, 100)){
				$error['fecha_despacho'] = "Ingrese fecha de despacho";
		  }
		  if (isset($error)){
		//reenviar los errores
				$smarty->assign('errores', $error);
		} else {
				//echo "<br>Buscar despacho";
				$lista_despachos = $despacho->buscar_despacho_sueldos($nombre_despacho,$fecha_despacho);
						
						if ($lista_despachos != null){
							$smarty->assign('lista_despachos', $lista_despachos);
						} else {
							$smarty->assign('mensaje', "No se encontr&oacute; resultados");
						}
					}
					//desplegamos el listado de la busqueda
					$smarty->assign('nombre_despacho', $nombre_despacho);
					$smarty->assign('fecha_despacho', $fecha_despacho);
					$smarty->display('sistema_de_produccion/despachos/despacho_busqueda_sueldos.html');
	}
	else
	{
	   if($funcion=="ver_detalle")
		{
		  $deid=$_GET['deid'];
		  $lista_despachos=$despacho->mostrar_elementos($deid);
		  $estado_despacho=$despacho->estado_despacho($deid);
		  $descripcion_despacho = $despacho->descripcion_despacho($deid);
		  $smarty->assign('descripcion_despacho', $descripcion_despacho);
		 // $contador=0;
		  for($contador=0;$contador<sizeof($lista_despachos);$contador++) 
		  {
			$num_asignacion=$lista_despachos[$contador]['asignacion_detalle_id'];
			$resumen_total[$num_asignacion]['para_despacho']= $recepcion_calidad->total_para_despacho($num_asignacion, $deid);
			$resumen_total[$num_asignacion]['recepcionado']= $recepcion_calidad->total_recepcionado($num_asignacion);
			$resumen_total[$num_asignacion]['limpieza']=$calidad->cantidad_limpieza_falta($num_asignacion);
			$resumen_total[$num_asignacion]['calidad_falta']=$calidad->cantidad_calidad_falta($num_asignacion);
			$resumen_total[$num_asignacion]['rechazo']=$rechazos->total_rechazado($num_asignacion);
			$resumen_total[$num_asignacion]['total_arreglado']=$rechazos->total_arreglado($num_asignacion);
			$resumen_total[$num_asignacion]['detalle_asignacion']=$calidad->buscar_asignacion($num_asignacion);
		  }
		  
		/*  echo "<pre>";
			 print_r($resumen_total);
		  echo "</pre>";*/
		  
		  $smarty->assign('resumen_total', $resumen_total);
		  $smarty->assign('estado_despacho', $estado_despacho);
		  $smarty->assign('deid', $deid);
		  $smarty->display('sistema_de_produccion/despachos/despacho_busqueda_sueldos_detalle.html');
		  
		}
	  else
	  {	
		if($funcion=="habilitar")
		{
		
			$deid=$_POST['deid'];
			$opcion=$_POST['opcion'];
			$descripcion_despacho = $despacho->descripcion_despacho($deid);
			$smarty->assign('descripcion_despacho', $descripcion_despacho);
			
			if($opcion=='habilitar')
			{
				 $despacho->habilitar_sueldos($deid);
				 $smarty->assign('mensaje', 'el despacho '.$deid.' fué habilitado');
			}
			if($opcion=='deshabilitar')
			{
				 $despacho->deshabilitar_sueldos($deid);
				  $smarty->assign('mensaje', 'el despacho '.$deid.' fué deshabilitado');	 
			}
			$estado_despacho=$despacho->estado_despacho($deid);
			 $lista_despachos=$despacho->mostrar_elementos($deid);
					 // $contador=0;
				  for($contador=0;$contador<sizeof($lista_despachos);$contador++) 
				  {
					$num_asignacion=$lista_despachos[$contador]['asignacion_detalle_id'];
					$resumen_total[$num_asignacion]['recepcionado']= $recepcion_calidad->total_recepcionado($num_asignacion);
					$resumen_total[$num_asignacion]['limpieza']=$calidad->cantidad_limpieza_falta($num_asignacion);
					$resumen_total[$num_asignacion]['calidad_falta']=$calidad->cantidad_calidad_falta($num_asignacion);
					$resumen_total[$num_asignacion]['rechazo']=$rechazos->total_rechazado($num_asignacion);
					$resumen_total[$num_asignacion]['total_arreglado']=$rechazos->total_arreglado($num_asignacion);
					$resumen_total[$num_asignacion]['detalle_asignacion']=$calidad->buscar_asignacion($num_asignacion);
			
				  }
		  
					/*  echo "<pre>";
						 print_r($resumen_total);
					  echo "</pre>";*/
				  $smarty->assign('resumen_total', $resumen_total);
				  $smarty->assign('deid', $deid);
				  $smarty->assign('estado_despacho', $estado_despacho);
				  $smarty->display('sistema_de_produccion/despachos/despacho_busqueda_sueldos_detalle.html');
		
		}
		else
		{
			//buscar despachos existentes
			unset($_SESSION['num_orden']);
			unset($_SESSION['deid']);
			unset($_SESSION['orden_id']);
					
			$ultimos_despachos = $despacho->ultimos_despachos();
			$smarty->assign('lista_despachos', $ultimos_despachos);
			$smarty->display('sistema_de_produccion/despachos/despacho_busqueda_sueldos.html');
		}
	  }
	}

}
?>