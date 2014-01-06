<?php
session_start();
define('CLAS', "../../clases/");
define('SMARTY_DIR', "../../smarty/");
require_once('../includes/seguridad.php');
require_once(SMARTY_DIR . 'Smarty.class.php');

$smarty = new Smarty();
$smarty->template_dir = '../../templates/';
$smarty->compile_dir = '../../templates_c';

require_once('../../clases/sistema_de_produccion/modelos.php');
include_once('../../clases/validador.php');
include_once('../../clases/sistema_de_produccion/estilos.php');
require_once('../includes/seguridad.php');
require("../includes/fecha.php");

$modelo = new Modelo;
$estilo = new Estilo;
$validar = new Validador();


if(!isset($_SESSION['logeo'])){
	header("Location: ../index_logeo.php");
} else {
	if(isset($_GET["busqueda_ajax"])){    
		$valor =  utf8_decode($_POST["value"]);
		$cadena = trim($valor);
		$tipo = $_GET["tipex"];
		
		echo "<ul>";
		echo "<li></li>";
		$lista = $modelo->busqueda_estilos2($cadena, $tipo);
	
		if(count($lista) == 0){
			echo "<li>No hay resultados</li>";
		} else {
			for($contador = 0; $contador < count($lista); $contador ++){
				echo "<li>".$lista[$contador]."</li>";
			}
		}
		echo "</ul>";
	} else {
		//aqui hacer las acciones
		$smarty->assign('fecha', $fecha);//
		$smarty->assign('nombres', $_SESSION["nombres"]);
		$smarty->assign('apellidos', $_SESSION["apellidos"]);
		
		if ($_GET['tabu']){
			$_SESSION['tabu'] = $_GET['tabu'];
			$smarty->assign('tabu', $_SESSION['tabu']);
	
			unset($_SESSION['nombre']);
			unset($_SESSION['numero']);
			unset($_SESSION['texto_estilo']);
		}
	
		switch ($_GET['opcion']){
			case 1 :{
				$smarty->display('sistema_de_produccion/modelos/formulario.html');				
				$smarty->display('contenido/includes/pie.html');
				break;
			}
			case 2 :{
				//echo "modificar modelo";
				$smarty->display('sistema_de_produccion/modelos/formulario.html');
				$smarty->display('contenido/includes/pie.html');
				break;
			}
			case 3 :{
				//echo "eliminar modelo";
				$smarty->display('sistema_de_produccion/modelos/formulario.html');
				$smarty->display('contenido/includes/pie.html');
				break;
			}
			case 4 :{
				//echo "clasificacion de modelos";
				if (isset($_POST['insertar_tipo'])) {
					$tipo = trim($_POST['tipo']);
					$smarty->assign('tipo', $tipo);
					if ($tipo == '')
						$error['err_tipo'] = "Ingrese descripci&oacute;n";
					if (isset($error)){
						$smarty->assign('tipo', $tipo);
						$smarty->assign('errores', $error);
					} else {
						$valor_auto_incremento = $modelo->sacar_auto_incremento('indicadores_tipo');
						$clase = chr($valor_auto_incremento + 64);
						$modelo->insertar_nuevo_tipo($clase, $tipo);
					}
				}
				
				$lista_indicadores_tipo = $modelo->listar_indicadores_tipo();
				$smarty->assign('lista_indicadores_tipo', $lista_indicadores_tipo);//
				$smarty->display('sistema_de_produccion/modelos/clasificacion.html');
				break;
			}
		}
		
		if (!empty($_POST)){
			if ($_POST['funcion']){
					if ($validar->validarTodo($_POST['nombre'], 1, 100)){
						$error['err_nombre'] = "Debe ingresar una marca";
					}
					if ($_POST['tipo'] == 1){
						if ($validar->validarTodo($_POST['numero'], 1, 100)){
							$error['err_numero'] = "Debe ingresar un modelo";
						}
					}
					if ($validar->validarTodo($_POST['texto_estilo'], 0, 100)){
						$error['err_texto_estilo'] = "Debe ingresar un estilo";
					} else {
						$estilo_id = $modelo->verificar_estilo(trim($_POST['texto_estilo']));
						if($estilo_id == -1)
							$error['err_estilo'] = "Estilo no valido";
						else{
							$_SESSION['estilo_id'] = $estilo_id;
						}
					}
	
					$nombre = trim($_POST['nombre']);
					$_SESSION['nombre'] = $nombre;
					$smarty->assign('nombre', $nombre);
					
					$numero = trim($_POST['numero']);
					$_SESSION['numero'] = $numero;
					$smarty->assign('numero', $numero);
					
					$smarty->assign('tabu',$_POST['tabu']);
					
					$tipo = trim($_POST['tipo']);
					$_SESSION['tipo'] = $tipo;
					$smarty->assign('tipo', $tipo);

					$texto_estilo = trim($_POST['texto_estilo']);
					$_SESSION['texto_estilo'] = $texto_estilo;
					$smarty->assign('estilo', $texto_estilo);

					$nombre_familia = trim($_POST['posfamilia']);
					$_SESSION['posfamilia'] = $nombre_familia;
					$smarty->assign('posfamilia', $nombre_familia);
					
					$modelo_familia = trim($_POST['posmodelo']);
					$_SESSION['posmodelo'] = $modelo_familia;
					$smarty->assign('posmodelo', $modelo_familia);

					if (isset($error)){
						$smarty->assign('errores', $error);
						$smarty->display('sistema_de_produccion/modelos/formulario.html');
					} else {
						$valores = $modelo->consulta_familia($nombre_familia, $modelo_familia, $estilo_id);
						if ($valores == null){
							$mensaje = "\"".$_SESSION['nombre']." - ".$_SESSION['texto_estilo']."\", no esta registrado";
							$smarty->assign('mensaje', $mensaje);
						}
						
						$smarty->assign('valores', $valores);
						$smarty->display('sistema_de_produccion/modelos/formulario.html');
						$smarty->display('sistema_de_produccion/modelos/detalle.html');	
					}
					
					$smarty->display('contenido/includes/pie.html');
			}
			
			if ($_POST['nuevafamilia']){
					//insertar nueva familia
					$matriz = $modelo->consulta_familia_existe($_SESSION['nombre'], $_SESSION['numero'], $_SESSION['estilo_id']);
					if ($matriz != null){
						//echo "<br>la familia existe";
						$mensaje = "La familia \"".$_SESSION['nombre']." - ".$_SESSION['texto_estilo']."\", ya existe";
						$smarty->assign('mensaje', $mensaje);
							
					} else {
						//echo "<br>NO existe";
						$familia_id = $modelo->ingresar_tipo($_SESSION['tipo'], trim($_SESSION['texto_estilo']), $_SESSION['nombre'], $_SESSION['numero']);
						//echo "<br>familia id: ".$familia_id;
						$matriz = $modelo->consulta_familia_cadena($familia_id, $_SESSION['estilo_id']);
						$cadena = $modelo->devolver_cadena($matriz);
						$modelo->actualizar_familia($familia_id, $cadena);
						
						$matriz = $modelo->consulta_familia_existe($_SESSION['nombre'], $_SESSION['numero'], $_SESSION['estilo_id']);
						$mensaje = "La familia se ingreso correctamente";
					}
					
				$smarty->assign('nombre', $_SESSION['nombre']);
				$smarty->assign('numero', $_SESSION['numero']);					
				$smarty->assign('estilo', $_SESSION['texto_estilo']);
				$smarty->assign('posfamilia', $_SESSION['posfamilia']);
				$smarty->assign('posmodelo', $_SESSION['posmodelo']);

				$smarty->assign('mensaje', $mensaje);
				$smarty->assign('valores', $matriz);				
				$smarty->display('sistema_de_produccion/modelos/formulario.html');
				$smarty->display('sistema_de_produccion/modelos/detalle.html');	
				$smarty->display('contenido/includes/pie.html');
			}
			
			
			if ($_POST['ingresar']){
				if (!isset($_POST['familia'])){
					//faltaaaaaaaaaaaaaaaaaaaaaaaaa
					$mensaje = "Debe seleccionar una familia";
					$matriz = $modelo->consulta_familia($_SESSION['posfamilia'], $_SESSION['posmodelo'], $_SESSION['estilo_id']);
					//$matriz = $modelo->consulta_familia($_SESSION['nombre'], $_SESSION['numero'], $_SESSION['estilo_id']);
				} else {
				//	echo "insertarrrr alguno que ya esta cod: ".$_POST['familia'];
					
					$matriz = $modelo->consulta_familia_existe($_SESSION['nombre'], $_SESSION['numero'], $_SESSION['estilo_id']);
					if ($matriz != null){
						//$mensaje = "El producto ya pertenece a la familia: \"".$_SESSION['nombre']." - ".$_SESSION['texto_estilo']."\"";
						$mensaje = "El producto ya pertenece a la familia";
						//echo $mensaje;
					} else {
						$integrante_id = $modelo->buscar_integrante($_POST['nombre'], $_POST['numero']);
						//echo "inte ".$integrante_id;

						if ($integrante_id == null){
							//echo "integrante existe: ".$integrante_id;
							$integrante_id = $modelo->adicionar_integrante($_POST['nombre'], $_POST['numero']);
						}
												
						$modelo->adicionar_a_tipo($_POST['familia'], $integrante_id);
						$matriz = $modelo->consulta_familia_cadena($_POST['familia'], $_SESSION['estilo_id']);
						$cadena = $modelo->devolver_cadena($matriz);
						$modelo->actualizar_familia($_POST['familia'], $cadena);
						
						//$mensaje = "El modelo se ingreso a la familia: \"".$cadena." - ".$_SESSION['texto_estilo']."\"";
						$mensaje = "El modelo se ingreso a la familia";
						$matriz = $modelo->consulta_familia_existe($_SESSION['nombre'], $_SESSION['numero'], $_SESSION['estilo_id']);		
					}
				}
			
				$smarty->assign('nombre', $_SESSION['nombre']);
				$smarty->assign('numero', $_SESSION['numero']);					
				$smarty->assign('estilo', $_SESSION['texto_estilo']);
				$smarty->assign('posfamilia', $_SESSION['posfamilia']);
				$smarty->assign('posmodelo', $_SESSION['posmodelo']);

				$smarty->assign('mensaje', $mensaje);
				$smarty->assign('valores', $matriz);				
				$smarty->display('sistema_de_produccion/modelos/formulario.html');
				$smarty->display('sistema_de_produccion/modelos/detalle.html');	
				$smarty->display('contenido/includes/pie.html');

			}
		}
	}

}

?>