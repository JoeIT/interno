<?php

session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/rechazos.php');
include_once('../../clases/sistema_de_produccion/recepcion_calidad.php');
include_once('../../clases/includes/validador.php');
require('../../controladores/includes/fecha.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$rechazo=new Rechazo;
$recepcion=new Recepcion_Calidad;
$validar = new Validador();
$funcion = $_GET['funcion'];
$num_asignacion = trim($_GET['num_asignacion']);

if($funcion=="registrar_rechazo")
{
		$cantidad=$_GET['cantidad'];
		
		if ($validar->validarTodo($cantidad, 1, 100)){
					$error['cantidad'] = "Ingresar una cantidad";} 
		else 
		{
				//validamos que sea solo numero
			if (!ereg("^[0-9]{1,}$", $cantidad)){
					$error['cantidad'] = "Ingresar solo n&uacute;meros";}
			else
			{
	    	 
				     $total_rechazo = $rechazo->total_rechazado($num_asignacion);
					 $total_recepcionado = $recepcion->total_recepcionado($num_asignacion);
					 $faltante=$total_recepcionado-$total_rechazo;
					 
					 if($faltante < $cantidad){
						$error['cantidad'] = "No se pueden rechazar tantos productos";}
			}	
		}
		//si existen errores
		if (isset($error))
		{
			$smarty->assign('errores', $error);
			$resultado_asignacion = $rechazo->buscar_asignacion($num_asignacion);
			$resumen_rechazos = $rechazo->resumen_rechazo($num_asignacion);
			$total_rechazado = $rechazo->total_rechazado($num_asignacion);
			$total_recepcionado = $recepcion->total_recepcionado($num_asignacion);
			$smarty->assign('detalle_asignacion', $resultado_asignacion);
			$smarty->assign('resumen_rechazos', $resumen_rechazos);
			$smarty->assign('total_rechazado', $total_rechazado);
			$smarty->assign('total_recepcionado', $total_recepcionado);
			$smarty->assign('fecha', $fecha);
	
		
				
		} 
		else 
		{
      
			 $fecha = date("d-m-Y");
			 $hora= date("H:i:s");
			 $area=$_GET['fallos'];
  	    	 $fecha=$validar->cambiaf_a_mysql($fecha);
			 $fecha=$fecha." ".$hora;
			 $resultado_asignacion = $rechazo->buscar_asignacion($num_asignacion);
			 if($area=="Maquinista")
			      $responsable_fallo=$resultado_asignacion['personal_id'];
			 else
			      $responsable_fallo=0;
	 		$resultado_registro = $rechazo->registrar_rechazo($num_asignacion,$cantidad,$area,$_SESSION['usuario_id'],$fecha,$responsable_fallo,$resultado_asignacion['personal_id']);

			$confirmacion="El registro se realizó con éxito";
			$smarty->assign('confirmacion', $confirmacion);
			$resumen_rechazos= $rechazo->resumen_rechazo($num_asignacion);
			$total_rechazado = $rechazo->total_rechazado($num_asignacion);
			$total_recepcionado = $recepcion->total_recepcionado($num_asignacion);
			if($total_rechazado==$resultado_asignacion['cantidad'])
			  $smarty->assign('mensaje_rechazo', "No se pueden rechazar más productos");
			$resultado_asignacion = $rechazo->buscar_asignacion($num_asignacion);
			$smarty->assign('detalle_asignacion', $resultado_asignacion);
			$smarty->assign('resumen_rechazos', $resumen_rechazos);
			$smarty->assign('total_rechazado', $total_rechazado);
			$smarty->assign('total_recepcionado', $total_recepcionado);
			$smarty->assign('fecha', $fecha);
			
		
		}			
}
else
{
    		$resultado_asignacion = $rechazo->buscar_asignacion($num_asignacion);
			$resumen_rechazos = $rechazo->resumen_rechazo($num_asignacion);
			$total_rechazado = $rechazo->total_rechazado($num_asignacion);
			$total_recepcionado = $recepcion->total_recepcionado($num_asignacion);
			$smarty->assign('detalle_asignacion', $resultado_asignacion);
			$smarty->assign('resumen_rechazos', $resumen_rechazos);
			$smarty->assign('total_rechazado', $total_rechazado);
			$smarty->assign('total_recepcionado', $total_recepcionado);
			$smarty->assign('fecha', $fecha);
	
			
 
}
$smarty->assign('tabu', 3);
$smarty->display('sistema_de_produccion/control_calidad/buscar_asignacion.html');
?>