<?php
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.
session_start();
define('CLAS', "../clases");
define('SMARTY_DIR', "../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../clases/activo.php');

$smarty = new Smarty();
$smarty->template_dir = '../templates/';
$smarty->compile_dir = '../templates_c';

$activo= new Activo();

//se aumento para identificar cuales son subidas de fotos y cuales son cambios tipos de cambio
$band=$_GET['bandera'];

echo 'Upload result:<br>'; // At least one symbol should be sent to response!!!
echo $nume=$_GET['text']; 
     
     
     
	  echo $num=explode("-",$nume);
	  $numero=$num[0];
	  $act_id=$num[1];
     
//$_SESSION['nun'][]=$numero;
if($band!=1){
  echo $uploaddir = dirname($_SERVER['SCRIPT_FILENAME'])."/UploadedFiles/";
	
	
$target_encoding = "ISO-8859-1";
echo '<pre>';
if(count($_FILES) > 0)
{
	
	$arrfile = pos($_FILES);
	 $uploadfile = $uploaddir . iconv("UTF-8", $target_encoding,$numero."_".basename($arrfile['name']));
	
	if (move_uploaded_file($arrfile['tmp_name'], $uploadfile)){
	   echo "File is valid, and was successfully uploaded.\n";
	  // $_SESSION['nun'][]=$uploadfile;
	   	   $foto=$numero."_".$arrfile['name'];
	   	   $fecha=date("Y-m-d");
	   $a1 = $activo->insertar_foto($foto,$numero,$fecha,$act_id);
	}
	   
}
else
	echo 'No files sent. Script is OK!'; //Say to Flash that script exists and can receive files

echo 'Here is some more debugging info:';

}
else//cuando viene del formulario de actualizar tipo de cambio
{
    echo $uploaddir = dirname($_SERVER['SCRIPT_FILENAME'])."/UploadedTipo/";
	
	
$target_encoding = "ISO-8859-1";
echo '<pre>';
if(count($_FILES) > 0)
{
	
	$arrfile = pos($_FILES);
	 $uploadfile = $uploaddir . iconv("UTF-8", $target_encoding,basename($arrfile['name']));
	
	if (move_uploaded_file($arrfile['tmp_name'], $uploadfile)){
	   echo "File is valid, and was successfully uploaded.\n";
           $foto=$arrfile['name'];
	   	   $fecha=date("Y-m-d");
	}
}
else
	echo 'No files sent. Script is OK!'; //Say to Flash that script exists and can receive files

echo 'Here is some more debugging info:';
}
?>