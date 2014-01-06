<?php
require_once('../clases/conexion.php');
require_once('../clases/grupo.php');
require_once('../clases/tipocambio.php');
require_once('../clases/locsecundaria.php');
require_once('../clases/locprimaria.php');
require_once('../clases/activo.php');
require_once('../clases/ActualizacionTodo.php');

class ActualizacionSec
{
	

/*Este calcula es cuando se cumple los tres primaria,secundaria,grupo del Reporte especifico*/
 function calcular_actualizacion_gru($fecha_inter,$fecha_inicio,$fecha_fin,$localizacion,$locsecundaria,$secundaria_id,$primaria_id,$grupo_id,$fecha_gestion_ante,$gest)
  {                               
  	$secundaria= new LocSecundaria();
	$tipo_cambio=new TipoCambio();
	$activo= new Activo();
	$acttodo= new ActualizacionTodo();				
				
 	 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   	
 	if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
		{	       
					
					$fecha_act=$secundaria->buscar_fecha_compra_gru($secundaria_id,$grupo_id);
					
				   							
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_periodo=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
					 		$meses_anterior=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
	  	   			 		
					 		
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=1;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/*******************/
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=0;
							    $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
							
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
										 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
								
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										 $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
							
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
							
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}
							
							/**********/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							
							$resp[$indice]=$lista_activo;
																	
							
							
			    			
						}
						else 
						{
						   if ($valor['fecha'] <= $fecha_fin) /*menor igual a la  fecha fin*/
						  { 
						  	$fecha_compra=$valor['fecha'];
							
							$grupo_id=$valor['grupo_id'];
							$act_id=$valor['act_id'];
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);
							$validar=2;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/*******************/
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							
	  	   			 		$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
	  	   			 		$meses_anterior=$meses_an;
							
							$salida=date('m', strtotime($fecha_inicio));
							
							$salida1=date('m', strtotime($fecha_compra));
							$año=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año1=date('Y',strtotime($fecha_fin));
							
							$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
							
							  if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
							
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
							 
								 		}
								 }
							/*****************/
							
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$indice]=$lista_activo;
						  }
						}
				
							
					}
				return $resp;
				}
						  
				
				
				else 
				{  
				 if ($fecha_inicio < $fecha_inter && $fecha_fin <= $fecha_inter)
				   {
				   	$secundaria= new LocSecundaria();
					$tipo_cambio=new TipoCambio();
					$activo= new Activo();
					
				   	$fecha_act=$secundaria->buscar_fecha_compra_gru($secundaria_id,$grupo_id);
									
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 				
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
			  	   			$meses_anterior=$meses_an;
			  	   			$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=3;
							$valor_ufv=0;//valor en ufv al 31-03-2007
							$valor_ufv2=0;//valor al final del reporte
							/************************/
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año2=date('Y',strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=6;
						    	$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
						
							}
						else{
							 if ($salida1==$salida2 && $año1=$año2 )
							   {  //si su vida comienza en el la fecha fin
							     $depre=10;						  
								 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
									  
								 }
					    	else{		 					
							
							   if ($fecha_compra < $fecha_fin )
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		     		
							   		
							   		   	 if ($fecha_compra > $fecha_inicio)
												{
												 $mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
												 $meses_periodo=$mesmedio;
												 $depre=11;
											     $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
								   			 
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=7; 			 	
												 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
							
												 
											 }
											else
											{ 
											 if($totalmeses >= $vida)
											   {
											   	 
											   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
												$mesmedio =$vida-$meses_anterior; 
												 
												 if($mesmedio >= 1)
												 { //mes medio llegaria hacer el mes periodo
													
												 	$meses_periodo=$mesmedio;
												 	
												   $depre=8;
											       $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
						
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													  $depre=9;
													  $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
						
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
							}
				
						}	
							/************************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$indice]=$lista_activo;
							
							
						}
						/*else 
						{
						    echo "<br>vamonos".$valor["fecha"];
									
							
						}*/
					}
					return $resp;
				
				   }
				   else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	  { $secundaria= new LocSecundaria();
						$tipo_cambio=new TipoCambio();
						$activo= new Activo();
						$gestion= new Gestion();
						 $fecha_gestion=$gestion->fecha_gestion($gest);
					 	 $fecha_ges_ini=$fecha_gestion['fecha_inicio'];
					 	 $fecha_ges_fin=$fecha_gestion['fecha_fin'];
				   	  	
				   	    $fecha_act=$secundaria->buscar_fecha_compra_gru($secundaria_id,$grupo_id,$primaria_id);
											
					foreach($fecha_act as $indice => $valor)
					 {
					  if ($valor['fecha_baja']== '' ||  $valor['fecha_baja'] > $fecha_fin)     			
				  		{
						if ($valor['fecha'] <= $fecha_inter)
						{
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
													
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=4;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/***********************/
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
														
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							
							$fe_compra=$activo->Primerdiames($fecha_compra);
							
							$meses_an=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
			  	   			$meses_anterior=$meses_an;							
						
			  	   			

							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=12;
								$lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						 	     
								
							}
						else{
							   if ($fecha_compra < $fecha_inter)
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
									 	
										 $depre=13; 			 	
										 $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
										 
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										$mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=14;
									       $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
										  
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											
											   $depre=15;
											   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
											   
											 }
											 
											 
					
											 
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							/***********************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$indice]=$lista_activo;
																	
							
			    			
						}
						else 
						{
						    //echo "<br>vamonos".$valor["fecha"];
						    $act_id=$valor['act_id'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							if($fecha_compra > $fecha_inter)
							{
							
							$grupo_id=$valor['grupo_id'];
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=5;
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/**********************/
								$meses_periodo=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
					 			$fe_compra=$activo->Primerdiames($fecha_compra);
							 
								$meses_anterior=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
	  	   			 		
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
								
								
								$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							    $valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							
								
							if ($salida1==$salida2 && $año1==$año2 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=16;
							
								$lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
							
							}
							
							else{		 					
							
							   if ($fecha_compra < $fecha_fin )
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		     	
							   		   	 if ($fecha_compra > $fecha_inicio)
												{
												 $fe_compra=$activo->Primerdiames($fecha_compra);
												 $mesmedio=$activo->diferencia_fechas_meses($fe_compra,$fecha_fin); 
												 $meses_periodo=$mesmedio;
												 $depre=17;
												 $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
									   			
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=18; 			 	
												 $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
												
												 
											 }
											else
											{ 
											 if($totalmeses >= $vida)
											   {
											   	 
											   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
												$mesmedio =$vida-$meses_anterior; 
												 
												 if($mesmedio >= 1)
												 { //mes medio llegaria hacer el mes periodo
													
												 	$meses_periodo=$mesmedio;
												 	
												   $depre=19;
												   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
												    
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													   $depre=20;
													   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
													  
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
								else{
									
								//echo "fcha".$fecha_compra=$valor['fecha'];
								$lista_activo=0;
								
							}
								
							}
							if ($lista_activo!=0)
							{
							 $resp[$indice]=$lista_activo;
							}		
							
							}
						  }
				
						
				  		 }	
						}
						return $resp;		
					
				   		
				   	}
				   	else 
				   	{
				   		if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   	    {   
				   	    	$secundaria= new LocSecundaria();
							$tipo_cambio=new TipoCambio();
							$activo= new Activo(); 
				   	    	$fecha_act=$secundaria->buscar_fecha_compra_gru($secundaria_id,$grupo_id);
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
											
						foreach($fecha_act as $indice => $valor) 
						{
						   if ($valor['fecha'] <= $fecha_inter)
						   {
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							$validar=6;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/*******************
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
								$meses_periodo=$meses_pe;
									
								$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
					  	   		$meses_anterior=$meses_an;							
								
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
								
							
							/******************************/
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=0;
								$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
						
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
										 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
							
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										 $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										  	$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
						
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
						
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
							/*****************/
							
							
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$indice]=$lista_activo;
										
							
								
						}
						else
						{  if ($fecha_compra <= $fecha_fin )
							{
							
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
						    $valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=0;
							$validar=7;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/***************/
							$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
							
								
								if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
							
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
							
								 		}
								 }

							/******************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$indice]=$lista_activo;
							}
						
						}
						
						}
				   		return $resp;
				   			
				   		
				   			
				   		}

				   }
				   	

				}
	
				
				
			}//hastaaqui
			
 	
 	
 	
 	
 	
 }
 /*Este calcula solo de la localizacion primaria y secundaria*/
function calcular_actualizacion_sec($fecha_inter,$fecha_inicio,$fecha_fin,$localizacion,$locsecundaria,$secundaria_id,$primaria_id,$fecha_gestion_ante,$gest)
  {		$secundaria= new LocSecundaria();
		$tipo_cambio=new TipoCambio();
		$activo= new Activo();
		$acttodo= new ActualizacionTodo();	
		
					
 	 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
 
 	if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
		{	       
					$fecha_act=$secundaria->buscar_fecha_compra_sec($secundaria_id);
											
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$descripcion=$valor['descripcion'];
							
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_periodo=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
					 		$meses_anterior=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
	  	   			 		
	  	   			 		$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
							
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=1;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte\
							/*****************/
							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=0;
								$lista_activo=$activo->listar_activo_secpri($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre,$primaria_id,$secundaria_id);
	
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
									 	$lista_activo=$activo->listar_activo_secpri($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$primaria_id,$secundaria_id);
		
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										 $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										   $lista_activo=$activo->listar_activo_secpri($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$primaria_id,$secundaria_id);
	
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$activo->listar_activo_secpri($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$primaria_id,$secundaria_id);
	
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
							
							
							
							/******************/
						//	$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							
							$resp[$descripcion][]=$lista_activo;
			    			
						}
						else 
						{
						    if ($valor['fecha'] > $fecha_inicio) /*menor igual a la  fecha fin*/
						   { 
						  	$fecha_compra=$valor['fecha'];
							
							$grupo_id=$valor['grupo_id'];
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);
							$validar=2;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/*****************************/
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							
	  	   			 		$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
	  	   			 		$meses_anterior=$meses_an;
							
							$salida=date('m', strtotime($fecha_inicio));
							
							$salida1=date('m', strtotime($fecha_compra));
							$año=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año1=date('Y',strtotime($fecha_fin));
							
							$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
							
							  if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
						
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
						 
								 		}
								 }
							
							
							/****************************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$descripcion][]=$lista_activo;
						  }
							
						}
				
							
					}
				return $resp;
				}
						  
				
				
				else 
				{  
				 if ($fecha_inicio < $fecha_inter && $fecha_fin <= $fecha_inter)
				   {
				   $secundaria= new LocSecundaria();
					$tipo_cambio=new TipoCambio();
					$activo= new Activo();
					
				   	$fecha_act=$secundaria->buscar_fecha_compra_sec($secundaria_id);
									
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$descripcion=$valor['descripcion'];
							
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
			  	   			$meses_anterior=$meses_an;
			  	   			$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=3;
							$valor_ufv=0;//valor en ufv al 31-03-2007
							$valor_ufv2=0;//valor al final del reporte
							/*************************/
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año2=date('Y',strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=6;
								$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
						
							}
						else{
							 if ($salida1==$salida2 && $año1=$año2 )
							   {  //si su vida comienza en el la fecha fin
							     $depre=10;						  
	
							     $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
								  
								 }
					    	else{		 					
							
							   if ($fecha_compra < $fecha_fin )
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		     		
							   		
							   		   	 if ($fecha_compra > $fecha_inicio)
												{
												 $mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
												 $meses_periodo=$mesmedio;
												 $depre=11;
												 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
							   			 
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=7; 			 	
												 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
						
												 
											 }
											else
											{ 
											 if($totalmeses >= $vida)
											   {
											   	 
											   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
												$mesmedio =$vida-$meses_anterior; 
												 
												 if($mesmedio >= 1)
												 { //mes medio llegaria hacer el mes periodo
													
												 	$meses_periodo=$mesmedio;
												 	
												   $depre=8;
												  	$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
						
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													  $depre=9;
													  $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
						
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
							}
				
						}	
							
							
							/*************************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$descripcion][]=$lista_activo;
								
						}
						/*else 
						{
						    echo "<br>vamonos".$valor["fecha"];
									
							
						}*/
					}
					return $resp;
				
				   }
				   else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	  { 
				   	  	 $secundaria= new LocSecundaria();
						 $tipo_cambio=new TipoCambio();
						 $activo= new Activo();
					     $gestion= new Gestion();
					      
			 			 $fecha_gestion=$gestion->fecha_gestion($gest);
					 	 $fecha_ges_ini=$fecha_gestion['fecha_inicio'];
					 	 $fecha_ges_fin=$fecha_gestion['fecha_fin'];
				   	  	 $fecha_act=$secundaria->buscar_fecha_compra_sec($secundaria_id,$primaria_id);
											
					foreach($fecha_act as $indice => $valor)
					 { if ($valor['fecha_baja']== '' ||  $valor['fecha_baja'] > $fecha_fin)     			
				  	   {
						if ($valor['fecha'] <= $fecha_inter)
						{
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$descripcion=$valor['descripcion'];
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 				$fecha_compra=$valor['fecha'];	
							$vida=$valor['vida_util'];
							
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
						
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=4;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/********************************/
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							
							
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							
							$fe_compra=$activo->Primerdiames($fecha_compra);
							
							$meses_an=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
			  	   			$meses_anterior=$meses_an;							
						

							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año2=date('Y',strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=12;
								$lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
							   
								
							}
						else{
							   if ($fecha_compra < $fecha_inter)
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
									 	
										 $depre=13; 			 	
										 $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
											 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										$mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=14;
										   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
										    		
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											
											   $depre=15;
											   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
										
											 }
											 
											 
					
											 
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
							
							
							
							/*********************************/
												
							
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$descripcion][]=$lista_activo;
																	
							
			    			
						}
						else 
						{
						    //echo "<br>vamonos".$valor["fecha"];
							$grupo_id=$valor['grupo_id'];
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							$vida=$valor['vida_util'];
							$fecha_compra=$valor['fecha'];
							
							if($fecha_compra > $fecha_inter)
							{ 
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=5;
							
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
								
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/************************************/
								
								$meses_periodo=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
					 			$fe_compra=$activo->Primerdiames($fecha_compra);
							 
								$meses_anterior=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
	  	   			 		
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
								
								$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
								
								$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
								$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							
								
													
							if ($salida1==$salida2 && $año1==$año2 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=16;
							
								$lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
								
							}
							
							else{		 					
							
							   if ($fecha_compra < $fecha_fin )
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		     	
							   		   	 if ($fecha_compra > $fecha_inicio)
												{ 
												 $fe_compra=$activo->Primerdiames($fecha_compra);
												
												 $mesmedio=$activo->diferencia_fechas_meses($fe_compra,$fecha_fin); 
												 $meses_periodo=$mesmedio;
												 $depre=17;
												 $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
								   			 	
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=18; 			 	
												 $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
												
												 
											 }
											else
											{ 
											 if($totalmeses >= $vida)
											   {
											   	 
											   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
												$mesmedio =$vida-$meses_anterior; 
												 
												 if($mesmedio >= 1)
												 { //mes medio llegaria hacer el mes periodo
													
												 	$meses_periodo=$mesmedio;
												 	
												   $depre=19;
												   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
												   
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													   $depre=20;
													   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
													
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
							else{
									
								//echo "fcha".$fecha_compra=$valor['fecha'];
								$lista_activo=0;
								
							}
								
								
								
							}
														
							if ($lista_activo!=0)
							{
						    $resp[$descripcion][]=$lista_activo;
							}				
							}
						}
				
						
				  	   }	
					}
						return $resp;		
					
				   		
				   	}
				   	else 
				   	{
				   		if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   	    {   $secundaria= new LocSecundaria();
							$tipo_cambio=new TipoCambio();
							$activo= new Activo(); 
				   	    	$fecha_act=$secundaria->buscar_fecha_compra_sec($secundaria_id);
											
						foreach($fecha_act as $indice => $valor) 
						{  
						   if ($valor['fecha'] <= $fecha_inter)
						   {
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$descripcion=$valor['descripcion'];
							$vida=$valor['vida_util'];
							$fecha_compra=$valor['fecha'];
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							$validar=6;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/**********************************/
							
							
							
								$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
								$meses_periodo=$meses_pe;
									
								$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
					  	   		$meses_anterior=$meses_an;							
								
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
								
							
							/******************************/
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=0;
					      		$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
							
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
										 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
								
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										 $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
							
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
							
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
								
							
							/****************************************/
							
							
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$descripcion][]=$lista_activo;
										
							
								
						}
						else
						{  $fecha_compra=$valor['fecha'];
						if ($fecha_compra <= $fecha_fin )
							{
							
							$act_id=$valor['act_id'];
						    $grupo_id=$valor['grupo_id'];
						    $descripcion=$valor['descripcion'];
							
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=0;
							$validar=7;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/******************/
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
							
								
								if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
						
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
						
								 		}
								 }
							/******************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$descripcion][]=$lista_activo;
							}
						
						}
						
						}
				   		return $resp;
				   			
				   		
				   			
				   		}

				   }
				   	

				}
	
				
				
			}//hastaaqui
			
 	
 	
 	
 	
 	
 }								
/*Este calcula los gastos de primaria y secundaria del reporte en especifico*/
function calcular_gasto_sec($fecha_inter,$fecha_inicio,$fecha_fin,$localizacion,$locsecundaria,$secundaria_id,$primaria_id,$fecha_gestion_ante,$fecha_ges_ini,$fecha_ges_fin)
  {		$secundaria= new LocSecundaria();
		$tipo_cambio=new TipoCambio();
		$activo= new Activo();
		$acttodo= new ActualizacionTodo();	
		
					
 	 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
 
 	if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
		{	       
					$fecha_act=$secundaria->buscar_fecha_compra_sec($secundaria_id);
											
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$descripcion=$valor['descripcion'];
							
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_periodo=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
					 		$meses_anterior=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
	  	   			 		
	  	   			 		$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
							
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=1;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte\
							/*****************/
							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=0;
								$lista_activo=$activo->listar_activo_secpri($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre,$primaria_id,$secundaria_id);
	
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
									 	$lista_activo=$activo->listar_activo_secpri($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$primaria_id,$secundaria_id);
		
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										 $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										   $lista_activo=$activo->listar_activo_secpri($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$primaria_id,$secundaria_id);
	
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$activo->listar_activo_secpri($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$primaria_id,$secundaria_id);
	
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
							
							
							
							/******************/
						//	$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							
							$resp[$descripcion][]=$lista_activo;
			    			
						}
						else 
						{
						    if ($valor['fecha'] > $fecha_inicio) /*menor igual a la  fecha fin*/
						   { 
						  	$fecha_compra=$valor['fecha'];
							
							$grupo_id=$valor['grupo_id'];
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);
							$validar=2;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/*****************************/
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							
	  	   			 		$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
	  	   			 		$meses_anterior=$meses_an;
							
							$salida=date('m', strtotime($fecha_inicio));
							
							$salida1=date('m', strtotime($fecha_compra));
							$año=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año1=date('Y',strtotime($fecha_fin));
							
							$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
							
							  if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
						
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
						 
								 		}
								 }
							
							
							/****************************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$descripcion][]=$lista_activo;
						  }
							
						}
				
							
					}
				return $resp;
				}
						  
				
				
				else 
				{  
				 if ($fecha_inicio < $fecha_inter && $fecha_fin <= $fecha_inter)
				   {
				   $secundaria= new LocSecundaria();
					$tipo_cambio=new TipoCambio();
					$activo= new Activo();
					
				   	$fecha_act=$secundaria->buscar_fecha_compra_sec($secundaria_id);
									
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$descripcion=$valor['descripcion'];
							
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
			  	   			$meses_anterior=$meses_an;
			  	   			$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=3;
							$valor_ufv=0;//valor en ufv al 31-03-2007
							$valor_ufv2=0;//valor al final del reporte
							/*************************/
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año2=date('Y',strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=6;
								$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
						
							}
						else{
							 if ($salida1==$salida2 && $año1=$año2 )
							   {  //si su vida comienza en el la fecha fin
							     $depre=10;						  
	
							     $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
								  
								 }
					    	else{		 					
							
							   if ($fecha_compra < $fecha_fin )
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		     		
							   		
							   		   	 if ($fecha_compra > $fecha_inicio)
												{
												 $mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
												 $meses_periodo=$mesmedio;
												 $depre=11;
												 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
							   			 
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=7; 			 	
												 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
						
												 
											 }
											else
											{ 
											 if($totalmeses >= $vida)
											   {
											   	 
											   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
												$mesmedio =$vida-$meses_anterior; 
												 
												 if($mesmedio >= 1)
												 { //mes medio llegaria hacer el mes periodo
													
												 	$meses_periodo=$mesmedio;
												 	
												   $depre=8;
												  	$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
						
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													  $depre=9;
													  $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
						
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
							}
				
						}	
							
							
							/*************************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$descripcion][]=$lista_activo;
								
						}
						/*else 
						{
						    echo "<br>vamonos".$valor["fecha"];
									
							
						}*/
					}
					return $resp;
				
				   }
				   else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	  { 
				   	  	 $secundaria= new LocSecundaria();
						 $tipo_cambio=new TipoCambio();
						 $activo= new Activo();
					     $gestion= new Gestion();

					     $fecha_act=$secundaria->buscar_fecha_compra_sec($secundaria_id,$primaria_id);
											
					foreach($fecha_act as $indice => $valor)
					 { if ($valor['fecha_baja']== '' ||  $valor['fecha_baja'] > $fecha_fin)     			
				  	   {
						if ($valor['fecha'] <= $fecha_inter)
						{
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$descripcion=$valor['descripcion'];
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 				$fecha_compra=$valor['fecha'];	
							$vida=$valor['vida_util'];
							
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
						
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=4;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/********************************/
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
																						
						
							$meses_pe=$activo->diferencia_fechas_meses($fecha_ges_ini,$fecha_inicio);	
							$meses_periodo=$meses_pe;
							$fe_compra=$activo->Primerdiames($fecha_compra);
							$meses_an=$activo->diferencia_fechas_meses($fe_compra,$fecha_ges_ini);
			  	   			$meses_anterior=$meses_an;	
			  	   									
							$meses_gestion=$activo->diferencia_fechas_meses($fecha_ges_fin,$fecha_ges_ini);
			  	   			
							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año2=date('Y',strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=12;
								$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						
								
							}
						else{
							   if ($fecha_compra < $fecha_inter)
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
									 	
										 $depre=13; 			 	
										$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_pe,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
							 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										$mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=14;
										  $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$mesmedio,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						  		
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											
											   $depre=15;
											   $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						
											 }
											 
											 
					
											 
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
							
							
							
							/*********************************/
												
							
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$descripcion][]=$lista_activo;
																	
							
			    			
						}
						else 
						{
						    //echo "<br>vamonos".$valor["fecha"];
							$grupo_id=$valor['grupo_id'];
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							$vida=$valor['vida_util'];
							$fecha_compra=$valor['fecha'];
							
							if($fecha_compra > $fecha_inter)
							{ 
							 if ($fecha_compra >=$fecha_ges_ini || $fecha_compra <$fecha_ges_fin)
							   {
							  	
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=5;
							
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
								
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/************************************/
															
								
								$meses_pe=$activo->diferencia_fechas_meses($fecha_ges_ini,$fecha_inicio);		
								$meses_periodo=$meses_pe;
								$fe_compra=$activo->Primerdiames($fecha_compra);
								$meses_an=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
				  	   			$meses_anterior=$meses_an;	
				  	   			$meses_gestion=$activo->diferencia_fechas_meses($fecha_ges_fin,$fecha_ges_ini);
				  	   		
								
								
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
								
								
								$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
								$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							
								
													
							if ($salida1==$salida2 && $año1==$año2 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=16;
							
								$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					
							}
							
							else{		 					
							
							   if ($fecha_compra < $fecha_fin )
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		     	
							   		   	 if ($fecha_compra > $fecha_inicio)
												{ 
												 $fe_compra=$activo->Primerdiames($fecha_compra);
												
												 $mesmedio=$activo->diferencia_fechas_meses($fe_compra,$fecha_fin); 
												 $meses_periodo=$mesmedio;
												 $depre=17;
												 $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						   			 
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=18; 			 	
												 $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_pe,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					
												 
											 }
											else
											{ 
											 if($totalmeses >= $vida)
											   {
											   	 
											   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
												$mesmedio =$vida-$meses_anterior; 
												 
												 if($mesmedio >= 1)
												 { //mes medio llegaria hacer el mes periodo
													
												 	$meses_periodo=$mesmedio;
												 	
												   $depre=19;
												  $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_an,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					 
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													   $depre=20;
													   $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
							else{
									
								//echo "fcha".$fecha_compra=$valor['fecha'];
								$lista_activo=0;
								
							}
								
								
								
							}
														
							if ($lista_activo!=0)
							{
						    $resp[$descripcion][]=$lista_activo;
							}	
							}			
							}
						}
				
						
				  	   }	
					}
						return $resp;		
					
				   		
				   	}
				   	else 
				   	{
				   		if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   	    {   $secundaria= new LocSecundaria();
							$tipo_cambio=new TipoCambio();
							$activo= new Activo(); 
				   	    	$fecha_act=$secundaria->buscar_fecha_compra_sec($secundaria_id);
											
						foreach($fecha_act as $indice => $valor) 
						{  
						   if ($valor['fecha'] <= $fecha_inter)
						   {
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$descripcion=$valor['descripcion'];
							$vida=$valor['vida_util'];
							$fecha_compra=$valor['fecha'];
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							$validar=6;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/**********************************/
							
							
							
								$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
								$meses_periodo=$meses_pe;
									
								$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
					  	   		$meses_anterior=$meses_an;							
								
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
								
							
							/******************************/
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=0;
					      		$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
							
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
										 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
								
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										 $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
							
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
							
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
								
							
							/****************************************/
							
							
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$descripcion][]=$lista_activo;
										
							
								
						}
						else
						{  $fecha_compra=$valor['fecha'];
						if ($fecha_compra <= $fecha_fin )
							{
							
							$act_id=$valor['act_id'];
						    $grupo_id=$valor['grupo_id'];
						    $descripcion=$valor['descripcion'];
							
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=0;
							$validar=7;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/******************/
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
							
								
								if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
						
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
						
								 		}
								 }
							/******************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$descripcion][]=$lista_activo;
							}
						
						}
						
						}
				   		return $resp;
				   			
				   		
				   			
				   		}

				   }
				   	

				}
	
				
				
			}//hastaaqui
			
 	
 	
 	
 	
 	
 }								
function calcular_gasto_gru($fecha_inter,$fecha_inicio,$fecha_fin,$localizacion,$locsecundaria,$secundaria_id,$primaria_id,$grupo_id,$fecha_gestion_ante,$fecha_ges_ini,$fecha_ges_fin)
  {                               
  	$secundaria= new LocSecundaria();
	$tipo_cambio=new TipoCambio();
	$activo= new Activo();
	$acttodo= new ActualizacionTodo();				
				
 	 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   	
 	if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
		{	       
					
					$fecha_act=$secundaria->buscar_fecha_compra_gru($secundaria_id,$grupo_id);
					
				   							
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_periodo=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
					 		$meses_anterior=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
	  	   			 		
					 		
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=1;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/*******************/
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=0;
							    $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
							
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
										 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
								
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										 $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
							
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
							
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}
							
							/**********/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							
							$resp[$indice]=$lista_activo;
																	
							
							
			    			
						}
						else 
						{
						   if ($valor['fecha'] <= $fecha_fin) /*menor igual a la  fecha fin*/
						  { 
						  	$fecha_compra=$valor['fecha'];
							
							$grupo_id=$valor['grupo_id'];
							$act_id=$valor['act_id'];
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);
							$validar=2;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/*******************/
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							
	  	   			 		$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
	  	   			 		$meses_anterior=$meses_an;
							
							$salida=date('m', strtotime($fecha_inicio));
							
							$salida1=date('m', strtotime($fecha_compra));
							$año=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año1=date('Y',strtotime($fecha_fin));
							
							$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
							
							  if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
							
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
							 
								 		}
								 }
							/*****************/
							
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$indice]=$lista_activo;
						  }
						}
				
							
					}
				return $resp;
				}
						  
				
				
				else 
				{  
				 if ($fecha_inicio < $fecha_inter && $fecha_fin <= $fecha_inter)
				   {
				   	$secundaria= new LocSecundaria();
					$tipo_cambio=new TipoCambio();
					$activo= new Activo();
					
				   	$fecha_act=$secundaria->buscar_fecha_compra_gru($secundaria_id,$grupo_id);
									
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 				
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
			  	   			$meses_anterior=$meses_an;
			  	   			$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=3;
							$valor_ufv=0;//valor en ufv al 31-03-2007
							$valor_ufv2=0;//valor al final del reporte
							/************************/
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año2=date('Y',strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=6;
						    	$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
						
							}
						else{
							 if ($salida1==$salida2 && $año1=$año2 )
							   {  //si su vida comienza en el la fecha fin
							     $depre=10;						  
								 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
									  
								 }
					    	else{		 					
							
							   if ($fecha_compra < $fecha_fin )
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		     		
							   		
							   		   	 if ($fecha_compra > $fecha_inicio)
												{
												 $mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
												 $meses_periodo=$mesmedio;
												 $depre=11;
											     $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
								   			 
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=7; 			 	
												 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
							
												 
											 }
											else
											{ 
											 if($totalmeses >= $vida)
											   {
											   	 
											   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
												$mesmedio =$vida-$meses_anterior; 
												 
												 if($mesmedio >= 1)
												 { //mes medio llegaria hacer el mes periodo
													
												 	$meses_periodo=$mesmedio;
												 	
												   $depre=8;
											       $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
						
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													  $depre=9;
													  $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
						
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
							}
				
						}	
							/************************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$indice]=$lista_activo;
							
							
						}
						/*else 
						{
						    echo "<br>vamonos".$valor["fecha"];
									
							
						}*/
					}
					return $resp;
				
				   }
				   else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	  { $secundaria= new LocSecundaria();
						$tipo_cambio=new TipoCambio();
						$activo= new Activo();
						$gestion= new Gestion();
						 	  	
				   	    $fecha_act=$secundaria->buscar_fecha_compra_gru($secundaria_id,$grupo_id,$primaria_id);
											
					foreach($fecha_act as $indice => $valor)
					 {
					  if ($valor['fecha_baja']== '' ||  $valor['fecha_baja'] > $fecha_fin)     			
				  		{
						if ($valor['fecha'] <= $fecha_inter)
						{
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
													
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=4;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/***********************/
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
														
							$meses_pe=$activo->diferencia_fechas_meses($fecha_ges_ini,$fecha_inicio);	
							$meses_periodo=$meses_pe;
							$fe_compra=$activo->Primerdiames($fecha_compra);
							$meses_an=$activo->diferencia_fechas_meses($fe_compra,$fecha_ges_ini);
			  	   			$meses_anterior=$meses_an;	
			  	   									
							$meses_gestion=$activo->diferencia_fechas_meses($fecha_ges_fin,$fecha_ges_ini);
			  	   			

							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=12;
								$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						 
								
							}
						else{
							   if ($fecha_compra < $fecha_inter)
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
									 	
										 $depre=13; 			 	
										$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_pe,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						 
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										$mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=14;
									       $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$mesmedio,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											
											   $depre=15;
											    $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						
											 }
											 
											 
					
											 
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							/***********************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$indice]=$lista_activo;
																	
							
			    			
						}
						else 
						{
						    //echo "<br>vamonos".$valor["fecha"];
						    $act_id=$valor['act_id'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							if($fecha_compra > $fecha_inter)
							{
							  if ($fecha_compra >=$fecha_ges_ini || $fecha_compra <$fecha_ges_fin)
							   {
							  
							$grupo_id=$valor['grupo_id'];
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=5;
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/**********************/
								$meses_pe=$activo->diferencia_fechas_meses($fecha_ges_ini,$fecha_inicio);		
								$meses_periodo=$meses_pe;
								$fe_compra=$activo->Primerdiames($fecha_compra);
								$meses_an=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
				  	   			$meses_anterior=$meses_an;	
				  	   			$meses_gestion=$activo->diferencia_fechas_meses($fecha_ges_fin,$fecha_ges_ini);
				  	   			
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
								
								
								$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							    $valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							
								
							if ($salida1==$salida2 && $año1==$año2 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=16;
							
								$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					
							}
							
							else{		 					
							
							   if ($fecha_compra < $fecha_fin )
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		     	
							   		   	 if ($fecha_compra > $fecha_inicio)
												{
												 $fe_compra=$activo->Primerdiames($fecha_compra);
												 $mesmedio=$activo->diferencia_fechas_meses($fe_compra,$fecha_fin); 
												 $meses_periodo=$mesmedio;
												 $depre=17;
												 $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						   			 
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=18; 			 	
												 $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_pe,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					
												 
											 }
											else
											{ 
											 if($totalmeses >= $vida)
											   {
											   	 
											   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
												$mesmedio =$vida-$meses_anterior; 
												 
												 if($mesmedio >= 1)
												 { //mes medio llegaria hacer el mes periodo
													
												 	$meses_periodo=$mesmedio;
												 	
												   $depre=19;
												   $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_an,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					  
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													   $depre=20;
													   $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
								else{
									
								//echo "fcha".$fecha_compra=$valor['fecha'];
								$lista_activo=0;
								
							}
								
							}
							if ($lista_activo!=0)
							{
							 $resp[$indice]=$lista_activo;
							}		
							}
							}
						  }
				
						
				  		 }	
						}
						return $resp;		
					
				   		
				   	}
				   	else 
				   	{
				   		if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   	    {   
				   	    	$secundaria= new LocSecundaria();
							$tipo_cambio=new TipoCambio();
							$activo= new Activo(); 
				   	    	$fecha_act=$secundaria->buscar_fecha_compra_gru($secundaria_id,$grupo_id);
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
											
						foreach($fecha_act as $indice => $valor) 
						{
						   if ($valor['fecha'] <= $fecha_inter)
						   {
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							$validar=6;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/*******************
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
								$meses_periodo=$meses_pe;
									
								$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
					  	   		$meses_anterior=$meses_an;							
								
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
								
							
							/******************************/
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=0;
								$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
						
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 { /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
										 $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
							
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 /*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
										 $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										  	$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
						
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
						
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
							/*****************/
							
							
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$indice]=$lista_activo;
										
							
								
						}
						else
						{  if ($fecha_compra <= $fecha_fin )
							{
							
							$act_id=$valor['act_id'];
							$grupo_id=$valor['grupo_id'];
						    $valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=0;
							$validar=7;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/***************/
							$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
							
								
								if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
							
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
							
								 		}
								 }

							/******************/
							//$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$indice]=$lista_activo;
							}
						
						}
						
						}
				   		return $resp;
				   			
				   		
				   			
				   		}

				   }
				   	

				}
	
				
				
			}//hastaaqui
			
 	
 	
 	
 	
 	
 }
 
}
?>