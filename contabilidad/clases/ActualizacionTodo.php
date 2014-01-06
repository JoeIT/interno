<?php
require_once('../clases/conexion.php');
require_once('../clases/grupo.php');
require_once('../clases/tipocambio.php');
require_once('../clases/locsecundaria.php');
require_once('../clases/locprimaria.php');
require_once('../clases/activo.php');
require_once('../clases/gestion.php');

class ActualizacionTodo
{
function listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria)
    {	$grupo= new Grupo();
		$tipo_cambio=new TipoCambio();
    	$gestion= new Gestion();
    	$con = new Conexion;
		if($con->conectar() == true){
			if ($localizacion!=''&& $locsecundaria!='')
			{$consulta = "
			SELECT	a.numero,a.descripcion,a.tipo_cambio,a.vida_util,a.ufv,a.valor_compra,a.num_act,a.fecha,a.grupo_id,g.descripcion as desgru,a.fecha_baja
			FROM	activos a,grupoactivo g
			where a.grupo_id='".$grupo_id."' and a.act_id='".$act_id."' and a.grupo_id=g.grupo_id and a.localizacion='".$localizacion."' and a.locsecundaria='".$locsecundaria."' 
			
			
			";
			}
			else {
			if ($grupo_id!='')	
			{$consulta = "
			SELECT	a.numero,a.descripcion,a.tipo_cambio,a.vida_util,a.ufv,a.valor_compra,a.num_act,a.fecha,a.grupo_id,g.descripcion as desgru,a.fecha_baja
			FROM	activos a,grupoactivo g
			where a.grupo_id='".$grupo_id."' and a.act_id='".$act_id."' and a.grupo_id=g.grupo_id 
			
			
			";
			
			}	
			else
			{
			$consulta = "
			SELECT	a.numero,a.descripcion,a.tipo_cambio,a.vida_util,a.ufv,a.valor_compra,a.num_act,a.fecha,a.grupo_id,g.descripcion as desgru,a.act_id,g.grupo,a.fecha_baja
			FROM	activos a,grupoactivo g
			where   a.act_id='".$act_id."' and a.grupo_id=g.grupo_id 
			
			
			";
			}	
			}	
			
			
		
			//echo "<br>SQL: ".$consulta,"<br>";and a.estado=1
			$resultado = mysql_query($consulta) or die ('La consulta -listar gru- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			  			
      			if($row=mysql_fetch_array($resultado))
      			
      			{ 
      				
					$respuesta['numero'] = $row['numero'];
					$respuesta['descripcion']=$row['descripcion'];
					$respuesta['tipo_cambio'] = $row['tipo_cambio'];
					$respuesta['vida_util'] = $row['vida_util'];
					$respuesta['ufv'] = $row['ufv'];
					$respuesta['fecha']=$row['fecha'];					
					$respuesta['valor_compra'] = $row['valor_compra'];
					$respuesta['grupo_id'] = $row['grupo_id'];
					$respuesta['desgru']=$row['desgru'];
					$respuesta['act_id']=$row['act_id'];
					$respuesta['grupo']=$row['grupo'];
					$respuesta['fecha_baja']=$row['fecha_baja'];
					   
			 		$respuesta['TC_baja']=$tipo_cambio->buscar_fecha_tipocambio($row['fecha_baja'],1);	
					$respuesta['UFV_baja']=$tipo_cambio->buscar_fecha_tipocambio($row['fecha_baja'],2);	 
					
					$fecha_gestion=$gestion->fecha_gestion($gest);
					$fecha_ges_ini=$fecha_gestion['fecha_inicio'];
					$fecha_ges_fin=$fecha_gestion['fecha_fin'];
					 	
					
					
					if ($validar==1)
					{	
					 $respuesta['valor'] =($valor_dolar/$row['tipo_cambio'])* $row['valor_compra'];
					 $respuesta['valor1'] =($valor_dolar2/$row['tipo_cambio'])* $row['valor_compra'];
				     $respuesta['incremento']=$respuesta['valor1']-$respuesta['valor'];
					 $respuesta['valor_ufvantes']=$respuesta['valor']*($valor_ufv/$row['ufv']);
					
					 $respuesta['valor_intermedio']=$respuesta['valor']/$valor_ufv;
				     $respuesta['valor_ufv']=$respuesta['valor_intermedio']*$valor_ufv2;
					 $respuesta['incremento_ufv']=$respuesta['valor_ufv']-$respuesta['valor'];
					 
					 $respuesta['calculo']=($valor_dolar_ges/$row['tipo_cambio'])* $row['valor_compra'];
					 $respuesta['gestion']=$respuesta['calculo'];	
					          		 
					 
					  if ($depre==0)
						 {  if ($row['grupo_id']!=1) 
				           	{
									
						 	$respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*1;
							$respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
							$respuesta['incremento_depre']=$respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar-$respuesta['depre_acumulada'];
						    $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];
						  
						    $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
						  
							 $respuesta['depre_acumulada_ufv']=($row['valor_compra']/$row['vida_util'])*1;
							 $respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv']/$row['vida_util']*$meses_periodo);
							 $respuesta['incremento_depre_ufv']=$respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada_ufv'];
							 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
							 
							 
							  
							 $respuesta['neto']=$respuesta['valor_ufv']-$respuesta['depre_fin_ufv'];
				           	}
				           	else 
				           	{
					          $respuesta['depre_acumulada_ufv']=0;
							  $respuesta['depre_periodo_ufv']= 0;
							  $respuesta['incremento_depre_ufv']=0;
							  $respuesta['depre_fin_ufv']=0;	
							  $respuesta['vida_restante']=0;
							  $respuesta['neto']=$respuesta['valor_ufv'];
							  $respuesta['miterrenito']=0;
					        				
				           	}
						 
						 }
					
					 
					 if ($depre==1)
					   {
					   	if ($row['grupo_id']!=1) 
					   	{
								
						$respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
						$respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
						$respuesta['incremento_depre']=($respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar)-$respuesta['depre_acumulada'];
					    $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];
					   
					    $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
					    
					    $respuesta['depre_acumulada_ufv']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
						$respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv']/$row['vida_util']*$meses_periodo);
						$respuesta['incremento_depre_ufv']=$respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada_ufv'];
						$respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
					 	
						$respuesta['neto']=$respuesta['valor_ufv']-$respuesta['depre_fin_ufv'];
						
					   	}
					   	else 
				           	{
					          $respuesta['depre_acumulada_ufv']=0;
							  $respuesta['depre_periodoufv']= 0;
							  $respuesta['incremento_depre_ufv']=0;
							  $respuesta['depre_fin_ufv']=0;	
							  $respuesta['vida_restante']=0;
							  $respuesta['neto']=$respuesta['valor_ufv'];
					          		
				           	}
					    
					    
					    
					   }
					if ($depre==2)
						{ 
						if ($row['grupo_id']!=1) 
				         {
									
						 $respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
						 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util']*$meses_periodo)-1;
						 $respuesta['incremento_depre']=$respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar-$respuesta['depre_acumulada'];
						 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
						 
						 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
						 
						 $respuesta['depre_acumulada_ufv']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
						 $respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv']/$row['vida_util']*$meses_periodo)-1;
						 $respuesta['incremento_depre_ufv']=$respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada_ufv'];
						 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
						
						  $respuesta['neto']=$respuesta['valor_ufv']-$respuesta['depre_fin_ufv'];
				         }
				         else 
				           	{
					          $respuesta['depre_acumulada_ufv']=0;
							  $respuesta['depre_periodo_ufv']= 0;
							  $respuesta['incremento_depre_ufv']=0;
							  $respuesta['depre_fin_ufv']=0;	
							  $respuesta['vida_restante']=0;
							  $respuesta['neto']=$respuesta['valor_ufv'];
					         	
				           	}
					    
					   
						}
					
					 if($depre==3)
					 {
					 	
					 	
					 	
					 	if ($row['grupo_id']!=1) 
				         {
							
					 	 $respuesta['depre_acumulada']=$respuesta['valor']-1;
						 $respuesta['depre_periodo']= 0;
						 $respuesta['incremento_depre']=$respuesta['incremento'];
						 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
						
						 $respuesta['vida_restante']=0;
					 	
						 $respuesta['depre_acumulada_ufv']=$respuesta['valor_ufvantes']-1;
						 $respuesta['depre_periodo_ufv']= 0;
						 $respuesta['incremento_depre_ufv']= $respuesta['incremento_ufv'];
						 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
						
						 $respuesta['neto']=1;
						 					 
						  			 
						 
				         }
						 else 
				           	{
					          $respuesta['depre_acumulada_ufv']=0;
							  $respuesta['depre_periodo_ufv']= 0;
							  $respuesta['incremento_depre_ufv']=0;
							  $respuesta['depre_fin_ufv']=0;	
							  $respuesta['vida_restante']=0;
							  $respuesta['neto']=$respuesta['valor_ufv'];
					          		
				           	}
					    
					   
					 }
									 
					 
					 
					}
					else
					{ if ($validar==2)
					     { $respuesta['valor'] =0;
						   $respuesta['valor1'] =($valor_dolar2/$row['tipo_cambio'])* $row['valor_compra'];
						   $respuesta['incremento']=$respuesta['valor1']-$row['valor_compra'];
						
						 
						   $respuesta['valor_intermedio'] =$row['valor_compra']/$row['ufv'];
				    	   $respuesta['valor_ufv']=$respuesta['valor_intermedio']*$valor_ufv2;				
					    
				           $respuesta['incremento_ufv']=$respuesta['valor_ufv']-$respuesta['valor_compra'];
						   $respuesta['gestion']=0;	
					
				           
				            if ($depre==4)
								{
								if ($row['grupo_id']!=1) 
				         		{
							
								 $respuesta['depre_acumulada']=0;								 
								 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								 $respuesta['incremento_depre']=0;
								 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
								 
								 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
							
								 
								 $respuesta['depre_acumulada_ufv']=0;
								 $respuesta['depre_periodo_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_periodo;;
								 $respuesta['incremento_depre_ufv']=0;
								 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
								
							
								  $respuesta['neto']=$respuesta['valor_ufv']-$respuesta['depre_fin_ufv'];
				         		}
					         		else 
					           	{
						          $respuesta['depre_acumulada_ufv']=0;
								  $respuesta['depre_periodo_ufv']= 0;
								  $respuesta['incremento_depre_ufv']=0;
								  $respuesta['depre_fin_ufv']=0;	
								  $respuesta['vida_restante']=0;
								  $respuesta['neto']=$respuesta['valor_ufv'];
						        }
						    
									 
								}
							if ($depre==5)
							{   
								if ($row['grupo_id']!=1) 
				         		{
								 $respuesta['depre_acumulada']=0;
								 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								 $respuesta['incremento_depre']=0;
								 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
								//$respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];

								 $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
								
								 $respuesta['depre_acumulada_ufv']=0;
								 $respuesta['depre_periodo_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_periodo;;
								 $respuesta['incremento_depre_ufv']=0;
								 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
								
							     $respuesta['neto']=$respuesta['valor_ufv']-$respuesta['depre_fin_ufv'];
				         		}
							  else 
				           		{
					          $respuesta['depre_acumulada_ufv']=0;
							  $respuesta['depre_periodo_ufv']= 0;
							  $respuesta['incremento_depre_ufv']=0;
							  $respuesta['depre_fin_ufv']=0;	
							  $respuesta['vida_restante']=0;
							  $respuesta['neto']=$respuesta['valor_ufv'];
					          }
					    						
							} 
				                    
				           
				           
				           
				           
					     }
					    else 
					    { if ($validar==3)
					       {
					       	$respuesta['valor'] =($valor_dolar/$row['tipo_cambio'])* $row['valor_compra'];;
						    $respuesta['valor1'] =($valor_dolar2/$row['tipo_cambio'])* $row['valor_compra'];
						    $respuesta['incremento']=$respuesta['valor1']-$respuesta['valor'];
							
				    	    $respuesta['valor_ufv']=0;			
					        $respuesta['incremento_ufv']=0;
					        if ($row['grupo_id']!=1) 
				          	{
					          if ($depre==6)
							 {  $respuesta['depre_acumulada']=0;
								$respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								$respuesta['incremento_depre']=0;
							    $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];
							    $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
							 
							    $respuesta['neto']=$respuesta['valor']-$respuesta['depre_fin'];
												    
							 }
							
					 
							 if ($depre==7)
							   {
								$respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
								$respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								//$respuesta['porque']=$valor_dolar2/$valor_dolar;
								$respuesta['incremento_depre']=($respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar)-$respuesta['depre_acumulada'];
							    $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];
							    
							    $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
							    $respuesta['neto']=$respuesta['valor']-$respuesta['depre_fin'];
								
							   }
								if ($depre==8)
									{ 
									 $respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
									 $respuesta['depre_periodo']= $respuesta['valor1']/$row['vida_util']*$meses_periodo;
									 $respuesta['incremento_depre']=$respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar-$respuesta['depre_acumulada'];
									 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
									 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
									 $respuesta['restante']=$meses_periodo;
									 $respuesta['neto']=$respuesta['valor']-$respuesta['depre_fin'];
								
									}
								
								 if($depre==9)
								 {
								 	 $respuesta['depre_acumulada']=$respuesta['valor']-1;
									 $respuesta['depre_periodo']= 0;
									 $respuesta['incremento_depre']=$respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar-$respuesta['depre_acumulada'];
									 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
									 $respuesta['vida_restante']=0;
								 	 $respuesta['neto']=$respuesta['valor']-$respuesta['depre_fin'];
								
								 	
								 }
					       		 if ($depre==10)
								{
								 $respuesta['depre_acumulada']=0;
								 $respuesta['prueba']=($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								 $respuesta['incremento_depre']=0;
								 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
							  	 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
								 $respuesta['neto']=$respuesta['valor']-$respuesta['depre_fin'];
								
							
								}
								if ($depre==11)
								{    
								 $respuesta['depre_acumulada']=0;
								 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								 $respuesta['incremento_depre']=0;
								 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
								 $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
								 $respuesta['neto']=$respuesta['valor']-$respuesta['depre_fin'];
								
								} 
				          	}
					       else{
						       	$respuesta['depre_acumulada']=0;
								$respuesta['depre_periodo']= 0;
								$respuesta['incremento_depre']=0;
								$respuesta['depre_fin']=0;	
								$respuesta['vida_restante']=0;
								$respuesta['neto']=$respuesta['valor']-$respuesta['depre_fin'];
								
					       }
					       
					        
					        
					       
					       }
					    	else
					    	{ if ($validar==4)
					    		{
					    			 $respuesta['valor'] =($valor_dolar/$row['tipo_cambio'])* $row['valor_compra'];
						   		     $respuesta['valor_intermedio'] =$respuesta['valor']/$valor_dolar2;//recuper ufv

						   		     $respuesta['valor_ufv']=$respuesta['valor_intermedio']*$valor_ufv;				
					    			 $respuesta['valor_ufv2']=$respuesta['valor_ufv']*($valor_ufv2/$valor_ufv);	
				          			 $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];

				          			 $respuesta['calculo']=($valor_dolar/$row['tipo_cambio'])* $row['valor_compra'];
					          		 $respuesta['calculo_intermedio'] =$respuesta['calculo']/$valor_dolar2;//$valor_dolar2 es ufv
					          		 $respuesta['gestion']=$respuesta['calculo_intermedio']*$valor_ufv_ges;	
					          		 
					    		
				          	   	 
				          			 if ($depre==12)
									 { 
									 	if ($row['grupo_id']!=1) 
				          				{
									 	$respuesta['depre_acumulada_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*1;
										$respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv2']/$row['vida_util'])*$meses_periodo;
										$respuesta['incremento_depre_ufv']=$respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada_ufv'];
									    $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];
									    $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
									 	$respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
				          				}
					          			else 
					          			{
					          			
					          			$respuesta['depre_acumulada_ufv']=0;
									 	$respuesta['depre_periodo_ufv']= 0;
										$respuesta['incremento_depre_ufv']=0;
										$respuesta['depre_fin_ufv']=0;	
										$respuesta['vida_restante']=0;
										$respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
					          			
					          			
					          			}	 
					          			
					          				
									  }
								    if ($depre==13)
									{ 
										
										if ($row['grupo_id']!=1) 
				          				{					
										 $respuesta['depre_acumulada_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_anterior;
										 $respuesta['depre_periodo_ufv']= $respuesta['valor_ufv2']/$row['vida_util']*$meses_periodo;
										 $respuesta['incremento_depre_ufv']=$respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada_ufv'];
										 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
										 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
										 $respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
				          				}
				          				else 
					          			{
					          			
					          			$respuesta['depre_acumulada_ufv']=0;
									 	$respuesta['depre_periodo_ufv']= 0;
										$respuesta['incremento_depre_ufv']=0;
										$respuesta['depre_fin_ufv']=0;	
										$respuesta['vida_restante']=0;
										$respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
					          			
					          			
					          			}	 
				          				
				          				}	 
				          			 
								  if ($depre==14)
									{ 
									if ($row['grupo_id']!=1) 
				          				{	
																
										 $respuesta['depre_acumulada_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_anterior;
										 $respuesta['depre_periodo_ufv']= $respuesta['valor_ufv2']/$row['vida_util']*$meses_periodo-1;
										 $respuesta['incremento_depre_ufv']=$respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada_ufv'];
										 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
										 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
										 $respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
				          				}
				          				else 
					          			{
					          			
					          			$respuesta['depre_acumulada_ufv']=0;
									 	$respuesta['depre_periodo_ufv']= 0;
										$respuesta['incremento_depre_ufv']=0;
										$respuesta['depre_fin_ufv']=0;	
										$respuesta['vida_restante']=0;
										$respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
					          			
					          			
					          			}	 
				          				
									}	 
				          			if ($depre==15)
									{ 
										if ($row['grupo_id']!=1) 
					          			{		
																
										 $respuesta['depre_acumulada_ufv']=$respuesta['valor_ufv']-1;
										 $respuesta['depre_periodo_ufv']= 0;
										// Anamery cambio$respuesta['incremento_depre_ufv']=$respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada_ufv'];
										 $respuesta['incremento_depre_ufv']= $respuesta['incremento_ufv'];
										 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
										 $respuesta['vida_restante']=0;
										 $respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
					          			}
				          			else 
					          			{
					          			
					          			$respuesta['depre_acumulada_ufv']=0;
									 	$respuesta['depre_periodo_ufv']= 0;
										$respuesta['incremento_depre_ufv']=0;
										$respuesta['depre_fin_ufv']=0;	
										$respuesta['vida_restante']=0;
										$respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
					          			
					          			
					          			}	 
				          			}	 
				          			          			 
				          		 
				          			 
					    			
					    		} 
					    		else 
					    		{ if ($validar==5)
					    			{ 
					    			 	   if ($depre==16)
										{
										
										  $respuesta['valor_ufv']=0;				
						    			  $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
					          			  $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_compra'];
										 
										if ($row['grupo_id']!=1)
										{ 
										 $respuesta['vida_restante']=$row['vida_util']-1;
 								     	 $respuesta['depre_acumulada_ufv']=0;
										 $respuesta['depre_periodo_ufv']=($respuesta['valor_ufv2']/$row['vida_util'])*1;
										 $respuesta['incremento_depre_ufv']=0;
										 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
										 $respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];; 
										}
										else
										{
											$respuesta['depre_acumulada_ufv']=0;
										 	$respuesta['depre_periodo_ufv']= 0;
											$respuesta['incremento_depre_ufv']=0;
											$respuesta['depre_fin_ufv']=0;	
											$respuesta['vida_restante']=0;
											$respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
						          			
													
												} 
				          			
										 
										}
										
										if ($depre==17)
										{    
											
									    $respuesta['valor_ufv']=0;				
					    			    $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
				          			    $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$row['valor_compra'];
				          			  
										if ($row['grupo_id']!=1)
										{	
										 $respuesta['depre_acumulada_ufv']=0;
										 $respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv2']/$row['vida_util'])*$meses_periodo;
										 $respuesta['incremento_depre_ufv']=0;
										 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
										 $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
										
										 $respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
										}
										else
										{
											$respuesta['depre_acumulada_ufv']=0;
										 	$respuesta['depre_periodo_ufv']= 0;
											$respuesta['incremento_depre_ufv']=0;
											$respuesta['depre_fin_ufv']=0;	
											$respuesta['vida_restante']=0;
											$respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
						          			
									
				          			
										}
										
										  
										} 
										if ($depre==18)
										{ 
										 $respuesta['valor_ufv']=($valor_ufv/$row['ufv'])*$row['valor_compra'];				
					    			   	 $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
				          			   	 $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];

				          			   	
																
										 $respuesta['depre_acumulada_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_anterior;
										 $respuesta['depre_periodo_ufv']= $respuesta['valor_ufv2']/$row['vida_util']*$meses_periodo;
										 $respuesta['incremento_depre_ufv']=$respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada_ufv'];
										 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
										 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
										 
										 $respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];

										 if ($respuesta['fecha'] < $fecha_ges_ini)
										 {
										 	$respuesta['gestion']=($valor_ufv_ges/$row['ufv'])*$row['valor_compra'];
										 
										 }
												
										}	
										
										if ($depre==19)
										{ 
																						
										 $respuesta['valor_ufv']=($valor_ufv/$row['ufv'])*$row['valor_compra'];				
					    			   	 $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
				          			   	 $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];
				          				
										
															
										 $respuesta['depre_acumulada_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_anterior;
										 $respuesta['depre_periodo_ufv']= $respuesta['valor_ufv2']/$row['vida_util']*$meses_periodo-1;
										 $respuesta['incremento_depre_ufv']=$respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada_ufv'];
										 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
										 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
										 
										 $respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
										 
										 $respuesta['gestion']=($valor_ufv_ges/$row['ufv'])*$row['valor_compra'];
										 
										
										}	 
						          		if ($depre==20)
						          		{
						          				
						          		 $respuesta['valor_ufv']=($valor_ufv/$row['ufv'])*$row['valor_compra'];				
					    			   	 $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
				          			   	 $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];
				          					
														
										 $respuesta['depre_acumulada_ufv']=$respuesta['valor_ufv']-1;
										 $respuesta['depre_periodo_ufv']= 0;
										// $respuesta['incremento_depre_ufv']=$respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada_ufv'];
										 $respuesta['incremento_depre_ufv']= $respuesta['incremento_ufv'];
										$respuesta['depre_fin_ufv']=$respuesta['depre_acumulada_ufv']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depre_ufv'];	
										 $respuesta['vida_restante']=0;
										
										 $respuesta['neto']=$respuesta['valor_ufv2']-$respuesta['depre_fin_ufv'];
										 
										 $respuesta['gestion']=($valor_ufv_ges/$row['ufv'])*$row['valor_compra'];
										
										}	
				          			  
				          			  
				          			  
					    		    }	
					    			
					    		    else 
					    		    {
					    		    	if ($validar==6)
					    				{
					    					$respuesta['valor'] =($valor_dolar/$row['tipo_cambio'])* $row['valor_compra'];
					 					 	$respuesta['valor1'] =($valor_dolar2/$row['tipo_cambio'])* $row['valor_compra'];
				    						$respuesta['incremento']=$respuesta['valor1']-$respuesta['valor'];
					
											
				     						$respuesta['valor_ufv']=$respuesta['valor1']/$row['ufv'];
				     						$respuesta['valor_ufv2']=$valor_ufv2*$respuesta['valor_ufv'];
											$respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];
											
											 if ($depre==0)
											 {  $respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*1;
												$respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
												$respuesta['incremento_depre']=$respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar-$respuesta['depre_acumulada'];
											    $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];
											  
											    $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
											  
												 $respuesta['depre_acumulada_ufv']=($respuesta['valor_intermedio']/$row['vida_util'])*1;
												 $respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv']/$row['vida_util']*$meses_periodo);
												 $respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];
												 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
											}
										
										 
										 if ($depre==1)
										   {
											$respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
											$respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
											$respuesta['incremento_depre']=($respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar)-$respuesta['depre_acumulada'];
										    $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];
										   
										    $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
										    
										    $respuesta['depre_acumulada_ufv']=($respuesta['valor_intermedio']/$row['vida_util'])*$meses_anterior;
											$respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv']/$row['vida_util']*$meses_periodo);
											$respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];
											$respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
																					    
										    
										   }
										if ($depre==2)
											{ 
											 $respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
											 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util']*$meses_periodo)-1;
											 $respuesta['incremento_depre']=$respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar-$respuesta['depre_acumulada'];
											 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
											 
											 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
											 
											 $respuesta['depre_acumulada_ufv']=($respuesta['valor_intermedio']/$row['vida_util'])*$meses_anterior;
											 $respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv']/$row['vida_util']*$meses_periodo)-1;
											 $respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];
											 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
											
											 
											 
											}
										
										 if($depre==3)
										 {
										 	 $respuesta['depre_acumulada']=$respuesta['valor']-1;
											 $respuesta['depre_periodo']= 0;
											 $respuesta['incremento_depre']=$respuesta['incremento'];
											 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
											
											 $respuesta['vida_restante']=0;
										 	
											 $respuesta['depre_acumulada_ufv']=$respuesta['valor_ufv']-1;
											 $respuesta['depre_periodo_ufv']= 0;
											 $respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];
											 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
											
										 	
										 }
					
											
											
					    		        }
					    		    	else
					    		    	 {
					    		    	 	
					    		    	 	 $respuesta['valor']=0;
					    		    	 	 $respuesta['valor1']=0;
					    		    	 	 $respuesta['incremento']=0;
					    		    		 $respuesta['valor_ufv']=($valor_ufv/$row['ufv'])*$row['valor_compra'];				
					    					 $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
				          			 		 $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];
				          			 		 
				          			 		    if ($depre==4)
												{
												 $respuesta['depre_acumulada']=0;								 
												 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
												 $respuesta['incremento_depre']=0;
												 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
												 
												 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
											
												 
												 $respuesta['depre_acumulada_ufv']=0;
												 $respuesta['depre_periodo_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_periodo;;
												 $respuesta['incremento_depreufv']=0;
												 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
												
											
												 
												 
												 
												}
											if ($depre==5)
											{    $respuesta['depre_acumulada']=0;
												 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
												 $respuesta['incremento_depre']=0;
												 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
												// $respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];
				
												 $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
												
												 $respuesta['depre_acumulada_ufv']=0;
												 $respuesta['depre_periodo_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_periodo;;
												 $respuesta['incremento_depreufv']=0;
												 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
												
											} 
								          			 		 
								          			
								    		  
					    		    	 }
					    		    		
					    		    }
					    		    
					    		    
					    		    
					    		    
					    			
					    		}
					    	
					    		
					    		
					    	}
					    		
					    				
					    	}
					    
					
					    
					    }
					    
									   
										  
				return $respuesta;	
						
					}
      			
      			
      			else
      			 {
      				return false;
				}	
			
			
		
		}
	}	
    } 
