<?php 
session_start();
define('CLAS', "../clases");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../clases/activo.php');
include_once('../clases/locprimaria.php');
include_once('../clases/locsecundaria.php');
include_once('../clases/grupo.php');
include_once('../clases/adquisicion.php');
include_once('../clases/asignacion.php');

include_once('../clases/includes/validador.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';

$activo= new Activo();
$primaria=new LocPrimaria();
$secundaria=new LocSecundaria();
$grupo=new Grupo();
$adquisicion=new Adquisicion();
$asignacion= new Asignacion();

if (!isset($_SESSION['logeo'])) {
	header("Location: ../index_logeo.php");
} else {
	
	$funcion = $_GET['funcion'];
	 $act_id=$_GET['act_id'];
	if ($funcion == "detalle") 
	{ 
		
		 $act_id = $_GET['elegido'];
		 $transferencia=$activo->reporte_transferencia($act_id);
		 $descripcion=$transferencia[0]['descripcion'];
		 $num_act=$transferencia[0]['num_act'];
	     $serie=$transferencia[0]['serie'];
		 $unidad=$transferencia[0]['unidad'];
		 $desgru=$transferencia[0]['desgru'];
		 $gru=$transferencia[0]['grupo'];
		 
		 /*
		 echo "<pre>";
		 print_r($transferencia);
		 echo "</pre>";
		
	*/
		
		$t=count($transferencia);
		    
			
			$maximot=20;
			  if($t <= $maximot )
			   	     $num_paginas=1;
				  else
				  {
					 if($t > $maximot )
			     		{
					       $num=$t/$maximot;
				       	   $num_paginas=ceil($num);
							   
						}
					 
					 }
			   		
			$smarty->assign('maximot', $maximot);
			$smarty->assign('t', $t);
			$smarty->assign('num_paginas',$num_paginas);
		    $smarty->assign('transferencia', $transferencia);
			$smarty->assign('descripcion', $descripcion);
			$smarty->assign('num_act', $num_act);
	    	$smarty->assign('serie', $serie);
	    	$smarty->assign('unidad', $unidad);
 		    $smarty->assign('desgru', $desgru);		    
            $smarty->assign('gru', $gru);	
		
		
		
		
		$smarty->display('reporte_transferencia.html');
		

	}
	
}
	
?>