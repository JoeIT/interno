<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/exportar_ordenes.php');
include_once('../../clases/includes/validador.php');
$exportador=new Exportar_Orden;
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if(isset($_GET["busqueda_ajax"]))
	 {    
		  $valor =  utf8_decode($_POST["value"]);
		  $cadena= trim($valor);
		  header("Content-Type: text/html; charset=iso-8859-1");
		  echo "<ul>";
		  $conexion=@mysql_connect("192.168.0.100","sistemas","sistema135");
		  mysql_select_db("extra_macaws",$conexion);
		  $consulta= "select nro_orden from orden where nro_orden like '%".$cadena."%' Order by cod_orden DESC Limit 0,5 ";
		  $resultado=mysql_query($consulta) or die('La consulta fall&oacute;: ' . mysql_error());
		  while($row = mysql_fetch_array($resultado))
		  {
			  echo "<li>".$row[0]."</li>";
		  }
		  echo "</ul>";
							
	} 
	 
	else
	{
	if($_GET['funcion']== "validar")
	{    $error="";
		 $validar = new Validador();
		 if ($validar->validarTodo($_POST['num_orden'], 1, 100))
			$error['err_numero_orden'] = "Ingresa el numero de orden";
		 
		if($error!="")
		{
			   $num_orden = $_POST['num_orden'];
			   $smarty->assign('errores',$error);
			   $smarty->assign('num_orden',$num_orden);
			   $smarty->display('sistema_de_produccion/migrar_orden/migrar_orden.html');
		}
		else
		{
		   $num_orden = $_POST['num_orden'];
		   $exportador->exportar_orden_produccion($num_orden);  
		   $error['err_confirm'] = "El registro se realizo correctamente";
		   $smarty->assign('errores',$error);
		   $smarty->display('sistema_de_produccion/migrar_orden/migrar_orden.html');
		 }
	}
	else
	{ 
		$smarty->display('sistema_de_produccion/migrar_orden/migrar_orden.html');
	}
	}

}
?>