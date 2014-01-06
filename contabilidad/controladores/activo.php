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
include_once('../clases/ActualizacionGrupo.php');

include_once('../clases/ActualizacionSec.php');

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
$actgrupo=new ActualizacionGrupo();
$actsec=new ActualizacionSec();
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
/**************************************************************************************************************/
/**************************SOLO UFV*********************************/
function escribir_todo_excel($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante)
						       {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra","Valor de la otra gestion a ".$fecha_gestion_ante."$".$valor_dolar_ges." UFV ".$valor_ufv_ges,
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv,"Valor al mes anterior".$fecha_antes."con".$valor_ufv,
	"Incremento por actualizacion AF con UFV","Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion Acumulada".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Dep Ac c/UFV".$fecha_fin."con UFV".$valor_ufv2,
	"Depreciacion Periodo UFV","Depreciacion Acum Actualiza".$fecha_fin."con UFV".$valor_ufv2,"Valor Neto s/g Actual Ufv al".$fecha_fin."con UFV".$valor_ufv2,"Vida Util Restante");
	$celdas=sizeof($myArr);
	$excel->writeCabecera($myArr);
	
	$suma_compra_total=0;
	$suma_gestion_total=0;
	$suma_valorufv1_total=0;
	$suma_incremento_total=0;
	$suma_valorufv2_total=0;
	$suma_depre_acumulada_total=0;
	$suma_incremento_depre_total=0;
	$suma_depre_periodo_total=0;
	$suma_depre_fin_total=0;
	$suma_neto_total=0;
	
	foreach($datos as $key => $value)
	{ 
	     $grupo=$key;
		  $excel->writeCabeceraGrupo($grupo, $celdas);
		
	  
	  $suma_compra=0;
	  $suma_gestion=0;
	  $suma_valorufv1=0;
	  $suma_incremento=0;
	  $suma_valorufv2=0;
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	  $suma_depre_fin=0;
	  $suma_neto=0;
	  
	  foreach($value as $llave => $val)
	  {	
	  	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		$gestion = $val['gestion'];
		$val_ufv=$valor_ufv;		
		$valor_ufv1=$val['valor_ufv'];
		$incremento_ufv=$val['incremento_ufv'];
		$valor_ufv2=$val['valor_ufv2'];
		$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		$incremento_depre_ufv=$val['incremento_depre_ufv'];
		$depre_periodo_ufv=$val['depre_periodo_ufv'];
		$depre_fin_ufv=$val['depre_fin_ufv'];
		$neto=$val['neto'];
		$vida_restante=$val['vida_restante'];
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_gestion=$suma_gestion+$gestion;
		$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		$suma_incremento=$suma_incremento+$incremento_ufv;
		$suma_valorufv2=$suma_valorufv2+$valor_ufv2;
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada_ufv;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre_ufv;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo_ufv;
		$suma_depre_fin=$suma_depre_fin+$depre_fin_ufv;
		$suma_neto=$suma_neto+$neto;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$gestion,$val_ufv,$valor_ufv1,
				$incremento_ufv,$valor_ufv2,$depre_acumulada_ufv,
				$incremento_depre_ufv,$depre_periodo_ufv,$depre_fin_ufv,$neto,$vida_restante);
				
		$excel->writeLine($myArr);
	  	
		}
		
		$sumasarray=array(" "," "," "," "," "," ",
					$suma_compra,$suma_gestion," ",$suma_valorufv1,$suma_incremento,
					$suma_valorufv2,$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo,$suma_depre_fin,$suma_neto," ");
		$excel->writeLineTotales($sumasarray);
		
		$suma_compra_total=$suma_compra_total+$suma_compra;
		$suma_gestion_total=$suma_gestion_total+$suma_gestion;
		$suma_valorufv1_total=$suma_valorufv1_total+$suma_valorufv1;
		$suma_incremento_total=$suma_incremento_total+$suma_incremento;
		$suma_valorufv2_total=$suma_valorufv2_total+$suma_valorufv2;
		$suma_depre_acumulada_total=$suma_depre_acumulada_total+$suma_depre_acumulada;
		$suma_incremento_depre_total=$suma_incremento_depre_total+$suma_incremento_depre;
		$suma_depre_periodo_total=$suma_depre_periodo_total+$suma_depre_periodo;
		$suma_depre_fin_total=$suma_depre_fin_total+$suma_depre_fin;
		$suma_neto_total=$suma_neto_total+$suma_neto;
		
		
		
	}
