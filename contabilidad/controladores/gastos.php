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

function escribir_todo_excel($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante)
						       {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra",
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv,"Valor al mes anterior".$fecha_antes."con".$valor_ufv,
	"Incremento por actualizacion AF con UFV","Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion gasto".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Acde la depreciacion del depiodo ante",
	"Depreciacion Periodo UFV".$fecha_fin."con UFV".$valor_ufv2);
	$celdas=sizeof($myArr);
	$excel->writeCabecera($myArr);
	
	$suma_compra_total=0;
	
	$suma_valorufv1_total=0;
	$suma_incremento_total=0;
	$suma_valorufv2_total=0;
	$suma_depre_acumulada_total=0;
	$suma_incremento_depre_total=0;
	$suma_depre_periodo_total=0;

	
	
	foreach($datos as $key => $value)
	{ 
	  $grupo=$key;
	  $excel->writeCabeceraGrupo($grupo, $celdas);
	  
	  
	  $suma_compra=0;
	  $suma_valorufv1=0;
	  $suma_incremento=0;
	  $suma_valorufv2=0;
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	 
	  
	  foreach($value as $llave => $val)
	  {	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		
		$val_ufv=$valor_ufv;		
		$valor_ufv1=$val['valor_ufv'];
		$incremento_ufv=$val['incremento_ufv'];
		$valor_ufv2=$val['valor_ufv2'];
		$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		$incremento_depre_ufv=$val['incremento_depre_ufv'];
		$depre_periodo_ufv=$val['depre_periodo_ufv'];
		
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		$suma_incremento=$suma_incremento+$incremento_ufv;
		$suma_valorufv2=$suma_valorufv2+$valor_ufv2;
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada_ufv;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre_ufv;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo_ufv;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$val_ufv,$valor_ufv1,
				$incremento_ufv,$valor_ufv2,$depre_acumulada_ufv,
				$incremento_depre_ufv,$depre_periodo_ufv);
				
		$excel->writeLine($myArr);
		}
		
		$sumasarray=array(" "," "," "," "," "," ",
					$suma_compra," ",$suma_valorufv1,$suma_incremento,
					$suma_valorufv2,$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo);
		$excel->writeLineTotales($sumasarray);
		
		$suma_compra_total=$suma_compra_total+$suma_compra;
		$suma_valorufv1_total=$suma_valorufv1_total+$suma_valorufv1;
		$suma_incremento_total=$suma_incremento_total+$suma_incremento;
		$suma_valorufv2_total=$suma_valorufv2_total+$suma_valorufv2;
		
		$suma_depre_acumulada_total=$suma_depre_acumulada_total+$suma_depre_acumulada;
		$suma_incremento_depre_total=$suma_incremento_depre_total+$suma_incremento_depre;
		$suma_depre_periodo_total=$suma_depre_periodo_total+$suma_depre_periodo;
		
		
	}
