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

if (isset($_GET['primaria']))
	{
		//echo "sdjhfdfhdhghe".
		$primaria_id=$_GET['primaria'];
		
		$primaria = $secundaria->consulta_lista_secundaria($primaria_id);
		/*
		echo "<pre>";
		print_r($distri);
		echo "</pre>";
		*/

		echo '<select name="secun" id="secun">';
		echo"<option value='selc'>Seleccionar</option>";   
		foreach ($primaria as $key => $value){
	
		echo "<option value='".$value['secundaria_id']."'>".$value['descripcion']."</option>";
		
	
		}
		
		
		
		echo "</select>	";

	}
	?>