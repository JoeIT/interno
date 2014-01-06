<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/sistema_de_produccion/despiece.php');
include_once('../../clases/sistema_de_produccion/materiales.php');
include_once('../../clases/sistema_de_produccion/posicionpiezas.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');


$detalle_orden=new Detalle_orden;
$validar=new Validador();
$orden=new OrdenProd;
$despiece=new Despiece;
$materiales=new Material;
$posiciones=new PosicionPieza;
$funcion=$_GET["funcion"];
//echo "ingreso:".$funcion;
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

 if(isset($_GET["busqueda_ajax"]))
 {    
      $valor =  $_POST["value"];
	
 $cadena= utf8_decode(trim($valor));
//      header('Content-Type: text/html; charset=utf-8');
	  header("Content-Type: text/html; charset=iso-8859-1");
	  echo "<ul>";
	  if($_GET["busqueda_ajax"]=="materiales")
	  { 
	   		$lista=$materiales->busqueda_materiales($cadena);
		   if(count($lista)==0)
	   		{
		   		echo "<li>No hay resultados</li>";
	  	    }
		   else
		   {
				for($contador=0;$contador<count($lista);$contador++)
	     		{
			   	$detalles=$lista[$contador]["unidad"]."-".$lista[$contador]["dimensiones"]."-".$lista[$contador]["desperdicio"];
		        echo '<li id="'.$detalles.'" title="'.$detalles.'">'.$lista[$contador]["nombre"].'</li>';
		  	 	}
	       }
	  }
	  else
	  {
	     if($_GET["busqueda_ajax"]=="posiciones")
	  	 {
		    $lista=$posiciones->busqueda_posiciones($cadena);
		     if(count($lista)==0)
	   		{
		   		echo "<li>No hay resultados</li>";
	  	    }
		   else
		   {
				for($contador=0;$contador<count($lista);$contador++)
	     		{
			   	      echo '<li>'.$lista[$contador].'</li>';
		  	 	}
	       }
		 } 
	  
	  }
	  echo "</ul>";
 }
 else
 {
   
	if($funcion=="registrar_material")
	{

    	if ($validar->validarTodo($_GET['texto_material'], 1, 100)) {
	   	   $error['err_texto_material'] = "Debe ingresar la descripcion del material";}
		else
		{
	
		    $material_id=$materiales->verificar_material(trim($_GET['texto_material']));
		
        	if($material_id==-1){ $error['err_texto_material'] = "Descripción del material no válida";}
	
		}
   
  
    	if (isset($error))
		{

	    	$orden_id=$_GET['orden_id'];
			$detalle_id=$_GET['detalle_id'];
			$material=$_GET['texto_material'];
	 		$cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
			$lista_materiales=$despiece->obtener_lista_materiales_despiece($detalle_id);
			
			$smarty->assign('orden_id', $orden_id);
			$smarty->assign('detalle_id', $detalle_id);
	       	$smarty->assign('cabecera', $cabecera);
			$smarty->assign('errores',$errores);
			$smarty->assign('lista_materiales',$lista_materiales);
			$smarty->assign('material',$material);
			$smarty->assign('nombres', $_SESSION["nombres"]);
			$smarty->assign('apellidos', $_SESSION["apellidos"]);
			$smarty->display('sistema_de_produccion/despiece/modificar_despiece.html');
		}
		else
		{
		    //*SE REGISTRA EL NUEVO MATERIAL PARA EL DESPIECE */
    		 $orden_id=$_GET['orden_id'];
			 $detalle_id=$_GET['detalle_id'];
		     $despiece->registrar_material($detalle_id,$material_id);
  			 $material=$_GET['texto_material'];
	 		 $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
	 		 $lista_materiales=$despiece->obtener_lista_materiales_despiece($detalle_id);
			//
	 		 $despiece->realizar_calculos_materiales($detalle_id);
			 header("Location: ver_detalle_orden_despiece.php?funcion=despiece&orden_id=".$orden_id."&elegido=".$detalle_id);
		}   
  }	  
  else
	{
	   if($funcion=="modificar_piezas")
	   { 
	      
	        $orden_id=$_GET["orden_id"];
	        $detalle_id=$_GET["detalle_id"];
			$material_id=$_GET["elegido"];
			$lista_piezas=$despiece->obtener_lista_piezas_material($detalle_id,$material_id);
		    $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
			$datos_materiales=$despiece->obtener_datos_material_despiece($detalle_id,$material_id);
			$smarty->assign('orden_id', $orden_id);
			$smarty->assign('detalle_id', $detalle_id);
	       	$smarty->assign('cabecera', $cabecera);
			$smarty->assign('datos_materiales',$datos_materiales);
			$smarty->assign('material_id',$material_id);
			$smarty->assign('lista_piezas', $lista_piezas);
			$smarty->assign('nombres', $_SESSION["nombres"]);
			$smarty->assign('apellidos', $_SESSION["apellidos"]);
			$smarty->display('sistema_de_produccion/despiece/modificar_piezas.html');
	   }
	   else
	   {
	         if($funcion=="registrar_pieza_material")
	 		 { 
	                if ($validar->validarTodo($_GET['texto_posicion'], 1, 100)) {
	   	  			 $error['err_texto_posicion'] = "Debe ingresar la posicion de la pieza";}
					else
					{
					    $posicion_id=$posiciones->verificar_posicion(trim($_GET['texto_posicion']));
				    	if($posicion_id==-1){ $error['err_texto_posicion'] = "Descripción de la posicion no válida";}
	
					}
					if ($validar->validarTodo($_GET['texto_largo'], 1, 100)) {
	   	  			   $error['err_texto_largo'] = "Debe ingresar el largo de la pieza";}
                    else
    				{
					     if ($validar->validarNumeros($_GET['texto_largo'], 1, 3)) {
				        	$error['err_texto_largo'] = "Debe ingresar un largo valido";}							
					}
					if ($validar->validarTodo($_GET['texto_ancho'], 1, 100)) {
	   	  			   $error['err_texto_ancho'] = "Debe ingresar el ancho de la pieza";}
                    else
    				{
					     if ($validar->validarNumeros($_GET['texto_largo'], 1, 3)) {
				        	$error['err_texto_ancho'] = "Debe ingresar un ancho valido";}							
					}
					if ($validar->validarTodo($_GET['texto_cantidad'], 1, 100)) {
	   	  			   $error['err_texto_cantidad'] = "Debe ingresar la cantidad de piezas";}
                    else
    				{
					     if ($validar->validarNumeros($_GET['texto_cantidad'], 1, 3)) {
				        	$error['err_texto_cantidad'] = "Debe ingresar una cantidad valido";}							
					}
					
					if (isset($error))
					{

					    	$orden_id=$_GET["orden_id"];
	        				$detalle_id=$_GET["detalle_id"];
							$material_id=$_GET["material_id"];
						
							$lista_piezas=$despiece->obtener_lista_piezas_material($detalle_id,$material_id);
						    $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
							$datos_materiales=$despiece->obtener_datos_material_despiece($detalle_id,$material_id);
							$smarty->assign('orden_id', $orden_id);
					 	    $smarty->assign('detalle_id', $detalle_id);
							$smarty->assign('cabecera', $cabecera);
							$smarty->assign('material_id', $material_id);
							$smarty->assign('datos_materiales',$datos_materiales);
							$smarty->assign('lista_piezas',$lista_piezas);
							$smarty->assign('posicion',$_GET["texto_posicion"]);
							$smarty->assign('largo',$_GET["texto_largo"]);
							$smarty->assign('ancho',$_GET["texto_ancho"]);
							$smarty->assign('errores',$error);
							$smarty->assign('cantidad',$_GET["texto_cantidad"]);
							$smarty->assign('nombres', $_SESSION["nombres"]);
							$smarty->assign('apellidos', $_SESSION["apellidos"]);
							$smarty->display('sistema_de_produccion/despiece/modificar_piezas.html');   
					}		
					else
					{
					     $orden_id=$_GET["orden_id"];
	        			 $detalle_id=$_GET["detalle_id"];
						 $material_id=$_GET["material_id"];
						 $largo=$_GET["texto_largo"];
						 $ancho=$_GET["texto_ancho"];
						
						 $cantidad=$_GET["texto_cantidad"];
					     $componente_id=$despiece->registrar_componente_nuevo($detalle_id,$material_id,$posicion_id,$largo,$ancho,$cantidad);
						  
						 $lista_piezas=$despiece->obtener_lista_piezas_material($detalle_id,$material_id);
						 $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
						 $datos_materiales=$despiece->obtener_datos_material_despiece($detalle_id,$material_id);
						 //
						 $despiece->realizar_calculos_materiales($detalle_id);
						 header("Location: modificar_despiece.php?funcion=volver_piezas&orden_id=".$orden_id."&detalle_id=".$detalle_id."&material_id=".$material_id);
						
						
					}
	              
	         }
			 else
			 {
			    
			      if($funcion== "modificar_datos_pieza")
		 		  {
				     $orden_id=$_GET["orden_id"];
	        		 $detalle_id=$_GET["detalle_id"];
					 $material_id=$_GET["material_id"];
				     $componente_id=$_GET["elegido"];
					 //$cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
					 $datos_materiales=$despiece->obtener_datos_material_despiece($detalle_id,$material_id); 
					 $datos_pieza=$despiece->obtener_datos_pieza_despiece($componente_id); 
					 $smarty->assign('orden_id', $orden_id);
					 $smarty->assign('detalle_id', $detalle_id);
					 $smarty->assign('material_id',$material_id); 
					 $smarty->assign('datos_materiales',$datos_materiales);
					 $smarty->assign('datos_pieza',$datos_pieza);
					 $smarty->assign('componente_id', $componente_id); 
  					 $smarty->assign('nombres', $_SESSION["nombres"]);
					 $smarty->assign('apellidos', $_SESSION["apellidos"]);
					 $smarty->display('sistema_de_produccion/despiece/modificar_datos_piezas.html');
				  } 
			      else
				  {
				       if($funcion=="modificar_datos_pieza_registro")
					   {
					       
					        if ($validar->validarTodo($_GET['texto_posicion'], 1, 100)) {
			   	  			 $error['err_texto_posicion'] = "Debe ingresar la posicion de la pieza";}
							else
							{ 
							    $posicion_id=$posiciones->verificar_posicion(trim($_GET['texto_posicion']));
						    	if($posicion_id==-1){ $error['err_texto_posicion'] = "Descripción de la posicion no válida";}
		
							}
							if ($validar->validarTodo($_GET['texto_largo'], 1, 100)) {
			   	  			   $error['err_texto_largo'] = "Debe ingresar el largo de la pieza";}
        		            else
    						{
					    		 if ($validar->validarNumeros($_GET['texto_largo'], 1, 3)) {
						        	$error['err_texto_largo'] = "Debe ingresar un largo valido";}							
							}
							if ($validar->validarTodo($_GET['texto_ancho'], 1, 100)) {
			   	  			   $error['err_texto_ancho'] = "Debe ingresar el ancho de la pieza";}
        		            else
    						{
					    		 if ($validar->validarNumeros($_GET['texto_largo'], 1, 3)) {
						        	$error['err_texto_ancho'] = "Debe ingresar un ancho valido";}							
							}
							if ($validar->validarTodo($_GET['texto_cantidad'], 1, 100)) {
	   	  			   			$error['err_texto_cantidad'] = "Debe ingresar la cantidad de piezas";}
		                    else
    						{
							     if ($validar->validarNumeros($_GET['texto_cantidad'], 1, 3)) {
				        			$error['err_texto_cantidad'] = "Debe ingresar una cantidad valido";}							
							}
					
							if (isset($error))
							{

					    		$orden_id=$_GET["orden_id"];
		        				$detalle_id=$_GET["detalle_id"];
								$material_id=$_GET["material_id"];
						        $componente_id=$_GET["componente_id"];

	                            $datos_materiales=$despiece->obtener_datos_material_despiece($detalle_id,$material_id); 
								$datos_pieza=$despiece->obtener_datos_pieza_despiece($componente_id); 
								
								$smarty->assign('orden_id', $orden_id);
								$smarty->assign('detalle_id', $detalle_id);
								$smarty->assign('material_id',$material_id); 
								$smarty->assign('componente_id', $componente_id);
					 			$smarty->assign('datos_materiales',$datos_materiales);
					 			$smarty->assign('datos_pieza',$datos_pieza);
					  			$smarty->assign('posicion',$_GET["texto_posicion"]);
								$smarty->assign('largo',$_GET["texto_largo"]);
								$smarty->assign('ancho',$_GET["texto_ancho"]);
								$smarty->assign('cantidad',$_GET["texto_cantidad"]);
								$smarty->assign('errores',$error);
  					 			$smarty->assign('nombres', $_SESSION["nombres"]);
								$smarty->assign('apellidos', $_SESSION["apellidos"]);
								$smarty->display('sistema_de_produccion/despiece/modificar_datos_piezas.html');
							}		
							else
							{
						        $orden_id=$_GET["orden_id"];
	        					$detalle_id=$_GET["detalle_id"];
								$material_id=$_GET["material_id"];
						    	$componente_id=$_GET["componente_id"];
								$largo=$_GET["texto_largo"];
								$ancho=$_GET["texto_ancho"];
							    $cantidad=$_GET["texto_cantidad"];
							    $componente_id=$despiece->actualizar_datos_componente($componente_id,$largo,$ancho,$cantidad,$posicion_id,$material_id);
						        //
							    $despiece->realizar_calculos_materiales($detalle_id);			
								 header("Location: modificar_despiece.php?funcion=volver_piezas&orden_id=".$orden_id."&detalle_id=".$detalle_id."&material_id=".$material_id);
								 
							
						   }
						}
					
				       else
					   {
					       if($funcion== "eliminar_pieza_material")
                           {
						     
						         $orden_id=$_GET["orden_id"];
	        		 			 $detalle_id=$_GET["detalle_id"];
								 $material_id=$_GET["material_id"];
							     $componente_id=$_GET["elegido"];
						         $despiece->eliminar_pieza_componente($componente_id,$material_id,$detalle_id);  
								//			
						         $despiece->realizar_calculos_materiales($detalle_id);
								 

								 header("Location: 								 modificar_despiece.php?funcion=volver_piezas&orden_id=".$orden_id."&detalle_id=".$detalle_id."&material_id=".$material_id);
								 
								 
								
								 
						   }		 		  
					       else
						   {
						        if($funcion=="modificar_material_despiece")
								{
								       $orden_id=$_GET["orden_id"];
								       $detalle_id=$_GET["detalle_id"];
									   $material_id=$_GET["elegido"];
									   $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
									   $datos_materiales=$despiece->obtener_datos_material_despiece($detalle_id,$material_id); 
									   
									   $smarty->assign('orden_id', $orden_id);
									   $smarty->assign('detalle_id', $detalle_id);
									   $smarty->assign('material_id',$material_id); 
									   $smarty->assign('cabecera', $cabecera);
						 			   $smarty->assign('datos_materiales',$datos_materiales);
							  		   $smarty->assign('nombres', $_SESSION["nombres"]);
									   $smarty->assign('apellidos', $_SESSION["apellidos"]);
									   $smarty->display('sistema_de_produccion/despiece/modificar_material_despiece.html');	 
								}
						        else
								{
								   if($funcion=="registrar_modificacion_material")
								   {
								              
									   if ($validar->validarTodo($_GET['texto_material'], 1, 100)) {
										   	   $error['err_texto_material'] = "Debe ingresar la descripcion del material";}
									   else
										{
	
										    $material_id=$materiales->verificar_material(trim($_GET['texto_material']));
								        	if($material_id==-1){ $error['err_texto_material'] = "Descripción del material no 
											válida";}
	
										}
   
  
								    	if (isset($error))
										{

									    	$orden_id=$_GET['orden_id'];
											$detalle_id=$_GET['detalle_id'];
											$material_id=$_GET['material_id'];
											$material=$_GET['texto_material'];
											$cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
											$datos_materiales=$despiece->obtener_datos_material_despiece($detalle_id,$material_id);
											$smarty->assign('orden_id', $orden_id);
									  		$smarty->assign('detalle_id', $detalle_id);
											$smarty->assign('material_id',$material_id); 
										    $smarty->assign('cabecera', $cabecera);
							 			    $smarty->assign('datos_materiales',$datos_materiales);
										    $smarty->assign('errores',$error);
										    $smarty->assign('material',$material);
								  		    $smarty->assign('nombres', $_SESSION["nombres"]);
										    $smarty->assign('apellidos', $_SESSION["apellidos"]);
										    $smarty->display('sistema_de_produccion/despiece/modificar_material_despiece.html');	 
										}
										else
										{
										
										    //*SE ACTUALIZA EL NUEVO MATERIAL PARA EL DESPIECE */
											  $orden_id=$_GET['orden_id'];
											 $detalle_id=$_GET['detalle_id'];
											 $material_id_anterior=$_GET['material_id'];
										     $despiece->modificar_material_despiece($detalle_id,$material_id_anterior,$material_id);
								  			 $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
											 $lista_materiales=$despiece->obtener_lista_materiales_despiece($detalle_id);
                                             //
											 $despiece->realizar_calculos_materiales($detalle_id);
											 header("Location: modificar_despiece.php?funcion=volver&orden_id=".$orden_id."&detalle_id=".$detalle_id);
										
										}   
								       
								   
								   }
								   else
								   {
								       if($funcion=="eliminar_material_despiece")
									   {
									     
									            $orden_id=$_GET["orden_id"];
								       			$detalle_id=$_GET["detalle_id"];
		 									    $material_id=$_GET["elegido"];
												$despiece->eliminar_material_despiece($detalle_id,$material_id);
												$cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
												$lista_materiales=$despiece->obtener_lista_materiales_despiece($detalle_id);
												//
												$despiece->realizar_calculos_materiales($detalle_id);
												header("Location: modificar_despiece.php?funcion=volver&orden_id=".$orden_id."&detalle_id=".$detalle_id);
										
											
									   
									   }
									   else
									   {
								       		if($funcion=="volver")
										    {
										       $orden_id = $_GET['orden_id'];
											   $detalle_id = $_GET['detalle_id'];
											   $despiece=new Despiece;
											   $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
											   $lista_materiales=$despiece->obtener_lista_materiales_despiece($detalle_id);
											   $smarty->assign('orden_id', $orden_id);
										 		$smarty->assign('detalle_id', $detalle_id);
									       	    $smarty->assign('cabecera', $cabecera);
											    $smarty->assign('lista_materiales',$lista_materiales);
												$smarty->assign('nombres', $_SESSION["nombres"]);
												$smarty->assign('apellidos', $_SESSION["apellidos"]);
												$smarty->display('sistema_de_produccion/despiece/modificar_despiece.html');
											  
										   
										    }
											else
											{
											    if($funcion=="volver_piezas")
												{
												  $orden_id=$_GET["orden_id"];
	        									  $detalle_id=$_GET["detalle_id"];
												  $material_id=$_GET["material_id"];
												
										    	  $lista_piezas=$despiece->obtener_lista_piezas_material($detalle_id,$material_id);
											      $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
												  $datos_materiales=$despiece->obtener_datos_material_despiece($detalle_id,$material_id);
												 
												  $smarty->assign('orden_id', $orden_id);
										 	      $smarty->assign('detalle_id', $detalle_id);
									       	      $smarty->assign('cabecera', $cabecera);
											      $smarty->assign('material_id', $material_id);
											      $smarty->assign('datos_materiales', $datos_materiales);
											      $smarty->assign('lista_piezas', $lista_piezas);
											      $smarty->assign('nombres', $_SESSION["nombres"]);
											      $smarty->assign('apellidos', $_SESSION["apellidos"]);
											      $smarty->display('sistema_de_produccion/despiece/modificar_piezas.html');
												

												}
												else
												{
												   if($funcion=="realizar_calculos")
												   {
												       $orden_id=$_GET['orden_id'];
													   $detalle_id=$_GET['detalle_id'];
													   $despiece->realizar_calculos_materiales($detalle_id);
									   $lista_resultados_material=$despiece->resultados_calculos_detalle($detalle_id);
				                       $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
									   $detalle_orden->registrar_despiece($detalle_id,"1");
									   $despiece->actualizar_fecha_fin_despiece_detalle($detalle_id);
													   	$smarty->assign('orden_id', $orden_id);
										 	    $smarty->assign('detalle_id', $detalle_id);
									       	    $smarty->assign('cabecera', $cabecera);
											    $smarty->assign('lista_resultados_materiales',$lista_resultados_material);
											    $smarty->assign('nombres', $_SESSION["nombres"]);
											    $smarty->assign('apellidos', $_SESSION["apellidos"]);
											    $smarty->display('sistema_de_produccion/despiece/resultados_despiece_detalle.html');
												
												   
												   }
												   else
												   {
												       if($funcion=="modificar_material_pieza")
													   {
													       $orden_id=$_GET["orden_id"];
	        											   $detalle_id=$_GET["detalle_id"];
					 									   $material_id=$_GET["material_id"];
														   $componente_id=$_GET["elegido"];
														 //$cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
														    $datos_materiales=$despiece->obtener_datos_material_despiece($detalle_id,$material_id); 
					 										$datos_pieza=$despiece->obtener_datos_pieza_despiece($componente_id); 
					 										$smarty->assign('orden_id', $orden_id);
														    $smarty->assign('detalle_id', $detalle_id);
														    $smarty->assign('material_id',$material_id); 
															$smarty->assign('datos_materiales',$datos_materiales);
															$smarty->assign('datos_pieza',$datos_pieza);
															$smarty->assign('componente_id', $componente_id); 
										  				    $smarty->assign('nombres', $_SESSION["nombres"]);
														    $smarty->assign('apellidos', $_SESSION["apellidos"]);
													        $smarty->display('sistema_de_produccion/despiece/modificar_material_pieza.html');
													   
													   }
												       else
													   {
													      if($funcion=="modificar_material_pieza_registro")
														  {
														       if ($validar->validarTodo($_GET['texto_posicion'], 1, 100)) {
													   	  			 $error['err_texto_posicion'] = "Debe ingresar la posicion de la pieza";}
															   else
															   { 
																    $posicion_id=$posiciones->verificar_posicion(trim($_GET['texto_posicion']));
															    	if($posicion_id==-1){ $error['err_texto_posicion'] = "Descripción de la posicion no válida";}
													
										     					}
																if ($validar->validarTodo($_GET['texto_material'], 1, 100)) {
													   	  			 $error['err_texto_material'] = "Debe ingresar el material de la pieza";}
															   else
															   { 
																    $material_id_nuevo=$materiales->verificar_material(trim($_GET['texto_material']));
															    	if($posicion_id==-1){ $error['err_texto_material'] = "Descripción del material no válida";}
													
										     					}												
																if ($validar->validarTodo($_GET['texto_largo'], 1, 100)) {
												   	  			   $error['err_texto_largo'] = "Debe ingresar el largo de la pieza";}
									        		            else
									    						{
														    		 if ($validar->validarNumeros($_GET['texto_largo'], 1, 3)) {
						        											$error['err_texto_largo'] = "Debe ingresar un largo valido";}							
																}
																if ($validar->validarTodo($_GET['texto_ancho'], 1, 100)) {
														   	  			   $error['err_texto_ancho'] = "Debe ingresar el ancho de la pieza";}
									        		            else
									    						{
														    		 if ($validar->validarNumeros($_GET['texto_largo'], 1, 3)) {
						        											$error['err_texto_ancho'] = "Debe ingresar un ancho valido";}							
																}
																if ($validar->validarTodo($_GET['texto_cantidad'], 1, 100)) {
											   	  			   			$error['err_texto_cantidad'] = "Debe ingresar la cantidad de piezas";}
												                else
									    						{
																     if ($validar->validarNumeros($_GET['texto_cantidad'], 1, 3)) {
				        													$error['err_texto_cantidad'] = "Debe ingresar una cantidad valido";}							
																}
															if (isset($error))
															{

													    		$orden_id=$_GET["orden_id"];
		        												$detalle_id=$_GET["detalle_id"];
																$material_id=$_GET["material_id"];
						        								$componente_id=$_GET["componente_id"];
								                            $datos_materiales=$despiece->obtener_datos_material_despiece($detalle_id,$material_id); 
															$datos_pieza=$despiece->obtener_datos_pieza_despiece($componente_id); 
															$smarty->assign('orden_id', $orden_id);
															$smarty->assign('detalle_id', $detalle_id);
															$smarty->assign('material_id',$material_id); 
															$smarty->assign('componente_id', $componente_id);
												 			$smarty->assign('datos_materiales',$datos_materiales);
					 										$smarty->assign('datos_pieza',$datos_pieza);
															$smarty->assign('material',$_GET["texto_material"]);
												  			$smarty->assign('posicion',$_GET["texto_posicion"]);
															$smarty->assign('largo',$_GET["texto_largo"]);
															$smarty->assign('ancho',$_GET["texto_ancho"]);
															$smarty->assign('cantidad',$_GET["texto_cantidad"]);
															$smarty->assign('errores',$error);
  					 										$smarty->assign('nombres', $_SESSION["nombres"]);
															$smarty->assign('apellidos', $_SESSION["apellidos"]);
															$smarty->display('sistema_de_produccion/despiece/modificar_material_pieza.html');
															}		
															else
															{
						        								$orden_id=$_GET["orden_id"];
									        					$detalle_id=$_GET["detalle_id"];
																$material_id=$_GET["material_id"];
														    	$componente_id=$_GET["componente_id"];
																$largo=$_GET["texto_largo"];
																$ancho=$_GET["texto_ancho"];
							    								$cantidad=$_GET["texto_cantidad"];
																
					    								$componente_id=$despiece->actualizar_datos_componente($componente_id,$largo,$ancho,$cantidad,$posicion_id,$material_id_nuevo);
														//			
					    								$despiece->realizar_calculos_materiales($detalle_id);
						 header("Location: modificar_despiece.php?funcion=volver_piezas&orden_id=".$orden_id."&detalle_id=".$detalle_id."&material_id=".$material_id);
								 
							
														   }
														  
														  
														  }
													   
													      else
														  {
														     if($funcion=="imprimir")
															 {
															      $orden_id=$_GET['orden_id'];
													 			  $detalle_id=$_GET['detalle_id'];
																  $lista_resultados_material=$despiece->resultados_calculos_detalle($detalle_id);
																  $cabecera_orden=$orden->obtener_cabecera_orden($orden_id);
				            							          $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
																  $detalle_orden->registrar_despiece($detalle_id,"1");
															   	  $smarty->assign('orden_id', $orden_id);
															 	  $smarty->assign('detalle_id', $detalle_id);
									       	    				  $smarty->assign('cabecera', $cabecera);
																  $smarty->assign('cabecera_orden', $cabecera_orden);
					  										      $smarty->assign('lista_resultados_materiales',$lista_resultados_material);
				 											      $smarty->assign('nombres', $_SESSION["nombres"]);
				           						            	  $smarty->assign('apellidos', $_SESSION["apellidos"]);
							                  				      $smarty->display('sistema_de_produccion/despiece/hoja_de_despiece.html');
															 
															 }
															 else
															 {
															     if($funcion=="resumen_despiece")
																 {
																      $orden_id=$_GET['orden_id'];
																	  $resumen=$despiece->resultados_resumen_despiece1($orden_id);
																	  $impri=$despiece->resumen_despiece_modificar($orden_id);
																	  
																	  $resumen_despiece=$resumen[2];
																	  $resumen_modelos=$resumen[1];
																	  $resumen_materiales=$resumen[0];
																	  
																	 /* echo "<pre>";
																	    print_r($resumen_despiece);
																	  echo "</pre>";
																	  echo "<br> RESUMEN MODELOSSSS";
																	    echo "<pre>";
																	    print_r($resumen_modelos);
																	  echo "</pre>";
																	  echo "<br> RESUMEN materiales";
																	  echo "<pre>";
																	    print_r($resumen_materiales);
																	  echo "</pre>";*/
																	  $total_modelos=count($resumen_modelos);
																	  $total_materiales=count($resumen_materiales);
																	  $maximo_modelos=9;
																	  $tamanio=15/9;
																	  $maximo_materiales=44;
																	//  echo "total modelos: ".$total_modelos;
																	 // echo "total materiales:".$total_materiales;
														  if($total_modelos<=$maximo_modelos && $total_materiales<$maximo_materiales)
																	  	     $num_paginas=1;
														  else
														  {
															 if($total_modelos>$maximo_modelos && $total_materiales <= $maximo_materiales)
															{
																		       $num=$total_modelos/$maximo_modelos;
																			   $num_paginas=ceil($num);
																			   
															}
																	       else
																		   {
																		        if($total_modelos<=$maximo_modelos && $total_materiales>$maximo_materiales)
																		       	{
																				 
																				   $num=$total_materiales/$maximo_materiales;
																				   $num_paginas=ceil($num);
																				}
																		        else
																				{
																				    if($total_modelos>$maximo_modelos && $total_materiales>$maximo_materiales)
																			       	{
																					
																					    $paginas_mat=$total_materiales/$maximo_materiales;
																						$paginas_materiales=ceil($paginas_mat);
																																			$paginas_mod=$total_modelos/$maximo_modelos;
																						$paginas_modelos=ceil($paginas_mod);
																						
																				$num_paginas=$paginas_modelos*$paginas_materiales;
																				    }
																				}
																		   }
																	  }
													  
							 $cabecera_orden=$orden->obtener_cabecera_orden($orden_id);
							 $smarty->assign('orden_id', $orden_id);
							 $smarty->assign('cabecera_orden', $cabecera_orden);
						  	 $smarty->assign('resumen_despiece',$resumen_despiece);
							 $smarty->assign('maximo_modelos',$maximo_modelos);
							 $smarty->assign('maximo_materiales',$maximo_materiales);
							 $smarty->assign('tamanio',$tamanio);
							 $smarty->assign('resumen_modelos',$resumen_modelos);
							 $smarty->assign('resumen_materiales',$resumen_materiales);
							 $smarty->assign('total_modelos',count($resumen_modelos));
							$smarty->assign('total_materiales',count($resumen_materiales));
							$smarty->assign('num_paginas',$num_paginas);
					 		$smarty->assign('nombres', $_SESSION["nombres"]);
					        $smarty->assign('apellidos', $_SESSION["apellidos"]);
					      
							
							if (isset($_GET['fecha'])){
								$despiece->actualizar_fecha_fin_despiece($orden_id);
							}
							
					        $smarty->display('sistema_de_produccion/despiece/hoja_de_resumen_despiece1.html');
																 }
															     else
																 {
																	
																 	if($funcion=="deshacer_despiece")
																    {
																	      $orden_id=$_GET['orden_id']; 
																		  $detalle_id=$_GET['detalle_id'];
																		 // echo $detalle_id;
																		  $despiece->eliminar_componentes($detalle_id);
																		  $despiece->eliminar_calculos_despiece($detalle_id);
																		  $detalle_orden->registrar_despiece($detalle_id,'0');
																		  $despiece=new Despiece;
	   	  															 $similares=$despiece->buscar_despieces_similares($detalle_id);
	   											 $cabecera=$detalle_orden->obtener_detalle_orden_despiece($orden_id,$detalle_id);
												   $smarty->assign('orden_id', $orden_id);
												   $smarty->assign('detalle_id', $detalle_id);
												   $smarty->assign('similares', $similares);
												   $smarty->assign('cabecera', $cabecera);
												   $smarty->display('sistema_de_produccion/despiece/seleccionar_despiece_similar.html');																		  
																	}
																	else {
																	//desde aqui franco ver	
																	 if($funcion=="ver_despiece")
																 {
																	  
																	  $orden_id=$_GET['orden_id'];
																	  $resumen=$despiece->resultados_resumen_despiece1($orden_id);
																	 // $impri=$despiece->resumen_despiece_modificar($orden_id);
																	  
																	  $resumen_despiece=$resumen[2];
																	  $resumen_modelos=$resumen[1];
																	  $resumen_materiales=$resumen[0];
																	 
																	  
																	 /* echo "<pre>";
																	    print_r($resumen_despiece);
																	  echo "</pre>";
																	  echo "<br> RESUMEN MODELOSSSS";
																	    echo "<pre>";
																	    print_r($resumen_modelos);
																	  echo "</pre>";
																	  echo "<br> RESUMEN materiales";
																	  echo "<pre>";
																	    print_r($resumen_materiales);
																	  echo "</pre>";*/
																	  $total_modelos=count($resumen_modelos);
																	  $total_materiales=count($resumen_materiales);
																	  /*Se modifico temporal mente samuel
																	  se debe modificar debido al tipo de material
																	  $maximo_modelos=7;
																	  $tamanio=15/7; */
																	  $maximo_modelos=9;
																	  $tamanio=15/9;
																	  $maximo_materiales=44;
																	//  echo "total modelos: ".$total_modelos;
																	 // echo "total materiales:".$total_materiales;
														  if($total_modelos<=$maximo_modelos && $total_materiales<$maximo_materiales)
																	  	     $num_paginas=1;
														  else
														  {
															 
															 if($total_modelos>$maximo_modelos && $total_materiales <= $maximo_materiales)
															{
																		     
																			   $num=$total_modelos/$maximo_modelos;
																			   $num_paginas=ceil($num);
																			   
															}
																	       else
																		   {
																				if($total_modelos<=$maximo_modelos && $total_materiales>$maximo_materiales)
																		       	{
																				 
																				  
																				   $num=$total_materiales/$maximo_materiales;
																				   $num_paginas=ceil($num);
																				}
																		        else
																				{
																				   //echo "entro a este if 2 con:".$total_modelos."-".$maximo_modelos."-". $total_materiales."-".$maximo_materiales;

																					if($total_modelos>$maximo_modelos && $total_materiales>$maximo_materiales)
																			       	{
																					
																					    
																						$paginas_mat=$total_materiales/$maximo_materiales;
																						$paginas_materiales=ceil($paginas_mat);
																						$paginas_mod=$total_modelos/$maximo_modelos;
																						$paginas_modelos=ceil($paginas_mod);
																						
																				$num_paginas=$paginas_modelos*$paginas_materiales;
																				    }
																				}
																		   }
																	  }
													  
							 $cabecera_orden=$orden->obtener_cabecera_orden($orden_id);
							 $smarty->assign('orden_id', $orden_id);
							 $smarty->assign('cabecera_orden', $cabecera_orden);
						  	 $smarty->assign('resumen_despiece',$resumen_despiece);
							 $smarty->assign('maximo_modelos',$maximo_modelos);
							 $smarty->assign('maximo_materiales',$maximo_materiales);
							 $smarty->assign('tamanio',$tamanio);
							 $smarty->assign('resumen_modelos',$resumen_modelos);
							 $smarty->assign('resumen_materiales',$resumen_materiales);
							 $smarty->assign('total_modelos',count($resumen_modelos));
							$smarty->assign('total_materiales',count($resumen_materiales));
							$smarty->assign('num_paginas',$num_paginas);
					 		$smarty->assign('nombres', $_SESSION["nombres"]);
					        $smarty->assign('apellidos', $_SESSION["apellidos"]);
					      
							
							if (isset($_GET['fecha'])){
								$despiece->actualizar_fecha_fin_despiece($orden_id);
							}
							
					        $smarty->display('sistema_de_produccion/despiece/hoja_de_resumen_despiece2.html');
																 }
																	
																	if($funcion=="parcial_despiece")
																 {
																      $orden_id=$_GET['orden_id'];
																	  $resumen=$despiece->resultados_resumen_despiece1($orden_id);
																	  //$impri=$despiece->resumen_despiece_modificar($orden_id);
																	  
																	  $resumen_despiece=$resumen[2];
																	  $resumen_modelos=$resumen[1];
																	  $resumen_materiales=$resumen[0];
																	  
																	 /* echo "<pre>";
																	    print_r($resumen_despiece);
																	  echo "</pre>";
																	  echo "<br> RESUMEN MODELOSSSS";
																	    echo "<pre>";
																	    print_r($resumen_modelos);
																	  echo "</pre>";
																	  echo "<br> RESUMEN materiales";
																	  echo "<pre>";
																	    print_r($resumen_materiales);
																	  echo "</pre>";*/
																	  $total_modelos=count($resumen_modelos);
																	  $total_materiales=count($resumen_materiales);
																	  $maximo_modelos=9;
																	  $tamanio=15/9;
																	  $maximo_materiales=44;
																	//  echo "total modelos: ".$total_modelos;
																	 // echo "total materiales:".$total_materiales;
														  if($total_modelos<=$maximo_modelos && $total_materiales<$maximo_materiales)
																	  	     $num_paginas=1;
														  else
														  {
															 if($total_modelos>$maximo_modelos && $total_materiales <= $maximo_materiales)
															{
																		       $num=$total_modelos/$maximo_modelos;
																			   $num_paginas=ceil($num);
																			   
															}
																	       else
																		   {
																		        if($total_modelos<=$maximo_modelos && $total_materiales>$maximo_materiales)
																		       	{
																				 
																				   $num=$total_materiales/$maximo_materiales;
																				   $num_paginas=ceil($num);
																				}
																		        else
																				{
																				    if($total_modelos>$maximo_modelos && $total_materiales>$maximo_materiales)
																			       	{
																					
																					    $paginas_mat=$total_materiales/$maximo_materiales;
																						$paginas_materiales=ceil($paginas_mat);
																					    $paginas_mod=$total_modelos/$maximo_modelos;
																						$paginas_modelos=ceil($paginas_mod);
																						
																				$num_paginas=$paginas_modelos*$paginas_materiales;
																				    }
																				}
																		   }
																	  }
													  
							 $cabecera_orden=$orden->obtener_cabecera_orden($orden_id);
							 $smarty->assign('orden_id', $orden_id);
							 $smarty->assign('cabecera_orden', $cabecera_orden);
						  	 $smarty->assign('resumen_despiece',$resumen_despiece);
							 $smarty->assign('maximo_modelos',$maximo_modelos);
							 $smarty->assign('maximo_materiales',$maximo_materiales);
							 $smarty->assign('tamanio',$tamanio);
							 $smarty->assign('resumen_modelos',$resumen_modelos);
							 $smarty->assign('resumen_materiales',$resumen_materiales);
							 $smarty->assign('total_modelos',count($resumen_modelos));
							$smarty->assign('total_materiales',count($resumen_materiales));
							$smarty->assign('num_paginas',$num_paginas);
					 		$smarty->assign('nombres', $_SESSION["nombres"]);
					        $smarty->assign('apellidos', $_SESSION["apellidos"]);
					      
							
							if (isset($_GET['fecha'])){
								$despiece->actualizar_fecha_fin_despiece($orden_id);
							}
							
					        $smarty->display('sistema_de_produccion/despiece/hoja_de_resumen_despiece1.html');
																 }
								
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	
																	}
																	
																	
																	
																 
																 }
															 }
														  
														  }
													   
													   
													   }
												   
												   
												   }
												
												}
											
											}
									   }
								   }
								   
								}
						    
						   }
					   
					   }
				      
				  }  
			 
			 
			 
			 } 
	   }

	}
}
?>