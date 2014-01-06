<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/ordenproductonuevo.php');
include_once('../../clases/sistema_de_produccion/propiedadesproductonuevo.php');
include_once('../../clases/sistema_de_produccion/estadoproductos.php');
include_once('../../clases/includes/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$smarty->assign('nombres', $_SESSION["nombres"]);
$smarty->assign('apellidos', $_SESSION["apellidos"]);
$ordenes_producto_nuevo=new OrdenProductoNuevo;
$propiedades_producto_nuevo=new PropiedadProductoNuevo;
$estado_productos=new EstadoProducto;
$funcion = $_POST['funcion'];
$estado=$_GET['estado'];
$opcion=$_GET['opcion'];
$cambio_tab=$_GET['tab'];
/***********************************/
/*echo "funcion:".$funcion."<br>";
echo "estado:".$estado."<br>";
echo "opcion:".$opcion."<br>";
*/



if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	/////////////////////////////////////*/
	$validar=new Validador();
	if(trim($cambio_tab)!="")
	{
		 if ($_GET['tabu'])
		 {
		   
			   $_SESSION['tabu'] = $_GET['tabu'];//
			   $smarty->assign('tabu',$_GET['tabu']);
			   //$elegido=$_POST['elegido'];
			   $consulta= $ordenes_producto_nuevo->consulta_lista_ordenes($estado);
			   $smarty->assign('lista_ordenes_productos',$consulta);
			   $smarty->assign('opcion',$opcion);
			   $smarty->assign('estado',$estado);
			   $smarty->display('sistema_de_produccion/orden_de_producto_nuevo/lista_ordenes_productos.html');
			}
	
	
	}
	else
	{
	
	//$url_relativa = "../../src/sistema_de_produccion/lista_ordenes_productos.php";
	//verificamos si se desea modificar la información de un usuario
		if($funcion=="modificar")
		{
			$elegido=$_POST['elegido'];
			$_SESSION['elegido'] = $elegido;
			$_SESSION['opcion'] = $opcion;
			header("Location: ../../controladores/sistema_de_produccion/modificar_producto_nuevo.php" );
		}
		else
		{    if($funcion=="buscar")
			 {
				  $cadena = trim($_POST['orden']);
				  $opcion_busqueda = trim($_POST['tipo']);
				  $_SESSION['tabu'] = $_POST['tabu'];//
				if ($validar->validarTodo($cadena, 1, 100)){
					if ($opcion_busqueda == "num_orden"){
							$error['err_nombre'] = "Ingresar C&oacute;digo de &Oacute;rden";
					} else {
							$error['err_nombre'] = "Ingresar Nombre de Cliente";
					}
				}
				$smarty->assign('orden',$cadena);
				if (isset($error)){
			
					//reenviar los errores
					  $consulta= $ordenes_producto_nuevo->consulta_lista_ordenes($estado);
					  $smarty->assign('lista_ordenes_productos',$consulta);
					  $smarty->assign('errores',$error);
					  $smarty->assign('opcion',$opcion);
					  $smarty->assign('estado',$estado);
					  $smarty->assign('tabu',$_SESSION['tabu']);
					  $smarty->display('sistema_de_produccion/orden_de_producto_nuevo/lista_ordenes_productos.html');
			} else {
			
					//echo "estado: ".$estado;
				//	if($estado=="")
					//   $estado=1;
					echo "opcion busqueda: ".$opcion_busqueda;
					$consulta= $ordenes_producto_nuevo->consultar_busqueda($cadena,$opcion_busqueda,$estado);
					if ($consulta != null){
					
						$smarty->assign('lista_ordenes_productos',$consulta);
						$smarty->assign('opcion',$opcion);
						$smarty->assign('estado',$estado);
						$smarty->assign('tabu',$_SESSION['tabu']);
						$smarty->assign('nombres', $_SESSION["nombres"]);
						$smarty->assign('apellidos', $_SESSION["apellidos"]);
						$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/lista_ordenes_productos.html');
						
					} else {
						if ($opcion_busqueda== "num_orden"){
								$smarty->assign('mensaje',"No se encontr&oacute; la &Oacute;rden de Producci&oacute;n");
						} else {
								$smarty->assign('mensaje',"No se encontr&oacute; el Nombre de Cliente");
						}
						
						// $consulta= $ordenes_producto_nuevo->consulta_lista_ordenes($estado);
						 $smarty->assign('orden',$cadena);
						 $smarty->assign('lista_ordenes_productos',$consulta);
						 $smarty->assign('tabu',$_SESSION['tabu']);
						 $smarty->assign('opcion',$opcion);
						 $smarty->assign('estado',$estado);
						 $smarty->display('sistema_de_produccion/orden_de_producto_nuevo/lista_ordenes_productos.html');	   
							//$smarty->display('sistema_de_produccion/asignacion/detalle.html');
					}
						//$smarty->assign('tabu',$_SESSION['tabu']);
						//$smarty->display('sistema_de_produccion/asignacion/formulario.html');
						//$smarty->display('sistema_de_produccion/asignacion/detalle.html');
				}				
				//fin de la busqueda
	
			 }
			 else
			 {
				if($funcion=="detalle")
				{
						$elegido=$_POST['elegido'];
						$_SESSION['elegido'] = $elegido;
						$_SESSION['opcion'] = $opcion;
						header("Location: ver_detalle_orden_productos.php");
				}
				else
				{   
				   if($funcion=="estado")
				   {
						$elegido=$_POST['elegido'];
						$_SESSION['elegido'] = $elegido;
						$_SESSION['opcion'] = $opcion;
						header("Location: ver_historial_orden_productos.php");
				   }
				   else
				   {
					 if($funcion=="registrar_fotografia")
					 {
						$elegido=$_POST['elegido'];
						$_SESSION['elegido'] = $elegido;
						$_SESSION['opcion'] = $opcion;
						header("Location: registrar_fotografia_orden_productos.php");  
					 }
					 else
					 {
					   if($funcion=="registrar_materiales")
					   {
							 $elegido=$_POST['elegido'];
							 $_SESSION['elegido'] = $elegido;
							 $_SESSION['opcion'] = $opcion;
							 header("Location: registrar_material_orden_productos.php"); 
					   }
					   else
					   {
							if($funcion=="ver_materiales")
							{
								   $elegido=$_POST['elegido'];
								   $_SESSION['elegido'] = $elegido;
								   $_SESSION['opcion'] = $opcion;
									header("Location: ver_materiales_orden_productos.php"); 
									
							
							}
							else
							{			
								 if($funcion=="eliminar")
								{	
								 
									$elegido=$_POST['elegido'];
									$ordenes_producto_nuevo->deshabilitar_orden($elegido); 
									$consulta= $ordenes_producto_nuevo->consulta_lista_ordenes($estado);
									$smarty->assign('lista_ordenes_productos',$consulta);
									$smarty->assign('opcion',$opcion);
									$smarty->assign('estado',$estado);
									$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/lista_ordenes_productos.html');
									
								}
								else
								{	   
									if($funcion=="cerrar_orden")
									{
										  $elegido=$_POST['elegido'];
										  $_SESSION['elegido'] = $elegido;
										  $_SESSION['opcion'] = $opcion;
										  header("Location: ver_detalle_orden_productos.php"); 
									 
									}
									else
									{
										$consulta= $ordenes_producto_nuevo->consulta_lista_ordenes($estado);
										$smarty->assign('lista_ordenes_productos',$consulta);
										$smarty->assign('opcion',$opcion);
										$smarty->assign('estado',$estado);
										$smarty->display('sistema_de_produccion/orden_de_producto_nuevo/lista_ordenes_productos.html');
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