$sumastotales=array(" "," "," "," "," "," ",
					$suma_compra_total," ",$suma_valorufv1_total,$suma_incremento_total,
					$suma_valorufv2_total,$suma_depre_acumulada_total,$suma_incremento_depre_total,
					$suma_depre_periodo_total," ");
		$excel->writeLineTotales($sumastotales);
	
		
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}
function escribir_gasto($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion)
  {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra",
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv,"Valor al mes anterior".$fecha_antes."con".$valor_ufv,
	"Incremento por actualizacion AF con UFV","Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion gasto".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Acde la depreciacion del depiodo ante",
	"Depreciacion Periodo UFV".$fecha_fin."con UFV".$valor_ufv2);
	$celdas=sizeof($myArr);
	$titulo=array($descripcion);	
	$excel->writeCabecera($titulo);
	$excel->writeCabecera($myArr);
	 
	  
	  $suma_compra=0;
	  $suma_valorufv1=0;
	  $suma_incremento=0;
	  $suma_valorufv2=0;
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	 
	  
	  foreach($datos as $llave => $val)
	  {	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		
		$val_ufv=$valor_ufv;		
		$valor_ufv1=$val['valor_ufv'];
		$incremento_ufv=$val['incremento_ufv'];
		$valor_ufv2=$val['valor_ufv2'];
		$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		$incremento_depre_ufv=$val['incremento_depre_ufv'];
		$depre_periodo_ufv=$val['depre_periodo_ufv'];
		
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		$suma_incremento=$suma_incremento+$incremento_ufv;
		$suma_valorufv2=$suma_valorufv2+$valor_ufv2;
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada_ufv;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre_ufv;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo_ufv;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$val_ufv,$valor_ufv1,
				$incremento_ufv,$valor_ufv2,$depre_acumulada_ufv,
				$incremento_depre_ufv,$depre_periodo_ufv);
				
		$excel->writeLine($myArr);
		}
		
		$sumasarray=array(" "," "," "," "," "," ",
					$suma_compra," ",$suma_valorufv1,$suma_incremento,
					$suma_valorufv2,$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo);
		$excel->writeLineTotales($sumasarray);
			
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}
function escribir_gasto_sec($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripri,$descrisecu)
						       {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra",
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv,"Valor al mes anterior".$fecha_antes."con".$valor_ufv,
	"Incremento por actualizacion AF con UFV","Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion gasto".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Acde la depreciacion del depiodo ante",
	"Depreciacion Periodo UFV".$fecha_fin."con UFV".$valor_ufv2);
	$celdas=sizeof($myArr);
	$titulo=array($descripri);
	$subtitulo=array($descrisecu);	
	
	$excel->writeCabecera($titulo);
	$excel->writeCabecera($subtitulo);
	$excel->writeCabecera($myArr);	
	
	$suma_compra_total=0;
	
	$suma_valorufv1_total=0;
	$suma_incremento_total=0;
	$suma_valorufv2_total=0;
	$suma_depre_acumulada_total=0;
	$suma_incremento_depre_total=0;
	$suma_depre_periodo_total=0;

	
	
	foreach($datos as $key => $value)
	{ 
	  $grupo=$key;
	  $excel->writeCabeceraGrupo($grupo, $celdas);
	  
	  
	  $suma_compra=0;
	  $suma_valorufv1=0;
	  $suma_incremento=0;
	  $suma_valorufv2=0;
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	 
	  
	  foreach($value as $llave => $val)
	  {	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		
		$val_ufv=$valor_ufv;		
		$valor_ufv1=$val['valor_ufv'];
		$incremento_ufv=$val['incremento_ufv'];
		$valor_ufv2=$val['valor_ufv2'];
		$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		$incremento_depre_ufv=$val['incremento_depre_ufv'];
		$depre_periodo_ufv=$val['depre_periodo_ufv'];
		
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		$suma_incremento=$suma_incremento+$incremento_ufv;
		$suma_valorufv2=$suma_valorufv2+$valor_ufv2;
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada_ufv;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre_ufv;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo_ufv;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$val_ufv,$valor_ufv1,
				$incremento_ufv,$valor_ufv2,$depre_acumulada_ufv,
				$incremento_depre_ufv,$depre_periodo_ufv);
				
		$excel->writeLine($myArr);
		
	  
	  
	  
	  
	  }
		
		$sumasarray=array(" "," "," "," "," "," ",
					$suma_compra," ",$suma_valorufv1,$suma_incremento,
					$suma_valorufv2,$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo);
		$excel->writeLineTotales($sumasarray);
			
		
	    $suma_compra_total=$suma_compra_total+$suma_compra;
		
		$suma_valorufv1_total=$suma_valorufv1_total+$suma_valorufv1;
		$suma_incremento_total=$suma_incremento_total+$suma_incremento;
		$suma_valorufv2_total=$suma_valorufv2_total+$suma_valorufv2;
		$suma_depre_acumulada_total=$suma_depre_acumulada_total+$suma_depre_acumulada;
		$suma_incremento_depre_total=$suma_incremento_depre_total+$suma_incremento_depre;
		$suma_depre_periodo_total=$suma_depre_periodo_total+$suma_depre_periodo;
		
	
	//echo "data is write into myXls.xls Successfully.";
}

		$sumastotales=array(" "," "," "," "," "," ",
					$suma_compra_total," ",$suma_valorufv1_total,$suma_incremento_total,
					$suma_valorufv2_total,$suma_depre_acumulada_total,$suma_incremento_depre_total,
					$suma_depre_periodo_total," ");
		$excel->writeLineTotales($sumastotales);
	
		
		
	$excel->close();
	//echo "data is write into myXls.xls Successfully.";
}

