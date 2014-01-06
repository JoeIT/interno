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
include_once('../clases/tipocambio.php');

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
$tipo_ca=new TipoCambio();
if (!isset($_SESSION['logeo'])) {
	header("Location: ../index_logeo.php");
} else {
	
	$funcion = $_GET['funcion'];
	 $act_id=$_GET['act_id'];
	if ($funcion == "detalle") 
	{ 
		
		 $act_id = $_GET['elegido'];
		 $detalle=$activo->detalle_activo_completo($act_id);
		 
		 
		 $sec_id=$detalle['secundaria_id'];
	     $loc=$detalle['primaria_id'];
		 $gru=$detalle['grupo_id'];
		 $tipo_cambio=round ($detalle['tipo_cambio'],2);	
		 $numero=$detalle['numero'];
		 $det_adqui=$detalle['det_adqui'];
		
		 $primaria=$primaria->listar_primarias($loc);		
		 $secundaria=$secundaria->listar_secundarias($sec_id,$loc);
		 $grupo=$grupo->listar_gru($gru);
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
		$smarty->assign('primaria',$primaria);
		$smarty->assign('secundaria',$secundaria);
		$smarty->assign('grupo',$grupo);
		$smarty->assign('adqui',$adqui);
		$smarty->assign('adquis',$adquis);
		$smarty->assign('tipo_cambio',$tipo_cambio);
		$smarty->assign('si','si');
		$smarty->assign('foto', $foto);
		$smarty->assign('nombre_asis2', $nombre_asis2);
		$smarty->assign('nombre_asis', $nombre_asis);
		$smarty->assign('resp_id2', $resp_pri);
		$smarty->assign('resp_id', $resp_pp);
		$smarty->assign('resp_1', $resp_pri);
		$smarty->assign('resp_2', $resp_pp);
		$smarty->assign('det_adqui', $det_adqui);
		$smarty->display('modificar_activo_completo.html');
		

	}
	if(isset($_GET['modificar_activo_completo']))
	{ 
		if($_GET['funcion']== "validar")
			{    $error="";
				 $validar = new Validador();
				
				 /*if ($_GET['pri']=="selc"|| $_GET['pri']=='')
			 		$error['err_pri'] = "Selecione localizacion primaria";
				 if ($_GET['secun']=='selc' || $_GET['secun']=='')
			 		$error['err_sec'] = "Selecione localizacion secundaria";	
			 	 if ($_GET['gru']=='selc' || $_GET['gru']=='')
			 		$error['err_gru'] = "Selecione cuenta";
			 		*/
			 	 if ($validar->validarTodo($_GET['nombre_asis2'], 1, 100))
			 		$error['err_nombre'] = "Introduzca el nombre del Responsable";
			 	 if ($validar->validarTodo($_GET['nombre_asis'], 1, 100))
			 		$error['err_nombre1'] = "Introduzca el nombre del Asignado";
			 	 
			 	 if ($validar->validarTodo($_GET['vida_util'], 1, 100))
			 		$error['err_vida'] = "Introduzca Vida util";
			 	 if ($validar->validarNumeros($_GET['vida_util'], 1, 100))
			 		$error['err_vida'] = "Introduzca numeros en vida util";
			 		
			 	 if ($_GET['adqui']=='selc' || $_GET['adqui']=='' )
			 		$error['err_adqui'] = "Selecione tipo de adquisicion";
			 	 
			 	 if ($validar->validarTodo($_GET['descripcion'], 1, 100))
			 		$error['descripcion'] = "Introduzca descripcion";
			 		
			 	 
			 	 if ($validar->validarTodo($_GET['valor'], 1, 100))
		 	 		$error['err_valor'] = "Introduzca el valor Compra";
		 	 		
		 	 	 if ($validar->validarNumerosDecimales($_GET['valor'], 1, 100))
			 		$error['err_valor'] = "Introduzca Valor Compra";
			 		
			 	 if ( $validar->validarTodo($_GET['residual'], 1, 100))
			 		$error['err_residual'] = "Introduzca el valor residual";
			 		
			 		if ($validar->validarNumerosDecimales($_GET['residual'], 1, 100))
			 		$error['err_residual'] = "Introduzca Valor Residual";
			 		
		 		if ($_GET['fecha']>="2007-01-01")	
			 	{	$res1=$tipo_ca->buscar_fecha($_GET['fecha'],round($_GET['tipo'],2));	
					$res2=$tipo_ca->buscar_fecha($_GET['fecha'],$_GET['ufv']);	

					if ($res1==false || $res2==false)
					{
					 $error['err_cambio'] = "El tipo de cambio  no corresponde a la fecha de ingreso";
			 		
					}
			 	}
			 		
				if($error!="")
				{   
					
					
					$primaria_id=$_GET['pri'];
				    $secundaria_id=$_GET['secun'];
				    $grupo_id=$_GET['gru'];
				    $num_act=$_GET['num_corr'];
				    $nombre_asis2=$_GET['nombre_asis2'];
					$nombre_asis=$_GET['nombre_asis'];	
					$resp_id2=$_GET['resp_id2'];
					$resp_id=$_GET['resp_id'];
					$resp_1=$_GET['resp_1'];
					$resp_2=$_GET['resp_2'];
					
					$descripcion=$_GET['descripcion'];
					$valor=$_GET['valor'];
					$residual=$_GET['residual'];
					$ad_id=$_GET['adqui'];
					$serie=$_GET['serie'];
					$unidad=$_GET['unidad'];
					$vida_util=$_GET['vida_util'];
					$numerito=$_GET['numerito'];
					$det_adqui=$_GET['det_adqui'];
					
				 	 $adqui=$adquisicion->listar_adqui($ad_id);
		 			 $adquis=$adquisicion->lisadqui($ad_id);
		
					if($nombre_asis2=='' && $nombre_asis=='' && $valor='' && $residual=''&& $ad_id=="selc")
					{ 
						

					  	  	$detalle=$activo->detalle_activo($act_id);
		 
		 
		 					 $sec_id=$detalle['secundaria_id'];
	    					 $loc=$detalle['primaria_id'];
							 $gru=$detalle['grupo_id'];
							 $tipo_cambio=round ($detalle['tipo_cambio'],2);	
							 $numero=$detalle['numero'];
							 
		
						 	 $primaria=$primaria->listar_primarias($loc);		
							 $secundaria=$secundaria->listar_secundarias($sec_id,$loc);
							 $grupo=$grupo->listar_gru($gru);
							 $adqui=$adquisicion->listar_adquisicion();
							 $foto=$activo->mostrar_foto($numero);
							/*echo "<pre>";
							print_r($foto);
							echo "</pre>";
							*/
							$smarty->assign('detalle',$detalle);
							$smarty->assign('primaria',$primaria);
							$smarty->assign('secundaria',$secundaria);
							$smarty->assign('grupo',$grupo);
							$smarty->assign('adqui',$adqui);
							$smarty->assign('tipo_cambio',$tipo_cambio);
							$smarty->assign('si','si');
							$smarty->assign('foto', $foto);
							$smarty->assign('adqui',$adqui);
							$smarty->assign('adquis',$adquis);
					  				  
					    
					}
					
					else 
					{
						
		 					 $detalle=$activo->detalle_activo_completo($act_id);
		 
		 
		 					 $sec_id=$detalle['secundaria_id'];
	    					 $loc=$detalle['primaria_id'];
							 $gru=$detalle['grupo_id'];
							 $tipo_cambio=round($detalle['tipo_cambio'],2);	
							 $numero=$detalle['numero'];
							 $det_adqui=$detalle['det_adqui'];	
						 	 $primaria=$primaria->listar_primarias($loc);		
							 $secundaria=$secundaria->listar_secundarias($sec_id,$loc);
							 $grupo=$grupo->listar_gru($gru);
							 $adqui=$adquisicion->listar_adquisicion();
							 $foto=$activo->mostrar_foto($numero);
							/*echo "<pre>";
							print_r($foto);
							echo "</pre>";
							*/
							$smarty->assign('detalle',$detalle);
							$smarty->assign('primaria',$primaria);
							$smarty->assign('secundaria',$secundaria);
							$smarty->assign('grupo',$grupo);
							$smarty->assign('adqui',$adqui);
							$smarty->assign('det_adqui',$det_adqui);
							$smarty->assign('tipo_cambio',$tipo_cambio);
							$smarty->assign('si','si');
							$smarty->assign('foto', $foto);
							$smarty->assign('adqui',$adqui);
							$smarty->assign('adquis',$adquis);
							
						
					}
							$smarty->assign('nombre_asis2',$nombre_asis2);	
							$smarty->assign('nombre_asis', $nombre_asis);	
							$smarty->assign('resp_id2',$resp_id2);	
							$smarty->assign('resp_id',$resp_id);	 
							
						
							$smarty->assign('errores',$error);
						     $smarty->display('modificar_activo_completo.html');
					
				}
		
					else 
				{	
		
					/*dddddddddddddddddddddddddddddddddddddd*/
		
		
		
				 $primaria_id=$_GET['pri'];
				 $secundaria_id=$_GET['secun'];
		          
				 $prima=$primaria->localizacionpri($primaria_id);
				 $localizacion=$prima['localizacion'];
		 
				 $secunda=$secundaria->localizacionsec($secundaria_id);
				 $locsecundaria=$secunda['locsecundaria'];
			 		 
				 $grupo_id=$_GET['gru'];
				 $gru=$grupo->locgru($grupo_id);
				 $locgru=$gru['grupo'];
			     $numerito=$_GET['numerito'];
				 $fotito=$_GET['foto'];
				 
	/*	 echo "<pre>";
		print_r($foto)."si solouno";
		echo "</pre>";
				 */
				
				 
			if ($localizacion < 10 )
			{$localizacion="0".$localizacion;}
			else 
			{$localizacion=$localizacion;}
			
			if ($locsecundaria <10 )
			{$locsecundaria="0".$locsecundaria;}
			else 
			{$locsecundaria=$locsecundaria;}
			if ($locgru <10 )			
			{$locgru="0".$locgru;}
			else 
			{$locgru=$locgru;}		

			
			$num_act=$_GET['num_corr'];
			
			if ($num_act <= 9)
			{
				$num_act1="0000".$num_act;
			}
			else 
			{
				if ($num_act <= 99)
				{$num_act1="000".$num_act;}
				else {
					if ($num_act <= 999)
					{$num_act1="00".$num_act;}				
					 else 
					 {
					 	if ($num_act <= 9999)
						{$num_act1="0".$num_act;}	
					 	else 
					 	{$num_act1=$num_act;}
					 }
					
				}
			
			}
			
			
			
		 			
		 $numeronuevo=$localizacion.".".$locsecundaria.".".$locgru.".".$num_act1;			
		 
		 $resp_id=$_GET['resp_id'];
		 $resp_pri=$_GET['resp_id2'];
		 
		 $resp_1=$_GET['resp_1'];
		 $resp_2=$_GET['resp_2'];
		 
		 $cantidad=$_GET['cantidad'];
		 $adqui=$_GET['adqui'];	
		 $det_adqui=$_GET['det_adqui'];
		
		 	
		
		$fecha=$_GET['fecha'];
		$unidad=$_GET['unidad'];
		$serie=$_GET['serie'];
		$descripcion=strtoupper($_GET['descripcion']);
		
		$tipo_cambio=$_GET['tipo'];
		$ufv=$_GET['ufv'];
		$vida_util=$_GET['vida_util'];
				
		$valor=$_GET['valor'];
		$valor_residual=$_GET['residual'];
		
		$usuario_id=$_SESSION['usuario_id'];
		$fecha1=date("Y/m/d");
	    $foto=$activo->mostrar_foto($numerito);
		$modificar=$activo->actualizar_activo($numeronuevo,$localizacion,$locsecundaria,$grupo_id,$resp_id,$resp_pri,$num_act,$cantidad,$adqui,$fecha,$unidad,$serie,$descripcion,$tipo_cambio,$ufv,$vida_util,$valor,$valor_residual,$act_id,$det_adqui);
		
		if ( $resp_1 != $resp_pri|| $resp_2 != $resp_id)
		{
			$asig=$asignacion->actualizar_asignacion($usuario_id,$resp_id,$fecha1,$act_id,$secundaria_id,$primaria_id,$resp_pri);
		}
		//actualizar la tabla fotografia
/*
		echo "44444<pre>";
		print_r($foto)."si solouno";
		echo "</pre>";*/
		if ($foto!='')
		{	foreach($foto as $key => $value){
	 		$num=$value['numero'];
			$fotografia=$value['fotografia'];
			$foto_id=$value['foto_id'];
			$indice=$key+1;
			$nuevafoto=$numeronuevo."-".$indice.'_IMG'.'.jpg';


		  $fotos=$activo->actualizar_foto($act_id,$numeronuevo,$nuevafoto,$foto_id);
		  chmod("UploadedFiles/".$fotografia, 0777);
		  rename("UploadedFiles/".$fotografia, "UploadedFiles/".$nuevafoto);
		
		}
		}
		/*
		
	    $fotografia=$numero.'_IMG'.'.jpg';
		$fotos=$activo->actualizar_foto($act_id,$numero,$fotografia);
		chmod("UploadedFiles/".$fotito, 0777);
		rename("UploadedFiles/".$fotito, "UploadedFiles/".$fotografia);
		*/
		
		
		$error['err_confirm'] = "El registro se realizo correctamente";
		
		$primaria=$primaria->listar_locprimaria();
		$secundaria=$secundaria->listar_locsecundaria();
		$grupo=$grupo->listar_grupo();
		$mostrar_foto=$activo->detalle_activo_completo($act_id);	
		$smarty->assign('errores',$error);
		$smarty->assign('primaria', $primaria);					
		$smarty->assign('secundaria', $secundaria);
		$smarty->assign('grupo', $grupo);
		
		$smarty->assign('verfoto', 'si');
		$smarty->assign('mostrar_foto',$mostrar_foto);
		$smarty->display('listar_activo_completo.html');
		
				
	}

	
	}
 }
}
	
?>