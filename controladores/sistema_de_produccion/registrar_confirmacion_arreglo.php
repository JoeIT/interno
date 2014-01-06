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
$funcion = $_POST['funcion'];
$num_asignacion = trim($_GET['num_asignacion']);

if($funcion=="confirmar_arreglo")
{
        $seleccionados=$_POST['seleccionados'];
		$num_asignacion = trim($_POST['num_asignacion']);
		$fecha_finalizacion = date("d-m-Y");
   	    $fecha_finalizacion=$validar->cambiaf_a_mysql($fecha_finalizacion);
		$hora= date("H:i:s");
		$fecha_finalizacion=$fecha_finalizacion." ".$hora;
		$resultado_asignacion = $rechazo->buscar_asignacion($num_asignacion);
		for($contador=0; $contador<count($seleccionados); $contador++)
		{
		     $resultado_registro = $rechazo->registrar_confirmacion_arreglo($seleccionados[$contador],$fecha_finalizacion);
		}
		
		$confirmacion="El registro se realizo con éxito";
		$smarty->assign('confirmacion', $confirmacion);
		$resumen_arreglos= $rechazo->resumen_arreglos($num_asignacion);
		$smarty->assign('detalle_asignacion', $resultado_asignacion);
		$smarty->assign('resumen_arreglos', $resumen_arreglos);			
}
else
{
    		$resultado_asignacion = $rechazo->buscar_asignacion($num_asignacion);
			$resumen_arreglos = $rechazo->resumen_arreglos($num_asignacion);
			$smarty->assign('detalle_asignacion', $resultado_asignacion);
			$smarty->assign('resumen_arreglos', $resumen_arreglos); 
}
$smarty->assign('tabu', 4);
$smarty->display('sistema_de_produccion/control_calidad/buscar_asignacion.html');
?>