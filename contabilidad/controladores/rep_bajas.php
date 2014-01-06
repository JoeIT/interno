<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
header ("Cache-Control: no-cache, must-revalidate ");

define('CLAS', "../clases");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../clases/activo.php');
include_once('../clases/locprimaria.php');
include_once('../clases/locsecundaria.php');
include_once('../clases/grupo.php');
include_once('../clases/responsable.php');
include_once('../clases/moneda.php');
include_once('../clases/adquisicion.php');
include_once('../clases/asignacion.php');
include_once('../clases/tipocambio.php');
include_once('../clases/gestion.php');
include_once('../clases/ActualizacionTodo.php');

include_once('../clases/includes/validador.php');
include("excelwriter.inc.php");
$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';


$activo= new Activo();
$primaria=new LocPrimaria();
$secundaria=new LocSecundaria();
$grupo= new Grupo();
$responsable= new Responsable();
$moneda= new Moneda();
$adquisicion= new Adquisicion();
$asignacion= new Asignacion();
$tipo_cambio=new TipoCambio();
$gestion=new Gestion();

$acttodo=new ActualizacionTodo();

function escribir_todo_excel($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante)
						       {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Fecha Baja", "T/C baja","UFV baja","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra","Valor de la otra gestion a ".$fecha_gestion_ante."$".$valor_dolar_ges." UFV ".$valor_ufv_ges,
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv
	,"Valor al".$fecha_fin."con UFV".$valor_ufv2,
	"Depreciacion Acum Actualiza".$fecha_fin."con UFV".$valor_ufv2,"Valor Neto s/g Actual Ufv al".$fecha_fin."con UFV".$valor_ufv2,"Vida Util Restante");
	$celdas=sizeof($myArr);
	$excel->writeCabecera($myArr);
	
	$suma_compra_total=0;
	$suma_gestion_total=0;
	//$suma_valorufv1_total=0;
	//$suma_incremento_total=0;
	$suma_valorufv2_total=0;
	//$suma_depre_acumulada_total=0;
	//$suma_incremento_depre_total=0;
	//$suma_depre_periodo_total=0;
	$suma_depre_fin_total=0;
	$suma_neto_total=0;
	
	foreach($datos as $key => $value)
	{ 
	     $grupo=$key;
		  $excel->writeCabeceraGrupo($grupo, $celdas);
		
	  
	  $suma_compra=0;
	  $suma_gestion=0;
	  //$suma_valorufv1=0;
	 // $suma_incremento=0;
	  $suma_valorufv2=0;
	 // $suma_depre_acumulada=0;
	 // $suma_incremento_depre=0;
	 // $suma_depre_periodo=0;
	  $suma_depre_fin=0;
	  $suma_neto=0;
	  
	  foreach($value as $llave => $val)
	  {	
	  	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$fecha_baja=$val['fecha_baja'];
		$baja_dolar=$val['TC_baja'];
		$baja_ufv=$val['UFV_baja'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		$gestion = $val['gestion'];
		$val_ufv=$valor_ufv;		
		//$valor_ufv1=$val['valor_ufv'];
		//$incremento_ufv=$val['incremento_ufv'];
		$valor_ufv2=$val['valor_ufv2'];
		//$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		//$incremento_depre_ufv=$val['incremento_depre_ufv'];
		//$depre_periodo_ufv=$val['depre_periodo_ufv'];
		$depre_fin_ufv=$val['depre_fin_ufv'];
		$neto=$val['neto'];
		$vida_restante=$val['vida_restante'];
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_gestion=$suma_gestion+$gestion;
		//$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		//$suma_incremento=$suma_incremento+$incremento_ufv;
		$suma_valorufv2=$suma_valorufv2+$valor_ufv2;
		//$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada_ufv;
		//$suma_incremento_depre=$suma_incremento_depre+$incremento_depre_ufv;
		//$suma_depre_periodo=$suma_depre_periodo+$depre_periodo_ufv;
		$suma_depre_fin=$suma_depre_fin+$depre_fin_ufv;
		$suma_neto=$suma_neto+$neto;
		
		

		
		
		$myArr=array($numero,$fecha,$fecha_baja,$baja_dolar,$baja_ufv,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$gestion,$val_ufv,$valor_ufv2,
				$depre_fin_ufv,$neto,$vida_restante);
				
		$excel->writeLine($myArr);
	  	
		}
		
		$sumasarray=array(" "," "," "," "," "," "," "," "," ",
					$suma_compra,$suma_gestion," ",
					$suma_valorufv2,
					$suma_depre_fin,$suma_neto," ");
		$excel->writeLineTotales($sumasarray);
		
		$suma_compra_total=$suma_compra_total+$suma_compra;
		$suma_gestion_total=$suma_gestion_total+$suma_gestion;
		//$suma_valorufv1_total=$suma_valorufv1_total+$suma_valorufv1;
		//$suma_incremento_total=$suma_incremento_total+$suma_incremento;
		$suma_valorufv2_total=$suma_valorufv2_total+$suma_valorufv2;
		//$suma_depre_acumulada_total=$suma_depre_acumulada_total+$suma_depre_acumulada;
		//$suma_incremento_depre_total=$suma_incremento_depre_total+$suma_incremento_depre;
		//$suma_depre_periodo_total=$suma_depre_periodo_total+$suma_depre_periodo;
		$suma_depre_fin_total=$suma_depre_fin_total+$suma_depre_fin;
		$suma_neto_total=$suma_neto_total+$suma_neto;
		
		
		
	}
$sumastotales=array(" "," "," "," "," "," "," "," "," ",
					$suma_compra_total,$suma_gestion_total," ",
					$suma_valorufv2_total,
					$suma_depre_fin_total,$suma_neto_total," ");
		$excel->writeLineTotales($sumastotales);
	
		
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
	
}

