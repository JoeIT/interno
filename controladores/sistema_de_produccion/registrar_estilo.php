<?php
session_start();
define('CLAS', "../clases/");
define('SMARTY_DIR', "../../smarty/");

require_once(SMARTY_DIR . 'Smarty.class.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
include_once('../../clases/validador.php');
require_once('../includes/seguridad.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

$estilo = new Estilo;
$validar = new Validador();

if(isset($_GET["busqueda_ajax"])){
	$valor = $_POST["value"];
	$cadena = utf8_decode(trim($valor));
	echo "<ul>";
	$lista=$estilo->busqueda_estilos($cadena);
	if(count($lista) == 0){
		echo "<li>No hay resultados</li>";
	} else {
		for($contador = 0; $contador < count($lista); $contador ++){
			echo "<li>".$lista[$contador]."</li>";
		}
	}
	echo "</ul>";
} else {
	if (!empty($_POST)) {
		$descripcion = $_POST['descripcion'];
		$tipo_estilo = $_POST['tipo'];
		
		if ($validar->validarTodo($_POST['descripcion'], 1, 100))
			$error['err_descripcion'] = "Ingrese la descripcion del estilo";
			$estilo_id = $estilo->verificar_estilo(trim($_POST['descripcion']));
			
			if($estilo_id != -1)
				{$error['err_estilo'] = "El estilo ya existe";
		}
		
		if (isset($error)){
			$smarty->assign('title', 'Formulario de registro de un estilo');
			$smarty->assign('tipo', $tipo_estilo);
			if ($_POST['popup'] == "true"){
				$smarty->assign('estilo',false);
			}
			
			$smarty->assign('descripcion',$_POST['descripcion']);
			$smarty->assign('errores',$error);
			
			$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_estilo.html');
		} else {
			$resultado = $estilo->nuevo_estilo($descripcion, $tipo_estilo);
			
			if($resultado){
				$error['err_confirm'] = "El registro se realizo correctamente";
				$smarty->assign('errores',$error);
				
				$consulta = $estilo->consulta_lista_estilos();
				if ($_POST['popup'] == "true") {
					$smarty->assign('estilo',true);
					$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_estilo.html');
				} else {
					$smarty->assign('estilo',$consulta); 	
					$smarty->display('sistema_de_produccion/orden_de_produccion/lista_estilo.html');
				}
			}
		}
	} else {
		$smarty->assign('descripcion',$descripcion);
		$smarty->assign('errores',$errores);
		
		if ($_GET['popup']== "false") {
			$smarty->assign('estilo',true);
		} else {
			$smarty->assign('estilo',false);
		}
		$smarty->display('sistema_de_produccion/orden_de_produccion/registrar_estilo.html');
	}
}
?>