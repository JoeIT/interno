<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/despiece.php');
include_once('../../clases/sistema_de_produccion/materiales.php');
include_once('../../clases/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/sistema_de_produccion/despiece/';
$smarty->compile_dir = '../../templates_c';

$despiece=new Despiece;
$materiales=new Material;

if(isset($_GET["busqueda_ajax"]))
 {    
      $valor = $_POST["value"];
	  $cadena=  utf8_decode(trim($valor));
	  echo "<ul>";
	  $lista=$despiece->busqueda_tipos($cadena);
	  if(count($lista)==0)
	  {
		   echo "<li>No hay resultados</li>";
	  }
	  else
	  {
		for($contador=0;$contador<count($lista);$contador++)
	     	{
		        echo "<li>".$lista[$contador]."</li>";
		  
		 	}
	  
	  }
	  echo "</ul>";
 }
else {
	if($_POST['funcion']== "validar"){
		$validar = new Validador();
		if ($validar->validarTodo($_POST['nombre'], 1, 100))
			$error['err_nombre'] = "Ingrese el nombre del material";
		//if ($validar->validarTodo($_POST['descripcion'], 1, 100))
		//	$error['err_descripcion'] = "Ingrese la descripcion";
		if ($validar->validarTodo($_POST['unidad'], 1, 100))
			$error['err_unidad'] = "Ingrese la unidad";

		if ($_POST['tipo']=='insumo'){
			/*if ($validar->validarNumeros($_POST['desperdicio'],1,100))
				$error['err_desperdicio'] = "Ingrese el desperdicio";
			if ($validar->validarNumeros($_POST['ancho'],1,100))
				$error['err_ancho'] = "Ingrese el ancho";
			if ($validar->validarNumeros($_POST['largo'],1,100))
				$error['err_largo'] = "Ingrese el largo";*/
			if ($validar->validarTodo($_POST['nombre_tipo'], 1, 100))
				$error['err_nombre_tipo'] = "Seleccione el tipo de material";
			else {
				//debemos encontrar el ID de esa categoria
				$material_id = $materiales->verificar_material_existente(trim($_POST['nombre_tipo']));
				if ($material_id == -1){
					$error['err_nombre_tipo'] = "Material no válido";
					//echo $_POST['nombre_tipo'];
				}
			}			
		}
		
		
		if (isset($error)){
			$nombre = $_POST['nombre'];
			$descripcion = $_POST['descripcion'];
			$unidad = $_POST['unidad'];
			$tipo = $_POST['tipo'];
			$nombre_tipo = $_POST['nombre_tipo'];
			$largo = $_POST['largo'];
			$ancho = $_POST['ancho'];
			$desperdicio = $_POST['desperdicio'];
			
			$smarty->assign('nombre',$nombre);
			$smarty->assign('descripcion',$descripcion);
			$smarty->assign('unidad',$unidad);
			$smarty->assign('tipo',$tipo);
			$smarty->assign('nombre_tipo',$nombre_tipo);
			$smarty->assign('largo',$largo);
			$smarty->assign('ancho',$ancho);
			$smarty->assign('desperdicio',$desperdicio);
			$smarty->assign('errores',$error);
			
			
			$smarty->display('registrar_material.html');
		} else {
			$nombre = $_POST['nombre'];
			$descripcion = $_POST['descripcion'];
			$unidad = $_POST['unidad'];
			$desperdicio = $_POST['desperdicio'];
			$tipo = $_POST['tipo'];
			$nombre_tipo = $_POST['nombre_tipo'];
			$largo = $_POST['largo'];
			$ancho = $_POST['ancho'];
			
			//echo $nombre."-".$descripcion."-".$unidad."-".$desperdicio."-".$tipo."-".$material_id."-".$largo."-".$ancho;
			
			$contador=0;
			$resultado=$despiece->nuevo_material_completo($nombre,$descripcion,$unidad,$desperdicio,$material_id,$tipo,$ancho,$largo);
			
			//enviamos la informacion a la función de adicionar cliente
			
			if($resultado){
				$error['err_confirm'] = "El registro se realizo correctamente";
				$smarty->assign('errores',$error);
				$smarty->display('registrar_material.html');
				exit;
			}
		}
	} else {
		$smarty->display('registrar_material.html');
	}
}
?>