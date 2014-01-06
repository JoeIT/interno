<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/ordenproductonuevo.php');
include_once('../../clases/sistema_de_produccion/estadoproductos.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);

$ordenes_producto_nuevo=new OrdenProductoNuevo;
$estadoproducto=new EstadoProducto;
$funcion=$_POST['funcion'];
 if(trim($funcion=="validar"))
 {
    $observaciones = $_POST['observaciones'];
    $ordenproducto_id=$_POST['elegido'];
	$estado=$_POST['estado'];
	// SUBIR EL ARCHIVO AL SERVIDOR
            if($HTTP_POST_FILES['grafico']['name']!="")
          	{
			    $nombre_archivo = $HTTP_POST_FILES['grafico']['name'];
				$tipo_archivo = $HTTP_POST_FILES['grafico']['type'];
				$tamano_archivo = $HTTP_POST_FILES['grafico']['size']; 
			    if(!(strpos($tipo_archivo, "gif") || strpos($tipo_archivo, "jpeg")))
				{
				   $error="Tipo de Archivo no valido";  
				   $detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
				   $_SESSION['ordenproducto_id'] = $ordenproducto_id;
				   $_SESSION['detalle_orden']= $detalle_orden;
				   $_SESSION['error']= $error;
				    header("Location: ../../src/sistema_de_produccion/registrar_fotografia_orden_productos.php");	
				
				}
				else
				{  
				    $prefijo = substr(md5(uniqid(rand())),0,6);
				    $nombre_archivo="../../imagenes_producto_nuevo/".$prefijo."_".$nombre_archivo;
				
				    move_uploaded_file($HTTP_POST_FILES['grafico']['tmp_name'], $nombre_archivo);
				    
					$ordenes_producto_nuevo->actualizar_grafico($ordenproducto_id,$nombre_archivo);
					$ordenes_producto_nuevo->actualizar_estado($ordenproducto_id,$estado);
					$usuario_id=$_SESSION['usuario_id'];
				    $estadoproducto->registrar_estado($ordenproducto_id,trim($observaciones),$usuario_id);
				    $detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
				    $_SESSION['elegido'] = $ordenproducto_id;
					$_SESSION['opcion'] = "lista_busqueda";
					header("Location: ../../controladores/sistema_de_produccion/ver_detalle_orden_productos.php");
				
				}
			
		    
	        }
			else
			{
			       $error="Debe ingresar un archivo de imagen";  
				   $detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
				   $smarty->assign('ordenproducto_id',$ordenproducto_id);
				   $smarty->assign('detalle_orden',$detalle_orden);
	               $smarty->assign('error',$error);
                   $smarty->display('sistema_de_produccion/orden_de_producto_nuevo/registrar_fotografia_orden_productos.html');
			}
	
 }
 else
 {
    $ordenproducto_id=$_SESSION['elegido'];
	$opcion=$_SESSION['opcion'];
	$detalle_orden=$ordenes_producto_nuevo->obtener_detalle($ordenproducto_id);
	$smarty->assign('ordenproducto_id',$ordenproducto_id);
	$smarty->assign('detalle_orden',$detalle_orden);
	$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/registrar_fotografia_orden_productos.html');
 }	   
?>