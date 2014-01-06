<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';
require_once('../../clases/sistema_de_produccion/area.php');
include_once('../../clases/sistema_de_produccion/personal.php');
include_once('../../clases/validador.php');
require_once('../includes/seguridad.php');
require("../includes/fecha.php");

$personal = new Personal();
$area = new Area();
$validar = new Validador();
	
if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
 	  
    if(isset($_GET["busqueda_ajax"]))
    {    
      $valor = $_POST["value"];
	  $cadena= utf8_decode(trim($valor));
	  echo "<ul>";
	  if($_GET["busqueda_ajax"]=='personal')
	    $lista=$personal->busqueda_personal($cadena);
	  if(count($lista)==0)
	  {
		   echo "<li>No hay resultados</li>";
	  }
	  else
	  {
		for($contador=0;$contador<count($lista);$contador++)
	     	{
		        $detalles = $lista[$contador]["personal_id"];
                echo '<li id="'.$detalles.'">&nbsp;'.$lista[$contador]["completo"].'</li>';
		 	}
	  }
	  echo "</ul>";
    }
        else 
        {
	 	if ($_GET['funcion'] == "validar") 
         { 
           if (isset($error))
           {
					$smarty->assign('errores', $error);        
			}
            else
            {
             if ($validar->validarTodo($_POST['maquinista_id'], 1, 100))
             {
				$error['err_responarea'] = "Ingresar responsable de area";
			 }
             if ($validar->validarTodo($_POST['nombre_area'], 1, 100))
             {
				$error['err_nombrea'] = "Ingresar nombre de area";
			 }
            $id_area = $area->verificar_area($_POST['nombre_area']);
			if ($id_area != -1) 
            {
				$error['err_nombrea'] = " Area existe.";
                $smarty->display('sistema_de_produccion/normas/registrar_area.html');
            }
			if ($id_area == -1) 
            {
                $ver=$area->ingresar_area($_POST['maquinista_id'],$_POST['nombre_area'],$_POST['observaciones']);
				$smarty->assign('err_firstname', "Los datos fueron almacenados correctamente");
				$smarty->display('sistema_de_produccion/normas/registrar_area.html');
			} 
            }
		} 
        else
        {
            $smarty->assign('id_responsable', $_POST['personal_id']);
            $smarty->assign('nombre_area', $_POST['nombre_area']);
			$smarty->assign('observaciones',$_POST['observaciones']);
			$smarty->display('sistema_de_produccion/normas/registrar_area.html');    
        }
	   }
}
?>