/*consulta que muestar solo el gasto del activo */    
function listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria)
    {	$grupo= new Grupo();
		$tipo_cambio=new TipoCambio();
    	
    	$con = new Conexion;
		if($con->conectar() == true){
			if ($localizacion!=''&& $locsecundaria!='')
			{$consulta = "
			SELECT	a.numero,a.descripcion,a.tipo_cambio,a.vida_util,a.ufv,a.valor_compra,a.num_act,a.fecha,a.grupo_id,g.descripcion as desgru,a.fecha_baja
			FROM	activos a,grupoactivo g
			where a.grupo_id='".$grupo_id."' and a.act_id='".$act_id."' and a.grupo_id=g.grupo_id and a.localizacion='".$localizacion."' and a.locsecundaria='".$locsecundaria."' 
			
			
			";
			}
			else {
			if (
			$grupo_id!='')	
			{$consulta = "
			SELECT	a.numero,a.descripcion,a.tipo_cambio,a.vida_util,a.ufv,a.valor_compra,a.num_act,a.fecha,a.grupo_id,g.descripcion as desgru,a.fecha_baja
			FROM	activos a,grupoactivo g
			where a.grupo_id='".$grupo_id."' and a.act_id='".$act_id."' and a.grupo_id=g.grupo_id 
			
			
			";
			
			}	
			else{
			
			$consulta = "
			SELECT	a.numero,a.descripcion,a.tipo_cambio,a.vida_util,a.ufv,a.valor_compra,a.num_act,a.fecha,a.grupo_id,g.descripcion as desgru,a.act_id,g.grupo,a.fecha_baja
			FROM	activos a,grupoactivo g
			where   a.act_id='".$act_id."' and a.grupo_id=g.grupo_id 
			
			
			";
			}
			}
			//echo "<br>SQL: ".$consulta,"<br>";
			$resultado = mysql_query($consulta) or die ('La consulta -listar gru- fall&oacute;: ' . mysql_error());
			
			if (!$resultado)
				return false;
			else {
			  			
      			if($row=mysql_fetch_array($resultado))
      			
      			{ 
      				
					$respuesta['numero'] = $row['numero'];
					$respuesta['descripcion']=$row['descripcion'];
					$respuesta['tipo_cambio'] = $row['tipo_cambio'];
					$respuesta['vida_util'] = $row['vida_util'];
					$respuesta['ufv'] = $row['ufv'];
					$respuesta['fecha']=$row['fecha'];
					$respuesta['fecha_baja']=$row['fecha_baja'];				
					$respuesta['valor_compra'] = $row['valor_compra'];
					$respuesta['grupo_id'] = $row['grupo_id'];
					$respuesta['desgru']=$row['desgru'];
					$respuesta['act_id']=$row['act_id'];
					$respuesta['grupo']=$row['grupo'];
					
					if ($validar==1)
					{	
					 $respuesta['valor'] =($valor_dolar/$row['tipo_cambio'])* $row['valor_compra'];
					 $respuesta['valor1'] =($valor_dolar2/$row['tipo_cambio'])* $row['valor_compra'];
				     $respuesta['incremento']=$respuesta['valor1']-$respuesta['valor'];
					 $respuesta['valor_ufvantes']=$respuesta['valor']*($valor_ufv/$row['ufv']);
					
					 $respuesta['valor_intermedio']=$respuesta['valor']/$valor_ufv;
				     $respuesta['valor_ufv']=$respuesta['valor_intermedio']*$valor_ufv2;
					 $respuesta['incremento_ufv']=$respuesta['valor_ufv']-$respuesta['valor'];
					 
					  if ($depre==0)
						 { if ($row['grupo_id']!=1) 
				          	{	
				          		$respuesta['depre_acumulada']=0;
								$respuesta['depre_periodo']=0;
								$respuesta['incremento_depre']=0;
									   		
				          				
					          	$respuesta['depre_acumulada_ufv']=0;
								$respuesta['depre_periodo_ufv']= 0;
								$respuesta['incremento_depre_ufv']=0;
										
							  }
				          	else 
					        {
					          			
					        	$respuesta['depre_acumulada']=0;
								$respuesta['depre_periodo']=0;
								$respuesta['incremento_depre']=0;
								   	
					        	$respuesta['depre_acumulada_ufv']=0;
							 	$respuesta['depre_periodo_ufv']= 0;
								$respuesta['incremento_depre_ufv']=0;
										
					          			
					       	}
							 
							 
						 }
					
					 
					 if ($depre==1)
					   {
												
					    if ($row['grupo_id']!=1) 
				          {	
				          	$respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
							$respuesta['depre_periodo']= $respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar;
							$respuesta['incremento_depre']=$respuesta['depre_periodo']-$respuesta['depre_acumulada'];
										    
				          									
							$respuesta['depre_acumulada_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_anterior;
							$respuesta['depre_periodo_ufv']= $respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv;
							$respuesta['incremento_depre_ufv']=$respuesta['depre_periodo_ufv']-$respuesta['depre_acumulada_ufv'];
									
						    }
				        else 
					    	{
					        $respuesta['depre_acumulada']=0;
							$respuesta['depre_periodo']=0;
							$respuesta['incremento_depre']=0;
											
					         $respuesta['depre_acumulada_ufv']=0;
							 $respuesta['depre_periodo_ufv']= 0;
							 $respuesta['incremento_depre_ufv']=0;
					        }
					    
					    
					    
					   }
					if ($depre==2)
						{ 
						 
						 if ($row['grupo_id']!=1) 
				        {	
							$respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
							$respuesta['depre_periodo']= $respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar;
							$respuesta['incremento_depre']=$respuesta['depre_periodo']-$respuesta['depre_acumulada'];
																
										
							 $respuesta['depre_acumulada_ufv']= $respuesta['valor_ufv']/$row['vida_util']*$meses_anterior;
							 $respuesta['depre_periodo_ufv']= $respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv;
							 $respuesta['incremento_depre_ufv']=$respuesta['depre_periodo_ufv']-$respuesta['depre_acumulada_ufv'];
							 
				         }
				         else 
					    {   
					    	$respuesta['depre_acumulada']=0;
							$respuesta['depre_periodo']=0;
							$respuesta['incremento_depre']=0;
								
					          			
					    	$respuesta['depre_acumulada_ufv']=0;
						 	$respuesta['depre_periodo_ufv']= 0;
							$respuesta['incremento_depre_ufv']=0;
										
					    }	 
						}
					
					 if($depre==3)
					 {
					 	 
						 if ($row['grupo_id']!=1) 
					    {$respuesta['depre_acumulada']=0;
						 $respuesta['depre_periodo']= 0;
						 $respuesta['incremento_depre']=0;
						 		
															
						 $respuesta['depre_acumulada_ufv']=0;
						 $respuesta['depre_periodo_ufv']= 0;
						 $respuesta['incremento_depre_ufv']=0;
						 }
				       	else 
					    {
					     $respuesta['depre_acumulada']=0;
						 $respuesta['depre_periodo']=0;
						 $respuesta['incremento_depre']=0;
								    			
					     $respuesta['depre_acumulada_ufv']=0;
						 $respuesta['depre_periodo_ufv']= 0;
						 $respuesta['incremento_depre_ufv']=0;
										
					          			
					  	}
					 }
									 
					 
					 
					}
					else
					{ 
						if ($validar==2)
					     { $respuesta['valor'] =0;
						   $respuesta['valor1'] =($valor_dolar2/$row['tipo_cambio'])* $row['valor_compra'];
						   $respuesta['incremento']=$respuesta['valor1']-$row['valor_compra'];
						
						 
						   $respuesta['valor_intermedio'] =$row['valor_compra']/$row['ufv'];
				    	   $respuesta['valor_ufv']=$respuesta['valor_intermedio']*$valor_ufv2;				
					    
				           $respuesta['incremento_ufv']=$respuesta['valor_ufv']-$respuesta['valor_compra'];
						   $respuesta['gestion']=0;	
					
						 			
						  
				           
				            if ($depre==4)
								{
								if ($row['grupo_id']!=1) 
				         		{
							
								 $respuesta['depre_acumulada']=0;								 
								 $respuesta['depre_periodo']= 0;
						  		 $respuesta['incremento_depre']=0;
						
								 
								 $respuesta['depre_acumulada_ufv']=0;
								 $respuesta['depre_periodo_ufv']=0;
								 $respuesta['incremento_depre_ufv']=0;
								
							
								  
				         		}
					         		else 
					           	{
						          $respuesta['depre_acumulada_ufv']=0;
								  $respuesta['depre_periodo_ufv']= 0;
								  $respuesta['incremento_depre_ufv']=0;
								  
								  
						        }
						    
				            
							              
				            
					     }
					     }
					    else 
					    { if ($validar==3)
					       {
					       	$respuesta['valor'] =($valor_dolar/$row['tipo_cambio'])* $row['valor_compra'];;
						    $respuesta['valor1'] =($valor_dolar2/$row['tipo_cambio'])* $row['valor_compra'];
						    $respuesta['incremento']=$respuesta['valor1']-$respuesta['valor'];
							
				    	    $respuesta['valor_ufv']=0;			
					        $respuesta['incremento_ufv']=0;
					          if ($depre==6)
							 {  $respuesta['depre_acumulada']=0;
								$respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								$respuesta['incremento_depre']=0;
							    $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];
							    $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
							 	
							   
												    
							 }
							
					 
							 if ($depre==7)
							   {
								$respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
								$respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								//$respuesta['porque']=$valor_dolar2/$valor_dolar;
								$respuesta['incremento_depre']=($respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar)-$respuesta['depre_acumulada'];
							    $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];
							    
							    $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
							   
							   }
								if ($depre==8)
									{ 
									 $respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
									 $respuesta['depre_periodo']= $respuesta['valor1']/$row['vida_util']*$meses_periodo;
									 $respuesta['incremento_depre']=$respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar-$respuesta['depre_acumulada'];
									 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
									 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
									 $respuesta['restante']=$meses_periodo;
									}
								
								 if($depre==9)
								 {
								 	 $respuesta['depre_acumulada']=$respuesta['valor']-1;
									 $respuesta['depre_periodo']= 0;
									 $respuesta['incremento_depre']=$respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar-$respuesta['depre_acumulada'];
									 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
									 $respuesta['vida_restante']=0;
								 	
								 	
								 }
					       		 if ($depre==10)
								{
								 $respuesta['depre_acumulada']=0;
								 $respuesta['prueba']=($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								 $respuesta['incremento_depre']=0;
								 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
							  	 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
								
			
								}
								if ($depre==11)
								{    
								 $respuesta['depre_acumulada']=0;
								 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
								 $respuesta['incremento_depre']=0;
								 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
								 $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
								 
								} 
				           
					        
					        
					        
					        
					       
					       }
					    	else
					    	{ if ($validar==4)
					    		{
					    			 $respuesta['valor'] =($valor_dolar/$row['tipo_cambio'])* $row['valor_compra'];
						   		     $respuesta['valor_intermedio'] =$respuesta['valor']/$valor_dolar2;//recuper ufv

						   		     $respuesta['valor_ufv']=$respuesta['valor_intermedio']*$valor_ufv;				
					    			 $respuesta['valor_ufv2']=$respuesta['valor_ufv']*($valor_ufv2/$valor_ufv);	
				          			 $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];
				          			 
				          			 $respuesta['calculo']=($valor_dolar/$row['tipo_cambio'])* $row['valor_compra'];
				          			 $respuesta['calculo_intermedio'] =$respuesta['calculo']/$valor_dolar2;
				          			
				          	   	 
				          			 if ($depre==12)
									 { 
									 	if ($row['grupo_id']!=1) 
				          				{
									 	$respuesta['depre_acumulada_ufv']=0;
										$respuesta['depre_periodo_ufv']= 0;
										$respuesta['incremento_depre_ufv']=0;
									    	
				          				}
					          			else 
					          			{
					          			
					          			$respuesta['depre_acumulada_ufv']=0;
									 	$respuesta['depre_periodo_ufv']= 0;
										$respuesta['incremento_depre_ufv']=0;
										
					          			
					          			}	 
					          			
					          				
									  }
								    if ($depre==13)
									{ 
										
										if ($row['grupo_id']!=1) 
				          				{					
										 $respuesta['depre_acumulada_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_anterior;
										 $respuesta['depre_periodo_ufv']= $respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv;
										 $respuesta['incremento_depre_ufv']=$respuesta['depre_periodo_ufv']-$respuesta['depre_acumulada_ufv'];
									
									    }
				          				else 
					          			{
					          			
					          			$respuesta['depre_acumulada_ufv']=0;
									 	$respuesta['depre_periodo_ufv']= 0;
										$respuesta['incremento_depre_ufv']=0;
										
					          			
					          			}	 
				          				
				          				}	 
				          			 
								  if ($depre==14)
									{ 
									if ($row['grupo_id']!=1) 
				          				{	
																
										
										 $respuesta['depre_acumulada_ufv']= $respuesta['valor_ufv']/$row['vida_util']*$meses_anterior;
										 $respuesta['depre_periodo_ufv']= $respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv;
										 $respuesta['incremento_depre_ufv']=$respuesta['depre_periodo_ufv']-$respuesta['depre_acumulada_ufv'];
									     $respuesta['cualquiera']=$meses_anterior;
				          				}
				          				else 
					          			{
					          			
					          			$respuesta['depre_acumulada_ufv']=0;
									 	$respuesta['depre_periodo_ufv']= 0;
										$respuesta['incremento_depre_ufv']=0;
										
					          			}	 
				          				
									}	 
				          			if ($depre==15)
									{ 
										if ($row['grupo_id']!=1) 
					          			{		
																
										 $respuesta['depre_acumulada_ufv']=0;
										 $respuesta['depre_periodo_ufv']= 0;
										 $respuesta['incremento_depre_ufv']=0;
										 }
				          			else 
					          			{
					          			
					          			$respuesta['depre_acumulada_ufv']=0;
									 	$respuesta['depre_periodo_ufv']= 0;
										$respuesta['incremento_depre_ufv']=0;
										
					          			
					          			}	 
				          			}	 
				          			          			 
				          		 
				          			 
					    			
					    		} 
					    		else 
					    		{ if ($validar==5)
					    			{ 
					    			 	   if ($depre==16)
										{
										
										  $respuesta['valor_ufv']=0;				
						    			  $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
					          			  $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_compra'];
										 
										if ($row['grupo_id']!=1)
										{ 
										 $respuesta['depre_acumulada_ufv']=0;
										 $respuesta['depre_periodo_ufv']=0;
										 $respuesta['incremento_depreufv']=0;
										 
										}
										else
										{
											$respuesta['depre_acumulada_ufv']=0;
										 	$respuesta['depre_periodo_ufv']= 0;
											$respuesta['incremento_depre_ufv']=0;
													
												} 
				          			
										 
										}
										
										if ($depre==17)
										{    
											
									    $respuesta['valor_ufv']=0;				
					    			    $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
				          			    $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$row['valor_compra'];
				          			  
										if ($row['grupo_id']!=1)
										{	
										 						
										 $respuesta['depre_acumulada_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_anterior;
										 $respuesta['depre_periodo_ufv']= $respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv;
										 $respuesta['incremento_depre_ufv']=$respuesta['depre_periodo_ufv']-$respuesta['depre_acumulada_ufv'];
										 
										}
										else
										{
											$respuesta['depre_acumulada_ufv']=0;
										 	$respuesta['depre_periodo_ufv']= 0;
											$respuesta['incremento_depre_ufv']=0;
											
									
				          			
										}
										
										  
										} 
										 if ($depre==18)
										{ 
										 $respuesta['valor_ufv']=($valor_ufv/$row['ufv'])*$row['valor_compra'];				
					    			   	 $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
				          			   	 $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];
	
				          			   	
																
										 $respuesta['depre_acumulada_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_anterior;
										 $respuesta['depre_periodo_ufv']= $respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv;
										 $respuesta['incremento_depre_ufv']=$respuesta['depre_periodo_ufv']-$respuesta['depre_acumulada_ufv'];
										
												
										}	
										
										  if ($depre==19)
										{ 
																						
										 $respuesta['valor_ufv']=($valor_ufv/$row['ufv'])*$row['valor_compra'];				
					    			   	 $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
				          			   	 $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];
				          				 $respuesta['mesanterio']=$meses_anterior;
										
															
										 $respuesta['depre_acumulada_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_anterior;
										 $respuesta['depre_periodo_ufv']= $respuesta['depre_acumulada_ufv']*$valor_ufv2/$valor_ufv;
										 $respuesta['incremento_depre_ufv']= $respuesta['depre_periodo_ufv']-$respuesta['depre_periodo_ufv'];
										 
										
										}	 
						          		if ($depre==20)
						          		{
						          				
						          		 $respuesta['valor_ufv']=($valor_ufv/$row['ufv'])*$row['valor_compra'];				
					    			   	 $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
				          			   	 $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];
				          					
														
										 $respuesta['depre_acumulada_ufv']=0;
										 $respuesta['depre_periodo_ufv']= 0;
										 $respuesta['incremento_depre_ufv']=0;
										
										}	
				          			  
				          			  
				          			  
					    		    }	
					    			
					    		    else 
					    		    {
					    		    	if ($validar==6)
					    				{
					    					$respuesta['valor'] =($valor_dolar/$row['tipo_cambio'])* $row['valor_compra'];
					 					 	$respuesta['valor1'] =($valor_dolar2/$row['tipo_cambio'])* $row['valor_compra'];
				    						$respuesta['incremento']=$respuesta['valor1']-$respuesta['valor'];
					
											
				     						$respuesta['valor_ufv']=$respuesta['valor1']/$row['ufv'];
				     						$respuesta['valor_ufv2']=$valor_ufv2*$respuesta['valor_ufv'];
											$respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];
											
											 if ($depre==0)
											 {  $respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*1;
												$respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
												$respuesta['incremento_depre']=$respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar-$respuesta['depre_acumulada'];
											    $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];
											  
											    $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
											  
												 $respuesta['depre_acumulada_ufv']=($respuesta['valor_intermedio']/$row['vida_util'])*1;
												 $respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv']/$row['vida_util']*$meses_periodo);
												 $respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];
												 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
											}
										
										 
										 if ($depre==1)
										   {
											$respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
											$respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
											$respuesta['incremento_depre']=($respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar)-$respuesta['depre_acumulada'];
										    $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];
										   
										    $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
										    
										    $respuesta['depre_acumulada_ufv']=($respuesta['valor_intermedio']/$row['vida_util'])*$meses_anterior;
											$respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv']/$row['vida_util']*$meses_periodo);
											$respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];
											$respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
																					    
										    
										   }
										if ($depre==2)
											{ 
											 $respuesta['depre_acumulada']=($respuesta['valor']/$row['vida_util'])*$meses_anterior;
											 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util']*$meses_periodo)-1;
											 $respuesta['incremento_depre']=$respuesta['depre_acumulada']*$valor_dolar2/$valor_dolar-$respuesta['depre_acumulada'];
											 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
											 
											 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
											 
											 $respuesta['depre_acumulada_ufv']=($respuesta['valor_intermedio']/$row['vida_util'])*$meses_anterior;
											 $respuesta['depre_periodo_ufv']= ($respuesta['valor_ufv']/$row['vida_util']*$meses_periodo)-1;
											 $respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];
											 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
											
											 
											 
											}
										
										 if($depre==3)
										 {
										 	 $respuesta['depre_acumulada']=$respuesta['valor']-1;
											 $respuesta['depre_periodo']= 0;
											 $respuesta['incremento_depre']=$respuesta['incremento'];
											 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
											
											 $respuesta['vida_restante']=0;
										 	
											 $respuesta['depre_acumulada_ufv']=$respuesta['valor_ufv']-1;
											 $respuesta['depre_periodo_ufv']= 0;
											 $respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];
											 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
											
										 	
										 }
					
											
											
					    		        }
					    		    	else
					    		    	 {
					    		    	 	
					    		    	 	 $respuesta['valor']=0;
					    		    	 	 $respuesta['valor1']=0;
					    		    	 	 $respuesta['incremento']=0;
					    		    		 $respuesta['valor_ufv']=($valor_ufv/$row['ufv'])*$row['valor_compra'];				
					    					 $respuesta['valor_ufv2']=($valor_ufv2/$row['ufv'])*$row['valor_compra'];	
				          			 		 $respuesta['incremento_ufv']=$respuesta['valor_ufv2']-$respuesta['valor_ufv'];
				          			 		 
				          			 		    if ($depre==4)
												{
												 $respuesta['depre_acumulada']=0;								 
												 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
												 $respuesta['incremento_depre']=0;
												 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
												 
												 $respuesta['vida_restante']=$row['vida_util']-($meses_anterior+$meses_periodo);
											
												 
												 $respuesta['depre_acumulada_ufv']=0;
												 $respuesta['depre_periodo_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_periodo;;
												 $respuesta['incremento_depreufv']=0;
												 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
												
											
												 
												 
												 
												}
											if ($depre==5)
											{    $respuesta['depre_acumulada']=0;
												 $respuesta['depre_periodo']= ($respuesta['valor1']/$row['vida_util'])*$meses_periodo;
												 $respuesta['incremento_depre']=0;
												 $respuesta['depre_fin']=$respuesta['depre_acumulada']+$respuesta['depre_periodo']+$respuesta['incremento_depre'];	
												// $respuesta['incremento_depreufv']=$respuesta['depre_acumulada']*$valor_ufv2/$valor_ufv-$respuesta['depre_acumulada'];
				
												 $respuesta['vida_restante']=$row['vida_util']-$meses_periodo;
												
												 $respuesta['depre_acumulada_ufv']=0;
												 $respuesta['depre_periodo_ufv']=($respuesta['valor_ufv']/$row['vida_util'])*$meses_periodo;;
												 $respuesta['incremento_depreufv']=0;
												 $respuesta['depre_fin_ufv']=$respuesta['depre_acumulada']+$respuesta['depre_periodo_ufv']+$respuesta['incremento_depreufv'];	
												
											} 
								          			 		 
								          			
				          			 		 
				          			 		 
				          			 		 
				          			 		 
				          			 		 
				          			 		 
				          			 		 
				          			 		 
				          			 		 
				          			 		 
				          			 		 
					    		  
					    		    	 }
					    		    		
					    		    }
					    		    
					    		    
					    		    
					    		    
					    			
					    		}
					    	
					    		
					    		
					    	}
					    		
					    				
					    	}
					    
					
					    
					    }
					    
									   
										  
				return $respuesta;	
						
					}
      			
      			
      			else
      			 {
      				return false;
				}	
			
			
		
		}
	}	
    } 