$sumastotales=array(" "," "," "," "," "," ",
					$suma_compra_total,$suma_gestion_total," ",$suma_valorufv1_total,$suma_incremento_total,
					$suma_valorufv2_total,$suma_depre_acumulada_total,$suma_incremento_depre_total,
					$suma_depre_periodo_total,$suma_depre_fin_total,$suma_neto_total," ");
		$excel->writeLineTotales($sumastotales);
	
		
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
	
}
function  escribir_todo_grupo($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion)
 {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
	
	
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra","Valor de la otra gestion a ".$fecha_gestion_ante."$".$valor_dolar_ges." UFV ".$valor_ufv_ges,
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv,"Valor al mes anterior".$fecha_antes."con".$valor_ufv,
	"Incremento por actualizacion AF con UFV","Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion Acumulada".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Dep Ac c/UFV".$fecha_fin."con UFV".$valor_ufv2,
	"Depreciacion Periodo UFV","Depreciacion Acum Actualiza".$fecha_fin."con UFV".$valor_ufv2,"Valor Neto s/g Actual Ufv al".$fecha_fin."con UFV".$valor_ufv2,"Vida Util Restante");
	$celdas=sizeof($myArr);
	$titulo=array($descripcion);	
	$celdas=sizeof($myArr);
	$excel->writeCabecera($titulo);
	$excel->writeCabecera($myArr);
	
	
  
	  $suma_compra=0;
	  $suma_gestion=0;
	  $suma_valorufv1=0;
	  $suma_incremento=0;
	  $suma_valorufv2=0;
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	  $suma_depre_fin=0;
	  $suma_neto=0;
	  
	  foreach($datos as $llave => $val)
	  {	
	  	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		$gestion = $val['gestion'];
		$val_ufv=$valor_ufv;		
		$valor_ufv1=$val['valor_ufv'];
		$incremento_ufv=$val['incremento_ufv'];
		$valor_ufv2=$val['valor_ufv2'];
		$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		$incremento_depre_ufv=$val['incremento_depre_ufv'];
		$depre_periodo_ufv=$val['depre_periodo_ufv'];
		$depre_fin_ufv=$val['depre_fin_ufv'];
		$neto=$val['neto'];
		$vida_restante=$val['vida_restante'];
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_gestion=$suma_gestion+$gestion;
		$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		$suma_incremento=$suma_incremento+$incremento_ufv;
		$suma_valorufv2=$suma_valorufv2+$valor_ufv2;
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada_ufv;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre_ufv;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo_ufv;
		$suma_depre_fin=$suma_depre_fin+$depre_fin_ufv;
		$suma_neto=$suma_neto+$neto;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$gestion,$val_ufv,$valor_ufv1,
				$incremento_ufv,$valor_ufv2,$depre_acumulada_ufv,
				$incremento_depre_ufv,$depre_periodo_ufv,$depre_fin_ufv,$neto,$vida_restante);
				
		$excel->writeLine($myArr);
	  	
		}
		
		$sumasarray=array(" "," "," "," "," "," ",
					$suma_compra,$suma_gestion," ",$suma_valorufv1,$suma_incremento,
					$suma_valorufv2,$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo,$suma_depre_fin,$suma_neto," ");
		$excel->writeLineTotales($sumasarray);
		
				
		
	
	
		
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
	
}
function  escribir_todo_sec($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripri,$descrisecu)
 {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra","Valor de la otra gestion a ".$fecha_gestion_ante."$".$valor_dolar_ges." UFV ".$valor_ufv_ges,
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv,"Valor al mes anterior".$fecha_antes."con".$valor_ufv,
	"Incremento por actualizacion AF con UFV","Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion Acumulada".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Dep Ac c/UFV".$fecha_fin."con UFV".$valor_ufv2,
	"Depreciacion Periodo UFV","Depreciacion Acum Actualiza".$fecha_fin."con UFV".$valor_ufv2,"Valor Neto s/g Actual Ufv al".$fecha_fin."con UFV".$valor_ufv2,"Vida Util Restante");
	$celdas=sizeof($myArr);
	$titulo=array($descripri);
	$subtitulo=array($descrisecu);	
	
	$excel->writeCabecera($titulo);
	$excel->writeCabecera($subtitulo);
	$excel->writeCabecera($myArr);
	
	$suma_compra_total=0;
	$suma_gestion_total=0;
	$suma_valorufv1_total=0;
	$suma_incremento_total=0;
	$suma_valorufv2_total=0;
	$suma_depre_acumulada_total=0;
	$suma_incremento_depre_total=0;
	$suma_depre_periodo_total=0;
	$suma_depre_fin_total=0;
	$suma_neto_total=0;
	
	foreach($datos as $key => $value)
	{ 
	     $grupo=$key;
		  $excel->writeCabeceraGrupo($grupo, $celdas);
		
	  
	  $suma_compra=0;
	  $suma_gestion=0;
	  $suma_valorufv1=0;
	  $suma_incremento=0;
	  $suma_valorufv2=0;
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	  $suma_depre_fin=0;
	  $suma_neto=0;
	  
	  foreach($value as $llave => $val)
	  {	
	  	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		$gestion = $val['gestion'];
		$val_ufv=$valor_ufv;		
		$valor_ufv1=$val['valor_ufv'];
		$incremento_ufv=$val['incremento_ufv'];
		$valor_ufv2=$val['valor_ufv2'];
		$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		$incremento_depre_ufv=$val['incremento_depre_ufv'];
		$depre_periodo_ufv=$val['depre_periodo_ufv'];
		$depre_fin_ufv=$val['depre_fin_ufv'];
		$neto=$val['neto'];
		$vida_restante=$val['vida_restante'];
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_gestion=$suma_gestion+$gestion;
		$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		$suma_incremento=$suma_incremento+$incremento_ufv;
		$suma_valorufv2=$suma_valorufv2+$valor_ufv2;
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada_ufv;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre_ufv;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo_ufv;
		$suma_depre_fin=$suma_depre_fin+$depre_fin_ufv;
		$suma_neto=$suma_neto+$neto;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$gestion,$val_ufv,$valor_ufv1,
				$incremento_ufv,$valor_ufv2,$depre_acumulada_ufv,
				$incremento_depre_ufv,$depre_periodo_ufv,$depre_fin_ufv,$neto,$vida_restante);
				
		$excel->writeLine($myArr);
	  	
		}
		
		$sumasarray=array(" "," "," "," "," "," ",
					$suma_compra,$suma_gestion," ",$suma_valorufv1,$suma_incremento,
					$suma_valorufv2,$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo,$suma_depre_fin,$suma_neto," ");
		$excel->writeLineTotales($sumasarray);
		
		$suma_compra_total=$suma_compra_total+$suma_compra;
		$suma_gestion_total=$suma_gestion_total+$suma_gestion;
		$suma_valorufv1_total=$suma_valorufv1_total+$suma_valorufv1;
		$suma_incremento_total=$suma_incremento_total+$suma_incremento;
		$suma_valorufv2_total=$suma_valorufv2_total+$suma_valorufv2;
		$suma_depre_acumulada_total=$suma_depre_acumulada_total+$suma_depre_acumulada;
		$suma_incremento_depre_total=$suma_incremento_depre_total+$suma_incremento_depre;
		$suma_depre_periodo_total=$suma_depre_periodo_total+$suma_depre_periodo;
		$suma_depre_fin_total=$suma_depre_fin_total+$suma_depre_fin;
		$suma_neto_total=$suma_neto_total+$suma_neto;
		
		
		
	}
$sumastotales=array(" "," "," "," "," "," ",
					$suma_compra_total,$suma_gestion_total," ",$suma_valorufv1_total,$suma_incremento_total,
					$suma_valorufv2_total,$suma_depre_acumulada_total,$suma_incremento_depre_total,
					$suma_depre_periodo_total,$suma_depre_fin_total,$suma_neto_total," ");
		$excel->writeLineTotales($sumastotales);
	
		
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
	
}

function  escribir_todo_secprigru($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripri,$descrisecu,$descripcion)
 {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
	
	
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra","Valor de la otra gestion a ".$fecha_gestion_ante."$".$valor_dolar_ges." UFV ".$valor_ufv_ges,
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv,"Valor al mes anterior".$fecha_antes."con".$valor_ufv,
	"Incremento por actualizacion AF con UFV","Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion Acumulada".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Dep Ac c/UFV".$fecha_fin."con UFV".$valor_ufv2,
	"Depreciacion Periodo UFV","Depreciacion Acum Actualiza".$fecha_fin."con UFV".$valor_ufv2,"Valor Neto s/g Actual Ufv al".$fecha_fin."con UFV".$valor_ufv2,"Vida Util Restante");
	$celdas=sizeof($myArr);
	$titulo=array($descripcion);	
	$subtitulo=array($descrisecu);	
	$prititulo=array($descripri);	
	$celdas=sizeof($myArr);
	
	$excel->writeCabecera($prititulo);
	$excel->writeCabecera($subtitulo);
	$excel->writeCabecera($titulo);
	$excel->writeCabecera($myArr);
	
	
  
	  $suma_compra=0;
	  $suma_gestion=0;
	  $suma_valorufv1=0;
	  $suma_incremento=0;
	  $suma_valorufv2=0;
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	  $suma_depre_fin=0;
	  $suma_neto=0;
	  
	  foreach($datos as $llave => $val)
	  {	
	  	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		$gestion = $val['gestion'];
		$val_ufv=$valor_ufv;		
		$valor_ufv1=$val['valor_ufv'];
		$incremento_ufv=$val['incremento_ufv'];
		$valor_ufv2=$val['valor_ufv2'];
		$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		$incremento_depre_ufv=$val['incremento_depre_ufv'];
		$depre_periodo_ufv=$val['depre_periodo_ufv'];
		$depre_fin_ufv=$val['depre_fin_ufv'];
		$neto=$val['neto'];
		$vida_restante=$val['vida_restante'];
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_gestion=$suma_gestion+$gestion;
		$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		$suma_incremento=$suma_incremento+$incremento_ufv;
		$suma_valorufv2=$suma_valorufv2+$valor_ufv2;
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada_ufv;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre_ufv;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo_ufv;
		$suma_depre_fin=$suma_depre_fin+$depre_fin_ufv;
		$suma_neto=$suma_neto+$neto;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$gestion,$val_ufv,$valor_ufv1,
				$incremento_ufv,$valor_ufv2,$depre_acumulada_ufv,
				$incremento_depre_ufv,$depre_periodo_ufv,$depre_fin_ufv,$neto,$vida_restante);
				
		$excel->writeLine($myArr);
	  	
		}
		
		$sumasarray=array(" "," "," "," "," "," ",
					$suma_compra,$suma_gestion," ",$suma_valorufv1,$suma_incremento,
					$suma_valorufv2,$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo,$suma_depre_fin,$suma_neto," ");
		$excel->writeLineTotales($sumasarray);
		
				
		
	
	
		
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
		
}						        						     

