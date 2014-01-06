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
if (!isset($_SESSION['logeo'])) {
	header("Location: ../index_logeo.php");
} else {

	if(isset($_GET['buscar']))
	{   $nom=strtoupper($_GET['nombre_act']);
		
		$act=$grupo->listar_gru_nombre($nom);
		
		$smarty->assign('act', $act);	
		$smarty->assign('busqueda1', 'si');	
		$smarty->display('busqueda.html');
		  	   			  
	}
 $funcion = $_GET['funcion'];
	if ($funcion == "detalle") 
		{ 
		
		 $act_id = $_GET['elegido'];
		 $detalle=$activo->detalle_activo_completo($act_id);
		 
		 
		 $sec_id=$detalle['secundaria_id'];
	     $loc=$detalle['primaria_id'];
		 $gru=$detalle['grupo_id'];
		 $tipo_cambio=round ($detalle['tipo_cambio'],2);	
		 $numero=$detalle['numero'];
		 $det_adqui=$detalle['det_adqui'];
		
		 $primaria=$primaria->listar_primarias($loc);		
		 $secundaria=$secundaria->listar_secundarias($sec_id,$loc);
		 $grupo=$grupo->listar_gru($gru);
		 $adqui=$adquisicion->listar_adqui($detalle['ad_id']);
		 
		 $adquis=$adquisicion->lisadqui($detalle['ad_id']);
		
		 $foto=$activo->mostrar_foto($numero);
		
		 $respPri=$activo->buscar_responsablepri($act_id);
		
		 $nombre_asis2=$respPri['completopri'];
		 $resp_pri=$respPri['resp_id'];
		  
	     $resp=$activo->buscar_asignado($act_id);
		/*echo "<pre>";
		print_r($foto);
		echo "</pre>";*/
		 $resp_pp=$resp['resp_id'];
		 $nombre_asis=$resp['completo'];
		
		
		$smarty->assign('detalle',$detalle);
		$smarty->assign('numerito',$numero);
		$smarty->assign('primaria',$primaria);
		$smarty->assign('secundaria',$secundaria);
		$smarty->assign('grupo',$grupo);
		$smarty->assign('adqui',$adqui);
		$smarty->assign('adquis',$adquis);
		$smarty->assign('tipo_cambio',$tipo_cambio);
		$smarty->assign('si','si');
		$smarty->assign('foto', $foto);
		$smarty->assign('nombre_asis2', $nombre_asis2);
		$smarty->assign('nombre_asis', $nombre_asis);
		$smarty->assign('resp_id2', $resp_pri);
		$smarty->assign('resp_id', $resp_pp);
		$smarty->assign('resp_1', $resp_pri);
		$smarty->assign('resp_2', $resp_pp);
		$smarty->assign('det_adqui', $det_adqui);
		$smarty->display('modificar_activo_completo.html');
		

	}
}
?>