/*Calcula la actualizacion desde el momneto de compra,
 los parametros de calculo para la depresiacion se toma desde el momento de alta del activo
 Este utiliza listar_activo_todo*/
function calcular_actualizacion_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion_ante,$gest)
  {    
  	$activo= new Activo();
	$grupo= new Grupo();
	$tipo_cambio=new TipoCambio();
	$acttodo= new ActualizacionTodo();			
  	$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
 	 
 	if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
 	{   $grupo= new Grupo();
		$tipo_cambio=new TipoCambio();
		$activo= new Activo();
		$gestion= new Gestion();
					      
		$fecha_gestion=$gestion->fecha_gestion($gest);
		$fecha_ges_ini=$fecha_gestion['fecha_inicio'];
		$fecha_ges_fin=$fecha_gestion['fecha_fin'];
					 	 					
 
		$fecha_act=$grupo->buscar_fecha_compra_todo();
									
		foreach($fecha_act as $indice => $valor) 
		{ $descripcion=$valor['descripcion'];
		  if ($valor['fecha_baja']== '' ||  $valor['fecha_baja'] > $fecha_fin)     			
			{
				
		     if ($valor['fecha'] <= $fecha_inter)
			  {	  
							
			       			$act_id=$valor['act_id'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
																					
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							$fe_compra=$activo->Primerdiames($fecha_compra);
							$meses_an=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
			  	   			$meses_anterior=$meses_an;							
						    					 		
					 		
					 		
	  	   			 		$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
	  	   			 		
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=1;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/**************/
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							
					
							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
					
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								
								$depre=0;
								$lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
								
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    {  
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		  
							    	
									if($totalmeses < $vida )
									 {  /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
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
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										    $depre=2;
										  	$lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
										   
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						 						
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
							
							
							
							
							/******************/
						//	$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
													
							
						    
							$resp[$descripcion][]=$lista_activo;	
							
			    			
						}
						else 
						{
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
						
							
						  if ($fecha_compra > $fecha_inter)
						  { 
						    $valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);
							$validar=2;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/**************/
							
	  	   			 		$meses_periodo=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
					 		$fe_compra=$activo->Primerdiames($fecha_compra);
							$meses_anterior=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
	  	   			 		
	  	   			 		
	  	   			 		
							$salida=date('m', strtotime($fecha_inicio));
							
							$salida1=date('m', strtotime($fecha_compra));
							$año=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año1=date('Y',strtotime($fecha_fin));
							
							
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							
							
							
							$mesmedio=$activo->diferencia_fechas_meses($fe_compra,$fecha_fin); 
							
							  if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								    $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
									
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
							  							    
								 		}
								 	else{
									
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
				 if ($fecha_inicio < $fecha_inter && $fecha_fin <= $fecha_inter)
				   {
				   	$grupo= new Grupo();
					$tipo_cambio=new TipoCambio();
					$activo= new Activo();
					
				   $fecha_act=$grupo->buscar_fecha_compra_todo();
									
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							/*en este caso la fecjha de compar siempre lo llevo al primer dia del mes porque se toma todo el mes*/
							$fe_compra=$activo->Primerdiames($fecha_compra);
							
							$meses_an=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
			  	   			$meses_anterior=$meses_an;							
						     
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=3;
							$valor_ufv=0;//valor en ufv al 31-03-2007
							$valor_ufv2=0;//valor al final del reporte
							/*********************/
							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año2=date('Y',strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=6;
								$lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						
							}
						else{
							 if ($salida1==$salida2 && $año1=$año2 )
							   {  //si su vida comienza en el la fecha fin
							     $depre=10;						  
							     $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						
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
											     $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=7; 			 	
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
												 	
												   $depre=8;
												   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													  $depre=9;
													  $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
							}
				
						}	
							
							
							
							
							
							/**************************/
						//	$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$descripcion][]=$lista_activo;
							
							
							/*echo "<pre>";
							print_r($resp);
							echo "</pre>";
							*/
						}
						else 
						{
						    $lista_activo=0;
							
							
						}
						if ($lista_activo!=0)
						{
						    $resp[$descripcion][]=$lista_activo;
						}		
						
						
					}
					return $resp;
				
				   }
				   else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	  {  $grupo= new Grupo();
						 $tipo_cambio=new TipoCambio();
						 $activo= new Activo();
					     $gestion= new Gestion();
					      
			 			 $fecha_gestion=$gestion->fecha_gestion($gest);
					 	 $fecha_ges_ini=$fecha_gestion['fecha_inicio'];
					 	 $fecha_ges_fin=$fecha_gestion['fecha_fin'];
					 	 
					 	
				   	     $fecha_act=$grupo->buscar_fecha_compra_todo();
					 	 
				   	     
				   	     
				  foreach($fecha_act as $indice => $valor)
				  {
				  	if ($valor['fecha_baja']== '' ||  $valor['fecha_baja'] > $fecha_fin)     			
				  	{
				  
					  if ($valor['fecha'] <= $fecha_inter )
						{   							
							
							$descripcion=$valor['descripcion'];
							$act_id=$valor['act_id']."<br>";
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
							/*en este caso la fecjha de compar siempre lo llevo al primer dia del mes porque se toma todo el mes*/
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
									   { $mesmedio =$vida-$meses_anterior;

									   	/*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
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
							
							
							
							
							
							
							/*****************/
							//$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$descripcion][]=$lista_activo;
							
																
							
			    			
						}
						else 
						{
						  
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							 $fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
						
							
							if  ($fecha_compra > $fecha_inter)
							{
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=5;
							
							/************/
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
								
								$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
								$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
								
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
				
							
							
							/************/
						//	$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
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
				   	/*else 
				   	{ 
				   		if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   	    {   
				   	    	$grupo= new Grupo();
							$tipo_cambio=new TipoCambio();
							$activo= new Activo(); 
				   	    	$fecha_act=$grupo->buscar_fecha_compra_todo();
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
										
						foreach($fecha_act as $indice => $valor) 
						{
						   if ($valor['fecha'] <= $fecha_inter)
						   {
							$descripcion=$valor['descripcion'];
							$fecha_compra=$valor['fecha'];
							$act_id=$valor['act_id'];
							$vida=$valor['vida_util'];
                            
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							$validar=6;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
								
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
								$depre=0;
								$lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
								
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 { 
										 $depre=1; 	
											 	
										 $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						 				
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
						
							//$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							//echo "descripcion1".$descripcion." <br>";		
							
							$resp[$descripcion][]=$lista_activo;
							
								
								
						}
						else
						{  
							if ($fecha_compra <= $fecha_fin && $fecha_compra > $fecha_inicio)
							{
								$act_id=$valor['act_id'];
							    $descripcion=$valor['descripcion'];
							    $vida=$valor['vida_util'];
							    $fecha_compra=$valor['fecha'];
							    
							    $valor_dolar=0; //valor al comienzo del reporte
								$valor_dolar2=0;
								$validar=7;
								
								$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
								$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte

							
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
							
								
								if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$acttodo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre,$valor_dolar_ges,$valor_ufv_ges);
						
								 }	
								 else
								 {
								 	
							        if ($fecha_compra <= $fecha_fin)
								 		{   $fe_compra=$activo->Primerdiames($fecha_compra);
												
								 			$mesmedio=$activo->diferencia_fechas_meses($fe_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						 
								 		}
								 }
							
							$resp[$descripcion][]=$lista_activo;
							}
						
						}
						
						}
				   		return $resp;
				   			
				   		
				   			
				   		}

				   }*/
				   	
				}
	
				
				
			}//hastaaqui
			
 	
 	
 	
 	
 	
 }
/*solo calcula el gasto del activo el tiempo que haya estado en el la gestion requerida
 las fechas para el calculo de la  depreciacion varia otros parametros */
function calcular_gasto_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion_ante,$fecha_ges_ini,$fecha_ges_fin)
  {    
  	$activo= new Activo();
	$grupo= new Grupo();
	$tipo_cambio=new TipoCambio();
	$acttodo= new ActualizacionTodo();					
  	$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
 	 
 	if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
 	{   if ($valor['fecha_baja']== '' ||  $valor['fecha_baja'] > $fecha_fin)     			
		{
 
					$fecha_act=$grupo->buscar_fecha_compra_todo();
									
					foreach($fecha_act as $indice => $valor) 
					{ $descripcion=$valor['descripcion'];
						if ($valor['fecha'] <= $fecha_inter)
						{	
							
	               			$act_id=$valor['act_id'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
								
	  	   			 		$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
	  	   			 		
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=1;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
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
								
								$depre=0;
								$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
								
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    {  
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		  
							    	
									if($totalmeses < $vida )
									 {  /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
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
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
									        $depre=2;
								  		  	$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$mesmedio,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
								 			 
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											  	$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
												
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
							
							
							
							
							/******************/
						//	$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
													
							
						    
							$resp[$descripcion][]=$lista_activo;	
							
			    			
						}
						else 
						{   
						    $fecha_compra=$valor['fecha'];
							$act_id=$valor['act_id'];
							
						    if ($fecha_compra > $fecha_inter)
						   { 
						  	 if ($fecha_compra >=$fecha_ges_ini || $fecha_compra <$fecha_ges_fin)
							   {
							//$descripcion=$valor['descripcion'];
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);
							$validar=2;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/**************/
							
						    	$meses_pe=$activo->diferencia_fechas_meses($fecha_ges_ini,$fecha_inicio);		
								$meses_periodo=$meses_pe;
								$fe_compra=$activo->Primerdiames($fecha_compra);
								$meses_an=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
				  	   			$meses_anterior=$meses_an;	
				  	   			$meses_gestion=$activo->diferencia_fechas_meses($fecha_ges_fin,$fecha_ges_ini);
				  	   			
							
							
							
							$salida=date('m', strtotime($fecha_inicio));
							
							$salida1=date('m', strtotime($fecha_compra));
							$año=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año1=date('Y',strtotime($fecha_fin));
							
							$mesmedio=$activo->diferencia_fechas_meses($fe_compra,$fecha_fin); 
							
							  if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
				 	        	   $lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
							
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fe_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_an,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
							 			 
								 		}
								 		else{
									
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
				 if ($fecha_inicio < $fecha_inter && $fecha_fin <= $fecha_inter)
				   {
				   	$grupo= new Grupo();
					$tipo_cambio=new TipoCambio();
					$activo= new Activo();
					
				   $fecha_act=$grupo->buscar_fecha_compra_todo();
									
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
			  	   			$meses_anterior=$meses_an;
			  	   			$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=3;
							$valor_ufv=0;//valor en ufv al 31-03-2007
							$valor_ufv2=0;//valor al final del reporte
							/*********************/
							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año2=date('Y',strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=6;
								 $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
					
							}
						else{
							 if ($salida1==$salida2 && $año1=$año2 )
							   {  //si su vida comienza en el la fecha fin
							     $depre=10;						  
							     $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
								  
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
											     $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
							   			 
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=7; 			 	
												 $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
					
												 
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
												   $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
					
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													  $depre=9;
													  $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
					
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
							}
				
						}	
							
							
							
							
							
							/**************************/
						//	$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$descripcion][]=$lista_activo;
							
							
							/*echo "<pre>";
							print_r($resp);
							echo "</pre>";
							*/
						}
						
						else 
						{
						    /*echo "<br>vamonos".$valor["fecha"];*/
							
							
						}
					
					}
				   
					return $resp;
				
				   }
				   else 
				   {
				   if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	  {  $grupo= new Grupo();
						 $tipo_cambio=new TipoCambio();
						 $activo= new Activo();
					     
				    $fecha_act=$grupo->buscar_fecha_compra_todo();
											
					foreach($fecha_act as $indice => $valor)
					 {if ($valor['fecha_baja']== '' ||  $valor['fecha_baja'] > $fecha_fin)     			
				  		{
				  
						if ($valor['fecha'] <= $fecha_inter)
						{     
							$descripcion=$valor['descripcion'];
							$act_id=$valor['act_id'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
						
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=4;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
							
							
							
														
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
							
							
							
							
							
							/*****************/
							//$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$descripcion][]=$lista_activo;
							
																
							
			    			
						}
						else 
						{
						    //echo "<br>vamonos".$valor["fecha"];
						
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
						
							
							if  ($fecha_compra > $fecha_inter)
							{
							   if ($fecha_compra >=$fecha_ges_ini || $fecha_compra <$fecha_ges_fin)
							   {
							   	$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
								$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
								$validar=5;
								
								/************/
								 		  	   			 			
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
								
								
								$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
								
								$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
								$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
								
								$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
								$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							
							if ($salida1==$salida2 && $año1==$año2 )//se pregunta si la fecha de compra no es igual a la fecha fin del reporte
							{  
								$depre=16;
								$lista_activo=$acttodo->listar_gasto_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,0,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
					
							}
							
							else{		 					
							
							   if ($fecha_compra < $fecha_fin )
							    { 
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
									$fe_compra=$activo->Primerdiames($fecha_compra);
							   		$meses_an=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);  	
							   		     	
							   		   	 if ($fecha_compra > $fecha_inicio)
												{
												 
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
									
								$lista_activo=0;
								
								}
								
								
							}
				
							
							
							/************/
						//	$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
								if ($lista_activo!=0)					
								{$resp[$descripcion][]=$lista_activo;
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
				   		/*if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   	    {   
				   	    	$grupo= new Grupo();
							$tipo_cambio=new TipoCambio();
							$activo= new Activo(); 
				   	    	$fecha_act=$grupo->buscar_fecha_compra_todo();
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
										
						foreach($fecha_act as $indice => $valor) 
						{
						   if ($valor['fecha'] <= $fecha_inter)
						   {
							$descripcion=$valor['descripcion'];
							$fecha_compra=$valor['fecha'];
							$act_id=$valor['act_id'];
							$vida=$valor['vida_util'];
                            
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							$validar=6;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
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
								
							
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=0;
								$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges);
						
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 {  $depre=1; 	
											 	
										 $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges);
						 	
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									   	 $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										   $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges);
						
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$valor_dolar_ges,$valor_ufv_ges);
						
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
						
							//$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							echo "descripcion1".$descripcion." <br>";		
							
							$resp[$descripcion][]=$lista_activo;
							
								
								
						}
						else
						{  	
							if ($fecha_compra <= $fecha_fin && $fecha_compra > $fecha_inicio)
							{
								echo "activo".$act_id=$valor['act_id'];
							    $descripcion=$valor['descripcion'];
							    $vida=$valor['vida_util'];
							    echo "fecha compra2".$fecha_compra=$valor['fecha'];
							    
							    $valor_dolar=0; //valor al comienzo del reporte
								$valor_dolar2=0;
								$validar=7;
								
								$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
								$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte

							
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
							
								
								if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre,$valor_dolar_ges,$valor_ufv_ges);
						
								 }	
								 else
								 {
								 	
							        if ($fecha_compra <= $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges);
						 
								 		}
								 }
							
							//$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							//echo "descripcion2".$descripcion." <br>";	
							$resp[$descripcion][]=$lista_activo;
							}
						
						}
						
						}
				   		return $resp;
				   			
				   		
				   			
				   		}*/

				   }
				   	

				}
	
				
				
			}//hastaaqui
			
 	
 	
 	
 	
 	
 }
 /*Calcula las actualizaciones hasta el momento qu ese dio de baja al activo pero en todo el periodo
 Este utiliza listar_activo_todo
 ojo la fecha de calculo paar el activo dado de baja  es de un mes antes a la fecha actual del
  dado de baja es decir la actulizacion del activo sacra de un mes antes los datos */
function calcular_bajas_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion_ante,$gest)
  {    
  	$activo= new Activo();
	$grupo= new Grupo();
	$tipo_cambio=new TipoCambio();
	$acttodo= new ActualizacionTodo();					
  	$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
 	 
 	if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
 	{
 
					$fecha_act=$grupo->buscar_fecha_compra_todo();
									
					foreach($fecha_act as $indice => $valor) 
					{ $descripcion=$valor['descripcion'];
						if ($valor['fecha'] <= $fecha_inter)
						{	
							
							
	               			$act_id=$valor['act_id'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_periodo=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
					 		$meses_anterior=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
	  	   			 	
	  	   			 		$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
	  	   			 		
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=1;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/**************/
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							
					
							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
					
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								
								$depre=0;
								$lista_activo=$activo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
									
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    {  
							    	
									$totalmeses =$meses_anterior+$meses_periodo;
							   		  
							    	
									if($totalmeses < $vida )
									 {  /*esta condicion verifica que aun no se haya acabado su vida util*/
										 $depre=1; 			 	
										 $lista_activo=$activo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
							            
										 
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
										  	$lista_activo=$activo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
										
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											  	$lista_activo=$activo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
						 						
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
							
							
							
							
							/******************/
						//	$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
													
							
						    
							$resp[$descripcion][]=$lista_activo;	
							
			    			
						}
						else 
						{
						   if ($valor['fecha'] <= $fecha_fin) /*menor igual a la  fecha fin*/
						  { 
						    $fecha_compra=$valor['fecha'];
							$act_id=$valor['act_id'];
							//$descripcion=$valor['descripcion'];
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);
							$validar=2;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							/**************/
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
								    $lista_activo=$activo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
									      
								 }	
								 else
								 {
								 	
							        if ($fecha_compra < $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo_todo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges,$gest,$localizacion,$locsecundaria);
							              
								 		}
								 }
							/*******************/
							//$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
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
				   	$grupo= new Grupo();
					$tipo_cambio=new TipoCambio();
					$activo= new Activo();
					
				   $fecha_act=$grupo->buscar_fecha_compra_todo();
									
					foreach($fecha_act as $indice => $valor) 
					{
						if ($valor['fecha'] <= $fecha_inter)
						{	
							
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_fin);	
							$meses_periodo=$meses_pe;
							$meses_an=$activo->diferencia_fechas_meses($fecha_compra,$fecha_inicio);
			  	   			$meses_anterior=$meses_an;
			  	   			$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			 
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=3;
							$valor_ufv=0;//valor en ufv al 31-03-2007
							$valor_ufv2=0;//valor al final del reporte
							/*********************/
							
							$salida=date('m',strtotime($fecha_inicio));
							$año=date('Y',strtotime($fecha_inicio));
							
							$salida1=date('m',strtotime($fecha_compra));
							$año1=date('Y',strtotime($fecha_compra));
							
							$salida2=date('m', strtotime($fecha_fin));
							$año2=date('Y',strtotime($fecha_fin));
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=6;
								 $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre);
					
							}
						else{
							 if ($salida1==$salida2 && $año1=$año2 )
							   {  //si su vida comienza en el la fecha fin
							     $depre=10;						  
							     $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre);
								  
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
											     $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre);
							   			 
												}
										else {
											
											if($totalmeses < $vida )
											 { /*esta condicion verifica que aun no se haya acabado su vida util*/
											 	
												 $depre=7; 			 	
												 $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
					
												 
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
												   $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre);
					
												 }		
												 else
												 { if($mesmedio < 1)
												 
													 {//su vida ya termino entoces vida restante 0
													 
													  $depre=9;
													  $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre);
					
													 }
											     }
												 
										 
										 				
									 
									  									
								 		}											
							
										}			   
							   			
				
												
							   		}
							    
							   		
									
								
								}
							}
				
						}	
							
							
							
							
							
							/**************************/
						//	$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$descripcion][]=$lista_activo;
							
							
							/*echo "<pre>";
							print_r($resp);
							echo "</pre>";
							*/
						}
						else 
						{
						    /*echo "<br>vamonos".$valor["fecha"];*/
							
							
						}
					}
					return $resp;
				
				   }
				   else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	  {  $grupo= new Grupo();
						 $tipo_cambio=new TipoCambio();
						 $activo= new Activo();
					     $gestion= new Gestion();
					      
			 			 $fecha_gestion=$gestion->fecha_gestion($gest);
					 	 $fecha_ges_ini=$fecha_gestion['fecha_inicio'];
					 	 $fecha_ges_fin=$fecha_gestion['fecha_fin'];
					 	 
					 	
				   	     $fecha_act=$grupo->buscar_fecha_compra_todo();
					 	 
				   	     
				   	     
				  foreach($fecha_act as $indice => $valor)
				  {
				  	if ($valor['fecha_baja']!='' &&( $valor['fecha_baja'] >= $fecha_inicio || $valor['fecha_baja'] <= $fecha_fin))     			
				  	{
				  
					  if ($valor['fecha'] <= $fecha_inter )
						{   							
							
							$descripcion=$valor['descripcion'];
							$act_id=$valor['act_id']."<br>";
							$fecha_compra=$valor['fecha'];
							/*necesitamos ir a la fecha del ultimo dia del mes de un mes anaterior de la fecha dado de baja*/
							$fecha_baja1=$valor['fecha_baja'];
							$fecha_baja2=$activo->Primerdiames($fecha_baja1);
							$fecha_baja=$activo->diferencia_fechas_undia($fecha_baja2);
								
							$vida=$valor['vida_util'];
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
							
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=4;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_baja,2);//valor al final del reporte
							
							/***********************/
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							
							
							$meses_pe=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_baja);	
							$meses_periodo=$meses_pe;
							/*en este caso la fecjha de compar siempre lo llevo al primer dia del mes porque se toma todo el mes*/
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
									   { $mesmedio =$vida-$meses_anterior;

									   	/*significa que puede ser que haya cabo su vida perose tiene que saber sise acabo dentro del reporte o antes*/
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
							
							
							
							
							
							
							/*****************/
							//$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$descripcion][]=$lista_activo;
							
																
							
			    			
						}
						else 
						{
						  
							$act_id=$valor['act_id'];
							$descripcion=$valor['descripcion'];
							$fecha_compra=$valor['fecha'];
							$vida=$valor['vida_util'];
							$fecha_baja=$valor['fecha_baja'];
							
							if  ($fecha_compra > $fecha_inter)
							{
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=5;
							
							/************/
							 	$meses_periodo=$activo->diferencia_fechas_meses($fecha_inicio,$fecha_baja);	
					 			$fe_compra=$activo->Primerdiames($fecha_compra);
							 	$meses_anterior=$activo->diferencia_fechas_meses($fe_compra,$fecha_inicio);
	  	   			 		
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
								
								
								$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
								
								$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							    $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_baja,2);//valor al final del reporte
								
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
												 $mesmedio=$activo->diferencia_fechas_meses($fe_compra,$fecha_baja); 
												 
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
				
							
							
							/************/
						//	$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
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
				  /* 	else 
				   	{
				   		if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   	    {   
				   	    	$grupo= new Grupo();
							$tipo_cambio=new TipoCambio();
							$activo= new Activo(); 
				   	    	$fecha_act=$grupo->buscar_fecha_compra_todo();
							$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
							
										
						foreach($fecha_act as $indice => $valor) 
						{
						   if ($valor['fecha'] <= $fecha_inter)
						   {
							$descripcion=$valor['descripcion'];
							$fecha_compra=$valor['fecha'];
							$act_id=$valor['act_id'];
							$vida=$valor['vida_util'];
                            
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							$validar=6;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
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
								
							
							
							if ($salida==$salida1 && $año==$año1 )//se pregunta si la fecha de compra no es igual a la fecha inicio del reporte
							{  
								$depre=0;
								$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,1,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges);
						
							}
						else{
							   if ($fecha_compra <  $fecha_inicio)
							    { 
									$totalmeses =$meses_anterior+$meses_periodo;
							   		   
							    	
									if($totalmeses < $vida )
									 {  $depre=1; 	
											 	
										 $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges);
						 	
										 
									 }
									else
									{ 
									 if($totalmeses >= $vida)
									   {
									    $mesmedio =$vida-$meses_anterior; 
										 
										 if($mesmedio >= 1)
										 { //mes medio llegaria hacer el mes periodo
											$validar=1;	
										 	$meses_periodo=$mesmedio;
										 	
										   $depre=2;
										   $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges);
						
										 }		
										 else
										 { if($mesmedio < 1)
										 
											 {//su vida ya termino entoces vida restante 0
											   $depre=3;
											   $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,$meses_anterior,$mesmedio,$depre,$valor_dolar_ges,$valor_ufv_ges);
						
											 }
									     }
										 
										 
										 				
									   }
									     
									    
									}
								
								}
										
								
								
								
						}	
							
							//$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							echo "descripcion1".$descripcion." <br>";		
							
							$resp[$descripcion][]=$lista_activo;
							
								
								
						}
						else
						{  	
							if ($fecha_compra <= $fecha_fin && $fecha_compra > $fecha_inicio)
							{
								echo "activo".$act_id=$valor['act_id'];
							    $descripcion=$valor['descripcion'];
							    $vida=$valor['vida_util'];
							    echo "fecha compra2".$fecha_compra=$valor['fecha'];
							    
							    $valor_dolar=0; //valor al comienzo del reporte
								$valor_dolar2=0;
								$validar=7;
								
								$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
								$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte

							
								$salida=date('m',strtotime($fecha_inicio));
								$año=date('Y',strtotime($fecha_inicio));
								
								$salida1=date('m',strtotime($fecha_compra));
								$año1=date('Y',strtotime($fecha_compra));
								
								$salida2=date('m', strtotime($fecha_fin));
								$año2=date('Y',strtotime($fecha_fin));
							
								
								if ($salida1==$salida2 && $año==$año1 )
								 {  //cuando el activo se compro dentro de la fecha del reporte
							
								   $depre=4;						  
								   $lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,1,$depre,$valor_dolar_ges,$valor_ufv_ges);
						
								 }	
								 else
								 {
								 	
							        if ($fecha_compra <= $fecha_fin)
								 		{
								 			$mesmedio=$activo->diferencia_fechas_meses($fecha_compra,$fecha_fin); 
								 			$meses_periodo=$mesmedio;
								 			$depre=5;
								    		$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar,0,$meses_periodo,$depre,$valor_dolar_ges,$valor_ufv_ges);
						 
								 		}
								 }
						
							//$lista_activo=$activo->listar_activo_grupo_todo($valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							//echo "descripcion2".$descripcion." <br>";	
							$resp[$descripcion][]=$lista_activo;
							}
						
						}
						
						}
				   		return $resp;
				   			
				   		
				   			
				   		}

				   }*/
				   	

				}
	
				
				
			}//hastaaqui
			
 	
 	
 	
 	
 	
 }
	
	
	
}
?>