if (!isset($_SESSION['logeo'])) {
	header("Location: ../index_logeo.php");
} else {	
		      	
				$todo=$_GET['todo'];
				
				$fecha_inicio1 = trim($_GET["fecha_inicio"]);
				$fecha_fin1 = trim($_GET["fecha_fin"]);
				$fecha_inter=trim($_GET['corte']);

				$gest=$_GET['gest'];
			 	$fecha_gestion=$gestion->fecha_gestion($gest);
			 	$fecha_gestion_ante=$fecha_gestion['fecha_inicio'];
			 	
			 	$fecha_ges_ini=$fecha_gestion['fecha_inicio'];
			 	$fecha_ges_fin=$fecha_gestion['fecha_fin'];
			 	$corte ='2007-03-31'; 
			 	$fecha_ges=$fecha_gestion['fecha_inicio'];
			 	$fecha_gestion_ante=$activo->diferencia_fechas_undia($fecha_ges);
			 	$gestion=$gestion->listar_gestion();
			 		
			 	
					
				 if ($fecha_inter=='')
				 {

				  $error['fecha_inter'] = "Introduzca fecha de corte";
				  $smarty->assign('error',$error);
			     
				 
				 }
				
				if ($fecha_fin1 < $fecha_inicio1)
				{
					 $error['fecha'] = "La fecha de finalizaci&oacute;n debe ser mayor";
			         $smarty->assign('error',$error);
			        
			
				 }		
				 if ($todo=='')
				 {   $error['todo'] = "Seleccione una de las opciones para generar el Reporte";
			         $smarty->assign('error',$error);
					  
			
				 }
			         
			     if ($_GET['gest']=='selc')
				 {   $error['gest'] = "Seleccione la Gestion";
			         $smarty->assign('error',$error);
			    
			
				 }   
				 if ($fecha_inicio1 < $fecha_ges_ini || $fecha_fin1 > $fecha_ges_fin)
				 {
				 	 $error['control'] = "Las fechas del reporte no corresponden a la de la Gestion Seleccionada";
			         $smarty->assign('error',$error);
			        
				 }    
				
			if (isset($error))
			{
				
				  $primaria=$primaria->listar_locprimaria();
				  $grupo=$grupo->listar_grupo();													
				   
				   	$smarty->assign('fecha_inicio',$fecha_inicio1);
					$smarty->assign('fecha_fin',$fecha_fin1);
					$smarty->assign('error', $error);
					$smarty->assign('primaria', $primaria);								
					$smarty->assign('grupo', $grupo);
					$smarty->assign('corte',$corte);	
					$smarty->assign('gest',$gest);
					$smarty->assign('gestion', $gestion);					
					$smarty->assign('todo', $todo);	
					$smarty->assign('repor', $repor);	
					$smarty->display('rep_baja.html');	
							
										
				} 
	 	 else
	 		 { if ($todo==1)
	  	   		{  
	  			
	  	   			//$calculo=$activo->calcular_actualizacion_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion_ante);
	  	   		 		
					//  $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
	  	   			
				    if ($fecha_antes == $fecha_inter && $fecha_fin1 >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
					{

					 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
					 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
					 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
						
				/***Dato de l agestion anterior****/
							
					$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
					$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
														
							
					$act='bajas-'.$fecha_inicio.'-'.$fecha_fin;
		  	   		$calculo=$activo->calcular_gasto_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion,$fecha_ges_ini,$fecha_ges_fin);
	
			  	   	if ($calculo != null)
					   {
					     escribir_dolar_ufv_excel($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante);
						 $smarty->assign('excel',$act);
					    }
					
					
					 $smarty->assign('ver','si');
							
					}
		 	
			 	 else 
					{  
					 if ($fecha_inicio1 < $fecha_inter && $fecha_fin1 <= $fecha_inter)
				   {
				   	
				   	 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
		 			 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
						
					 $valor_ufv=0;//valor en ufv al 31-03-2007
					 
									 
				   	 $smarty->assign('ver1','si');
				   	 
				   	 
				   	
				   }
				    else 
				   {
				   	if ($fecha_inicio1 > $fecha_inter && $fecha_fin1 > $fecha_inter)
				   	{   
				   		if ( $fecha_inicio1 >= $fecha_ges_ini && $fecha_fin1 <= $fecha_ges_fin )
				   		{	
				   			
						$fecha_fin= $activo->diferencia_fechas_undia($fecha_inicio1);
						$fecha_inicio=$activo->Primerdiames($fecha_fin);
				
			
				   			 $fecha_antes=$fecha_inicio;
		 			        
				   		    $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
					   
							/***Dato de l agestion anterior****/
							
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
														
							
							$act='Bajas un perido antes-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				//$calculo=$acttodo->calcular_bajas_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion,$fecha_ges_ini,$fecha_ges_fin);
							$calculo=$acttodo->calcular_bajas_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion_ante,$gest);
						
			  	   			if ($calculo != null)
						       {
					
						          escribir_todo_excel($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante);
						          $smarty->assign('excel',$act);
						        }
						        
								
				   		    $smarty->assign('ver2','si');
				   		 
				   		}
				   		 
				   		 
				   		 
				   	}
				   	else 
				   	{
				   		if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   		{
				   			$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   			 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							 
							 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);
							
				   			 $smarty->assign('ver3','si');
				   			 
				   		
				   		}
				   
					}
		
			 	}
		
			
		 	
		 
				}	
	  	   			  /*echo "<pre>";
	  	   			  print_r($calculo);
	  	   			  echo "</pre>";
						*/
	  	   			    $grupo=$grupo->listar_grupo();	
				        $primaria=$primaria->listar_locprimaria();	
						$smarty->assign('primaria', $primaria);													
						$smarty->assign('grupo', $grupo);
				
						$smarty->assign('lista_activo', $calculo);	
						$smarty->assign('fecha_antes', $fecha_antes);	
						$smarty->assign('fecha_inicio', $fecha_inicio);
						$smarty->assign('fecha_fin', $fecha_fin);
						$smarty->assign('fecha_inter', $fecha_inter);
						$smarty->assign('valor_dolar', $valor_dolar);	
						$smarty->assign('valor_dolar2', $valor_dolar2);	
					    $smarty->assign('valor_ufv', $valor_ufv);	
						$smarty->assign('valor_ufv2', $valor_ufv2);	
						$smarty->display('impresion_actualizacion.html');
	  	   			  
							
							
						
					
	  	   		
	  	   			
			
	  }
	  // empieza la opcion 2 especifico
	  	
			
	
		  
        	
		 }
		       
                
        
		
      }
	
		
       
 
       
       
  

?>