function escribir_gasto_secprigru($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripri,$descrisecu,$descripcion)
						       {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra",
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv,"Valor al mes anterior".$fecha_antes."con".$valor_ufv,
	"Incremento por actualizacion AF con UFV","Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion gasto".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Acde la depreciacion del depiodo ante",
	"Depreciacion Periodo UFV".$fecha_fin."con UFV".$valor_ufv2);
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
	  $suma_valorufv1=0;
	  $suma_incremento=0;
	  $suma_valorufv2=0;
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	 
	  
	  foreach($datos as $llave => $val)
	  {	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		
		$val_ufv=$valor_ufv;		
		$valor_ufv1=$val['valor_ufv'];
		$incremento_ufv=$val['incremento_ufv'];
		$valor_ufv2=$val['valor_ufv2'];
		$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		$incremento_depre_ufv=$val['incremento_depre_ufv'];
		$depre_periodo_ufv=$val['depre_periodo_ufv'];
		
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		$suma_incremento=$suma_incremento+$incremento_ufv;
		$suma_valorufv2=$suma_valorufv2+$valor_ufv2;
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada_ufv;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre_ufv;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo_ufv;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$val_ufv,$valor_ufv1,
				$incremento_ufv,$valor_ufv2,$depre_acumulada_ufv,
				$incremento_depre_ufv,$depre_periodo_ufv);
				
		$excel->writeLine($myArr);
		}
		
		$sumasarray=array(" "," "," "," "," "," ",
					$suma_compra," ",$suma_valorufv1,$suma_incremento,
					$suma_valorufv2,$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo);
		$excel->writeLineTotales($sumasarray);
				
		
	$excel->close();
	
		
	}