/*************dolares*****************/
function escribir_dolar_ufv($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$valor_dolar,$valor_dolar2)
						       {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra","Valor de la otra gestion a ".$fecha_gestion_ante."$".$valor_dolar_ges." UFV ".$valor_ufv_ges,
	"Valor dolar al ".$fecha_antes."con".$valor_dolar,"Incremento por actualizacion AF con Dolar","Valor al".$fecha_fin."con dolar".$valor_dolar2,"Valorufv inicial".$fecha_antes,"Valor UFV".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Act con UFV",
	"Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion Acum".$fecha_antes."con Dolar".$valor_dolar,"Incremento por Actualizacion Dep Acumlada","Depreciacion del Periodo","Depreciacion Acumulada al".$fecha_fin,"Depreciacion Acum".$fecha_antes."con UFV".$valor_ufv,"Incremento por Actualizacion Dep Acumlada","Depreciacion del Periodo","Depreciacion Acumulada al".$fecha_fin,"Valor Neto s/g Actual Ufv al".$fecha_fin."con UFV".$valor_ufv2,"Vida Util Restante");
	$celdas=sizeof($myArr);
	$excel->writeCabecera($myArr);
	
	$suma_compra_total=0;
	$suma_gestion_total=0;
	$suma_valordolar1_total=0;
	$suma_incremento_total=0;
	$suma_valordolar2_total=0;
	
	$suma_valantes_total=0;
	$suma_valorufv1_total=0;
	$suma_incremento_ufv_total=0;
	$suma_valorufv2_total=0;
	

	
	$suma_depre_acumulada_total=0;
	$suma_incremento_depre_total=0;
	$suma_depre_periodo_total=0;
	$suma_depre_fin_total=0;
	
	$suma_depre_acumulada_ufv_total=0;
	$suma_incremento_depre_ufv_total=0;
	$suma_depre_periodo_ufv_total=0;
	$suma_depre_fin_ufv_total=0;
	
	
	$suma_neto_total=0;
	
	foreach($datos as $key => $value)
	{ 
	  $grupo=$key;
	  $excel->writeCabeceraGrupo($grupo, $celdas);
	  
	  
	  $suma_compra=0;
	  $suma_gestion=0;
	  
	  
	  $suma_valordolar1=0;
	  $suma_incremento=0;
	  $suma_valordolar2=0;
	  
	  $suma_valantes=0;
	  $suma_valorufv1=0;
	  $suma_incrementoufv=0;
	  $suma_valorufv2=0;
		  
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	  $suma_depre_fin=0;
	  
	  $suma_depre_acumulada_ufv=0;
	  $suma_incremento_depre_ufv=0;
	  $suma_depre_periodo_ufv=0;
	  $suma_depre_fin_ufv=0;
	
	  $suma_neto=0;
	  
	  foreach($value as $llave => $val)
	  {	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		$gestion = $val['gestion'];
		
		$valor_dolar1=$val['valor'];
		$incremento=$val['incremento'];
		$valor_dolar2=$val['valor1'];
	
		
		
		$val_ufv=$valor_ufv;	
		
		$valantes=$val['valor_ufvantes'];	
		$valor_ufv1=$val['valor_ufv'];
		$incremento_ufv=$val['incremento_ufv'];
		
		$depre_acumulada=$val['depre_acumulada'];
		$incremento_depre=$val['incremento_depre'];
		$depre_periodo=$val['depre_periodo'];
		$depre_fin=$val['depre_fin'];
		
		$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		$incremento_depre_ufv=$val['incremento_depre_ufv'];
		$depre_periodo_ufv=$val['depre_periodo_ufv'];
		$depre_fin_ufv=$val['depre_fin_ufv'];
		$neto=$val['neto'];
		$vida_restante=$val['vida_restante'];
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_gestion=$suma_gestion+$gestion;
		
		$suma_valordolar1=$suma_valordolar1+$valor_dolar1;
		$suma_incremento=$suma_incremento+$incremento;
		$suma_valordolar2=$suma_valordolar2+$valor_dolar2;

		
		$suma_valantes=$suma_valantes+$valantes;
		$suma_incrementoufv=$suma_incrementoufv+$incremento_ufv;
		$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo;
		$suma_depre_fin=$suma_depre_fin+$depre_fin;
		
		$suma_depre_acumulada_ufv=$suma_depre_acumulada_ufv+$depre_acumulada_ufv;
		$suma_incremento_depre_ufv=$suma_incremento_depre_ufv+$incremento_depre_ufv;
		$suma_depre_periodo_ufv=$suma_depre_periodo_ufv+$depre_periodo_ufv;
		$suma_depre_fin_ufv=$suma_depre_fin_ufv+$depre_fin_ufv;
		
		$suma_neto=$suma_neto+$neto;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$gestion,$valor_dolar1,$incremento,$valor_dolar2,$val_ufv,$valantes,
				$incremento_ufv,$valor_ufv1,$depre_acumulada,$incremento_depre,$depre_periodo,$depre_fin,$depre_acumulada_ufv,$incremento_depre_ufv,$depre_periodo_ufv,$depre_fin_ufv,$neto,$vida_restante);
				
		$excel->writeLine($myArr);
		}
		
		$sumasarray=array(" "," "," "," "," "," ",
					$suma_compra,$suma_gestion,$suma_valordolar1,$suma_incremento,$suma_valordolar2,"",$suma_valantes,$suma_incrementoufv,$suma_valorufv1,
					$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo,$suma_depre_fin,$suma_depre_acumulada_ufv,$suma_incremento_depre_ufv,
					$suma_depre_periodo_ufv,$suma_depre_fin_ufv,$suma_neto," ");
		$excel->writeLineTotales($sumasarray);
		
		$suma_compra_total=$suma_compra_total+$suma_compra;
		$suma_gestion_total=$suma_gestion_total+$suma_gestion;
		
		$suma_valordolar1_total=$suma_valordolar1_total+$suma_valordolar1;
		$suma_incremento_total=$suma_incremento_total+$suma_incremento;
		$suma_valordolar2_total=$suma_valordolar2_total+$suma_valordolar2;
		
		
		$suma_valantes_total=$suma_valantes_total+$suma_valantes;
		$suma_incremento_ufv_total=$suma_incremento_ufv_total+$suma_incrementoufv;
		$suma_valorufv1_total=$suma_valorufv1_total+$suma_valorufv1;
		$suma_depre_acumulada_total=$suma_depre_acumulada_total+$suma_depre_acumulada;
		$suma_incremento_depre_total=$suma_incremento_depre_total+$suma_incremento_depre;
		$suma_depre_periodo_total=$suma_depre_periodo_total+$suma_depre_periodo;
		$suma_depre_fin_total=$suma_depre_fin_total+$suma_depre_fin;
		
		$suma_depre_acumulada_ufv_total=$suma_depre_acumulada_ufv_total+$suma_depre_acumulada_ufv;
		$suma_incremento_depre_ufv_total=$suma_incremento_depre_ufv_total+$suma_incremento_depre_ufv;
		$suma_depre_periodo_ufv_total=$suma_depre_periodo_ufv_total+$suma_depre_periodo_ufv;
		$suma_depre_fin_ufv_total=$suma_depre_fin_ufv_total+$suma_depre_fin_ufv;
	
		
		$suma_neto_total=$suma_neto_total+$suma_neto;
		
		
		
	}
