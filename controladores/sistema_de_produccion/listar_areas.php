<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

include_once('../includes/fecha.php');
include_once('../../clases/validador.php');

require_once('../../clases/sistema_de_produccion/area.php');
require_once('../../clases/noconformidad_apertura.php');
$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
$funcion = $_POST['funcion'];   
$validar = new Validador();
$area= new Area();
if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
	} 
    else 
	{
    if($_GET['funcion']=="modificar")
    {
        $id=$_GET['elegido'];	
		$consulta=$area->consultar_area($id);
        $smarty->assign('titulo', 'Modificar Area');
        $smarty->assign('id_area',$consulta[0]["id_area"]);
        $smarty->assign('id_responsable',$consulta[0]["id_responsable"]);
        $smarty->assign('nombre_area',$consulta[0]["nombre_area"]);
        $smarty->assign('observaciones',$consulta[0]["observaciones"]);
        $smarty->assign('estado',$consulta[0]["estado"]);
		$smarty->display('sistema_de_produccion/normas/modificar_area.html');
    }
  	else
    {    
	  if($funcion=="eliminar")
	  {
       $nro_registro=$_POST['elegido']; 
	   $consulta=$area->eliminar_area($nro_registro);
       $areas=$area->listar_reg_areas();
       $smarty->assign('areas', $areas);
       $smarty->display('sistema_de_produccion/normas/lista_areas.html');             	 		   
	  }
      else
      {
	  if($funcion=="modificar_datos")
	  {
      if ($validar->validarTodo($_POST['nombre_area'], 1, 100))
	   $error['err_descripcion'] = "Ingresa el nombre del area";
	   if (isset($error))
	   {
		$smarty->assign('error',$error);
		$smarty->assign('id',$_POST['elegido']);
		$smarty->assign('maquinista_id',$_POST['maquinista_id']);
        $smarty->assign('nombre_area',$_POST['nombre_area']);
        $smarty->assign('observaciones',$_POST['observaciones']);
	    $smarty->display('sistema_de_produccion/normas/modificar_area.html');			
	  }
	  else
	  {
                   
      $id = $_POST['elegido'];
      $nombre_area = $_POST['nombre_area'];
      $maquinista_id = $_POST['maquinista_id'];
	  $observaciones = $_POST['observaciones'];
      $resultado=$area->modificar_area($id,$maquinista_id,$nombre_area,$observaciones);
      $areas=$area->listar_reg_areas();
      $smarty->assign('areas', $areas);
      $smarty->display('sistema_de_produccion/normas/lista_areas.html');
      }
	 }
	 else
	 { 
       $areas=$area->listar_reg_areas();
       $smarty->assign('areas', $areas);
       $smarty->display('sistema_de_produccion/normas/lista_areas.html');
	 }
     }
	}
 }
?>