function escribir_gasto_dolarufv($nombre_archivo,$datos,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante)
						       {
	$excel=new ExcelWriter("../reportes/actualizaciones/".$nombre_archivo.".xls");
	if($excel==false)
		echo $excel->error;
		
	$myArr=array("Codigo","Fecha Ingreso ","Descripcion", "T/C de la Compra","UFV de la Compra","Vida Util(meses)","Valor Compra",
	"Valor UFV inicial".$fecha_antes."con".$valor_ufv,"Valor al mes anterior".$fecha_antes."con".$valor_ufv,
	"Incremento por actualizacion AF con UFV","Valor al".$fecha_fin."con UFV".$valor_ufv2,"Depreciacion gasto".$fecha_antes."con UFV".$valor_ufv,"Incremento P/Acde la depreciacion del depiodo ante",
	"Depreciacion Periodo UFV".$fecha_fin."con UFV".$valor_ufv2);
	$celdas=sizeof($myArr);
	$excel->writeCabecera($myArr);
	
	$suma_compra_total=0;
	
	$suma_valorufv1_total=0;
	$suma_incremento_total=0;
	$suma_valantes_total=0;
	$suma_depre_acumulada_total=0;
	$suma_incremento_depre_total=0;
	$suma_depre_periodo_total=0;

	
	
	foreach($datos as $key => $value)
	{ 
	  $grupo=$key;
	  $excel->writeCabeceraGrupo($grupo, $celdas);
	  
	  
	  $suma_compra=0;
	  $suma_valorufv1=0;
	  $suma_incremento=0;
	  $suma_valantes=0;
	  $suma_depre_acumulada=0;
	  $suma_incremento_depre=0;
	  $suma_depre_periodo=0;
	 
	  
	  foreach($value as $llave => $val)
	  {	
	  	$numero = $val['numero'];
		$fecha = $val['fecha'];
		$descripcion =$val['descripcion'];
		$tipo_cambio = $val['tipo_cambio'];
		$ufv=$val['ufv'];
		$vida_util = $val['vida_util'];
		$valor_compra = $val['valor_compra'];
		
		$val_ufv=$valor_ufv;		
		$valor_ufv1=$val['valor_ufv'];
		$incremento_ufv=$val['incremento_ufv'];
		$valor_ufvantes=$val['valor_ufvantes'];
		$depre_acumulada_ufv=$val['depre_acumulada_ufv'];
		$incremento_depre_ufv=$val['incremento_depre_ufv'];
		$depre_periodo_ufv=$val['depre_periodo_ufv'];
		
			
		$suma_compra=$suma_compra+$valor_compra;
		$suma_valorufv1=$suma_valorufv1+$valor_ufv1;
		$suma_incremento=$suma_incremento+$incremento_ufv;
		$suma_valantes=$suma_valantes+$valor_ufvantes;
		$suma_depre_acumulada=$suma_depre_acumulada+$depre_acumulada_ufv;
		$suma_incremento_depre=$suma_incremento_depre+$incremento_depre_ufv;
		$suma_depre_periodo=$suma_depre_periodo+$depre_periodo_ufv;
		
		

		
		
		$myArr=array($numero,$fecha,$descripcion,$tipo_cambio,$ufv,
				$vida_util,$valor_compra,$val_ufv,$valor_ufvantes,$incremento_ufv,$valor_ufv1,
				$valor_ufv2,$depre_acumulada_ufv,
				$incremento_depre_ufv,$depre_periodo_ufv);
				
		$excel->writeLine($myArr);
		}
		
		$sumasarray=array(" "," "," "," "," "," ",
					$suma_compra," ",$suma_valantes,$suma_incremento,
					$suma_valorufv1,$suma_depre_acumulada,$suma_incremento_depre,
					$suma_depre_periodo);
		$excel->writeLineTotales($sumasarray);
		
		$suma_compra_total=$suma_compra_total+$suma_compra;
		$suma_valorufv1_total=$suma_valorufv1_total+$suma_valorufv1;
		$suma_incremento_total=$suma_incremento_total+$suma_incremento;
		$suma_valantes_total=$suma_valantes_total+$suma_valantes;
		
		$suma_depre_acumulada_total=$suma_depre_acumulada_total+$suma_depre_acumulada;
		$suma_incremento_depre_total=$suma_incremento_depre_total+$suma_incremento_depre;
		$suma_depre_periodo_total=$suma_depre_periodo_total+$suma_depre_periodo;
		
		
	}
$sumastotales=array(" "," "," "," "," "," ",
					$suma_compra_total," ",$suma_valantes_total,$suma_incremento_total,
					$suma_valorufv1_total,$suma_depre_acumulada_total,$suma_incremento_depre_total,
					$suma_depre_periodo_total);
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
} else {	
		      	
				$todo=$_GET['todo'];
				
				$fecha_inicio = trim($_GET["fecha_inicio"]);
				$fecha_fin = trim($_GET["fecha_fin"]);
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
				
				if ($fecha_fin < $fecha_inicio)
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
				 if ($fecha_inicio < $fecha_ges_ini || $fecha_fin >$fecha_ges_fin)
				 {
				 	 $error['control'] = "Las fechas del reporte no corresponden a la de la Gestion Seleccionada";
			         $smarty->assign('error',$error);
			        
				 }    
				
			if (isset($error)){
				
				  $primaria=$primaria->listar_locprimaria();
				  $grupo=$grupo->listar_grupo();													
				   
				   	$smarty->assign('fecha_inicio',$fecha_inicio);
					$smarty->assign('fecha_fin',$fecha_fin);
					$smarty->assign('error', $error);
					$smarty->assign('primaria', $primaria);								
					$smarty->assign('grupo', $grupo);
					$smarty->assign('corte',$corte);	
					$smarty->assign('gest',$gest);
					$smarty->assign('gestion', $gestion);					
					$smarty->assign('todo', $todo);	
					$smarty->assign('repor', $repor);	
					$smarty->display('gastos.html');	
							
										
				} 
	 	 else
	 		 { if ($todo==1)
	  	   		{  
	  			
	  	   			//$calculo=$activo->calcular_actualizacion_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion_ante);
	  	   		 
				     $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
	  	   			
				    if ($fecha_antes == $fecha_inter && $fecha_fin >=$fecha_inter) //este if tiene que aparece en el reporte los dos ufv y dolar
					{
					if ( $fecha_inicio >= $fecha_ges_ini && $fecha_fin <= $fecha_ges_fin )
				   		{	
				   		
					 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
					 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,1);//valor en dolar al 31-03-2007
					 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
					 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
						
				/***Dato de l agestion anterior****/
							
					$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
					$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
					$act='gastos-'.$fecha_inicio.'-'.$fecha_fin;
		  	   		$calculo=$acttodo->calcular_gasto_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion,$fecha_ges_ini,$fecha_ges_fin);
	
			  	   	if ($calculo != null)
					    {
					
					      escribir_gasto_dolarufv($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante);
					      $smarty->assign('excel',$act);
					     }
						        									
					
					
					 $smarty->assign('ver','si');
				   		}	
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
				   		if ( $fecha_inicio >= $fecha_ges_ini && $fecha_fin <= $fecha_ges_fin )
				   		{	
				   			$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
		 			        
				   		    $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
					   
							/***Dato de l agestion anterior****/
							
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
														
							
							$act='gastos-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				$calculo=$acttodo->calcular_gasto_todo($fecha_inter,$fecha_inicio,$fecha_fin,$fecha_gestion,$fecha_ges_ini,$fecha_ges_fin);
	
			  	   			if ($calculo != null)
						       {
					
						          escribir_todo_excel($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante);
						          $smarty->assign('excel',$act);
						        }
						        
								
				   		    $smarty->assign('ver2','si');
				   		 
				   		}
				   		 
				   		 
				   		 
				   	}
				   	else 
				   	{/*
				   		if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
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
	  	   			 /* echo "<pre>";
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
					$smarty->assign('gestion',$gestion);
					$smarty->assign('primaria', $primaria);								
					$smarty->assign('grupo', $grupo);					
					$smarty->display('gastos.html');			
										
	} 
		 
		 
	else{
		
		    
		 if ($pri =='selc'& $secun =='selc' & $grupo_id !='')
		 {	 
		   //echo "ya pues funciona";
		     //	$calculo=$activo->calcular_actualizacion($fecha_inter,$fecha_inicio,$fecha_fin,$grupo_id);

		    	
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
					  $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				
					
						          escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						          $smarty->assign('excel',$act);
					
					 $valor_ufv=0;//valor en ufv al 31-03-2007
					 $smarty->assign('descripcion', $descripcion);
				   	 $smarty->assign('ver1','si');
				   	 
				   	 
				   	
				   }
				    else 
				   {
				   	if ($fecha_inicio > $fecha_inter && $fecha_fin > $fecha_inter)
				   	{  
				   		if ( $fecha_inicio >= $fecha_ges_ini && $fecha_fin <= $fecha_ges_fin )
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
														
							$act='gastos-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				$calculo=$actgrupo->calcular_gasto($fecha_inter,$fecha_inicio,$fecha_fin,$grupo_id,$fecha_gestion_ante,$fecha_ges_ini,$fecha_ges_fin);
			  	   			if ($calculo != null)
						       {
					
						          escribir_gasto($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						          $smarty->assign('excel',$act);
						        }
							
									        
							
							
							$smarty->assign('descripcion', $descripcion);
					        $smarty->assign('fecha_antes', $fecha_antes);
				   		    $smarty->assign('ver2','si');
				   		 
				   		}
				   	
				   		 
				   	}
				   	else 
				   	{
				   		if ($fecha_inicio < $fecha_inter && $fecha_fin > $fecha_inter)
				   		{    
				   			
				   			 $descripcion=$grupo->locgru($grupo_id);	
		 					 $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   			 $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,1); //valor al comienzo del reporte
							 $valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1);//valor en dolar al 31-03-2007
							  $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   		
					
						          escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						          $smarty->assign('excel',$act);
					
							 $valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor en ufv al 31-03-2007
							 $valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);
							 $smarty->assign('descripcion', $descripcion);
				   			 $smarty->assign('ver3','si');
				   			 
				   		
				   		}
				   
					}
		
			 	}
		
			}
		  			 
						
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
				   		if ( $fecha_inicio >= $fecha_ges_ini && $fecha_fin <= $fecha_ges_fin )
				   		{
				   		    $fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);

		 			        $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
					        
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
							$act='gastos-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				
							$calculo=$actsec->calcular_gasto_sec($fecha_inter,$fecha_inicio,$fecha_fin,$localizacion,$locsecundaria,$secun,$pri,$fecha_gestion_ante,$fecha_ges_ini,$fecha_ges_fin);

							if ($calculo != null)
						       {
					
						          escribir_gasto_sec($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripri,$descrisecu);
						         
						          $smarty->assign('excel',$act);
						        }
							
							
							$smarty->assign('descripcion', $descripcion);
					        $smarty->assign('fecha_antes', $fecha_antes);
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
							  $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   		
					
						          escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						          $smarty->assign('excel',$act);
					
				   			 $smarty->assign('ver3','si');
				   			 
				   		
				   		}
				   
					}
		
			 	}
		
			}
		      
						
		 		
				 		
               		/*  echo "<pre>";
	  	   			  print_r($calculo);
	  	   			  echo "</pre>";*/
			
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
				   		  		 
			    //	$calculo=$activo->calcular_actualizacion_gru($fecha_inter,$fecha_inicio,$fecha_fin,$secun,$grupo_id);
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
				   		 if ( $fecha_inicio >= $fecha_ges_ini && $fecha_fin <= $fecha_ges_fin )
				   		{
				   		 
				   	    	$fecha_antes=$activo->diferencia_fechas_undia($fecha_inicio);
				   		 			        
				   		    $valor_dolar=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,1); //valor al comienzo del reporte
							$valor_dolar2=$tipo_cambio->buscar_fecha_tipocambio($fecha_inter,2);//valor ufv fecha inter
							$valor_ufv=$tipo_cambio->buscar_fecha_tipocambio($fecha_antes,2);//valor en ufv al 31-03-2007
							$valor_ufv2=$tipo_cambio->buscar_fecha_tipocambio($fecha_fin,2);//valor al final del reporte
					     
							    
							$valor_dolar_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,1); //valor $ a la fecha fin de la gestion anterior
							$valor_ufv_ges=$tipo_cambio->buscar_fecha_tipocambio($fecha_gestion_ante,2);//valor de ufve a fecha fin de la gestion anterior
						
							
								$act='Gasto-'.$fecha_inicio.'-'.$fecha_fin;
		  	   				
								$calculo=$actsec->calcular_gasto_gru($fecha_inter,$fecha_inicio,$fecha_fin,$localizacion,$locsecundaria,$secun,$pri,$grupo_id,$fecha_gestion_ante,$fecha_ges_ini,$fecha_ges_fin);
							if ($calculo != null)
						       {
					
						          escribir_gasto_secprigru($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripri,$descrisecu,$descripcion);
						         
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
							  $act='Noexisteresultado-'.$fecha_inicio.'-'.$fecha_fin;
		  	   		
					
						          escribir_no_exite($act,$calculo,$fecha_antes,$fecha_inicio,$fecha_fin,$fecha_inter,$valor_ufv, $valor_ufv2,$valor_dolar_ges,$valor_ufv_ges,$fecha_gestion_ante,$descripcion);
						          $smarty->assign('excel',$act);
					
				   			 $smarty->assign('ver3','si');
				   			 
				   		
				   		}
				   
					}
		
			 	}
		
			}
		  
					
				
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
       
       
       
       
  

?>