$sumastotales=array(" "," "," "," "," "," ",
					$suma_compra_total,$suma_gestion_total,$suma_valordolar1_total,$suma_incremento_total,$suma_valodolar2_total,"",$suma_valantes_total,$suma_incremento_ufv_total,$suma_valorufv1_total,
					$suma_depre_acumulada_total,$suma_incremento_depre_total,
					$suma_depre_periodo_total,$suma_depre_fin_total,$suma_depre_acumulada_ufv_total,$suma_incremento_depre_ufv_total,
					$suma_depre_periodo_ufv_total,$suma_depre_fin_ufv_total,$suma_neto_total," ");
		$excel->writeLineTotales($sumastotales);
	
		
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}
function escribir_dolar($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$valor_dolar,$valor_dolar2)
						       {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","Vida Util(meses)","Valor Compra",
	"Valor dolar al ".$fecha_antes."con".$valor_dolar,"Incremento por actualizacion AF con Dolar","Valor al".$fecha_fin."con dolar".$valor_dolar2,"Depreciacion Acum".$fecha_antes."con Dolar".$valor_dolar,"Incremento por Actualizacion Dep Acumlada","Depreciacion del Periodo","Depreciacion Acumulada al".$fecha_fin,"Valor Neto s/g Actual Ufv al".$fecha_fin."con UFV".$valor_dolar2,"Vida Util Restante");
	$celdas=sizeof($myArr);
	$excel->writeCabecera($myArr);
	
	$suma_compra_total=0;
	$suma_gestion_total=0;
	$suma_valordolar1_total=0;
	$suma_incremento_total=0;
	$suma_valordolar2_total=0;
	
	
	$suma_depre_acumulada_total=0;
	$suma_incremento_depre_total=0;
	$suma_depre_periodo_total=0;
	$suma_depre_fin_total=0;
	
	$suma_neto_total=0;
	
	foreach($datos as $key => $value)
	{ 
	  $grupo=$key;
	  $excel->writeCabeceraGrupo($grupo, $celdas);
	  
	  
	  $suma_compra=0;
	  $suma_gestion=0;
	  
	  
	  $suma_valordolar1=0;
	  $suma_incremento=0;
	  $suma_valordolar2=0;
	  
	  $suma_valorufv1=0;
	  $suma_incrementoufv=0;
	  $suma_valorufv2=0;
		  
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	  $suma_depre_fin=0;
	  	  
	  $suma_neto=0;
	  
	  foreach($value as $llave => $val)
	  {	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		$gestion = $val['gestion'];
		
		$valor_dolar1=$val['valor'];
		$incremento=$val['incremento'];
		$valor_dolar2=$val['valor1'];
			
		
		$depre_acumulada=$val['depre_acumulada'];
		$incremento_depre=$val['incremento_depre'];
		$depre_periodo=$val['depre_periodo'];
		$depre_fin=$val['depre_fin'];
		
		$neto=$val['neto'];
		$vida_restante=$val['vida_restante'];
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_gestion=$suma_gestion+$gestion;
		
		$suma_valordolar1=$suma_valordolar1+$valor_dolar1;
		$suma_incremento=$suma_incremento+$incremento;
		$suma_valordolar2=$suma_valordolar2+$valor_dolar2;

		
		
		
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo;
		$suma_depre_fin=$suma_depre_fin+$depre_fin;
		$suma_neto=$suma_neto+$neto;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,
				$vida_util,$valor_compra,$valor_dolar1,$incremento,$valor_dolar2
				,$depre_acumulada,$incremento_depre,$depre_periodo,$depre_fin,$neto,$vida_restante);
				
		$excel->writeLine($myArr);
		}
		
		$sumasarray=array(" "," "," "," "," ",
					$suma_compra,$suma_valordolar1,$suma_incremento,$suma_valordolar2,
					$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo,$suma_depre_fin,
					$suma_neto," ");
		$excel->writeLineTotales($sumasarray);
		
		$suma_compra_total=$suma_compra_total+$suma_compra;
		$suma_gestion_total=$suma_gestion_total+$suma_gestion;
		
		$suma_valordolar1_total=$suma_valodolar1_total+$suma_valordolar1;
		$suma_incremento_total=$suma_incremento_total+$suma_incremento;
		$suma_valordolar2_total=$suma_valordolar2_total+$suma_valordolar2;
		
		
		
		$suma_depre_acumulada_total=$suma_depre_acumulada_total+$suma_depre_acumulada;
		$suma_incremento_depre_total=$suma_incremento_depre_total+$suma_incremento_depre;
		$suma_depre_periodo_total=$suma_depre_periodo_total+$suma_depre_periodo;
		$suma_depre_fin_total=$suma_depre_fin_total+$suma_depre_fin;
		
				
		$suma_neto_total=$suma_neto_total+$suma_neto;
		
		
		
	}
$sumastotales=array(" "," "," "," "," ",
					$suma_compra_total,$suma_valordolar1_total,$suma_incremento_total,$suma_valordolar2_total,
					$suma_depre_acumulada_total,$suma_incremento_depre_total,
					$suma_depre_periodo_total,$suma_depre_fin_total,
					$suma_neto_total," ");
		$excel->writeLineTotales($sumastotales);
	
		
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}
function escribir_no_exite($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante)
						       {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra","Valor de la otra gestion a ".$fecha_gestion_ante."$".$valor_dolar_ges." UFV ".$valor_ufv_ges,
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv,"Valor al mes anterior".$fecha_antes."con".$valor_ufv,
	"Incremento por actualizacion AF con UFV","Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion Acumulada".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Dep Ac c/UFV".$fecha_fin."con UFV".$valor_ufv2,
	"Depreciacion Periodo UFV","Depreciacion Acum Actualiza".$fecha_fin."con UFV".$valor_ufv2,"Valor Neto s/g Actual Ufv al".$fecha_fin."con UFV".$valor_ufv2,"Vida Util Restante");
	$celdas=sizeof($myArr);
	$excel->writeCabecera($myArr);
	
	
		
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
	
}


if (!isset($_SESSION['logeo'])) {
	header("Location: ../index_logeo.php");
}

