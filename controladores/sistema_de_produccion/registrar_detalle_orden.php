<?php
session_start();

include_once('../../clases/sistema_de_produccion/detalle_orden.php');
include_once('../../clases/includes/validador.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/sistema_de_produccion/colores.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases//sistema_de_produccion/clip.php');
include_once('../../clases/sistema_de_produccion/chapa.php');
include_once('../../clases/sistema_de_produccion/cuero.php');
include_once('../../clases/sistema_de_produccion/etiquetas.php');
include_once('../../clases/sistema_de_produccion/propiedades.php');
include_once('../../clases/sistema_de_produccion/familias.php');
include_once('../../clases/sistema_de_produccion/fuentes.php');
include_once('../../clases/sistema_de_produccion/lugargrabado.php');
require_once('../includes/seguridad.php');

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

if (!empty($_POST))
{
    if ($validar->validarTodo($_POST['texto_modelo'], 1, 100)) {
   	   $error['err_texto_modelo'] = "Debe ingresar la descripción del modelo";}
	else
	{
	
	    $familia_id=$familias->verifica_familia_valida(trim($_POST['texto_modelo']));
        if($familia_id==-1){ $error['err_texto_modelo'] = "Descripción del modelo no válida";}
	
	}
    if ($validar->validarTodo($_POST['texto_estilo'], 1, 100)) {
    	$error['err_texto_estilo'] = "Debe ingresar un estilo";}
	else
	{  
	   $estilo_id=$estilos->verificar_estilo(trim($_POST['texto_estilo']));
       if($estilo_id==-1){ $error['err_texto_estilo'] = "Estilo no valido";}
	}
    if ($validar->validarTodo($_POST['texto_clip'], 1, 100)) {
    	$error['err_texto_clip'] = "Debe ingresar un clip";}
	else
	{  
	   $clip_id=$clips->verificar_clip(trim($_POST['texto_clip']));
       if($clip_id==-1){ $error['err_texto_clip'] = "Clip no valido";}
	}
	if ($validar->validarTodo($_POST['texto_cuero'], 1, 100)) {
    	$error['err_texto_cuero'] = "Debe ingresar un tipo de cuero";}
	else
	{  
	   $cuero_id=$cueros->verificar_cuero(trim($_POST['texto_cuero']));
       if($cuero_id==-1){ $error['err_texto_cuero'] = "Cuero no valido";}
	}
	if ($validar->validarTodo($_POST['texto_color'], 1, 100)) {
    	$error['err_texto_color'] = "Debe ingresar un color";}
	else
	{  
	   $color_id=$colores->verificar_color(trim($_POST['texto_color']));
       if($color_id==-1){ $error['err_texto_color'] = "Color no valido";}
	}
	if ($validar->validarTodo($_POST['texto_etiqueta'], 1, 100)) {
    	$error['err_texto_etiqueta'] = "Debe ingresar una etiqueta";}
	else
	{  
	   $etiqueta_id=$etiquetas->verificar_etiqueta(trim($_POST['texto_etiqueta']));
       if($etiqueta_id==-1){ $error['err_texto_etiqueta'] = "Etiqueta no valida";}
	}
		
	if ($validar->validarTodo($_POST['texto_sello'], 1, 100)) {
    	$error['err_texto_sello'] = "Debe ingresar un sello o chapa";}	
	else
	{  
	   $sello_id=$sellos->verificar_chapa(trim($_POST['texto_sello']));
       if($sello_id==-1){ $error['err_texto_sello'] = "Sello/Chapa no valido";}
	}
	if ($validar->validarTodo($_POST['texto_cantidad'], 1, 100)) {
    	$error['err_texto_cantidad'] = "Debe ingresar una cantidad";}							 
	else
    {
	     if ($validar->validarNumeros($_POST['texto_cantidad'], 1, 3)) {
        	$error['err_texto_cantidad'] = "Debe ingresar una cantidad valida";}							
	}
	if ($validar->validarTodo($_POST['texto_unidad'], 1, 100)) {
    	$error['err_texto_unidad'] = "Debe ingresar la unidad";}	
	if ($validar->validarTodo($_POST['texto_pedido'], 1, 100)) {
    	$error['err_texto_pedido'] = "Debe ingresar el codigo de pedido";}	
	if (!$validar->validarTodo($_POST['texto_lugar_grabado'], 1, 100)) 
	{
	    if ($validar->validarTodo($_POST['texto_grabado'], 1, 100)) {
	    	$error['err_texto_grabado'] = "Debe ingresar un grabado";}	
    	$grabado_id=$grabados->verificar_lugar(trim($_POST['texto_lugar_grabado']));
        if($grabado_id==-1){ $error['err_texto_lugar_grabado'] = "Lugar de grabado no válido";}
	}
	if (!$validar->validarTodo($_POST['texto_fuente'], 1, 100)) 
	{
        if ($validar->validarTodo($_POST['texto_grabado'], 1, 100)) {
	    	$error['err_texto_grabado'] = "Debe ingresar un grabado";}
    	$fuente_id=$fuentes->verificar_fuente(trim($_POST['texto_fuente']));
        if($fuente_id==-1){ $error['err_texto_fuente'] = "Tipo de fuente no valido";}
	}
	if (!$validar->validarTodo($_POST['texto_grabado'], 1, 100)) 
	{
	     if ($validar->validarTodo($_POST['texto_fuente'], 1, 100) and $validar->validarTodo($_POST['texto_lugar_grabado'], 1, 100))
		 {
		     $error['err_texto_lugar_grabado'] = "Debe ingresar el tipo de fuente o el lugar de grabado"; 
		 }
	
	}	
	if ($validar->validarTodo($_POST['texto_prioridad'], 1, 100)) {
    	$error['err_texto_prioridad'] = "Debe ingresar la prioridad";}							 
	else
    {
	     if ($validar->validarNumeros($_POST['texto_prioridad'], 1, 3)) {
        	$error['err_texto_prioridad'] = "Debe ingresar una prioridad valida";}							
	}						
    if (isset($error))
	{
		$_SESSION['num_orden'] = $_POST['num_orden'];
		$_SESSION['num_cup'] = $_POST['num_cup'];
		$_SESSION['orden_id'] = $_POST['orden_id'];
		$_SESSION['fecha']=  $_POST['fecha'];
		$_SESSION['fechaentrega']= $_POST['fechaentrega'];
		$_SESSION['cliente']= $_POST['cliente'];
		$_SESSION['modelo'] = $_POST['texto_modelo'];
  	    $_SESSION['estilo'] = $_POST['texto_estilo'];
		$_SESSION['clip']=  $_POST['texto_clip'];
		$_SESSION['cuero']= $_POST['texto_cuero'];
		$_SESSION['color']= $_POST['texto_color'];
		$_SESSION['etiqueta']= $_POST['texto_etiqueta'];
		$_SESSION['sello']= $_POST['texto_sello'];
		$_SESSION['observaciones']= $_POST['observaciones'];	
		$_SESSION['observaciones_internas']= $_POST['observaciones_internas'];
		$_SESSION['cantidad']= $_POST['texto_cantidad'];
		$_SESSION['unidad']= $_POST['texto_unidad'];	
		$_SESSION['pedido']= $_POST['texto_pedido'];
		$_SESSION['prioridad']= $_POST['texto_prioridad'];
		$_SESSION['grabado']= $_POST['texto_grabado'];	
		$_SESSION['fuente']= $_POST['texto_fuente'];
		$_SESSION['lugar_grabado']= $_POST['texto_lugar_grabado'];							
		$_SESSION['errores']= $error;
		$detalle=$detalle_orden->obtener_detalle_orden($_POST['orden_id']);
		$_SESSION['detalle']= $detalle;
		header("Location: ../../src/sistema_de_produccion/registrar_detalle_orden.php");
	}
	else
	{
	    
		$prop_id=$propiedades->insertar_nueva_propiedad($estilo_id,$clip_id,$cuero_id,$color_id,$etiqueta_id,$sello_id);
		
        $detalle_orden->adicionar_producto_detalle($_POST['orden_id'],$_POST['texto_cantidad'],$familia_id,$_POST['texto_unidad'],$_POST['texto_prioridad'],$_POST['observaciones'],$_POST['texto_pedido'],$prop_id,$_POST['texto_grabado'],$_POST['texto_lugar_grabado'],$_POST['texto_fuente'],$_POST['observaciones_internas']);
		
		$detalle=$detalle_orden->obtener_detalle_orden($_POST['orden_id']);
		
		$_SESSION['num_orden'] = $_POST['num_orden'];
		$_SESSION['orden_id'] = $_POST['orden_id'];
		$_SESSION['num_cup'] = $_POST['num_cup'];
		$_SESSION['fecha']=  $_POST['fecha'];
		$_SESSION['fechaentrega']= $_POST['fechaentrega'];
		$_SESSION['cliente']= $_POST['cliente'];
		$_SESSION['errores']= $error;
		$_SESSION['detalle']= $detalle;
	    header("Location: ../../src/sistema_de_produccion/registrar_detalle_orden.php");
	
	
	}
		  
}
?>