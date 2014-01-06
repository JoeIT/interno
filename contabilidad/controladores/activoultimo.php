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

include_once('../clases/includes/validador.php');

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

/*******************************************************************************************************************
esto sirve para  separar el numero del activo e insertarlos por separado
$acti = $activo->numero();


foreach ($acti as $indice=> $valor) 
{
		
	 $a[$indice]=$valor['numero'];
	 $numero=str_replace(" ","",$a[$indice]);
	 $act_id=$valor['act_id'];
	
		 $num=explode(".",$numero);
	  	
	
		  $localizacion=$num[0];
	  	  $locsecundaria=$num[1];
		  $grupo=$num[2];
		  $num_act=$num[3];
		
			
	 		
	$a1 = $activo->insertar_numero($localizacion,$locsecundaria,$grupo,$num_act,$act_id,$numero);
   }

/*
echo "<pre>";
print_r($e);
echo "</pre>";*/
	
/*$smarty->assign('acti', $acti);
$smarty->display('activos.html');*/
/*echo "fndsjgkdgj<pre>";
print_r($_SESSION['nun']);
echo "</pre>";*/
/***************************************************************************************************************/



if (!isset($_SESSION['logeo'])) {
	header("Location: ../index_logeo.php");
}

else {
		
	        //$smarty->display('fotos.html');
	
		$opcion = $_GET['opcion'];
		switch ($opcion){
			
			case 'registrar_foto':
						
			  	 	//echo $act_id=$_GET['act_id'];
			  	 	 $numero=$_GET['numero'];
			  	 	 //$smarty->assign('act_id', $act_id);
			  	 	 $smarty->assign('numero', $numero);
					 $smarty->display('fotos.html');
				
			break;
			
			case 'registrar_activo':
				 	
				 	
					$primaria=$primaria->listar_locprimaria();
					//$secundaria=$secundaria->listar_locsecundaria();
					$grupo=$grupo->listar_grupo();
					$moneda=$moneda->listar_moneda();
					$adqui=$adquisicion->listar_adquisicion();
				    $fecha=date("Y-m-d");
				    
				 
					$smarty->assign('primaria', $primaria);					
					//$smarty->assign('secundaria', $secundaria);
					$smarty->assign('grupo', $grupo);
					$smarty->assign('moneda', $moneda);
					$smarty->assign('adqui', $adqui);
					$smarty->assign('fecha', $fecha);
					$smarty->assign('ver','si');
					
					$smarty->display('registrar_activo.html');
					
			break;
		
		
		case 'busqueda_ajax' : {
			//recuperando las variables
			$nombre =  utf8_decode(trim($_POST["nombre"]));
			
			
			echo "<ul>";
			$lista = $responsable->busqueda_responsable($nombre);

			if(count($lista) == 0){
				echo "<li>No hay resultados</li>";
			} else {
				for($contador = 0; $contador < count($lista); $contador ++){
					$detalles = $lista[$contador]["resp_id"];
					echo '<li id="'.$detalles.'">'.$lista[$contador]["completo"].'</li>';
				}
			}
			echo "</ul>";
			
			break;
		}
		case 'busqueda_ajax1' : {
			//recuperando las variables
			$nombre =  utf8_decode(trim($_POST["nombre"]));
			
			
			echo "<ul>";
			$lista = $responsable->busqueda_responsable($nombre);

			if(count($lista) == 0){
				echo "<li>No hay resultados</li>";
			} else {
				for($contador = 0; $contador < count($lista); $contador ++){
					$detalles = $lista[$contador]["resp_id"];
					echo '<li id="'.$detalles.'">'.$lista[$contador]["completo"].'</li>';
				}
			}
			echo "</ul>";
			
			break;
		}
		case 'modificar_activo':
				
					$primaria=$primaria->listar_locprimaria();
					$secundaria=$secundaria->listar_locsecundaria();
					$grupo=$grupo->listar_grupo();
					
					
					$smarty->assign('primaria', $primaria);					
					$smarty->assign('secundaria', $secundaria);
					$smarty->assign('grupo', $grupo);
					
					
					$smarty->display('listar_activo.html');
			break;
			
		case 'modificar_activo_completo':
				
					$primaria=$primaria->listar_locprimaria();
					$secundaria=$secundaria->listar_locsecundaria();
					$grupo=$grupo->listar_grupo();
					
					
					$smarty->assign('primaria', $primaria);					
					$smarty->assign('secundaria', $secundaria);
					$smarty->assign('grupo', $grupo);
					
					
					$smarty->display('listar_activo_completo.html');
			break;

			case 'actualizacion':
					$fecha_inicio = date("Y-m-d"); 
			   		$fecha_fin = date("Y-m-d"); 
			   		$primaria=$primaria->listar_locprimaria();
					$grupo=$grupo->listar_grupo();													
				
					$smarty->assign('primaria', $primaria);	
					$smarty->assign('grupo', $grupo);	
					$smarty->assign('fecha_inicio',$fecha_inicio);
					$smarty->assign('fecha_fin',$fecha_fin);
					$smarty->display('actualizacion.html');
					
					
			break;
			
}
		
		if (isset($_GET['insertar_activo']))
		{
			
			if($_GET['funcion']== "validar")
			{    $error="";
				 $validar = new Validador();
				
				 if ($_GET['pri']=="selc"|| $_GET['pri']=='')
			 		$error['err_pri'] = "Selecione localizacion primaria";
				 if ($_GET['secun']=='selc' || $_GET['secun']=='')
			 		$error['err_sec'] = "Selecione localizacion secundaria";	
			 	 if ($_GET['gru']=='selc' || $_GET['gru']=='')
			 		$error['err_gru'] = "Selecione cuenta";
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
			 		
				if($error!="")
				{   
					
					$fecha=date("Y-m-d");
					$primaria_id=$_GET['pri'];
				    $secundaria_id=$_GET['secun'];
				    $grupo_id=$_GET['gru'];
				    $num_act=$_GET['num_corr'];
				    $nombre_asis2=$_GET['nombre_asis2'];
					$nombre_asis=$_GET['nombre_asis'];	
					$resp_id2=$_GET['$resp_id2'];
					$resp_id=$_GET['resp_id'];
					$descripcion=$_GET['descripcion'];
					$valor=$_GET['valor'];
					$residual=$_GET['residual'];
					$ad_id=$_GET['adqui'];
					$serie=$_GET['serie'];
					$unidad=$_GET['unidad'];
					$vida_util=$_GET['vida_util'];
					
					
					
					if($primaria_id=="selc" && $secundaria_id=="selc" && $grupo_id=="selc" && $nombre_asis2=='' && $nombre_asis=='' && $descripcion='' && $valor='' && $residual=''&& $ad_id=="selc")
					{ 
						

					  	 $fecha=date("Y-m-d");
						 $primaria=$primaria->listar_locprimaria();
						 $grupo=$grupo->listar_grupo();
						 $moneda=$moneda->listar_moneda();
						 $adqui=$adquisicion->listar_adquisicion();
						  			  
					
					  	      $smarty->assign('grupo', $grupo);
							  $smarty->assign('moneda', $moneda);
							  $smarty->assign('adqui', $adqui);
							  $smarty->assign('primaria', $primaria);
							  $smarty->assign('fecha', $fecha);
							  $smarty->assign('adqui', $adqui);
							  $smarty->assign('ver','si');
					  
					  
					    
					}
					else
					{
						
						$fecha=date("Y-m-d");
						
						$prima1=$primaria->localizacionpri($primaria_id);
						//$primaria=$primaria->listar_primarias($primaria_id);
							$primaria=$primaria->listar_locprimaria();
						$secunda=$secundaria->localizacionsec($secundaria_id);
						$secundaria=$secundaria->consulta_lista_secundaria($primaria_id);
						
						$gru=$grupo->locgru($grupo_id);
						$correlativo= $grupo->contar_numcorr($grupo_id);
						//$grupo=$grupo->listar_gru($grupo_id);
						$grupo=$grupo->listar_grupo();
						
						//$adqui=$adquisicion->listar_adqui($ad_id);
						$adqui=$adquisicion->listar_adquisicion();
						$ad=$adquisicion->lisadqui($ad_id);
						/*
						echo "<pre>";
						print_r($ad);
						echo "</pre>";
						*/
						
						$moneda=$moneda->listar_moneda();
						
						$smarty->assign('prima1', $prima1);	
						$smarty->assign('primaria', $primaria);	
						
						$smarty->assign('secunda', $secunda);	
						$smarty->assign('secundaria', $secundaria);	
						$smarty->assign('nombre_asis2',$nombre_asis2);	
						$smarty->assign('resp_id2',$resp_id2);
						$smarty->assign('nombre_asis',$nombre_asis);	
						$smarty->assign('resp_id',$resp_id);
						$smarty->assign('descripcion', $descripcion);
						$smarty->assign('valor', $valor);
						$smarty->assign('residual', $residual);
						
						$smarty->assign('ad',$ad);
						$smarty->assign('adqui',$adqui);
						
						$smarty->assign('serie', $serie);
						$smarty->assign('unidad', $unidad);
						$smarty->assign('vida_util', $vida_util);
						$smarty->assign('fecha', $fecha);
						$smarty->assign('gru', $gru);
						$smarty->assign('grupo', $grupo);
						$smarty->assign('correlativo', $correlativo);
						$smarty->assign('moneda', $moneda);
							
					    $smarty->assign('num_act', $num_act);		
						$smarty->assign('ver2','si');
						
						
						
					
					
					
					}

					
					
					
					
			  	 $smarty->assign('errores',$error);
		 		 $smarty->display('registrar_activo.html');
				}
				else
				{
				  $primaria_id=$_GET['pri'];
				  $secundaria_id=$_GET['secun'];
				  $grupo_id=$_GET['gru'];
				  $num_act=$_GET['num_corr'];
					
				  
				  
				  
				  
					$locapri=$primaria->localizacionpri($primaria_id);
					$localizacion=$locapri['localizacion'];
					
					
					$locsecun1=$secundaria->localizacionsec($secundaria_id);
					$locsecundaria=$locsecun1['locsecundaria'];
					
					$gru1=$grupo->locgru($grupo_id);
					$locgrupo=$gru1['grupo'];
					
					if ($localizacion < 10 )
					{$localizacion="0".$localizacion;}
					else 
					{$localizacion=$localizacion;}
			
					if ($locsecundaria <10 )
					{$locsecundaria="0".$locsecundaria;}
					else 
					{$locsecundaria=$locsecundaria;}
					
					if ($locgrupo <10 )			
					{$locgrupo="0".$locgrupo;}
					else 
					{$locgrupo=$locgrupo;}	
					
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
					
					
					
			    	$nombre1=$_GET['nombre_asis2'];
					$nombre=$_GET['nombre_asis'];
					$resp_id=$_GET['resp_id'];
					$resp_pri=$_GET['resp_id2'];
				
					
					echo "dsffd".$tipo_cambio=$_GET['tipo'];
					$ufv=$_GET['ufv'];
					$cantidad=$_GET['cantidad'];
					$unidad=$_GET['unidad'];
					$vida_util=$_GET['vida_util'];
			
					
					if( $_GET['serie'] =='')
					$serie='NULL';
					else
					$serie=$_GET['serie'];
						
					$descripcion=$_GET['descripcion'];
					$valor_compra=$_GET['valor'];
					$residual=$_GET['residual'];
					$usuario_id=$_SESSION['usuario_id'];
					$fecha = trim($_GET["fecha_inicio"]);
			
			
			 		$ad_id=$_GET['adqui'];
					$numero=$localizacion.".".$locsecundaria.".".$locgrupo.".".$num_act1;
			 
					$acti=$activo->insertar_activo($numero,$descripcion,$serie,$fecha,$vida_util,$valor_compra,$tipo_cambio,$ad_id,$localizacion,$locsecundaria,$grupo_id,$resp_id,$num_act,$unidad,$cantidad,$residual,$resp_pri,$ufv);
			 	    $id = mysql_insert_id();
			
			 		$resultado=$asignacion->insertar_asignacion($id,$usuario_id,$resp_id,$fecha,$secundaria_id,$primaria_id,$resp_pri);	
				    $mostrar_foto=$activo->detalle_activo($id);
		  
		//   if($resultado!=0)
		  // {	    
		   			
		   			$primaria=$primaria->listar_locprimaria();
					$secundaria=$secundaria->listar_locsecundaria();
					$grupo=$grupo->listar_grupo();
					$moneda=$moneda->listar_moneda();
					$adqui=$adquisicion->listar_adquisicion();
					$fecha=date("Y-m-d");
				    		    
					$smarty->assign('primaria', $primaria);					
					//$smarty->assign('secundaria', $secundaria);
					$smarty->assign('grupo', $grupo);
					$smarty->assign('moneda', $moneda);
					$smarty->assign('adqui', $adqui);
					$smarty->assign('fecha', $fecha);
					$smarty->assign('mostrar_foto',$mostrar_foto);
					$smarty->assign('verfoto','si');
			  	$error['err_confirm'] = "El registro se realizo correctamente";
		
			 	
			
				 $smarty->assign('errores',$error);
				$smarty->display('registrar_activo.html');
				
		 //  }
		   
	
		 }
	}
	else
	{ 
	   
		
		
		header("Location: activo.php?opcion=registrar_activo");
	}
			
}
if (isset($_GET['listar_activo']))
	{
			$primaria_id=$_GET['pri'];
			$secundaria_id=$_GET['secun'];
			$grupo_id=$_GET['gru'];
			$num_act=$_GET['num_act'];

				 if ($_GET['pri']=='selc')
			 		$error['err_pri'] = "Selecione localizacion primaria";
				 if ($_GET['secun']=='selc')
			 		$error['err_sec'] = "Selecione localizacion secundaria";	
			 	 if ($_GET['gru']=='selc')
			 		$error['err_gru'] = "Selecione cuenta";
			 		
			 		if($error!="")
					{
					$primaria=$primaria->listar_locprimaria();
					$secundaria=$secundaria->listar_locsecundaria();
					$grupo=$grupo->listar_grupo();
					
					
					$smarty->assign('primaria', $primaria);					
					$smarty->assign('secundaria', $secundaria);
					$smarty->assign('grupo', $grupo);
					$smarty->assign('errores',$error);
					
					$smarty->display('listar_activo.html');
						
					
					}
					
					else
					{ 
						if ($primaria_id !=''& $secundaria_id !='' & $grupo_id !=''& $num_act !='')
						 {	
			 				$locsecun1=$secundaria->localizacionsec($secundaria_id);
							$locsecundaria=$locsecun1['locsecundaria'];
				
							$locprima=$primaria->localizacionpri($primaria_id);
							$localizacion=$locprima['localizacion'];
				
				 		    $lista1=$activo->listacompleta($localizacion,$locsecundaria,$grupo_id,$num_act);
				  			$smarty->assign('lista1',$lista1);
				  			if ($lista1==0)
				  			{
				  				$error="No se encontro ningun activo";
				 				$smarty->assign('error',$error);
				  				
				  				
				  			}			  
						 }
						 
				 	
				 					 	
						 	
						 if($primaria_id !=''& $secundaria_id !='' & $grupo_id !=''& $num_act =='')	
						  	{
			  				 $locsecun1=$secundaria->localizacionsec($secundaria_id);
							 $locsecundaria=$locsecun1['locsecundaria'];

				 			$locprima=$primaria->localizacionpri($primaria_id);
							 $localizacion=$locprima['localizacion'];
			  	 
							 $lista1=$activo->listaactivos1($localizacion,$locsecundaria,$grupo_id);
							 $smarty->assign('lista1',$lista1);
				  
							 if ($lista1==0)
				  			{
				  				$error="No se encontro ningun activo";
				 				$smarty->assign('error',$error);
				  				
				  				
				  			}		
							 
			  				}
			  			
			  	
				 
				 $primaria=$primaria->listar_locprimaria();
				 $secundaria=$secundaria->listar_locsecundaria();
				 $grupo=$grupo->listar_grupo();
								
				 $smarty->assign('primaria', $primaria);					
				 $smarty->assign('secundaria', $secundaria);
				 $smarty->assign('grupo', $grupo);
				
				 $smarty->assign('busqueda1','si');
				
			     $smarty->display('listar_activo.html');
					}
		}
		
if (isset($_GET['listar_activo_completo']))
	{
			$primaria_id=$_GET['pri'];
			$secundaria_id=$_GET['secun'];
			$grupo_id=$_GET['gru'];
			$num_act=$_GET['num_act'];

				 if ($_GET['pri']=='selc')
			 		$error['err_pri'] = "Selecione localizacion primaria";
				 if ($_GET['secun']=='selc')
			 		$error['err_sec'] = "Selecione localizacion secundaria";	
			 	 if ($_GET['gru']=='selc')
			 		$error['err_gru'] = "Selecione cuenta";
			 		
			 		if($error!="")
					{
					$primaria=$primaria->listar_locprimaria();
					$secundaria=$secundaria->listar_locsecundaria();
					$grupo=$grupo->listar_grupo();
					
					
					$smarty->assign('primaria', $primaria);					
					$smarty->assign('secundaria', $secundaria);
					$smarty->assign('grupo', $grupo);
					$smarty->assign('errores',$error);
					
					$smarty->display('listar_activo_completo.html');
						
					
					}
					
					else
					{ 
						if ($primaria_id !=''& $secundaria_id !='' & $grupo_id !=''& $num_act !='')
						 {	
			 				$locsecun1=$secundaria->localizacionsec($secundaria_id);
							$locsecundaria=$locsecun1['locsecundaria'];
				
							$locprima=$primaria->localizacionpri($primaria_id);
							$localizacion=$locprima['localizacion'];
				
				 		    $lista1=$activo->listacompleta($localizacion,$locsecundaria,$grupo_id,$num_act);
				  			$smarty->assign('lista1',$lista1);
				  			if ($lista1==0)
				  			{
				  				$error="No se encontro ningun activo";
				 				$smarty->assign('error',$error);
				  				
				  				
				  			}			  
						 }
						 
				 	
				 					 	
						 	
						 if($primaria_id !=''& $secundaria_id !='' & $grupo_id !=''& $num_act =='')	
						  	{
			  				 $locsecun1=$secundaria->localizacionsec($secundaria_id);
							 $locsecundaria=$locsecun1['locsecundaria'];

				 			$locprima=$primaria->localizacionpri($primaria_id);
							 $localizacion=$locprima['localizacion'];
			  	 
							 $lista1=$activo->listaactivos1($localizacion,$locsecundaria,$grupo_id);
							 $smarty->assign('lista1',$lista1);
				  
							 if ($lista1==0)
				  			{
				  				$error="No se encontro ningun activo";
				 				$smarty->assign('error',$error);
				  				
				  				
				  			}		
							 
			  				}
			  			
			  	
				 
				 $primaria=$primaria->listar_locprimaria();
				 $secundaria=$secundaria->listar_locsecundaria();
				 $grupo=$grupo->listar_grupo();
								
				 $smarty->assign('primaria', $primaria);					
				 $smarty->assign('secundaria', $secundaria);
				 $smarty->assign('grupo', $grupo);
				
				 $smarty->assign('busqueda1','si');
				
			     $smarty->display('listar_activo_completo.html');
					}
		}
		
		if (isset($_GET['actualizacion']))
		{
				//buscamos el detalle
				
				$todo=$_GET['todo'];
				
				$fecha_inicio = trim($_GET["fecha_inicio"]);
				$fecha_fin = trim($_GET["fecha_fin"]);
				$fecha_inter=trim($_GET['corte']);
	
			 	
					
				 if ($fecha_inter=='')
				 {

				  $error['fecha_inter'] = "Introduzca fecha de corte";
				  $smarty->assign('error',$error);
			     
				 
				 }
				
				if ($fecha_fin < $fecha_inicio)
				{
					 $error['fecha'] = "La fecha de finalizaci&oacute;n debe ser mayor";
			         $smarty->assign('error',$error);
			         $smarty->assign('fecha_inicio',$fecha_inicio);
			         $smarty->assign('fecha_fin',$fecha_fin);
			         
			
				 }		
				 if ($todo=='')
				 {  $error['todo'] = "Seleccione una de las opciones para generar el Reporte";
			         $smarty->assign('error',$error);}
					/*
				if ($valor_dolar==false)
				{
					$error['valor'] = "no existe tipo de cambio dolar  para esta fecha ".$fecha_inicio;	
							
					$smarty->assign('error',$error);
								
				}
				
					if ($valor_dolar2==false)
					{
						$error['valor'] = "no existe tipo de cambio dolar para esta fecha ".$fecha_fin;	
							
						$smarty->assign('error',$error);
					}
					
				
					if ($valor_ufv==false)
					{
						$error['valor'] = "no existe tipo de cambio UFV para esta fecha ".$fecha_ini;	
							
						$smarty->assign('error',$error);
					}
					
					if ($valor_ufv1==false)
					{
						$error['valor'] = "no existe tipo de cambio UFV para esta fecha ".$fecha_fin;	
							
						$smarty->assign('error',$error);
					}
					
									
					
				*/
			if (isset($error)){
				
				  $primaria=$primaria->listar_locprimaria();
				  $grupo=$grupo->listar_grupo();													
				
					$smarty->assign('error', $error);
					$smarty->assign('primaria', $primaria);								
					$smarty->assign('grupo', $grupo);					
					$smarty->display('actualizacion.html');			
										
				} 
	  else
	  { if ($todo==1)
	  	{
	  		$smarty->display('actualizacion.html');
	  		
	  	 }
	  
	  	
			
		if ($todo==2)	 	
		{  
			$grupo_id=$_GET['gru'];    
		    $pri=$_GET['pri'];
		    $secun=$_GET['secun'];
		    
		  if ($pri =='selc'& $secun =='selc' & $grupo_id =='selc')
		 {   
			if ($grupo_id=='selc')
		 	{	$error['err_gru'] = "Selecione cuenta";
		 		$smarty->assign('error', $error);
		 	}
		 	
		 }
		 else {
		 if ($pri !='selc'& $secun !='selc' & $grupo_id =='selc')
		 {
		 	if ($grupo_id=='selc')
		 	{	$error['err_gru'] = "Selecione cuenta";
		 		$smarty->assign('error', $error);
		 	}
		 	
		 }	
		 }
	if (isset($error)){
				
				  $primaria=$primaria->listar_locprimaria();
				  $grupo=$grupo->listar_grupo();													
				
					$smarty->assign('error', $error);
					$smarty->assign('primaria', $primaria);								
					$smarty->assign('grupo', $grupo);					
					$smarty->display('actualizacion.html');			
										
				} 
		 
		 
	else{
		
		    
		 if ($pri =='selc'& $secun =='selc' & $grupo_id !='')
		 {	 
		 	
		 	
		  	 $descripcion=$grupo->locgru($grupo_id);	
		  
			if ($fecha_inicio == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
				{	
					
					$fecha_act=$grupo->buscar_fecha_compra($grupo_id);
										
					foreach($fecha_act as $indice => $valor) {
						if ($valor['fecha'] <= $fecha_inter)
						{	
							$act_id=$valor['act_id'];
							//echo " <br>holalsaaaaa ".$valor["fecha"];
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inicio,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=1;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
							$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$indice]=$lista_activo;
																	
							
			    			
						}
						else 
						{
						   // echo "<br>vamonos".$valor["fecha"];
						
							$act_id=$valor['act_id'];
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);
							$validar=2;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
							$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$indice]=$lista_activo;
							
						}
				
						
							
						}
							$grupo=$grupo->listar_grupo();													
							$smarty->assign('grupo', $grupo);
							$smarty->assign('lista_activo', $resp);	
							$smarty->assign('fecha_inicio', $fecha_inicio);
							$smarty->assign('fecha_fin', $fecha_fin);
							$smarty->assign('fecha_inter', $fecha_inter);
							$smarty->assign('valor_dolar', $valor_dolar);	
			   				$smarty->assign('valor_dolar2', $valor_dolar2);	
			    			$smarty->assign('valor_ufv', $valor_ufv);	
			    			$smarty->assign('valor_ufv2', $valor_ufv2);	
			    			$smarty->assign('descripcion', $descripcion);	
							$smarty->assign('ver','si');
				 			$smarty->display('actualizacion.html');		
					
					
						
				  	
				}
						  
				
				
				else 
				{  
				 if ($fecha_inicio < $fecha_inter && $fecha_fin < $fecha_inter)
				   {
				   	$fecha_act=$grupo->buscar_fecha_compra($grupo_id);
									
					foreach($fecha_act as $indice => $valor) {
						if ($valor['fecha'] <= $fecha_inter)
						{	
							$act_id=$valor['act_id'];
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inicio,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
							$validar=3;
							$valor_ufv=0;//valor en ufv al 31-03-2007
							$valor_ufv2=0;//valor al final del reporte
							
							$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$indice]=$lista_activo;
							
							
						}
						else 
						{
						    echo "<br>vamonos".$valor["fecha"];
									
							
						}
					}
					
					
							$grupo=$grupo->listar_grupo();													
							$smarty->assign('grupo', $grupo);
							$smarty->assign('lista_activo', $resp);	
							$smarty->assign('fecha_inicio', $fecha_inicio);
							$smarty->assign('fecha_fin', $fecha_fin);
							$smarty->assign('fecha_inter', $fecha_inter);	
							$smarty->assign('valor_dolar', $valor_dolar);	
			   				$smarty->assign('valor_dolar2', $valor_dolar2);	
			    			$smarty->assign('valor_ufv', $valor_ufv);	
			    			$smarty->assign('valor_ufv2', $valor_ufv2);	
			    			$smarty->assign('descripcion', $descripcion);
							$smarty->assign('ver1','si');
				 			$smarty->display('actualizacion.html');	
					
					
					
								
				   }
				   else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	  { $fecha_act=$grupo->buscar_fecha_compra($grupo_id);
											
					foreach($fecha_act as $indice => $valor)
					 {
						if ($valor['fecha'] <= $fecha_inter)
						{
							 $act_id=$valor['act_id'];
							//echo " <br>holalsaaaaa ".$valor["fecha"];
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$validar=4;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inicio,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
							$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$indice]=$lista_activo;
																	
							
			    			
						}
						else 
						{
						    //echo "<br>vamonos".$valor["fecha"];
						
							$act_id=$valor['act_id'];
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=0;
							$validar=5;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inicio,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
							$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$indice]=$lista_activo;
							
						}
				
						
							
						}
							$grupo=$grupo->listar_grupo();													
							$smarty->assign('grupo', $grupo);
							$smarty->assign('lista_activo', $resp);	
							$smarty->assign('fecha_inicio', $fecha_inicio);
							$smarty->assign('fecha_fin', $fecha_fin);	
							$smarty->assign('fecha_inter', $fecha_inter);
							$smarty->assign('valor_dolar', $valor_dolar);	
			   				$smarty->assign('valor_dolar2', $valor_dolar2);	
			    			$smarty->assign('valor_ufv', $valor_ufv);	
			    			$smarty->assign('valor_ufv2', $valor_ufv2);	
			    			$smarty->assign('descripcion', $descripcion);
							$smarty->assign('ver2','si');
				 			$smarty->display('actualizacion.html');		
					
				   		
				   	}
				   	else 
				   	{
				   		if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   	    { $fecha_act=$grupo->buscar_fecha_compra($grupo_id);
											
						foreach($fecha_act as $indice => $valor) 
						{
						   if ($valor['fecha'] <= $fecha_inter)
						   {
							$act_id=$valor['act_id'];
							
							$valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inicio,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							$validar=6;
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
							$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
														
							$resp[$indice]=$lista_activo;
												
							
								
						}
						else
						{   $act_id=$valor['act_id'];
							$valor_dolar=0; //valor al comienzo del reporte
							$valor_dolar2=0;
							$validar=7;
							
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
							
							$lista_activo=$activo->listar_activo_grupo($grupo_id,$valor_dolar,$valor_dolar2,$valor_ufv,$valor_ufv2,$act_id,$validar);
							$resp[$indice]=$lista_activo;
						}
						
						}
				   		
				   			$grupo=$grupo->listar_grupo();													
							$smarty->assign('grupo', $grupo);
							$smarty->assign('lista_activo', $resp);	
							$smarty->assign('fecha_inicio', $fecha_inicio);
							$smarty->assign('fecha_fin', $fecha_fin);	
							$smarty->assign('fecha_inter', $fecha_inter);
							$smarty->assign('valor_dolar', $valor_dolar);	
			   				$smarty->assign('valor_dolar2', $valor_dolar2);	
			    			$smarty->assign('valor_ufv', $valor_ufv);	
			    			$smarty->assign('valor_ufv2', $valor_ufv2);	
			    			$smarty->assign('descripcion', $descripcion);
							$smarty->assign('ver3','si');
				 			$smarty->display('actualizacion.html');	
				   		
				   			
				   		}

				   }
				   	

				}
	
				
				
			}//hastaaqui
			
		 	}
        }	
        if($pri !=''& $secun !='' & $grupo_id !='')
        {
        	
        }
                
        
		
      }
	
		
       
	 }
       
       
       
       
       
       
     
 }

}
?>