else {
			      	
		$opcion = $_GET['opcion'];
		switch ($opcion){
			
			case 'registrar_foto':
						
			  	 	//echo $act_id=$_GET['act_id'];
			  	 	 $numero=$_GET['numero'];
			  	 	 //$smarty->assign('act_id', $act_id);
			  	 	 $smarty->assign('numero', $numero);
					 $smarty->display('fotos.html');
				
			break;
			
			case 'prueba':
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
		/*case 'modificar_activo':
				
					$primaria=$primaria->listar_locprimaria();
					$secundaria=$secundaria->listar_locsecundaria();
					$grupo=$grupo->listar_grupo();
					
					
					$smarty->assign('primaria', $primaria);					
					$smarty->assign('secundaria', $secundaria);
					$smarty->assign('grupo', $grupo);
					
					
					$smarty->display('listar_activo.html');
			break;
			*/
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
			   		$corte ='2007-03-31'; 
			   		$primaria=$primaria->listar_locprimaria();
					$grupo=$grupo->listar_grupo();													
					$gestion=$gestion->listar_gestion();
					
					$smarty->assign('primaria', $primaria);	
					$smarty->assign('grupo', $grupo);	
					$smarty->assign('gestion', $gestion);	
					$smarty->assign('fecha_inicio',$fecha_inicio);
					$smarty->assign('fecha_fin',$fecha_fin);
					$smarty->assign('corte',$corte);
					$smarty->display('actualizacion.html');
					
					
			break;
			case 'rep_responsable':
					
			   		
			   		$smarty->display('rep_responsable.html');
					
					
			break;
			
			case 'transferencia':
					$primaria=$primaria->listar_locprimaria();
					$secundaria=$secundaria->listar_locsecundaria();
					$grupo=$grupo->listar_grupo();
					
					
					$smarty->assign('primaria', $primaria);					
					$smarty->assign('secundaria', $secundaria);
					$smarty->assign('grupo', $grupo);
			   		
			   		$smarty->display('transferencia.html');
					
					
			break;
			
			case 'baja':
					
				    $primaria=$primaria->listar_locprimaria();
					$secundaria=$secundaria->listar_locsecundaria();
					$grupo=$grupo->listar_grupo();
					
					
					$smarty->assign('primaria', $primaria);					
					$smarty->assign('secundaria', $secundaria);
					$smarty->assign('grupo', $grupo);
			   		
			   		$smarty->display('darbaja.html');
					
					
			break;
			
			case 'rep_bajas':
					
					$fecha_inicio = date("Y-m-d"); 
			   		$fecha_fin = date("Y-m-d"); 
			   		$corte ='2007-03-31'; 
			   		$primaria=$primaria->listar_locprimaria();
					$grupo=$grupo->listar_grupo();													
					$gestion=$gestion->listar_gestion();
					
					$smarty->assign('primaria', $primaria);	
					$smarty->assign('grupo', $grupo);	
					$smarty->assign('gestion', $gestion);	
					$smarty->assign('fecha_inicio',$fecha_inicio);
					$smarty->assign('fecha_fin',$fecha_fin);
					$smarty->assign('corte',$corte);
					
			   		$smarty->display('rep_baja.html');
					
					
			break;
			
			case 'gastos':
					
				    $fecha_inicio = date("Y-m-d"); 
			   		$fecha_fin = date("Y-m-d"); 
			   		$corte='2007-03-31';
			   		$primaria=$primaria->listar_locprimaria();
					$grupo=$grupo->listar_grupo();													
					$gestion=$gestion->listar_gestion();
					
					
					$smarty->assign('primaria', $primaria);	
					$smarty->assign('grupo', $grupo);	
					$smarty->assign('gestion', $gestion);	
					$smarty->assign('corte', $corte);	
					$smarty->assign('fecha_inicio',$fecha_inicio);
					$smarty->assign('fecha_fin',$fecha_fin);
					$smarty->display('gastos.html');
					
					
					
			break;
			
			case 'lista_resp':
					
				   	
					$smarty->display('lista_resp.html');
					
					
					
			break;
			case 'buscar':
					
				   	
					$smarty->display('busqueda.html');
					
					
					
			break;
			case 'rep_ingreso':
				
				    $fecha_inicio = date("Y-m-d"); 
			   		$fecha_fin = date("Y-m-d");
			   		
			   		$smarty->assign('fecha_inicio',$fecha_inicio);
					$smarty->assign('fecha_fin',$fecha_fin);
					
					$smarty->display('rep_ingreso.html');
					
					
					
			break;
            case 'automatico'://para realizar el tipo de cambio automatico
                    $tipos_actuales=$activo->tipoCambioActual();
                    $fecha=date("Y-m-d");
                    $smarty->assign('tipos_actuales', $tipos_actuales);
                    $smarty->assign('fecha', $fecha);		
                    $smarty->display('tipo_cambio_automatico.html');
            break;
            case 'automatico_tipo':
                    $nombrearchivo="../controladores/UploadedTipo/".$_POST['nombre_archivo'];
					//echo $nombrearchivo."<br/>";
                    if(file_exists($nombrearchivo)==1)
                    {
                        $smarty->assign('mensaje', "");
                        $ver=$activo->actualizacion_automatica($nombrearchivo);
                        if($ver && $ver!="mayor")//=="existe")
                        {
							if($ver['id']==1)
								$smarty->assign('mensaje',"Existe valores registrados para la fecha, DOLAR.");
							else
								$smarty->assign('mensaje',"Existe valores registrados para la fecha, UFV.");
                        }
                        else//en el caso de q no encontro iguales y cargo el archivo bien pero aun no cambio cuales seran los tipos de dolar y ufv actuales es decir: "0000-00-00"""
                        {
							if($ver=="mayor")
								$smarty->assign('mensaje',"Existe fechas mayores a la fecha actual.");
                        }
                        $tipos_actuales=$activo->tipoCambioActual();
                        $fecha=date("Y-m-d");
                        $smarty->assign('tipos_actuales', $tipos_actuales);
                        $smarty->assign('fecha', $fecha);
                        $smarty->display('tipo_cambio_automatico.html');    
                    }
                    else
                    {
                        $smarty->assign('mensaje', "El archivo no fue encontrado");
                        $tipos_actuales=$activo->tipoCambioActual();
                        $fecha=date("Y-m-d");
                        $smarty->assign('tipos_actuales', $tipos_actuales);
                        $smarty->assign('fecha', $fecha);
                        $smarty->display('tipo_cambio_automatico.html');   
                    }
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
			 	 
			 	/* if ($validar->validarTodo($_GET['vida_util'], 1, 100))
			 		$error['err_vida'] = "Introduzca Vida util";*/
			 	 if ($validar->validarNumeros($_GET['vida_util'], 0, 100))
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
					$resp_id2=$_GET['resp_id2'];
					$resp_id=$_GET['resp_id'];
					$descripcion=$_GET['descripcion'];
					$valor=$_GET['valor'];
					$residual=$_GET['residual'];
					$ad_id=$_GET['adqui'];
					$serie=$_GET['serie'];
					$unidad=$_GET['unidad'];
					$vida_util=$_GET['vida_util'];
					$det_adqui=$_GET['det_adqui'];
					
					
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
						$smarty->assign('det_adqui', $det_adqui);
						
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
				
					
					$tipo_cambio=$_GET['tipo'];
					$ufv=$_GET['ufv'];
					$cantidad=$_GET['cantidad'];
					$unidad=$_GET['unidad'];
					//$vida_util=$_GET['vida_util'];
			
					
					if( $_GET['serie'] =='')
					$serie='NULL';
					else
					$serie=$_GET['serie'];
					
					if( $_GET['vida_util'] =='')
					$vida_util='';
					else
					$vida_util=$_GET['vida_util'];
						
					$descripcion=strtoupper($_GET['descripcion']);
					$valor_compra=$_GET['valor'];
					$residual=$_GET['residual'];
					$usuario_id=$_SESSION['usuario_id'];
					$fecha = trim($_GET["fecha_inicio"]);
			
			
			 		$ad_id=$_GET['adqui'];
			 		$det_adqui=$_GET['det_adqui'];
			 		if( $_GET['det_adqui'] =='')
					$det_adqui='';
					
					$numero=$localizacion.".".$locsecundaria.".".$locgrupo.".".$num_act1;
			 
					$acti=$activo->insertar_activo($numero,$descripcion,$serie,$fecha,$vida_util,$valor_compra,$tipo_cambio,$ad_id,$localizacion,$locsecundaria,$grupo_id,$resp_id,$num_act,$unidad,$cantidad,$residual,$resp_pri,$ufv,$det_adqui,$usuario_id);
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

				/* if ($_GET['pri']=='selc')
			 		$error['err_pri'] = "Selecione localizacion primaria";
				 if ($_GET['secun']=='selc')
			 		$error['err_sec'] = "Selecione localizacion secundaria";	*/
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
						if ($primaria_id !=''&& $secundaria_id !='' && $grupo_id !=''&& $num_act !='')
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
						 
				 	
				 					 	
						 	
						 if($primaria_id !=''&& $secundaria_id !='' && $grupo_id !=''&& $num_act =='')	
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
			  			
			  				 	
						 if($primaria_id =='selc' && $grupo_id !='' && $num_act =='')	
						 
						  	{
			  				//echo "que pasa";
						  		
							 $lista1=$activo->listaactivos2($grupo_id);
							 
							/* echo "<pre>";
							 print_r($lista1);
							 echo "</pre>";*/
							 
							 $smarty->assign('lista1',$lista1);
				  
							 if ($lista1==0)
				  			{
				  				$error="No se encontro ningun activo";
				 				$smarty->assign('error',$error);
				  				
				  				
				  			}		
							 
			  				}
			  				 if($primaria_id =='selc' && $grupo_id !='' && $num_act !='')	
						 
						  	{
			  				//echo "que pasa";
						  		
							 $lista1=$activo->listagruponumero($grupo_id,$num_act);
							 
							/* echo "<pre>";
							 print_r($lista1);
							 echo "</pre>";*/
							 
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
		/*******************************************ACTUALIZACION*********************************************************/
		if (isset($_GET['actualizacion']))
		{
				//buscamos el detalle
				
				$todo=$_GET['todo'];
				
				$fecha_inicio = trim($_GET["fecha_inicio"]);
				$fecha_fin = trim($_GET["fecha_fin"]);
				$fecha_inter=trim($_GET['corte']);
				
				$gest=$_GET['gest'];
			 	$fecha_gestion=$gestion->fecha_gestion($gest);
			 	$fecha_ges=$fecha_gestion['fecha_inicio'];
			 	$fecha_gestion_ante=$activo->diferencia_fechas_undia($fecha_ges);
			 	
			 		
			 	$fecha_ges_ini=$fecha_gestion['fecha_inicio'];
			 	$fecha_ges_fin=$fecha_gestion['fecha_fin'];
			 	$corte ='2007-03-31'; 
			 	$gestion=$gestion->listar_gestion();
			 	
			 		
			 	
					
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
			         
			      if ($_GET['gest']=='selc')
				 {   $error['gest'] = "Seleccione la Gestion";
			         $smarty->assign('error',$error);
			    
			
				 }   
				 if ($fecha_inicio < $fecha_ges_ini || $fecha_fin >$fecha_ges_fin)
				 {
				 	 $error['control'] = "Las fechas del reporte no corresponden a la de la Gestion Seleccionada";
			         $smarty->assign('error',$error);
			        
				 }    
				
			if (isset($error)){
				
				  $primaria=$primaria->listar_locprimaria();
				  $grupo=$grupo->listar_grupo();													
				    
				    $fecha_inicio = date("Y-m-d"); 
			   		$fecha_fin = date("Y-m-d"); 
			   		$smarty->assign('fecha_inicio',$fecha_inicio);
					$smarty->assign('fecha_fin',$fecha_fin);
					$smarty->assign('error', $error);
					$smarty->assign('primaria', $primaria);								
					$smarty->assign('grupo', $grupo);		
					$smarty->assign('gest',$gest);
					$smarty->assign('gestion', $gestion);
					$smarty->assign('corte',$corte);						
					$smarty->display('actualizacion.html');			
										
				} 
	 	 else
	 		 { if ($todo==1)
	  	   		{  
	  			
	  	   			//$calculo=$activo->calcular_actualizacion_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion_ante);
	  	   			  	   			
				     $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
	  	   			
				    if($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
					{

					 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
					 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
					 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
						
					 $valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
					 $valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
														
							
					 $act='Actualizacion-'.$fecha_inicio.'-'.$fecha_fin;
		  	   		 $calculo=$acttodo->calcular_actualizacion_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion_ante,$gest);
	
			  	   	if($calculo != null)
					 {
					
					  escribir_dolar_ufv($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$valor_dolar,$valor_dolar2);
					  $smarty->assign('excel',$act);
					 }
										
					 $smarty->assign('ver','si');
							
					}
		 	
		 	 else 
				{  
				 if ($fecha_inicio < $fecha_inter && $fecha_fin <= $fecha_inter)
				   {
				   	
				   	 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
		 			 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
						
					 $valor_ufv=0;//valor en ufv al 31-03-2007
					   $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				
					
						          escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						          $smarty->assign('excel',$act);
					
					 
				   	 $smarty->assign('ver1','si');
				   			
				   	 
				   	
				   }
				    else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	{
				   			$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
		 			        
				   		    $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
					   
							/***Dato de l agestion anterior****/
							
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
														
							
							$act='Actualizacion-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				$calculo=$acttodo->calcular_actualizacion_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion_ante,$gest);
	
			  	   			if ($calculo != null)
						       {
					
						          escribir_todo_excel($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante);
						          $smarty->assign('excel',$act);
						        }
						        
							/*echo "<pre>";
							print_r($calculo);
							echo "</pre>";
								*/
				   		    $smarty->assign('ver2','si');
				   		 
				   		
				   		 
				   		 
				   		 
				   	}
				   	/*else 
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
				   
					}*/
		
			 	}
		
			
		 	
		 
				}	
	  	   			  /*echo "<pre>";
	  	   			  print_r($calculo);
	  	   			  echo "</pre>";*/
						
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
						//$smarty->display('actualizacion_todo.html');
				        $smarty->display('impresion_actualizacion.html');
	  	   			  
							
							
						
					
						
			
	  }
	  // empieza la opcion 2 especifico
	  	
			
		if ($todo==2)	 	
		{ 	
			$fecha_inter=trim($_GET['corte']);
			$grupo_id=$_GET['gru'];    
		    $pri=$_GET['pri'];
		    $secun=$_GET['secun'];
		    
		  if ($pri =='selc'& $secun =='selc' & $grupo_id =='selc')
		 {   
			if ($grupo_id=='selc')
		 	{	$error['err_gru'] = "Selecione cuenta";
		 		$smarty->assign('todo','2');
		 		$smarty->assign('error', $error);
		 	}
		 	
		 }
		 /*else {
		 if ($pri !='selc'& $secun !='selc' & $grupo_id =='selc')
		 {
		 	if ($grupo_id=='selc')
		 	{	$error['err_gru'] = "Selecione cuenta";
		 		$smarty->assign('error', $error);
		 	}
		 	
		 }	
		 }*/
	if (isset($error))
	{
				
		            $primaria=$primaria->listar_locprimaria();
				    $grupo=$grupo->listar_grupo();													
					$fecha_inicio = date("Y-m-d"); 
			   		$fecha_fin = date("Y-m-d"); 
			   		$smarty->assign('corte',$fecha_inter);
			   		$smarty->assign('fecha_inicio',$fecha_inicio);
					$smarty->assign('fecha_fin',$fecha_fin);
					$smarty->assign('error', $error);
					$smarty->assign('primaria', $primaria);								
					$smarty->assign('grupo', $grupo);					
					$smarty->display('actualizacion.html');			
										
	} 
		 
		 
	else{
		
		    
		 if ($pri =='selc'& $secun =='selc' & $grupo_id !='')
		 {	 
		  
		     	//$calculo=$activo->calcular_actualizacion($fecha_inter,$fecha_inicio,$fecha_fin,$grupo_id);

		    	
		     	$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
			if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
				{	 
					 $descripcion=$grupo->locgru($grupo_id);	
			 
					 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
					 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
					 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
					
					   $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				
					
						          escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						          $smarty->assign('excel',$act);
						       
								
					 
					 
					 $smarty->assign('descripcion', $descripcion);
					 $smarty->assign('ver','si');
										
				}
		 	
		 	 else 
				{  
				 if ($fecha_inicio < $fecha_inter && $fecha_fin <= $fecha_inter)
				   {
				   	 $descripcion=$grupo->locgru($grupo_id);	
				   	 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   	 
		 			 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
						
					 $valor_ufv=0;//valor en ufv al 31-03-2007
					  $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   		
					
						          escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						          $smarty->assign('excel',$act);
				
					 
					// $smarty->assign('descripcion', $descripcion);
				   	 //$smarty->assign('ver1','si');
				   	 		
				   	 
				   	
				   }
				    else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	{
				   		    
				   		    $descrip=$grupo->locgru($grupo_id);	
				   		    $descripcion=$descrip['descripcion'];
				   		    // porque el reporte tiene que mostrar un dia antes siempre.
		 			       $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);

		 			        $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte

							/***Dato de l agestion anterior****/
							
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
														
							
							$act='Actualizacion-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				
							$calculo=$actgrupo->calcular_actualizacion($fecha_inter,$fecha_inicio,$fecha_fin,$grupo_id,$fecha_gestion_ante,$gest);
			  	   			if ($calculo != null)
						       {
					
						          escribir_todo_grupo($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						          $smarty->assign('excel',$act);
						        }
							
														
							  $smarty->assign('ver2','si');
				   		 
				   		
				   		 
				   		 
				   		 
				   	}
				   	else 
				   	{
				   		/*if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   		{    
				   			
				   			 $descripcion=$grupo->locgru($grupo_id);	
		 					 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   			 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							 
							 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);
							 $smarty->assign('descripcion', $descripcion);
				   			 $smarty->assign('ver3','si');
				   			 
				   		
				   		}*/
				   
					}
		
			 	}
		
			}
						/* echo "<pre>";
	  	   			  print_r($calculo);
	  	   			  echo "</pre>";
						*/
		  
				   	    $grupo=$grupo->listar_grupo();	
				        $primaria=$primaria->listar_locprimaria();	
						$smarty->assign('primaria', $primaria);													
						$smarty->assign('grupo', $grupo);
						
						
						
						
						$smarty->assign('lista_activo', $calculo);	
						$smarty->assign('fecha_inicio', $fecha_inicio);
						$smarty->assign('fecha_fin', $fecha_fin);
						$smarty->assign('fecha_antes', $fecha_antes);
						$smarty->assign('fecha_inter', $fecha_inter);
						$smarty->assign('valor_dolar', $valor_dolar);	
						$smarty->assign('valor_dolar2', $valor_dolar2);	
					    $smarty->assign('valor_ufv', $valor_ufv);	
						$smarty->assign('valor_ufv2', $valor_ufv2);	
						$smarty->display('impresion_actualizacion.html');
			    	 
					 
		
		 }
		 
		 else{
		 	 if ($pri !='selc' && $secun !='selc' && $grupo_id =='selc')
		  	 {	
		  	 	
		  	 	 
		    	$descripri1=$primaria->localizacionpri($pri);	
		    	$descripri=$descripri1['descripcion'];
		    	$localizacion=$descripri1['localizacion'];
		    	
		    	$descrisecu2=$secundaria->localizacionsec($secun);
		    	$descrisecu=$descrisecu2['descripcion'];
		    	$locsecundaria=$descrisecu2['locsecundaria'];
		    	
		    	$grup_sec=$secundaria->grupo_sec($secun);
		    	
		 		//$calculo=$activo->calcular_actualizacion_sec($fecha_inter,$fecha_inicio,$fecha_fin,$secun);
		 		$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
					
                
		 		
		 		if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
				{	  
					 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
					 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
					 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
					 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
					
					 	  $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   			
					
						          escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						          $smarty->assign('excel',$act);
						
					$smarty->assign('ver','si');
					
					
								
				}
		 	
		 	 else 
				{  
				 if ($fecha_inicio < $fecha_inter && $fecha_fin <= $fecha_inter)
				   {
				   	$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
		 			 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
						
					 $valor_ufv=0;//valor en ufv al 31-03-2007
					  $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				
					
						 escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						 $smarty->assign('excel',$act);
				
				   	 $smarty->assign('ver1','si');
				   	 
				   	 
				   	
				   }
				    else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	{
				   		 
		 			        $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);

		 			        $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
					       
							/***Dato de l agestion anterior****/
							
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
														
							
							$act='Actualizacion-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				
							$calculo=$actsec->calcular_actualizacion_sec($fecha_inter,$fecha_inicio,$fecha_fin,$localizacion,$locsecundaria,$secun,$pri,$fecha_gestion_ante,$gest);
							if ($calculo != null)
						       {
					
						          escribir_todo_sec($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripri,$descrisecu);
						          $smarty->assign('excel',$act);
						        }
							
							      
					        
					        
				   		 $smarty->assign('ver2','si');
				   		 
				   		
				   		 
				   		 
				   		 
				   	}
				   	else 
				   {
				   		/*if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   		{
				   				
		 					 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   			 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							 
							 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);
							 
				   			 
				   		
				   		}*/
				   
					}
		
			 	}
		
			}
		      
						
		 		
				 	/*	echo "<pre>";
	  	   			  print_r($calculo);
	  	   			  echo "</pre>";
						*/
                
			
				   	    $grupo=$grupo->listar_grupo();	
				   	    $smarty->assign('descripri', $descripri);
			 			$smarty->assign('descrisecu', $descrisecu);
			 			$smarty->assign('grup_sec', $grup_sec);	
				        $primaria=$primaria->listar_locprimaria();	
						$smarty->assign('primaria', $primaria);													
						$smarty->assign('grupo', $grupo);
						$smarty->assign('lista_activo', $calculo);	
						$smarty->assign('fecha_inicio', $fecha_inicio);
						$smarty->assign('fecha_antes',$fecha_antes);
						$smarty->assign('fecha_fin', $fecha_fin);
						$smarty->assign('fecha_inter', $fecha_inter);
						$smarty->assign('valor_dolar', $valor_dolar);	
						$smarty->assign('valor_dolar2', $valor_dolar2);	
					    $smarty->assign('valor_ufv', $valor_ufv);	
						$smarty->assign('valor_ufv2', $valor_ufv2);	
						$smarty->display('impresion_actualizacion.html');
			    	 
				
			 	
			 	
			 	
			 	        
			 	
				
			 /*
			
		   	 echo"<pre>";
			 print_r($calculo);
			 echo "</pre>";	*/
					
		   	}
		   
		   else 
		    { 
		    	if ($pri !='selc'& $secun !='selc' & $grupo_id !='selc')
		    	{  
		    		
		    		$descripri1=$primaria->localizacionpri($pri);	
		    		$descripri=$descripri1['descripcion'];
		    		$localizacion=$descripri1['localizacion'];
		    	
		    		$descrisecu2=$secundaria->localizacionsec($secun);
		    		$descrisecu=$descrisecu2['descripcion'];
		    		$locsecundaria=$descrisecu2['locsecundaria'];
		    			    		
		    	    $descripcion1=$grupo->locgru($grupo_id);	
		    	    $descripcion=$descripcion1['descripcion'];
		 			
		 			$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   		  		 
			    	//$calculo=$activo->calcular_actualizacion_gru($fecha_inter,$fecha_inicio,$fecha_fin,$secun,$grupo_id);
					if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
				   {	  
						

					 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
					 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
					 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
					   $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				
					
					 escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
					 $smarty->assign('excel',$act);
					
					$smarty->assign('ver','si');
					
					
								
				}
		 	  
		 	 else 
				{  
				 if ($fecha_inicio < $fecha_inter && $fecha_fin <= $fecha_inter)
				   { 
				   	 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   	
		 			 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
						
					 $valor_ufv=0;//valor en ufv al 31-03-2007
					  $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   		
					 escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
					 $smarty->assign('excel',$act);
				
				   	 $smarty->assign('ver1','si');
				   	 
				   	 
				   	
				   }
				    else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	{
				   		 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   	
		 			        
				   		    $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
					         
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
														
							
							$act='Actualizacion-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				
								$calculo=$actsec->calcular_actualizacion_gru($fecha_inter,$fecha_inicio,$fecha_fin,$localizacion,$locsecundaria,$secun,$pri,$grupo_id,$fecha_gestion_ante,$gest);
							if ($calculo != null)
						       {
					
						          escribir_todo_secprigru($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripri,$descrisecu,$descripcion);
						          $smarty->assign('excel',$act);
						        }
							
				   		    $smarty->assign('ver2','si');
				   		 
				   		
				   		 
				   		 
				   		 
				   	}
				   	else 
				   	{
				   		/*if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   		{
				   				
		 					 
				   		 	 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   	
				   			 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							 
							 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);
							 
				   			 $smarty->assign('ver3','si');
				   			 
				   		
				   		}*/
				   
					}
		
			 	}
		
			}
		 
			
		   	/* echo"<pre>";
			 print_r($calculo);
			 echo "</pre>";	
					*/
				
				   	    $grupo=$grupo->listar_grupo();	
				   	    $smarty->assign('descripcion', $descripcion);
				   	    $smarty->assign('descripri', $descripri);
			 			$smarty->assign('descrisecu', $descrisecu);
			 			$smarty->assign('grup_sec', $grup_sec);	
				        $primaria=$primaria->listar_locprimaria();	
						$smarty->assign('primaria', $primaria);													
						$smarty->assign('grupo', $grupo);
						$smarty->assign('lista_activo', $calculo);	
						$smarty->assign('fecha_inicio', $fecha_inicio);
						$smarty->assign('fecha_fin', $fecha_fin);
						$smarty->assign('fecha_inter', $fecha_inter);
						$smarty->assign('fecha_antes', $fecha_antes);
						$smarty->assign('valor_dolar', $valor_dolar);	
						$smarty->assign('valor_dolar2', $valor_dolar2);	
					    $smarty->assign('valor_ufv', $valor_ufv);	
						$smarty->assign('valor_ufv2', $valor_ufv2);	
					    $smarty->display('impresion_actualizacion.html');
			    	 
					 	        
			 	
				
					
		   	}

					
					
		    	}
		    		
		    }
        	
		 }
		       
                
        
		
      }
	
		
       
	 }
       
       
       
       
       
       
     
 }
