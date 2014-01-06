<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');
require_once(CLAS . "conexion.php");
require_once(CLAS . "Reportes.php");
require_once(CLAS . "Personal.php");
require_once(CLAS . "validador.php");
require_once(CLAS . "ContriSucursal.php");

$url_relativa = "/sistema/macaws/reporteSalida.php";
$url_relativa2 = "/sistema/macaws/reportes.php";
$url_relativa3 = "/sistema/macaws/lvc.php";

if(isset($_SESSION['usuario_id']))
{
	$val = new Validador();
	$error=null;
	if(	isset($_REQUEST['genrepcv']) ||	isset($_REQUEST['generar']))
	{

		$fecha = $_REQUEST['fechactual'];

		if ($val->validarNumeros($_POST['intervalo'],1,3)|| $_POST['intervalo']<10 )
		{
			$error['periodogestion'] = "El valor del intervalo no es correcto. Debe ser minimo de 10 registros.";
			$_SESSION['errores'] = $error;
			$_SESSION['req'] = $_POST;
			header("Location: " . $url_relativa2);
		}
		else
		{
			$link = new Coneccion();
			$link = $link->conectiondb();
			$report = new Reportes($link);
			$totales = null;
			$periodo=$_POST['periodo'];
            
            if($_SESSION['sucursal_id']==1)
            {
            $sucursal=$_POST['sucursales']; 
            }
            else
            {
                $sucursal=$_SESSION['sucursal_id'];
            }
            
			$usuario_id = $report->validarGestionPeriodo($_POST['gestion'],$periodo);
			if(	isset($usuario_id))
			{
				$paginacion = null;
				if(	$_POST['tipo']==0	)
				$paginacion = $report->calculatePagesReport($_POST['intervalo'],0,$_POST['gestion'],$periodo,$_POST['alfanumerico'],$sucursal);
				if(	$_POST['tipo']==1	)
				$paginacion = $report->calculatePagesReport($_POST['intervalo'],1,$_POST['gestion'],$periodo,$_POST['tipoventa'],$sucursal);
				if($_POST['tipo']==2)
				{
					
					$davinci = $report->reporteDaVinci($_POST['tipo'],$periodo,$_POST['gestion'],$sucursal);
					$_SESSION['davincirepcv'] = $davinci;
					$_SESSION['davincirepper'] = $periodo;
					$_SESSION['davincirepges'] = $_POST['gestion'];
					$_SESSION['davincireptipo'] = $_POST['tipo'];					
					header("Location: " . "/sistema/macaws/reporteLcvDaVinci.php");
					exit;
				}
				if($_POST['tipo']==3)
				{
					$davinci = $report->reporteDaVinci($_POST['tipo'],$periodo,$_POST['gestion'],$sucursal);
					$_SESSION['davincirepcv'] = $davinci;
					$_SESSION['davincirepper'] = $periodo;
					$_SESSION['davincirepges'] = $_POST['gestion'];
					$_SESSION['davincireptipo'] = $_POST['tipo'];					
					header("Location: " . "/sistema/macaws/reporteLcvDaVinci.php");
					exit;
				}

				if($_POST['tipo']==4)
				{
					$sdi= $report->reporteSDIDUI($_POST['tipo'],$periodo,$_POST['gestion']);
					$_SESSION['sdidui'] = $sdi;
					$_SESSION['sdirepper'] = $periodo;
					$_SESSION['sdirepges'] = $_POST['gestion'];
					$_SESSION['sdireptipo'] = $_POST['tipo'];	
								
					header("Location: " . "/sistema/macaws/reporteSDI.php");
					exit;
				}
				if($_POST['tipo']==5)
				{
					$sdi= $report->reporteSDIFACT($_POST['tipo'],$periodo,$_POST['gestion']);
					$_SESSION['sdidui'] = $sdi;
					$_SESSION['sdirepper'] = $periodo;
					$_SESSION['sdirepges'] = $_POST['gestion'];
					$_SESSION['sdireptipo'] = $_POST['tipo'];					
					header("Location: " . "/sistema/macaws/reporteSDI.php");
					exit;
				}
	
					
				
				$_SESSION['pagreplcv'] = $paginacion;
				$_SESSION['req'] = $_POST;
				$_SESSION['fechactual'] = $fecha;
				header("Location: " . $url_relativa2);
			}
			else
			{
				$error['periodogestion'] = "El periodo: ".$_POST['periodo'].
				"  en la gestion: ".$_POST['gestion']." ,no esta cerrado.";
				$_SESSION['errores'] = $error;
				$_SESSION['req'] = $_POST;
				$_SESSION['fechactual'] = $fecha;
				header("Location: " . $url_relativa2);
			}
		}
	}
	if( isset($_GET['iniInt'])&&isset($_GET['endInt'])&&isset($_GET['tiporep'])&&isset($_GET['gestion'])&&isset($_GET['periodo']))
	{   $sucursal=$_GET['sucursal'];
		
		$link = new Coneccion();
		$link = $link->conectiondb();
		$report = new Reportes($link);
		$consucur = new ContriSucursal($link);
		$pers = new Personal($link);
		$usuario_id = $report->validarGestionPeriodo($_GET['gestion'],$_GET['periodo']);
		$persona = $pers->obtenerPersonal($usuario_id);
		$contribuyente = $consucur->obtenerContriSucursal($sucursal);
		$_SESSION['lcvcontribuyente']=$contribuyente;


		if(	$_GET['tiporep']==0	)
		{
			if(	$_REQUEST['alfa']!=2)
			$result['res'] = $report->buscar_ComprasReporte("","razon_social",$_GET['gestion'],$_GET['periodo'],null,$_GET['iniInt'],$_GET['endInt'],$sucursal);
			else
			{
				$result['res'] = $report->buscar_ComprasAlfanumericoReporte("","razon_social",$_GET['gestion'],$_GET['periodo'],null,$_GET['iniInt'],$_GET['endInt'],$sucursal);
				$_SESSION['repcomalfalcv']="alfanumerico";
			}

			if($_GET['pag']==$_GET['ttl'])
			$totales = $report->CompraTotalesGenerales($_GET['gestion'],$_GET['periodo'],$sucursal);
			$_SESSION['tiporeplcv']=0;
		}
		if(	$_GET['tiporep']==1	)
		{

			$result['res'] = $report->buscar_VentasReportes("","razon_social",$_GET['gestion'],$_GET['periodo'],null,$_GET['iniInt'],$_GET['endInt'],$_GET['tvent'],$sucursal);
			if($_GET['pag']==$_GET['ttl'])
			$totales = $report->ventaTotalesGenerales($_GET['gestion'],$_GET['periodo'],$_GET['tvent'],$sucursal);
			$_SESSION['tiporeplcv']=1;

			$tituloVenta="";
			if(	$_GET['tvent']==2	)
			$tituloVenta="Dosificaci&oacute;n Manual.";
			if(	$_GET['tvent']==3	)
			$tituloVenta="Dosificaci&oacute;n Computarizada.";
		}
		//echo "<br>tipo de venta:::::::> ".$_GET['tvent'];


		$fecha = $_GET['fechactual'];
		$result['gestion']=$_GET['gestion'];
		$result['periodo']=$_GET['periodo'];
		$result['pagina']=$_GET['pag'];
		$result['total']=$_GET['ttl'];
		$_SESSION['resultreplcv']=$result;
		$_SESSION['totalesreplcv']=$totales;
		$_SESSION['reppersonalcv']=$persona;
		$_SESSION['reptipventlcv']=$tituloVenta;
		$_SESSION['fechactual'] = $fecha;

		header("Location:$url_relativa","target:blank");

	}
}
else
header("Location: " . $url_relativa3);
?>