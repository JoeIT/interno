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
		 $detalle=$activo->detalle_activo_completo($act_id);
		 $fecha= date("Y-m-d"); 
		 
		 
		 $numero=$detalle['numero'];
		
		 
		 $adqui=$adquisicion->listar_adqui($detalle['ad_id']);
		 $adquis=$adquisicion->lisadqui($detalle['ad_id']);
		
		 $foto=$activo->mostrar_foto($numero);
		
		 $respPri=$activo->buscar_responsablepri($act_id);
		
		 $nombre_asis2=$respPri['completopri'];
		 $resp_pri=$respPri['resp_id'];
		  
	     $resp=$activo->buscar_asignado($act_id);
		/*echo "<pre>";
		print_r($foto);
		echo "</pre>";*/
		 $resp_pp=$resp['resp_id'];
		 $nombre_asis=$resp['completo'];
		
		
		$smarty->assign('detalle',$detalle);
		$smarty->assign('numerito',$numero);
		$smarty->assign('fecha', $fecha);
	
		$smarty->assign('adquis',$adquis);
		
		$smarty->assign('si','si');
		$smarty->assign('foto', $foto);
		$smarty->assign('nombre_asis2', $nombre_asis2);
		$smarty->assign('nombre_asis', $nombre_asis);
		$smarty->display('baja_activo.html');
		

	}
	if(isset($_GET['insertar_baja']))
	{ 
		if($_GET['funcion']== "validar")
			{    $error="";
				 $validar = new Validador();
				
				
			 	 
			 	 if ($validar->validarTodo($_GET['observaciones'], 1, 100))
			 		$error['observacion'] = "Introduzca observacion ";
			 		
			 	 
			 	
				if($error!="")
				{   
					
					
					 $detalle=$activo->detalle_activo_completo($act_id);
         	 		 $numero=$detalle['numero'];
		
		 			 $adqui=$adquisicion->listar_adqui($detalle['ad_id']);
		             $adquis=$adquisicion->lisadqui($detalle['ad_id']);
		
		             $foto=$activo->mostrar_foto($numero);
		
		             $respPri=$activo->buscar_responsablepri($act_id);
		
					 $nombre_asis2=$respPri['completopri'];
					 $resp_pri=$respPri['resp_id'];
					  
				     $resp=$activo->buscar_asignado($act_id);
					 $resp_pp=$resp['resp_id'];
					 $nombre_asis=$resp['completo'];
							
					$smarty->assign('detalle',$detalle);
					$smarty->assign('numerito',$numero);
		    		$smarty->assign('adquis',$adquis);
		    		$smarty->assign('fecha', $fecha);
					$smarty->assign('si','si');
					$smarty->assign('foto', $foto);
					$smarty->assign('nombre_asis2', $nombre_asis2);
					$smarty->assign('nombre_asis', $nombre_asis);
					$smarty->assign('errores',$error);
				}
				else 
					{ 
						 $obser=$_GET['observaciones'];
						 $act_id=$_GET['act_id'];
						 $fecha=$_GET['fecha'];	  			
						 $baja=$activo->dar_baja($act_id,$obser,0,$fecha);
						 
						/* echo "<pre>";
						 echo "prueba".print_r($baja);
						 echo "</pre>";
						 
						 */
		 				     $error['err_confirm'] = "El registro se realizo correctamente";
							 $primaria=$primaria->listar_locprimaria();
							 $secundaria=$secundaria->listar_locsecundaria();
							 $grupo=$grupo->listar_grupo();
					
					
							$smarty->assign('primaria', $primaria);					
							$smarty->assign('secundaria', $secundaria);
							$smarty->assign('grupo', $grupo);
							
							
						}
						
						
							 $smarty->assign('errores',$error);
						     $smarty->display('darbaja.html');
					
				
		
			}
	
	}
	
	
	
	
}
	
?>