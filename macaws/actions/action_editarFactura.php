<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Venta.php");
require_once(CLAS . "Compra.php");
require_once(CLAS . "validador.php");

$url_relativa = "/sistema/macaws/editarFactura.php";
$url_relativa2 = "/sistema/macaws/lvc.php";
$url_relativa3 = "/sistema/macaws/reportesLcv.php";

$val = new Validador();
$error = null;

if(isset($_SESSION['usuario_id']))
{
	if(	isset($_REQUEST['eliminar'])	)
	{
		$link = new Coneccion();
		$link = $link->conectiondb();
				
		if(strcasecmp($_POST['tipo'],"Compra")==0)
		{			
			$compra = new Compra($link);
			$compra->eliminarFacturaCompra($_POST['codfac']);
		}
		
		if(strcasecmp($_POST['tipo'],"Venta")==0)
		{
			$venta = new Venta($link);
			$venta->eliminarFacturaVenta($_POST['codfac']);
		}		
		header("Location: " . $url_relativa3);
	}
	else
	{
		$_SESSION['req'] = $_POST;
		//if($val->validarNumeros($_POST['diaperiodo'],1,2) )
		if(empty($_POST['fecha']))
		$error['diaperiodo'] = "Seleccione un dia valido.";

		if($val->validarNumeros($_POST['nit'],4,20))
		$error['nit'] = "NIT invalido.";

		if ($val->validarTodo($_POST['razon_social'], 1, 255))
		$error['rsocial'] = "Raz&oacute;n social vacio.";

		if($val->validarTextoNumeros2($_POST['factura'],1,25))
		$error['factura'] = "Factura invalida.";

		if($val->validarNumeros($_POST['autorizacion'],1,20))
		$error['autorizacion'] = "Autorizaci&oacute;n invalida.";


		if($val->validarTextoNumeros($_POST['codigo_control'],0,20))
		$error['control'] = "C&oacute;digo de control invalido.";

		if($val->validarNumerosDecimales($_POST['total_facturado'],1,10))
		$error['totalfact'] = "Cantidad Factura invalida.";

		if($val->validarNumerosDecimales($_POST['ice'],0,10))
		$error['ice'] = "Cantindad ICE invalida.";

		if($val->validarNumerosDecimales($_POST['exento'],0,10))
		$error['exento'] = "Cantidad Exentos invalida.";

		if(strcasecmp($_POST['tipo'],"Venta")==0)
		{
			if (!isset($_POST['tipovent']))
			{
				$error['tipoventa'] = "Seleccione un tipo de venta.";
			}
		}

		if (	isset($error)	)
		{
			$_SESSION['errores'] = $error;
		}
		else
		{
			$link = new Coneccion();
			$link = $link->conectiondb();
			$venta = new Venta($link);
			$compra = new Compra($link);
			if(!$venta->validarFacturaEdicion($_POST['factura'],$_POST['autorizacion'],$_POST['nit'],$_POST['codfac']))
			{				
				if(strcasecmp($_POST['tipo'],"Venta")==0)
				{

					$fecha ="".$_POST['gestion']."-".$_POST['periodo']."-".$_POST['fecha'];
					 if($_POST['sucursal']==0)//reviso si rescato de interfaz el valor de sucursal
                    {
                        $venta->updateVenta($fecha,$_POST['nit'],$_POST['razon_social'],$_POST['factura'],$_POST['autorizacion'],$_POST['codigo_control'],$_POST['total_facturado'],$_POST['ice'],$_POST['exento'],$_POST['importe'],$_POST['credito_fiscal'],$_POST['cod_pg'],$_SESSION['usuario_id'],$_POST['tipovent'],$_POST['codfac'],$_SESSION["sucursal_id"]);
                    }
                    else
                    {
                        $venta->updateVenta($fecha,$_POST['nit'],$_POST['razon_social'],$_POST['factura'],$_POST['autorizacion'],$_POST['codigo_control'],$_POST['total_facturado'],$_POST['ice'],$_POST['exento'],$_POST['importe'],$_POST['credito_fiscal'],$_POST['cod_pg'],$_SESSION['usuario_id'],$_POST['tipovent'],$_POST['codfac'],$_POST['sucursal']);
                    }

					//$venta->updateVenta($fecha,$_POST['nit'],$_POST['razon_social'],$_POST['factura'],$_POST['autorizacion'],$_POST['codigo_control'],$_POST['total_facturado'],$_POST['ice'],$_POST['exento'],$_POST['importe'],$_POST['credito_fiscal'],$_POST['cod_pg'],$_SESSION['usuario_id'],$_POST['tipovent'],$_POST['codfac'],$_POST['sucursal']);

					$_SESSION['mcweditfact']="";

				}
				if(strcasecmp($_POST['tipo'],"Compra")==0)
				{
					$fecha ="".$_POST['gestion']."-".$_POST['periodo']."-".$_POST['fecha'];
					if($_POST['sucursal']==0)//reviso si rescato de interfaz el valor de sucursal
                    {
					$compra->updateCompra($fecha,$_POST['nit'],$_POST['razon_social'],$_POST['factura'],$_POST['autorizacion'],$_POST['codigo_control'],$_POST['total_facturado'],$_POST['ice'],$_POST['exento'],$_POST['importe'],$_POST['credito_fiscal'],$_POST['cod_pg'],$_SESSION['usuario_id'],$_POST['codfac'],$_SESSION["sucursal_id"]);
					 }
                    else
                    {
                     $compra->updateCompra($fecha,$_POST['nit'],$_POST['razon_social'],$_POST['factura'],$_POST['autorizacion'],$_POST['codigo_control'],$_POST['total_facturado'],$_POST['ice'],$_POST['exento'],$_POST['importe'],$_POST['credito_fiscal'],$_POST['cod_pg'],$_SESSION['usuario_id'],$_POST['codfac'],$_POST['sucursal']);   
                    }
					//$compra->updateCompra($fecha,$_POST['nit'],$_POST['razon_social'],$_POST['factura'],$_POST['autorizacion'],$_POST['codigo_control'],$_POST['total_facturado'],$_POST['ice'],$_POST['exento'],$_POST['importe'],$_POST['credito_fiscal'],$_POST['cod_pg'],$_SESSION['usuario_id'],$_POST['codfac'],$_POST['sucursal']);
					
					$_SESSION['mcweditfact']="";
				}
			}
			else
			{
				$error['facturadoble']="Factura duplicada.";
				$_SESSION['errores'] = $error;
			}
		}
		header("Location: " . $url_relativa);
	}
}
else
header("Location: " . $url_relativa2);
?>