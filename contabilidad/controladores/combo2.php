<?php 
session_start();
define('CLAS', "../clases");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../clases/activo.php');
include_once('../clases/locprimaria.php');
include_once('../clases/locsecundaria.php');
include_once('../clases/grupo.php');
include_once('../clases/adquisicion.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';

$activo= new Activo();
$primaria=new LocPrimaria();
$secundaria=new LocSecundaria();
$grupo=new Grupo();
$adquisicion=new Adquisicion();
//echo "antes";

if (isset($_GET['cuenta']))
	{
		//echo "sdjhfdfhdhghe";
		$grupo_id=$_GET['cuenta'];
		
		$gru= $grupo->contar_numcorr($grupo_id);
		
		/*
		echo "<pre>";
		print_r($gru);
		echo "</pre>";
		*/

		echo "<input type='text' name='num_corr'  value='".$gru['num_act']."' readonly='false' size='12'/>";
		
		//	echo '<input type="text" name="num_corr"  value="'.$gru['num_act'].'" disabled="disabled"/>';
			
		
		
		
		

	}
	?>