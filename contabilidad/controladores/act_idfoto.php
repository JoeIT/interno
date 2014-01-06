<?php
session_start();
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

include_once('../clases/includes/validador.php');

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
/********************************************************************************************************************
esto sirve para  separar el numero del activo e insertarlos por separado*/
/*$acti = $activo->numero();


foreach ($acti as $indice=> $valor) 
{
		
	 $a[$indice]=$valor['numero'];
	 //remplazar el espacio en blanco por nada
	 $numero=str_replace(" ","",$a[$indice]);
	 $act_id=$valor['act_id'];
	
		 echo $num=explode(".",$numero);
	  	
	
		  $localizacion=$num[0];
	  	  $locsecundaria=$num[1];
		  $grupo=$num[2];
		  $num_act=$num[3];
		
			
	 		
	$a1 = $activo->insertar_numero($localizacion,$locsecundaria,$grupo,$num_act,$act_id,$numero);
   }

/*
echo "<pre>";
print_r($a1);
echo "</pre>";*/
	
/*$smarty->assign('acti', $acti);
$smarty->display('activos.html');*/
/*echo "fndsjgkdgj<pre>";
print_r($_SESSION['nun']);
echo "</pre>";*/
/***************************************************************************************************************/
/*esto servira para insertar a la tabla fotografia el acti_id del activo con el que esta
relacionado la fotos o fotos
*/

$acti = $activo->numero();
foreach ($acti as $indice=> $valor) 
{
	
	 $a[$indice]=$valor['numero'];	
	 $numero=$a[$indice];
	 $act_id=$valor['act_id'];
	 $foto=$activo->seleccionar_numfoto($numero);
	 
	 echo "<pre>";
	 print_r($foto);
	 echo "</pre>";	
	 
	 foreach ($foto as $ind=> $val)
	 {
	 	if ($numero == $val['numero'])
	 	{
	 		$acti = $activo->insertar_actidfoto($act_id,$val['numero']);
	 		
	 			
	 	}
	 	else 
	 	{
	 		echo "estos".$numero;
	 		
	 	}
	 	
	 }
		  
	 		
	
 }


/***********************************************************************************************************/
/*para renombrar fotos*/
/*		$fotoante='30.10.03.00058_IMG.jpg';
		$numerofin='30.10.03.01058';
		$act_id='283';
		$fotografia=$numerofin.'_IMG'.'.jpg';
		//chmod("UploadedFiles/".$fotoante, 0777);
		$foto=$activo->actualizar_foto($act_id,$numerofin,$fotografia);
		rename("UploadedFiles/".$fotoante, "UploadedFiles/".$fotografia);
        */