/*****************TRANSFERENCIAS************/
 if (isset($_GET['transferencia']))
	{
			$primaria_id=$_GET['pri'];
			$secundaria_id=$_GET['secun'];
		    $grupo_id=$_GET['gru'];
			$num_act=$_GET['num_act'];

				/* if ($_GET['pri']=='selc')
			 		$error['err_pri'] = "Selecione localizacion primaria";
				 if ($_GET['secun']=='selc')
			 		$error['err_sec'] = "Selecione localizacion secundaria";	*/
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
					
					$smarty->display('transferencia.html');
						
					
					}
					
					else
					{ 
						if ($primaria_id !=''&& $secundaria_id !='' && $grupo_id !=''&& $num_act !='')
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
						 
				 	
				 					 	
						 	
						 if($primaria_id !=''&& $secundaria_id !='' && $grupo_id !=''&& $num_act =='')	
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
			  			
			  				 	
						 if($primaria_id =='selc' && $grupo_id !='' && $num_act =='')	
						 
						  	{
			  				//echo "que pasa";
						  		
							 $lista1=$activo->listaactivos2($grupo_id);
																			 
							 $smarty->assign('lista1',$lista1);
				  
							 if ($lista1==0)
				  			{
				  				$error="No se encontro ningun activo";
				 				$smarty->assign('error',$error);
				  				
				  				
				  			}		
							 
			  				}
			  				
			  				
			  				 if($primaria_id =='selc' && $grupo_id !='' && $num_act !='')	
						 
						  	{
			  				//echo "que pasa";
						  		
							 $lista1=$activo->listagruponumero($grupo_id,$num_act);
							 
							/* echo "<pre>";
							 print_r($lista1);
							 echo "</pre>";*/
							 
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
				
			     $smarty->display('transferencia.html');
					}
		}

 /********************************BAJAS DE UN ACTIVO**************************************/
 
 	if (isset($_GET['baja']))
	
	{
			$primaria_id=$_GET['pri'];
			$secundaria_id=$_GET['secun'];
		    $grupo_id=$_GET['gru'];
			$num_act=$_GET['num_act'];
			

				/* if ($_GET['pri']=='selc')
			 		$error['err_pri'] = "Selecione localizacion primaria";
				 if ($_GET['secun']=='selc')
			 		$error['err_sec'] = "Selecione localizacion secundaria";	*/
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
					
					$smarty->display('darbaja.html');
						
					
					}
					
					else
					{ 
						if ($primaria_id !=''&& $secundaria_id !='' && $grupo_id !=''&& $num_act !='')
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
						 
				 	
				 					 	
						 	
						 if($primaria_id !=''&& $secundaria_id !='' && $grupo_id !=''&& $num_act =='')	
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
			  			
			  				 	
						 if($primaria_id =='selc' && $grupo_id !='' && $num_act =='')	
						 
						  	{
			  				//echo "que pasa";
						  		
							 $lista1=$activo->listaactivos2($grupo_id);
																			 
							 $smarty->assign('lista1',$lista1);
				  
							 if ($lista1==0)
				  			{
				  				$error="No se encontro ningun activo";
				 				$smarty->assign('error',$error);
				  				
				  				
				  			}		
							 
			  				}
			  				
			  				
			  				 if($primaria_id =='selc' && $grupo_id !='' && $num_act !='')	
						 
						  	{
			  				//echo "que pasa";
						  		
							 $lista1=$activo->listagruponumero($grupo_id,$num_act);
							 
							/* echo "<pre>";
							 print_r($lista1);
							 echo "</pre>";*/
							 
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
				
			     $smarty->display('darbaja.html');
					}
		}

 /*****************************************LISTA DE RESPONSABLES*****************************************************/
if (isset($_GET['listar_resp']))
{
	
	$resp_id2=$_GET['resp_id2'];

    $resp=$responsable->mostrar_responsable($resp_id2);
	
	$smarty->assign('resp',$resp);
	$smarty->assign('busqueda1','si');
	$smarty->display('lista_resp.html');
	
}

}


?>