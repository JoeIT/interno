<?php
session_start();

include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/sistema_de_produccion/ordenprod.php');
include_once('../../clases/include/validador.php');
require_once('../includes/seguridad.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/sistema_de_produccion/colores.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/sistema_de_produccion/clip.php');
include_once('../../clases/sistema_de_produccion/chapa.php');
include_once('../../clases/sistema_de_produccion/cuero.php');
include_once('../../clases/sistema_de_produccion/etiquetas.php');
include_once('../../clases/sistema_de_produccion/propiedades.php');
include_once('../../clases/sistema_de_produccion/familias.php');
include_once('../../clases/sistema_de_produccion/fuentes.php');
include_once('../../clases/sistema_de_produccion/lugargrabado.php');

$orden=new OrdenProd();
$detalle_orden=new Detalle_orden;
$fuentes=new Fuente;
$grabados=new Lugar;
$estilos=new Estilo;
$validar=new Validador();
$colores=new Color;
$clips=new Clip;
$sellos=new Chapa;
$cueros=new Cuero;
$etiquetas=new Etiqueta;
$propiedades=new Propiedad;
$familias=new Familia;

$funcion=$_POST["funcion"];

if($funcion=="modificar_fecha")
{
   $fecha=$_POST["date2"];
   $orden_id=$_POST["orden_id"];
   $orden->modificar_fecha_reprogramacion($orden_id,$fecha);
   $detalle=$detalle_orden->obtener_detalle_orden($orden_id);
   $cabecera=$orden->obtener_cabecera_orden($orden_id);
   $fecha=split("-",$cabecera["fecha_reprogramacion"]);
   $fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
   $cabecera["fecha_reprogramacion"]=$fecha1;
		$_SESSION['orden_id'] = $orden_id;
		$_SESSION['detalle']= $detalle;
		$_SESSION['cabecera']= $cabecera;
	    header("Location: ../../src/sistema_de_produccion/modificar_orden.php");
}
else
{
   if($funcion=="modificar_detalle")
   {
      $orden_id=$_POST["orden_id"];
	  $detalle_id=$_POST["elegido"];
	  $detalle=$detalle_orden->obtener_datos_detalle($detalle_id);
	  $cabecera=$orden->obtener_cabecera_orden($orden_id);
	  $fecha=split("-",$cabecera["fecha_reprogramacion"]);
	  $fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
	  $cabecera["fecha_reprogramacion"]=$fecha1;
	  $_SESSION['cabecera'] = $cabecera;
	  $_SESSION['orden_id'] = $orden_id;
	  $_SESSION['detalle_id']= $detalle_id;
	  $_SESSION['detalle']= $detalle;
	  header("Location: ../../src/sistema_de_produccion/modificar_detalle_orden.php");
   
   }
   else
   {
     if($funcion=="eliminar_detalle")
	 { 
	      $orden_id=$_POST["orden_id"];
		  $detalle_id=$_POST["elegido"];
		  $detalle_orden->deshabilitar_detalle($detalle_id);
	      $detalle=$detalle_orden->obtener_detalle_orden($orden_id);
		  $cabecera=$orden->obtener_cabecera_orden($orden_id);
		  $fecha=split("-",$cabecera["fecha_reprogramacion"]);
		  $fecha1=$fecha[2]."-".$fecha[1]."-".$fecha[0];
		  $cabecera["fecha_reprogramacion"]=$fecha1;
		$_SESSION['orden_id'] = $orden_id;
		$_SESSION['detalle']= $detalle;
		$_SESSION['cabecera']= $cabecera;
	    header("Location: ../..src/sistema_de_produccion/modificar_orden.php");
	  
	 }
	 else
	 {
	 
	    $detalle=$detalle_orden->obtener_detalle_orden($_POST['elegido']);
		$cabecera=$orden->obtener_cabecera_orden($_POST['elegido']);
        
		$_SESSION['orden_id'] = $_POST['elegido'];
		$_SESSION['detalle']= $detalle;
		$_SESSION['cabecera']= $cabecera;
	    header("Location: ../../src/sistema_de_produccion/modificar_orden.php");
	 }
   }
}


?>